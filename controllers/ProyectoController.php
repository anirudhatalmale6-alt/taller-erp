<?php
class ProyectoController extends Controller {

    public function index() {
        $this->requireAuth();
        $estado = $this->getParam('estado');
        $page = max(1, (int)$this->getParam('page', 1));

        $db = getDB();
        $where = '1=1';
        $params = [];
        if ($estado) { $where .= ' AND p.estado = ?'; $params[] = $estado; }

        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $stmt = $db->prepare("SELECT p.*, CONCAT(cl.nombre, ' ', IFNULL(cl.apellidos,'')) as cliente_nombre, v.marca, v.modelo
            FROM proyecto_cab p
            LEFT JOIN clientes cl ON p.id_cliente = cl.id
            LEFT JOIN vehiculos v ON p.matricula = v.matricula
            WHERE $where ORDER BY p.created_at DESC LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);
        $proyectos = $stmt->fetchAll();

        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM proyecto_cab p WHERE $where");
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];

        $this->view('proyectos/index', [
            'pageTitle' => 'Proyectos',
            'proyectos' => $proyectos,
            'pagination' => ['page' => $page, 'totalPages' => ceil($total / $perPage)],
            'estadoFiltro' => $estado,
        ]);
    }

    public function create() {
        $this->requireAuth();
        $clientes = Cliente::findAll("activo = 'SI'", [], 'nombre ASC');
        $operarios = Operario::activos();

        $this->view('proyectos/form', [
            'pageTitle' => 'Nuevo Proyecto',
            'doc' => null,
            'lineas' => [],
            'apuntes' => [],
            'clientes' => $clientes,
            'operarios' => $operarios,
            'selectedCliente' => (int)$this->getParam('id_cliente'),
            'selectedMatricula' => $this->getParam('matricula', ''),
            'extraJs' => ['assets/js/documento.js'],
        ]);
    }

    public function store() {
        $this->requireAuth();
        csrf_check();
        $data = $_POST;
        $data['id_proyecto'] = generarNumero('proyecto');
        $data['activo'] = 'SI';
        $data['created_by'] = currentUser()['id'];
        $id = Proyecto::insert($data);

        $doc = Proyecto::findById($id);
        $this->guardarLineas($doc['id_proyecto']);
        Model::recalcularTotales('proyecto_cab', $id, 'proyecto_det', 'id_proyecto', $doc['id_proyecto']);

        flash('success', 'Proyecto creado: ' . $data['id_proyecto']);
        redirect('index.php?c=proyecto&a=show&id=' . $id);
    }

    public function show() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Proyecto::conRelaciones($id);
        if (!$doc) { flash('error', 'Proyecto no encontrado'); redirect('index.php?c=proyecto'); }
        $lineas = Proyecto::getLineas($id);
        $fotos = Foto::porMatricula($doc['matricula']);

        $this->view('proyectos/show', [
            'pageTitle' => 'Proyecto ' . $doc['id_proyecto'],
            'doc' => $doc,
            'lineas' => $lineas,
            'fotos' => $fotos,
        ]);
    }

    public function edit() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $doc = Proyecto::findById($id);
        if (!$doc) { flash('error', 'Proyecto no encontrado'); redirect('index.php?c=proyecto'); }
        $lineas = Proyecto::getLineas($id);
        $clientes = Cliente::findAll("activo = 'SI'", [], 'nombre ASC');
        $operarios = Operario::activos();

        $this->view('proyectos/form', [
            'pageTitle' => 'Editar Proyecto ' . $doc['id_proyecto'],
            'doc' => $doc,
            'lineas' => $lineas,
            'clientes' => $clientes,
            'operarios' => $operarios,
            'selectedCliente' => $doc['id_cliente'],
            'selectedMatricula' => $doc['matricula'],
            'extraJs' => ['assets/js/documento.js'],
        ]);
    }

    public function update() {
        $this->requireAuth();
        csrf_check();
        $id = (int)$_POST['id'];
        Proyecto::update($id, $_POST);

        $doc = Proyecto::findById($id);
        $this->guardarLineas($doc['id_proyecto']);
        Model::recalcularTotales('proyecto_cab', $id, 'proyecto_det', 'id_proyecto', $doc['id_proyecto']);

        flash('success', 'Proyecto actualizado');
        redirect('index.php?c=proyecto&a=show&id=' . $id);
    }

    public function estado() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $estado = $this->getParam('estado');
        Proyecto::update($id, ['estado' => $estado]);
        flash('success', 'Estado actualizado');
        redirect('index.php?c=proyecto&a=show&id=' . $id);
    }

    private function guardarLineas($idProyecto) {
        $db = getDB();
        $db->prepare("DELETE FROM proyecto_det WHERE id_proyecto = ?")->execute([$idProyecto]);

        if (empty($_POST['lineas'])) return;

        $orden = 0;
        foreach ($_POST['lineas'] as $linea) {
            if (empty($linea['descripcion']) && empty($linea['concepto'])) continue;
            $importe = (float)($linea['importe'] ?? 0);

            $db->prepare("INSERT INTO proyecto_det (id_proyecto, id_tarea, descripcion, importe, tiempo_asignado, orden) VALUES (?,?,?,?,?,?)")
               ->execute([$idProyecto, $linea['id_tarea'] ?? null, $linea['descripcion'] ?? $linea['concepto'] ?? '', $importe, $linea['tiempo_asignado'] ?? 0, $orden++]);
        }
    }
}
