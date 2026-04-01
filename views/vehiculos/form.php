<?php $v = $vehiculo; $isEdit = !empty($v); ?>

<div class="card form-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><?= $isEdit ? 'Editar Vehiculo' : 'Nuevo Vehiculo' ?></h6>
        <a href="index.php?c=vehiculo" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php?c=vehiculo&a=<?= $isEdit ? 'update' : 'store' ?>">
            <?= csrf_field() ?>
            <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $v['id'] ?>"><?php endif; ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Cliente *</label>
                    <select class="form-select select2" name="id_cliente" required>
                        <option value="">Seleccionar cliente...</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($selectedCliente == $c['id']) ? 'selected' : '' ?>><?= e($c['nombre'] . ' ' . ($c['apellidos'] ?? '')) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Matricula *</label>
                    <input type="text" class="form-control" name="matricula" value="<?= e($v['matricula'] ?? '') ?>" required style="text-transform:uppercase">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Num. Chasis</label>
                    <input type="text" class="form-control" name="num_chasis" value="<?= e($v['num_chasis'] ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Marca</label>
                    <input type="text" class="form-control" name="marca" value="<?= e($v['marca'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Modelo</label>
                    <input type="text" class="form-control" name="modelo" value="<?= e($v['modelo'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Version</label>
                    <input type="text" class="form-control" name="version_modelo" value="<?= e($v['version_modelo'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Color</label>
                    <input type="text" class="form-control" name="color" value="<?= e($v['color'] ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Potencia</label>
                    <input type="text" class="form-control" name="potencia" value="<?= e($v['potencia'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ano (Modelo)</label>
                    <input type="number" class="form-control" name="anio" value="<?= e($v['anio'] ?? '') ?>" min="1950" max="2030">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Num. Motor</label>
                    <input type="text" class="form-control" name="num_motor" value="<?= e($v['num_motor'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Emisiones</label>
                    <input type="text" class="form-control" name="emisiones" value="<?= e($v['emisiones'] ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tipo Aceite</label>
                    <input type="text" class="form-control" name="tipo_aceite" value="<?= e($v['tipo_aceite'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha Matriculacion</label>
                    <input type="date" class="form-control" name="fecha_matriculacion" value="<?= e($v['fecha_matriculacion'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ano Fabricacion</label>
                    <input type="number" class="form-control" name="ano_fabricacion" value="<?= e($v['ano_fabricacion'] ?? '') ?>" min="1950" max="2030">
                </div>

                <div class="col-md-3 d-flex align-items-end gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="en_venta" value="SI" id="chkEnVenta" <?= ($v['en_venta'] ?? '') === 'SI' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="chkEnVenta">En Venta</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="vendido" value="SI" id="chkVendido" <?= ($v['vendido'] ?? '') === 'SI' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="chkVendido">Vendido</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="sustitucion" value="SI" id="chkSustitucion" <?= ($v['sustitucion'] ?? '') === 'SI' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="chkSustitucion">Sustitucion</label>
                    </div>
                </div>


            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Guardar Cambios' : 'Crear Vehiculo' ?></button>
                <a href="index.php?c=vehiculo" class="btn btn-outline-secondary ms-2">Cancelar</a>
            </div>
        </form>
    </div>
</div>
