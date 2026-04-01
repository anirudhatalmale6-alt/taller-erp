<?php
class AlbaranController extends Controller {

    public function index() {
        $this->requireAuth();
        $filtro_activo = $this->getParam('activo');
        $page = max(1, (int)$this->getParam('page', 1));

        $db = getDB();
        $where = '1=1';
        $params = [];
        if ($filtro_activo !== null && $filtro_activo !== '') {
            $where .= ' AND a.activo = ?';
            $params[] = $filtro_activo;
        }

        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $stmt = $db->prepare("SELECT a.*, CONCAT(cl.nombre, ' ', IFNULL(cl.apellidos,'')) as cliente_nombre, v.marca, v.modelo
            FROM albaran_cab a
            LEFT JOIN clientes cl ON a.id_cliente = cl.id
            LEFT JOIN vehiculos v ON a.matricula = v.matricula
            WHERE $where ORDER BY a.fecha DESC, a.id DESC LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);
        $docs = $stmt->fetchAll();

        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM albaran_cab a WHERE $where");
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];

        $this->view('documentos/documento_list', [
            'pageTitle' => 'Albaranes',
            'docs' => $docs,
            'tipo' => 'albaran',
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
            'pageTitle' => 'Nuevo Albaran',
            'doc' => null,
            'lineas' => [],
            'clientes' => $clientes,
            'operarios' => $operarios,
            'config' => $config,
            'tipo_doc' => 'PRESUPUESTO',
            'selectedCliente' => (int)$this->getParam('id_cliente'),
            'selectedMatricula' => $this->getParam('matricula', ''),
            'extraJs' => ['assets/js/documento.js'],
            'formAction' => 'albaran',
        ]);
    }

    public function store() {
        $this->requireAuth();
        csrf_check();
        $data = $_POST;
        $data['id_albaran'] = generarNumero('albaran');
        $data['activo'] = 'SI';
        $data['created_by'] = currentUser()['id'];
        $id = Albaran::insert($data);

        $doc = Albaran::findById($id);
        $this->guardarLineas($doc['id_albaran']);
        Model::recalcularTotales('albaran_cab', $id, 'albaran_det', 'id_albaran', $doc['id_albaran']);

        flash('success', 'Albaran creado: ' . $data['id_albaran']);
        redirect('index.php?c=albaran&a=show&id=' . $id);
    }

    public function show() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Albaran::conRelaciones($id);
        if (!$doc) { flash('error', 'Albaran no encontrado'); redirect('index.php?c=albaran'); }
        $lineas = Albaran::getLineas($id);

        $this->view('documentos/documento_show', [
            'pageTitle' => 'Albaran ' . $doc['id_albaran'],
            'doc' => $doc,
            'lineas' => $lineas,
            'tipo' => 'albaran',
            'nextAction' => 'factura',
            'nextLabel' => 'Crear Factura',
        ]);
    }

    public function edit() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Albaran::findById($id);
        if (!$doc) { flash('error', 'Albaran no encontrado'); redirect('index.php?c=albaran'); }
        $lineas = Albaran::getLineas($id);
        $clientes = Cliente::findAll("activo = 'SI'", [], 'nombre ASC');
        $operarios = Operario::activos();
        $config = getConfig();

        $this->view('documentos/presupuesto_form', [
            'pageTitle' => 'Editar Albaran ' . $doc['id_albaran'],
            'doc' => $doc,
            'lineas' => $lineas,
            'clientes' => $clientes,
            'operarios' => $operarios,
            'config' => $config,
            'tipo_doc' => $doc['tipo_doc'],
            'selectedCliente' => $doc['id_cliente'],
            'selectedMatricula' => $doc['matricula'],
            'extraJs' => ['assets/js/documento.js'],
            'formAction' => 'albaran',
        ]);
    }

    public function update() {
        $this->requireAuth();
        csrf_check();
        $id = (int)$_POST['id'];
        Albaran::update($id, $_POST);

        $doc = Albaran::findById($id);
        $this->guardarLineas($doc['id_albaran']);
        Model::recalcularTotales('albaran_cab', $id, 'albaran_det', 'id_albaran', $doc['id_albaran']);

        flash('success', 'Albaran actualizado');
        redirect('index.php?c=albaran&a=show&id=' . $id);
    }

    public function toggleActivo() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Albaran::findById($id);
        if (!$doc) { flash('error', 'Albaran no encontrado'); redirect('index.php?c=albaran'); }

        $nuevoActivo = ($doc['activo'] === 'SI') ? 'NO' : 'SI';
        Albaran::update($id, ['activo' => $nuevoActivo]);
        flash('success', 'Albaran ' . ($nuevoActivo === 'SI' ? 'activado' : 'desactivado'));
        redirect('index.php?c=albaran&a=show&id=' . $id);
    }

    public function convertir() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Albaran::findById($id);
        if (!$doc) { flash('error', 'Albaran no encontrado'); redirect('index.php?c=albaran'); }

        $config = getConfig();
        $idFactura = generarNumero('factura');
        $facturaId = Factura::insert([
            'id_factura' => $idFactura,
            'tipo_doc' => $doc['tipo_doc'],
            'matricula' => $doc['matricula'],
            'id_deposito' => $doc['id_deposito'],
            'id_cliente' => $doc['id_cliente'],
            'fecha' => date('Y-m-d'),
            'fecha_vencimiento' => date('Y-m-d', strtotime('+30 days')),
            'iva_porcentaje' => $doc['iva_porcentaje'],
            'descuento_porcentaje' => $doc['descuento_porcentaje'],
            'condiciones' => $config['condiciones_factura'] ?? '',
            'notas' => $doc['notas'],
            'activo' => 'SI',
            'estado' => 'borrador',
            'created_by' => currentUser()['id'],
        ]);

        Model::copiarLineas('albaran_det', 'id_albaran', $doc['id_albaran'], 'factura_det', 'id_factura', $idFactura);
        Model::recalcularTotales('factura_cab', $facturaId, 'factura_det', 'id_factura', $idFactura);
        Albaran::update($id, ['aceptado' => 'SI']);

        flash('success', 'Factura creada: ' . $idFactura);
        redirect('index.php?c=factura&a=show&id=' . $facturaId);
    }

    public function pdf() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Albaran::conRelaciones($id);
        $lineas = Albaran::getLineas($id);
        $config = getConfig();
        require_once __DIR__ . '/../core/PDF.php';
        generarPDFDocumento('Albaran', $doc, $lineas, $config);
    }

    private function guardarLineas($idAlbaran) {
        $db = getDB();
        $db->prepare("DELETE FROM albaran_det WHERE id_albaran = ?")->execute([$idAlbaran]);

        if (empty($_POST['lineas'])) return;

        $orden = 0;
        foreach ($_POST['lineas'] as $linea) {
            if (empty($linea['descripcion']) && empty($linea['concepto'])) continue;
            $cantidad = (float)($linea['cantidad'] ?? 1);
            $precio = (float)($linea['precio'] ?? 0);
            $importe = $cantidad * $precio;

            $db->prepare("INSERT INTO albaran_det (id_albaran, id_tarea, cantidad, descripcion, precio, importe, orden) VALUES (?,?,?,?,?,?,?)")
               ->execute([$idAlbaran, $linea['id_tarea'] ?? null, $cantidad, $linea['descripcion'] ?? $linea['concepto'] ?? '', $precio, $importe, $orden++]);
        }
    }
}
