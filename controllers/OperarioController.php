<?php
class OperarioController extends Controller {

    public function index() {
        $this->requireAuth();
        $operarios = Operario::findAll('1=1', [], 'activo DESC, nombre ASC');
        $this->view('operarios/index', ['pageTitle' => 'Operarios', 'operarios' => $operarios]);
    }

    public function create() {
        $this->requireAuth();
        $this->view('operarios/form', ['pageTitle' => 'Nuevo Operario', 'operario' => null]);
    }

    public function store() {
        $this->requireAuth();
        csrf_check();
        Operario::insert($_POST);
        flash('success', 'Operario creado correctamente');
        redirect('index.php?c=operario');
    }

    public function edit() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $operario = Operario::findById($id);
        if (!$operario) { flash('error', 'Operario no encontrado'); redirect('index.php?c=operario'); }
        $this->view('operarios/form', ['pageTitle' => 'Editar Operario', 'operario' => $operario]);
    }

    public function update() {
        $this->requireAuth();
        csrf_check();
        $id = (int)$_POST['id'];
        Operario::update($id, $_POST);
        flash('success', 'Operario actualizado');
        redirect('index.php?c=operario');
    }

    public function delete() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        Operario::update($id, ['activo' => 'NO']);
        flash('success', 'Operario desactivado');
        redirect('index.php?c=operario');
    }
}
