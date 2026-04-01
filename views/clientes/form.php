<?php $c = $cliente; $isEdit = !empty($c); ?>

<div class="card form-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><?= $isEdit ? 'Editar Cliente' : 'Nuevo Cliente' ?></h6>
        <a href="index.php?c=cliente" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php?c=cliente&a=<?= $isEdit ? 'update' : 'store' ?>">
            <?= csrf_field() ?>
            <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $c['id'] ?>"><?php endif; ?>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nombre *</label>
                    <input type="text" class="form-control" name="nombre" value="<?= e($c['nombre'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="apellidos" value="<?= e($c['apellidos'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">NIF/CIF</label>
                    <input type="text" class="form-control" name="nif" value="<?= e($c['nif'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Direccion</label>
                    <input type="text" class="form-control" name="direccion" value="<?= e($c['direccion'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">C. Postal</label>
                    <input type="text" class="form-control" name="cpostal" value="<?= e($c['cpostal'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Poblacion</label>
                    <input type="text" class="form-control" name="poblacion" value="<?= e($c['poblacion'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Provincia</label>
                    <input type="text" class="form-control" name="provincia" value="<?= e($c['provincia'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pais</label>
                    <input type="text" class="form-control" name="pais" value="<?= e($c['pais'] ?? 'Espana') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Telefono</label>
                    <input type="text" class="form-control" name="telefono" value="<?= e($c['telefono'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= e($c['email'] ?? '') ?>">
                </div>

                <div class="col-12"><hr><h6 class="fw-bold text-muted">Redes Sociales</h6></div>
                <div class="col-md-3">
                    <label class="form-label">Red Social 1</label>
                    <input type="text" class="form-control" name="redsocial1" value="<?= e($c['redsocial1'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Red Social 2</label>
                    <input type="text" class="form-control" name="redsocial2" value="<?= e($c['redsocial2'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Red Social 3</label>
                    <input type="text" class="form-control" name="redsocial3" value="<?= e($c['redsocial3'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Red Social 4</label>
                    <input type="text" class="form-control" name="redsocial4" value="<?= e($c['redsocial4'] ?? '') ?>">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Guardar Cambios' : 'Crear Cliente' ?></button>
                <a href="index.php?c=cliente" class="btn btn-outline-secondary ms-2">Cancelar</a>
            </div>
        </form>
    </div>
</div>
