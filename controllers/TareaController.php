<?php
class TareaController extends Controller {

    public function index() {
        $this->requireAuth();
        $tareas = TareaCatalogo::findAll('1=1', [], 'seccion ASC, descripcion ASC');
        $this->view('tareas/index', ['pageTitle' => 'Catalogo de Tareas', 'tareas' => $tareas]);
    }

    public function create() {
        $this->requireAuth();
        $this->view('tareas/form', ['pageTitle' => 'Nueva Tarea', 'tarea' => null]);
    }

    public function store() {
        $this->requireAuth();
        csrf_check();
        TareaCatalogo::insert($_POST);
        flash('success', 'Tarea creada correctamente');
        redirect('index.php?c=tarea');
    }

    public function edit() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $tarea = TareaCatalogo::findById($id);
        if (!$tarea) { flash('error', 'Tarea no encontrada'); redirect('index.php?c=tarea'); }
        $this->view('tareas/form', ['pageTitle' => 'Editar Tarea', 'tarea' => $tarea]);
    }

    public function update() {
        $this->requireAuth();
        csrf_check();
        $id = (int)$_POST['id'];
        TareaCatalogo::update($id, $_POST);
        flash('success', 'Tarea actualizada');
        redirect('index.php?c=tarea');
    }

    public function delete() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        TareaCatalogo::update($id, ['activo' => 'NO']);
        flash('success', 'Tarea desactivada');
        redirect('index.php?c=tarea');
    }

    // AJAX - search tasks for document line items
    public function search() {
        $q = $this->getParam('q');
        $tareas = TareaCatalogo::findAll("activo = 'SI' AND (id_tarea LIKE ? OR descripcion LIKE ?)", ["%$q%", "%$q%"], 'nombre ASC', '20');
        $this->json($tareas);
    }
}
