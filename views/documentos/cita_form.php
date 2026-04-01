<?php $c = $cita; $isEdit = !empty($c); ?>
<div class="card form-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><?= $isEdit ? 'Editar Cita ' . e($c['numero']) : 'Nueva Cita' ?></h6>
        <a href="index.php?c=cita" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php?c=cita&a=<?= $isEdit ? 'update' : 'store' ?>">
            <?= csrf_field() ?>
            <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $c['id'] ?>"><?php endif; ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Cliente *</label>
                    <select class="form-select select2" name="id_cliente" id="clienteSelect" required onchange="cargarVehiculos(this.value, 'vehiculoSelect', '<?= $selectedVehiculo ?>')">
                        <option value="">Seleccionar cliente...</option>
                        <?php foreach ($clientes as $cl): ?>
                            <option value="<?= $cl['id'] ?>" <?= ($selectedCliente == $cl['id']) ? 'selected' : '' ?>><?= e($cl['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vehiculo *</label>
                    <select class="form-select" name="id_vehiculo" id="vehiculoSelect" required>
                        <option value="">Seleccionar vehiculo...</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha y Hora *</label>
                    <input type="datetime-local" class="form-control" name="fecha_cita" value="<?= e($c['fecha_cita'] ?? ($_GET['fecha'] ?? '') . 'T09:00') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Duracion Estimada (min)</label>
                    <input type="number" class="form-control" name="duracion_estimada" value="<?= e($c['duracion_estimada'] ?? 60) ?>" min="15" step="15">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Operario</label>
                    <select class="form-select" name="id_operario">
                        <option value="">Sin asignar</option>
                        <?php foreach ($operarios as $o): ?>
                            <option value="<?= $o['id'] ?>" <?= ($c['id_operario'] ?? '') == $o['id'] ? 'selected' : '' ?>><?= e($o['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($isEdit): ?>
                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select class="form-select" name="estado">
                        <?php foreach (['pendiente','confirmada','en_curso','completada','cancelada'] as $e): ?>
                            <option value="<?= $e ?>" <?= ($c['estado'] ?? '') === $e ? 'selected' : '' ?>><?= ucfirst($e) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="col-12">
                    <label class="form-label">Motivo / Descripcion del trabajo</label>
                    <textarea class="form-control" name="motivo" rows="3"><?= e($c['motivo'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Notas internas</label>
                    <textarea class="form-control" name="notas" rows="2"><?= e($c['notas'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Guardar Cambios' : 'Crear Cita' ?></button>
                <a href="index.php?c=cita" class="btn btn-outline-secondary ms-2">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var clienteId = '<?= $selectedCliente ?>';
    var vehiculoId = '<?= $selectedVehiculo ?>';
    if (clienteId) cargarVehiculos(clienteId, 'vehiculoSelect', vehiculoId);
});
</script>
