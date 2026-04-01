<?php
class ClienteController extends Controller {

    public function index() {
        $this->requireAuth();
        $search = $this->getParam('q');
        $page = max(1, (int)$this->getParam('page', 1));

        $where = "activo = 'SI'";
        $params = [];
        if ($search) {
            $where .= " AND (nombre LIKE ? OR apellidos LIKE ? OR nif LIKE ? OR telefono LIKE ? OR email LIKE ?)";
            $params = ["%$search%", "%$search%", "%$search%", "%$search%", "%$search%"];
        }

        $result = Cliente::paginate($page, 20, $where, $params, 'nombre ASC');

        $this->view('clientes/index', [
            'pageTitle' => 'Clientes',
            'clientes' => $result['data'],
            'pagination' => $result,
            'search' => $search,
        ]);
    }

    public function create() {
        $this->requireAuth();
        $this->view('clientes/form', ['pageTitle' => 'Nuevo Cliente', 'cliente' => null]);
    }

    public function store() {
        $this->requireAuth();
        csrf_check();
        $data = $_POST;
        $data['fecha_alta'] = date('Y-m-d');
        $id = Cliente::insert($data);
        flash('success', 'Cliente creado correctamente');
        redirect('index.php?c=cliente&a=show&id=' . $id);
    }

    public function edit() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $cliente = Cliente::findById($id);
        if (!$cliente) { flash('error', 'Cliente no encontrado'); redirect('index.php?c=cliente'); }
        $this->view('clientes/form', ['pageTitle' => 'Editar Cliente', 'cliente' => $cliente]);
    }

    public function update() {
        $this->requireAuth();
        csrf_check();
        $id = (int)$_POST['id'];
        $data = $_POST;
        $data['fecha_modificacion'] = date('Y-m-d');
        Cliente::update($id, $data);
        flash('success', 'Cliente actualizado correctamente');
        redirect('index.php?c=cliente&a=show&id=' . $id);
    }

    public function show() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $cliente = Cliente::findById($id);
        if (!$cliente) { flash('error', 'Cliente no encontrado'); redirect('index.php?c=cliente'); }

        $vehiculos = Cliente::getVehiculos($id);

        $db = getDB();
        $depositos = $db->prepare("SELECT * FROM dep_cab WHERE id_cliente = ? AND activo = 'SI' ORDER BY fecha DESC LIMIT 10");
        $depositos->execute([$id]); $depositos = $depositos->fetchAll();

        $presupuestos = $db->prepare("SELECT * FROM pre_ord_cab WHERE id_cliente = ? AND activo = 'SI' ORDER BY fecha DESC LIMIT 10");
        $presupuestos->execute([$id]); $presupuestos = $presupuestos->fetchAll();

        $albaranes = $db->prepare("SELECT * FROM albaran_cab WHERE id_cliente = ? AND activo = 'SI' ORDER BY fecha DESC LIMIT 10");
        $albaranes->execute([$id]); $albaranes = $albaranes->fetchAll();

        $facturas = $db->prepare("SELECT * FROM factura_cab WHERE id_cliente = ? AND activo = 'SI' ORDER BY fecha DESC LIMIT 10");
        $facturas->execute([$id]); $facturas = $facturas->fetchAll();

        $this->view('clientes/show', [
            'pageTitle' => Cliente::nombreCompleto($cliente),
            'cliente' => $cliente,
            'vehiculos' => $vehiculos,
            'depositos' => $depositos,
            'presupuestos' => $presupuestos,
            'albaranes' => $albaranes,
            'facturas' => $facturas,
        ]);
    }

    public function delete() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        Cliente::update($id, ['activo' => 'NO']);
        flash('success', 'Cliente eliminado');
        redirect('index.php?c=cliente');
    }

    public function search() {
        $q = $this->getParam('q');
        $clientes = Cliente::search($q);
        $this->json(array_map(fn($c) => [
            'id' => $c['id'],
            'text' => Cliente::nombreCompleto($c) . ($c['nif'] ? ' (' . $c['nif'] . ')' : ''),
            'nombre' => Cliente::nombreCompleto($c),
            'telefono' => $c['telefono'],
            'email' => $c['email'],
        ], $clientes));
    }
}
