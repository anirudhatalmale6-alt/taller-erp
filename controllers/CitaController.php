<?php
class CitaController extends Controller {

    public function index() {
        $this->requireAuth();
        $estado = $this->getParam('estado');
        $page = max(1, (int)$this->getParam('page', 1));

        $where = '1=1';
        $params = [];
        if ($estado) { $where .= ' AND c.estado = ?'; $params[] = $estado; }

        $db = getDB();
        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM citas c WHERE " . str_replace('c.', '', $where));
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];

        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $stmt = $db->prepare("SELECT c.*, cl.nombre as cliente_nombre, v.matricula, v.marca, v.modelo, o.nombre as operario_nombre
            FROM citas c
            JOIN clientes cl ON c.id_cliente = cl.id
            JOIN vehiculos v ON c.id_vehiculo = v.id
            LEFT JOIN operarios o ON c.id_operario = o.id
            WHERE $where ORDER BY c.fecha_cita DESC LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);
        $citas = $stmt->fetchAll();

        $this->view('documentos/cita_list', [
            'pageTitle' => 'Citas',
            'citas' => $citas,
            'pagination' => ['page' => $page, 'totalPages' => ceil($total / $perPage)],
            'estado' => $estado,
        ]);
    }

    public function create() {
        $this->requireAuth();
        $clientes = Cliente::findAll('activo = 1', [], 'nombre ASC');
        $operarios = Operario::activos();

        $this->view('documentos/cita_form', [
            'pageTitle' => 'Nueva Cita',
            'cita' => null,
            'clientes' => $clientes,
            'operarios' => $operarios,
            'selectedCliente' => (int)$this->getParam('id_cliente'),
            'selectedVehiculo' => (int)$this->getParam('id_vehiculo'),
        ]);
    }

    public function store() {
        $this->requireAuth();
        csrf_check();
        $data = $_POST;
        $data['numero'] = generarNumero('cita');
        $data['created_by'] = currentUser()['id'];
        $id = Cita::insert($data);
        flash('success', 'Cita creada: ' . $data['numero']);
        redirect('index.php?c=cita&a=show&id=' . $id);
    }

    public function show() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $db = getDB();
        $stmt = $db->prepare("SELECT c.*, cl.nombre as cliente_nombre, cl.telefono as cliente_telefono, cl.email as cliente_email, v.matricula, v.marca, v.modelo, o.nombre as operario_nombre
            FROM citas c
            JOIN clientes cl ON c.id_cliente = cl.id
            JOIN vehiculos v ON c.id_vehiculo = v.id
            LEFT JOIN operarios o ON c.id_operario = o.id
            WHERE c.id = ?");
        $stmt->execute([$id]);
        $cita = $stmt->fetch();
        if (!$cita) { flash('error', 'Cita no encontrada'); redirect('index.php?c=cita'); }

        $this->view('documentos/cita_show', ['pageTitle' => 'Cita ' . $cita['numero'], 'cita' => $cita]);
    }

    public function edit() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $cita = Cita::findById($id);
        if (!$cita) { flash('error', 'Cita no encontrada'); redirect('index.php?c=cita'); }

        $clientes = Cliente::findAll('activo = 1', [], 'nombre ASC');
        $operarios = Operario::activos();

        $this->view('documentos/cita_form', [
            'pageTitle' => 'Editar Cita',
            'cita' => $cita,
            'clientes' => $clientes,
            'operarios' => $operarios,
            'selectedCliente' => $cita['id_cliente'],
            'selectedVehiculo' => $cita['id_vehiculo'],
        ]);
    }

    public function update() {
        $this->requireAuth();
        csrf_check();
        $id = (int)$_POST['id'];
        Cita::update($id, $_POST);
        flash('success', 'Cita actualizada');
        redirect('index.php?c=cita&a=show&id=' . $id);
    }

    public function estado() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $estado = $this->getParam('estado');
        Cita::update($id, ['estado' => $estado]);
        flash('success', 'Estado actualizado');
        redirect('index.php?c=cita&a=show&id=' . $id);
    }

    // Convert appointment to deposit
    public function convertir() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $cita = Cita::findById($id);
        if (!$cita) { flash('error', 'Cita no encontrada'); redirect('index.php?c=cita'); }

        $numero = generarNumero('deposito');
        $depositoId = Deposito::insert([
            'id_deposito' => $numero,
            'id_cliente' => $cita['id_cliente'],
            'matricula' => $cita['matricula'],
            'fecha' => date('Y-m-d'),
            'observaciones' => $cita['motivo'],
            'created_by' => currentUser()['id'],
        ]);

        Cita::update($id, ['estado' => 'completada']);
        flash('success', 'Deposito creado desde cita: ' . $numero);
        redirect('index.php?c=deposito&a=edit&id=' . $depositoId);
    }

    // Calendar JSON feed
    public function calendar() {
        $this->requireAuth();
        $start = $this->getParam('start');
        $end = $this->getParam('end');
        $citas = Cita::paraCalendario($start, $end);

        $colors = [
            'pendiente' => '#f59e0b',
            'confirmada' => '#3b82f6',
            'en_curso' => '#8b5cf6',
            'completada' => '#10b981',
            'cancelada' => '#ef4444',
        ];

        $events = array_map(fn($c) => [
            'id' => $c['id'],
            'title' => $c['cliente_nombre'] . ' - ' . $c['matricula'],
            'start' => $c['fecha_cita'],
            'end' => date('Y-m-d H:i:s', strtotime($c['fecha_cita']) + ($c['duracion_estimada'] * 60)),
            'url' => 'index.php?c=cita&a=show&id=' . $c['id'],
            'backgroundColor' => $colors[$c['estado']] ?? '#6b7280',
            'borderColor' => $colors[$c['estado']] ?? '#6b7280',
        ], $citas);

        $this->json($events);
    }

    public function delete() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        Cita::update($id, ['estado' => 'cancelada']);
        flash('success', 'Cita cancelada');
        redirect('index.php?c=cita');
    }
}
