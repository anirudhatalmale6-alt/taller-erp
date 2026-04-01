<?php
class VehiculoController extends Controller {

    public function index() {
        $this->requireAuth();
        $search = $this->getParam('q');
        $page = max(1, (int)$this->getParam('page', 1));

        $where = "v.activo = 'SI'";
        $params = [];
        if ($search) {
            $where .= " AND (v.matricula LIKE ? OR v.marca LIKE ? OR v.modelo LIKE ? OR CONCAT(c.nombre, ' ', IFNULL(c.apellidos,'')) LIKE ?)";
            $params = ["%$search%", "%$search%", "%$search%", "%$search%"];
        }

        $db = getDB();
        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM vehiculos v JOIN clientes c ON v.id_cliente = c.id WHERE $where");
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];

        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $stmt = $db->prepare("SELECT v.*, CONCAT(c.nombre, ' ', IFNULL(c.apellidos,'')) as cliente_nombre FROM vehiculos v JOIN clientes c ON v.id_cliente = c.id WHERE $where ORDER BY v.matricula ASC LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);
        $vehiculos = $stmt->fetchAll();

        $this->view('vehiculos/index', [
            'pageTitle' => 'Vehiculos',
            'vehiculos' => $vehiculos,
            'pagination' => ['page' => $page, 'totalPages' => ceil($total / $perPage), 'total' => $total],
            'search' => $search,
        ]);
    }

    public function create() {
        $this->requireAuth();
        $clienteId = (int)$this->getParam('cliente_id');
        $clientes = Cliente::findAll("activo = 'SI'", [], 'nombre ASC');

        $this->view('vehiculos/form', [
            'pageTitle' => 'Nuevo Vehiculo',
            'vehiculo' => null,
            'clientes' => $clientes,
            'selectedCliente' => $clienteId,
        ]);
    }

    public function store() {
        $this->requireAuth();
        csrf_check();
        $id = Vehiculo::insert($_POST);
        flash('success', 'Vehiculo creado correctamente');
        redirect('index.php?c=vehiculo&a=show&id=' . $id);
    }

    public function edit() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $vehiculo = Vehiculo::findById($id);
        if (!$vehiculo) { flash('error', 'Vehiculo no encontrado'); redirect('index.php?c=vehiculo'); }

        $clientes = Cliente::findAll("activo = 'SI'", [], 'nombre ASC');

        $this->view('vehiculos/form', [
            'pageTitle' => 'Editar Vehiculo',
            'vehiculo' => $vehiculo,
            'clientes' => $clientes,
            'selectedCliente' => $vehiculo['id_cliente'],
        ]);
    }

    public function update() {
        $this->requireAuth();
        csrf_check();
        $id = (int)$_POST['id'];
        Vehiculo::update($id, $_POST);
        flash('success', 'Vehiculo actualizado correctamente');
        redirect('index.php?c=vehiculo&a=show&id=' . $id);
    }

    public function show() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $vehiculo = Vehiculo::conCliente($id);
        if (!$vehiculo) { flash('error', 'Vehiculo no encontrado'); redirect('index.php?c=vehiculo'); }

        $db = getDB();
        $matricula = $vehiculo['matricula'];

        $citas = $db->prepare("SELECT * FROM citas WHERE id_vehiculo = ? ORDER BY fecha_cita DESC LIMIT 10");
        $citas->execute([$id]); $citas = $citas->fetchAll();

        $depositos = $db->prepare("SELECT * FROM dep_cab WHERE matricula = ? ORDER BY fecha DESC LIMIT 10");
        $depositos->execute([$matricula]); $depositos = $depositos->fetchAll();

        $presupuestos = $db->prepare("SELECT * FROM pre_ord_cab WHERE matricula = ? AND tipo_doc = 'PRESUPUESTO' ORDER BY fecha DESC LIMIT 10");
        $presupuestos->execute([$matricula]); $presupuestos = $presupuestos->fetchAll();

        $albaranes = $db->prepare("SELECT * FROM albaran_cab WHERE matricula = ? ORDER BY fecha DESC LIMIT 10");
        $albaranes->execute([$matricula]); $albaranes = $albaranes->fetchAll();

        $facturas = $db->prepare("SELECT * FROM factura_cab WHERE matricula = ? ORDER BY fecha DESC LIMIT 10");
        $facturas->execute([$matricula]); $facturas = $facturas->fetchAll();

        $this->view('vehiculos/show', [
            'pageTitle' => $vehiculo['matricula'] . ' - ' . $vehiculo['marca'] . ' ' . $vehiculo['modelo'],
            'vehiculo' => $vehiculo,
            'citas' => $citas,
            'depositos' => $depositos,
            'presupuestos' => $presupuestos,
            'albaranes' => $albaranes,
            'facturas' => $facturas,
        ]);
    }

    public function delete() {
        $this->requireAuth();
        csrf_check();
        $id = (int)$this->getParam('id');
        Vehiculo::update($id, ['activo' => 'NO']);
        flash('success', 'Vehiculo eliminado');
        redirect('index.php?c=vehiculo');
    }
}
