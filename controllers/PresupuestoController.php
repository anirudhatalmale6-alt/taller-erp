<?php
class PresupuestoController extends Controller {

    public function index() {
        $this->requireAuth();
        $filtro_activo = $this->getParam('activo');
        $page = max(1, (int)$this->getParam('page', 1));

        $db = getDB();
        $where = "p.tipo_doc = 'PRESUPUESTO'";
        $params = [];
        if ($filtro_activo !== null && $filtro_activo !== '') {
            $where .= ' AND p.activo = ?';
            $params[] = $filtro_activo;
        }

        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $stmt = $db->prepare("SELECT p.*, CONCAT(cl.nombre, ' ', IFNULL(cl.apellidos,'')) as cliente_nombre, v.marca, v.modelo
            FROM pre_ord_cab p
            LEFT JOIN clientes cl ON p.id_cliente = cl.id
            LEFT JOIN vehiculos v ON p.matricula = v.matricula
            WHERE $where ORDER BY p.fecha DESC, p.id DESC LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);
        $docs = $stmt->fetchAll();

        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM pre_ord_cab p WHERE $where");
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];

        $this->view('documentos/documento_list', [
            'pageTitle' => 'Presupuestos',
            'docs' => $docs,
            'tipo' => 'presupuesto',
            'pagination' => ['page' => $page, 'totalPages' => ceil($total / $perPage)],
            'filtro_activo' => $filtro_activo,
            'showTotal' => true,
        ]);
    }

    public function create() {
        $this->requireAuth();
        $clientes = Cliente::findAll("activo = 'SI'", [], 'nombre ASC');
        $operarios = Operario::activos();
        $config = getConfig();

        $this->view('documentos/presupuesto_form', [
            'pageTitle' => 'Nuevo Presupuesto',
            'doc' => null,
            'lineas' => [],
            'clientes' => $clientes,
            'operarios' => $operarios,
            'config' => $config,
            'tipo_doc' => 'PRESUPUESTO',
            'selectedCliente' => (int)$this->getParam('id_cliente'),
            'selectedMatricula' => $this->getParam('matricula', ''),
            'extraJs' => ['assets/js/documento.js'],
        ]);
    }

    public function store() {
        $this->requireAuth();
        csrf_check();
        $data = $_POST;
        $data['id_pre_ord'] = generarNumero('presupuesto');
        $data['tipo_doc'] = 'PRESUPUESTO';
        $data['activo'] = 'SI';
        $data['created_by'] = currentUser()['id'];
        $id = Presupuesto::insert($data);

        $doc = Presupuesto::findById($id);
        $this->guardarLineas($doc['id_pre_ord']);
        Model::recalcularTotales('pre_ord_cab', $id, 'pre_ord_det', 'id_pre_ord', $doc['id_pre_ord']);

        flash('success', 'Presupuesto creado: ' . $data['id_pre_ord']);
        redirect('index.php?c=presupuesto&a=show&id=' . $id);
    }

    public function show() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Presupuesto::conRelaciones($id);
        if (!$doc || $doc['tipo_doc'] !== 'PRESUPUESTO') { flash('error', 'Presupuesto no encontrado'); redirect('index.php?c=presupuesto'); }

        $lineas = Presupuesto::getLineas($id);

        $this->view('documentos/documento_show', [
            'pageTitle' => 'Presupuesto ' . $doc['id_pre_ord'],
            'doc' => $doc,
            'lineas' => $lineas,
            'tipo' => 'presupuesto',
            'nextAction' => 'albaran',
            'nextLabel' => 'Crear Albaran',
        ]);
    }

    public function edit() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Presupuesto::findById($id);
        if (!$doc) { flash('error', 'Presupuesto no encontrado'); redirect('index.php?c=presupuesto'); }

        $lineas = Presupuesto::getLineas($id);
        $clientes = Cliente::findAll("activo = 'SI'", [], 'nombre ASC');
        $operarios = Operario::activos();
        $config = getConfig();

        $this->view('documentos/presupuesto_form', [
            'pageTitle' => 'Editar Presupuesto ' . $doc['id_pre_ord'],
            'doc' => $doc,
            'lineas' => $lineas,
            'clientes' => $clientes,
            'operarios' => $operarios,
            'config' => $config,
            'tipo_doc' => 'PRESUPUESTO',
            'selectedCliente' => $doc['id_cliente'],
            'selectedMatricula' => $doc['matricula'],
            'extraJs' => ['assets/js/documento.js'],
        ]);
    }

    public function update() {
        $this->requireAuth();
        csrf_check();
        $id = (int)$_POST['id'];
        Presupuesto::update($id, $_POST);

        $doc = Presupuesto::findById($id);
        $this->guardarLineas($doc['id_pre_ord']);
        Model::recalcularTotales('pre_ord_cab', $id, 'pre_ord_det', 'id_pre_ord', $doc['id_pre_ord']);

        flash('success', 'Presupuesto actualizado');
        redirect('index.php?c=presupuesto&a=show&id=' . $id);
    }

    public function toggleActivo() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Presupuesto::findById($id);
        if (!$doc) { flash('error', 'Presupuesto no encontrado'); redirect('index.php?c=presupuesto'); }

        $nuevoActivo = ($doc['activo'] === 'SI') ? 'NO' : 'SI';
        Presupuesto::update($id, ['activo' => $nuevoActivo]);
        flash('success', 'Presupuesto ' . ($nuevoActivo === 'SI' ? 'activado' : 'desactivado'));
        redirect('index.php?c=presupuesto&a=show&id=' . $id);
    }

    public function aceptar() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $aceptado = $this->getParam('aceptado', 'SI');
        Presupuesto::update($id, ['aceptado' => $aceptado]);
        flash('success', 'Presupuesto ' . ($aceptado === 'SI' ? 'aceptado' : 'no aceptado'));
        redirect('index.php?c=presupuesto&a=show&id=' . $id);
    }

    public function convertir() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Presupuesto::findById($id);
        if (!$doc) { flash('error', 'Presupuesto no encontrado'); redirect('index.php?c=presupuesto'); }

        $config = getConfig();
        $idAlbaran = generarNumero('albaran');
        $albaranId = Albaran::insert([
            'id_albaran' => $idAlbaran,
            'tipo_doc' => $doc['tipo_doc'],
            'matricula' => $doc['matricula'],
            'id_deposito' => $doc['id_deposito'],
            'id_cliente' => $doc['id_cliente'],
            'fecha' => date('Y-m-d'),
            'iva_porcentaje' => $doc['iva_porcentaje'],
            'descuento_porcentaje' => $doc['descuento_porcentaje'],
            'notas' => $doc['notas'],
            'activo' => 'SI',
            'created_by' => currentUser()['id'],
        ]);

        Model::copiarLineas('pre_ord_det', 'id_pre_ord', $doc['id_pre_ord'], 'albaran_det', 'id_albaran', $idAlbaran);
        Model::recalcularTotales('albaran_cab', $albaranId, 'albaran_det', 'id_albaran', $idAlbaran);
        Presupuesto::update($id, ['aceptado' => 'SI']);

        flash('success', 'Albaran creado: ' . $idAlbaran);
        redirect('index.php?c=albaran&a=edit&id=' . $albaranId);
    }

    public function pdf() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Presupuesto::conRelaciones($id);
        $lineas = Presupuesto::getLineas($id);
        $config = getConfig();
        require_once __DIR__ . '/../core/PDF.php';
        generarPDFDocumento('Presupuesto', $doc, $lineas, $config);
    }

    private function guardarLineas($idPreOrd) {
        $db = getDB();
        $db->prepare("DELETE FROM pre_ord_det WHERE id_pre_ord = ?")->execute([$idPreOrd]);

        if (empty($_POST['lineas'])) return;

        $orden = 0;
        foreach ($_POST['lineas'] as $linea) {
            if (empty($linea['descripcion']) && empty($linea['concepto'])) continue;
            $cantidad = (float)($linea['cantidad'] ?? 1);
            $precio = (float)($linea['precio'] ?? 0);
            $importe = $cantidad * $precio;

            $db->prepare("INSERT INTO pre_ord_det (id_pre_ord, id_tarea, cantidad, descripcion, precio, importe, orden) VALUES (?,?,?,?,?,?,?)")
               ->execute([$idPreOrd, $linea['id_tarea'] ?? null, $cantidad, $linea['descripcion'] ?? $linea['concepto'] ?? '', $precio, $importe, $orden++]);
        }
    }
}
