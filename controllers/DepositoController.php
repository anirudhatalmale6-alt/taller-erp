<?php
class DepositoController extends Controller {

    public function index() {
        $this->requireAuth();
        $filtro_activo = $this->getParam('activo');
        $page = max(1, (int)$this->getParam('page', 1));

        $db = getDB();
        $where = '1=1';
        $params = [];
        if ($filtro_activo !== null && $filtro_activo !== '') {
            $where .= ' AND d.activo = ?';
            $params[] = $filtro_activo;
        }

        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $stmt = $db->prepare("SELECT d.*, CONCAT(cl.nombre, ' ', IFNULL(cl.apellidos,'')) as cliente_nombre, v.marca, v.modelo
            FROM dep_cab d
            LEFT JOIN clientes cl ON d.id_cliente = cl.id
            LEFT JOIN vehiculos v ON d.matricula = v.matricula
            WHERE $where ORDER BY d.fecha DESC, d.id DESC LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);
        $depositos = $stmt->fetchAll();

        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM dep_cab d WHERE $where");
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];

        $this->view('documentos/deposito_list', [
            'pageTitle' => 'Depositos',
            'depositos' => $depositos,
            'pagination' => ['page' => $page, 'totalPages' => ceil($total / $perPage)],
            'filtro_activo' => $filtro_activo,
        ]);
    }

    public function create() {
        $this->requireAuth();
        $clientes = Cliente::findAll("activo = 'SI'", [], 'nombre ASC');
        $operarios = Operario::activos();

        $this->view('documentos/deposito_form', [
            'pageTitle' => 'Nuevo Deposito',
            'deposito' => null,
            'clientes' => $clientes,
            'operarios' => $operarios,
            'selectedCliente' => (int)$this->getParam('id_cliente'),
            'selectedMatricula' => $this->getParam('matricula', ''),
        ]);
    }

    public function store() {
        $this->requireAuth();
        csrf_check();
        $data = $_POST;
        $data['id_deposito'] = generarNumero('deposito');
        $data['created_by'] = currentUser()['id'];
        $data['acepta_presupuesto'] = isset($_POST['acepta_presupuesto']) ? 'SI' : 'NO';
        $data['acepta_ocultos'] = isset($_POST['acepta_ocultos']) ? 'SI' : 'NO';
        $data['acepta_piezas'] = isset($_POST['acepta_piezas']) ? 'SI' : 'NO';
        $data['acepta_conduccion'] = isset($_POST['acepta_conduccion']) ? 'SI' : 'NO';
        $data['acepta_piezas_usadas'] = isset($_POST['acepta_piezas_usadas']) ? 'SI' : 'NO';
        $data['activo'] = 'SI';
        $id = Deposito::insert($data);

        // Handle signatures
        if (!empty($_POST['firma_resguardo_data'])) {
            $path = $this->guardarFirma($id, $_POST['firma_resguardo_data'], 'resguardo');
            if ($path) Deposito::update($id, ['firma_resguardo' => $path]);
        }
        if (!empty($_POST['firma_presupuesto_data'])) {
            $path = $this->guardarFirma($id, $_POST['firma_presupuesto_data'], 'presupuesto');
            if ($path) Deposito::update($id, ['firma_presupuesto' => $path]);
        }

        flash('success', 'Deposito creado: ' . $data['id_deposito']);
        redirect('index.php?c=deposito&a=show&id=' . $id);
    }

    public function show() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $deposito = Deposito::conRelaciones($id);
        if (!$deposito) { flash('error', 'Deposito no encontrado'); redirect('index.php?c=deposito'); }

        $fotos = Foto::porMatricula($deposito['matricula']);

        $this->view('documentos/deposito_show', [
            'pageTitle' => 'Deposito ' . $deposito['id_deposito'],
            'deposito' => $deposito,
            'fotos' => $fotos,
        ]);
    }

    public function edit() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $deposito = Deposito::findById($id);
        if (!$deposito) { flash('error', 'Deposito no encontrado'); redirect('index.php?c=deposito'); }

        $clientes = Cliente::findAll("activo = 'SI'", [], 'nombre ASC');
        $operarios = Operario::activos();

        $this->view('documentos/deposito_form', [
            'pageTitle' => 'Editar Deposito',
            'deposito' => $deposito,
            'clientes' => $clientes,
            'operarios' => $operarios,
            'selectedCliente' => $deposito['id_cliente'],
            'selectedMatricula' => $deposito['matricula'],
        ]);
    }

    public function update() {
        $this->requireAuth();
        csrf_check();
        $id = (int)$_POST['id'];
        $data = $_POST;
        $data['acepta_presupuesto'] = isset($_POST['acepta_presupuesto']) ? 'SI' : 'NO';
        $data['acepta_ocultos'] = isset($_POST['acepta_ocultos']) ? 'SI' : 'NO';
        $data['acepta_piezas'] = isset($_POST['acepta_piezas']) ? 'SI' : 'NO';
        $data['acepta_conduccion'] = isset($_POST['acepta_conduccion']) ? 'SI' : 'NO';
        $data['acepta_piezas_usadas'] = isset($_POST['acepta_piezas_usadas']) ? 'SI' : 'NO';
        Deposito::update($id, $data);

        if (!empty($_POST['firma_resguardo_data'])) {
            $path = $this->guardarFirma($id, $_POST['firma_resguardo_data'], 'resguardo');
            if ($path) Deposito::update($id, ['firma_resguardo' => $path]);
        }
        if (!empty($_POST['firma_presupuesto_data'])) {
            $path = $this->guardarFirma($id, $_POST['firma_presupuesto_data'], 'presupuesto');
            if ($path) Deposito::update($id, ['firma_presupuesto' => $path]);
        }

        flash('success', 'Deposito actualizado');
        redirect('index.php?c=deposito&a=show&id=' . $id);
    }

    public function convertir() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $deposito = Deposito::findById($id);
        if (!$deposito) { flash('error', 'Deposito no encontrado'); redirect('index.php?c=deposito'); }

        $config = getConfig();
        $idPreOrd = generarNumero('presupuesto');
        $presId = Presupuesto::insert([
            'id_pre_ord' => $idPreOrd,
            'tipo_doc' => 'PRESUPUESTO',
            'id_deposito' => $deposito['id_deposito'],
            'matricula' => $deposito['matricula'],
            'id_cliente' => $deposito['id_cliente'],
            'fecha' => date('Y-m-d'),
            'iva_porcentaje' => $config['iva_porcentaje'] ?? 21,
            'condiciones' => $config['condiciones_presupuesto'] ?? '',
            'notas' => $deposito['observaciones'],
            'activo' => 'SI',
            'created_by' => currentUser()['id'],
        ]);

        flash('success', 'Presupuesto creado: ' . $idPreOrd);
        redirect('index.php?c=presupuesto&a=edit&id=' . $presId);
    }

    public function toggleActivo() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $deposito = Deposito::findById($id);
        if (!$deposito) { flash('error', 'Deposito no encontrado'); redirect('index.php?c=deposito'); }

        $nuevoActivo = ($deposito['activo'] === 'SI') ? 'NO' : 'SI';
        Deposito::update($id, ['activo' => $nuevoActivo]);
        flash('success', 'Deposito ' . ($nuevoActivo === 'SI' ? 'activado' : 'desactivado'));
        redirect('index.php?c=deposito&a=show&id=' . $id);
    }

    private function guardarFirma($depositoId, $firmaData, $tipo) {
        if (strpos($firmaData, 'data:image') !== 0) return null;

        $dir = UPLOAD_PATH . 'firmas/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $filename = 'firma_' . $tipo . '_deposito_' . $depositoId . '_' . time() . '.png';
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $firmaData));
        file_put_contents($dir . $filename, $imageData);

        return 'uploads/firmas/' . $filename;
    }
}
