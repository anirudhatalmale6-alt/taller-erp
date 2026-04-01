<?php
class FacturaController extends Controller {

    public function index() {
        $this->requireAuth();
        $estado = $this->getParam('estado');
        $filtro_activo = $this->getParam('activo');
        $page = max(1, (int)$this->getParam('page', 1));

        $db = getDB();
        $where = '1=1';
        $params = [];
        if ($estado) { $where .= ' AND f.estado = ?'; $params[] = $estado; }
        if ($filtro_activo !== null && $filtro_activo !== '') { $where .= ' AND f.activo = ?'; $params[] = $filtro_activo; }

        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $stmt = $db->prepare("SELECT f.*, CONCAT(cl.nombre, ' ', IFNULL(cl.apellidos,'')) as cliente_nombre, v.marca, v.modelo
            FROM factura_cab f
            LEFT JOIN clientes cl ON f.id_cliente = cl.id
            LEFT JOIN vehiculos v ON f.matricula = v.matricula
            WHERE $where ORDER BY f.fecha DESC, f.id DESC LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);
        $facturas = $stmt->fetchAll();

        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM factura_cab f WHERE $where");
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];

        $this->view('documentos/factura_list', [
            'pageTitle' => 'Facturas',
            'facturas' => $facturas,
            'pagination' => ['page' => $page, 'totalPages' => ceil($total / $perPage)],
            'estadoFiltro' => $estado,
            'filtro_activo' => $filtro_activo,
        ]);
    }

    public function create() {
        $this->requireAuth();
        $clientes = Cliente::findAll("activo = 'SI'", [], 'nombre ASC');
        $operarios = Operario::activos();
        $config = getConfig();

        $this->view('documentos/factura_form', [
            'pageTitle' => 'Nueva Factura',
            'doc' => null,
            'lineas' => [],
            'clientes' => $clientes,
            'operarios' => $operarios,
            'config' => $config,
            'selectedCliente' => (int)$this->getParam('id_cliente'),
            'selectedMatricula' => $this->getParam('matricula', ''),
            'extraJs' => ['assets/js/documento.js'],
        ]);
    }

    public function store() {
        $this->requireAuth();
        csrf_check();
        $data = $_POST;
        $data['id_factura'] = generarNumero('factura');
        $data['activo'] = 'SI';
        $data['estado'] = 'borrador';
        $data['created_by'] = currentUser()['id'];
        $id = Factura::insert($data);

        $doc = Factura::findById($id);
        $this->guardarLineas($doc['id_factura']);
        Model::recalcularTotales('factura_cab', $id, 'factura_det', 'id_factura', $doc['id_factura']);

        flash('success', 'Factura creada: ' . $data['id_factura']);
        redirect('index.php?c=factura&a=show&id=' . $id);
    }

    public function show() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Factura::conRelaciones($id);
        if (!$doc) { flash('error', 'Factura no encontrada'); redirect('index.php?c=factura'); }
        $lineas = Factura::getLineas($id);

        $this->view('documentos/documento_show', [
            'pageTitle' => 'Factura ' . $doc['id_factura'],
            'doc' => $doc,
            'lineas' => $lineas,
            'tipo' => 'factura',
            'nextAction' => '',
            'nextLabel' => '',
        ]);
    }

    public function edit() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Factura::findById($id);
        if (!$doc) { flash('error', 'Factura no encontrada'); redirect('index.php?c=factura'); }
        $lineas = Factura::getLineas($id);
        $clientes = Cliente::findAll("activo = 'SI'", [], 'nombre ASC');
        $operarios = Operario::activos();
        $config = getConfig();

        $this->view('documentos/factura_form', [
            'pageTitle' => 'Editar Factura ' . $doc['id_factura'],
            'doc' => $doc,
            'lineas' => $lineas,
            'clientes' => $clientes,
            'operarios' => $operarios,
            'config' => $config,
            'selectedCliente' => $doc['id_cliente'],
            'selectedMatricula' => $doc['matricula'],
            'extraJs' => ['assets/js/documento.js'],
        ]);
    }

    public function update() {
        $this->requireAuth();
        csrf_check();
        $id = (int)$_POST['id'];
        Factura::update($id, $_POST);

        $doc = Factura::findById($id);
        $this->guardarLineas($doc['id_factura']);
        Model::recalcularTotales('factura_cab', $id, 'factura_det', 'id_factura', $doc['id_factura']);

        flash('success', 'Factura actualizada');
        redirect('index.php?c=factura&a=show&id=' . $id);
    }

    public function estado() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $estado = $this->getParam('estado');
        Factura::update($id, ['estado' => $estado]);
        flash('success', 'Estado actualizado');
        redirect('index.php?c=factura&a=show&id=' . $id);
    }

    public function pdf() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Factura::conRelaciones($id);
        $lineas = Factura::getLineas($id);
        $config = getConfig();
        require_once __DIR__ . '/../core/PDF.php';
        generarPDFDocumento('Factura', $doc, $lineas, $config);
    }

    private function guardarLineas($idFactura) {
        $db = getDB();
        $db->prepare("DELETE FROM factura_det WHERE id_factura = ?")->execute([$idFactura]);

        if (empty($_POST['lineas'])) return;

        $orden = 0;
        foreach ($_POST['lineas'] as $linea) {
            if (empty($linea['descripcion']) && empty($linea['concepto'])) continue;
            $cantidad = (float)($linea['cantidad'] ?? 1);
            $precio = (float)($linea['precio'] ?? 0);
            $importe = $cantidad * $precio;

            $db->prepare("INSERT INTO factura_det (id_factura, id_tarea, cantidad, descripcion, precio, importe, orden) VALUES (?,?,?,?,?,?,?)")
               ->execute([$idFactura, $linea['id_tarea'] ?? null, $cantidad, $linea['descripcion'] ?? $linea['concepto'] ?? '', $precio, $importe, $orden++]);
        }
    }
}
