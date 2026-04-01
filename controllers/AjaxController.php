<?php
class AjaxController extends Controller {

    // Used by deposito form and other forms to load vehicles by client
    public function vehiculosCliente() {
        $this->requireAuth();
        $clienteId = (int)$this->getParam('id');
        if (!$clienteId) $clienteId = (int)$this->getParam('cliente_id');
        $vehiculos = Vehiculo::findAll("id_cliente = ? AND activo = 'SI'", [$clienteId], 'matricula ASC');
        $this->json($vehiculos);
    }

    public function vehiculos() {
        $this->requireAuth();
        $clienteId = (int)$this->getParam('id');
        if (!$clienteId) $clienteId = (int)$this->getParam('cliente_id');
        $vehiculos = Vehiculo::findAll("id_cliente = ? AND activo = 'SI'", [$clienteId], 'matricula ASC');
        $this->json($vehiculos);
    }

    public function clientes() {
        $this->requireAuth();
        $q = $this->getParam('q');
        $clientes = Cliente::search($q);
        $this->json(array_map(fn($c) => [
            'id' => $c['id'],
            'text' => Cliente::nombreCompleto($c) . ($c['telefono'] ? ' - ' . $c['telefono'] : ''),
        ], $clientes));
    }

    public function tareas() {
        $this->requireAuth();
        $q = $this->getParam('q');
        $tareas = TareaCatalogo::findAll("activo = 'SI' AND (id_tarea LIKE ? OR descripcion LIKE ?)", ["%$q%", "%$q%"], 'descripcion ASC', '20');
        $this->json(array_map(fn($t) => [
            'id' => $t['id'],
            'text' => $t['id_tarea'] . ' - ' . $t['descripcion'],
            'id_tarea_code' => $t['id_tarea'],
            'descripcion' => $t['descripcion'],
            'tiempo_previsto' => $t['tiempo_previsto'],
        ], $tareas));
    }
}
