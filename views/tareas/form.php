<?php $t = $tarea; $isEdit = !empty($t); ?>
<div class="card form-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><?= $isEdit ? 'Editar Tarea' : 'Nueva Tarea' ?></h6>
        <a href="index.php?c=tarea" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php?c=tarea&a=<?= $isEdit ? 'update' : 'store' ?>">
            <?= csrf_field() ?>
            <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $t['id'] ?>"><?php endif; ?>

            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Codigo Tarea</label>
                    <input type="text" class="form-control" name="id_tarea" value="<?= e($t['id_tarea'] ?? '') ?>" placeholder="REV-01">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Descripcion *</label>
                    <input type="text" class="form-control" name="descripcion" value="<?= e($t['descripcion'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Seccion</label>
                    <select class="form-select" name="seccion">
                        <option value="">Sin seccion</option>
                        <?php foreach (['mecanica','electricidad','chapa','pintura','tapiceria','diagnosis'] as $s): ?>
                            <option value="<?= $s ?>" <?= ($t['seccion'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Familia</label>
                    <input type="text" class="form-control" name="familia" value="<?= e($t['familia'] ?? '') ?>" placeholder="revision, mantenimiento, frenos...">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tiempo Previsto (minutos)</label>
                    <input type="number" class="form-control" name="tiempo_previsto" value="<?= e($t['tiempo_previsto'] ?? 0) ?>" min="0">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Guardar' : 'Crear Tarea' ?></button>
                <a href="index.php?c=tarea" class="btn btn-outline-secondary ms-2">Cancelar</a>
            </div>
        </form>
    </div>
</div>
