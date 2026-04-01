<?php $o = $operario; $isEdit = !empty($o); ?>
<div class="card form-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><?= $isEdit ? 'Editar Operario' : 'Nuevo Operario' ?></h6>
        <a href="index.php?c=operario" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php?c=operario&a=<?= $isEdit ? 'update' : 'store' ?>">
            <?= csrf_field() ?>
            <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $o['id'] ?>"><?php endif; ?>

            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Iniciales</label>
                    <input type="text" class="form-control" name="id_iniciales" value="<?= e($o['id_iniciales'] ?? '') ?>" maxlength="10" placeholder="JG">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" class="form-control" name="nombre" value="<?= e($o['nombre'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="apellidos" value="<?= e($o['apellidos'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Seccion</label>
                    <select class="form-select" name="seccion">
                        <option value="">Sin seccion</option>
                        <?php foreach (['mecanica','electricidad','chapa','pintura','tapiceria','diagnosis'] as $s): ?>
                            <option value="<?= $s ?>" <?= ($o['seccion'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Clave</label>
                    <input type="text" class="form-control" name="clave" value="<?= e($o['clave'] ?? '') ?>" placeholder="Clave de acceso">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Telefono</label>
                    <input type="text" class="form-control" name="telefono" value="<?= e($o['telefono'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= e($o['email'] ?? '') ?>">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Guardar' : 'Crear Operario' ?></button>
                <a href="index.php?c=operario" class="btn btn-outline-secondary ms-2">Cancelar</a>
            </div>
        </form>
    </div>
</div>
