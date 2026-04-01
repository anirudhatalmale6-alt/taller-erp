<?php $c = $config; ?>
<div class="card form-card">
    <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-gear me-2"></i>Configuracion del Taller</h6></div>
    <div class="card-body">
        <form method="POST" action="index.php?c=config&a=update" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <h6 class="fw-bold mb-3 text-primary">Datos de la Empresa</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6"><label class="form-label">Nombre de la Empresa</label><input type="text" class="form-control" name="empresa_nombre" value="<?= e($c['empresa_nombre']) ?>"></div>
                <div class="col-md-3"><label class="form-label">CIF</label><input type="text" class="form-control" name="empresa_cif" value="<?= e($c['empresa_cif']) ?>"></div>
                <div class="col-md-3"><label class="form-label">Telefono</label><input type="text" class="form-control" name="empresa_telefono" value="<?= e($c['empresa_telefono']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Direccion</label><textarea class="form-control" name="empresa_direccion" rows="2"><?= e($c['empresa_direccion']) ?></textarea></div>
                <div class="col-md-3"><label class="form-label">Email</label><input type="email" class="form-control" name="empresa_email" value="<?= e($c['empresa_email']) ?>"></div>
                <div class="col-md-3">
                    <label class="form-label">Logo</label>
                    <input type="file" class="form-control" name="empresa_logo" accept="image/*">
                    <?php if ($c['empresa_logo']): ?><img src="<?= e($c['empresa_logo']) ?>" class="mt-2 border" style="max-height:50px"><?php endif; ?>
                </div>
            </div>

            <h6 class="fw-bold mb-3 text-primary">Impuestos y Moneda</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-3"><label class="form-label">IVA (%)</label><input type="number" class="form-control" name="iva_porcentaje" value="<?= e($c['iva_porcentaje']) ?>" step="0.01"></div>
                <div class="col-md-3"><label class="form-label">Moneda</label><input type="text" class="form-control" name="moneda" value="<?= e($c['moneda']) ?>"></div>
            </div>

            <h6 class="fw-bold mb-3 text-primary">Prefijos de Documentos</h6>
            <div class="row g-3 mb-4">
                <?php foreach (['cita','deposito','presupuesto','albaran','factura','proforma','proyecto'] as $p): ?>
                <div class="col-md-3 col-lg-2">
                    <label class="form-label"><?= ucfirst($p) ?></label>
                    <input type="text" class="form-control form-control-sm" name="prefijo_<?= $p ?>" value="<?= e($c['prefijo_'.$p]) ?>">
                </div>
                <?php endforeach; ?>
            </div>

            <h6 class="fw-bold mb-3 text-primary">Condiciones por Defecto</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6"><label class="form-label">Condiciones Presupuesto</label><textarea class="form-control" name="condiciones_presupuesto" rows="3"><?= e($c['condiciones_presupuesto']) ?></textarea></div>
                <div class="col-md-6"><label class="form-label">Condiciones Factura</label><textarea class="form-control" name="condiciones_factura" rows="3"><?= e($c['condiciones_factura']) ?></textarea></div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Guardar Configuracion</button>
        </form>
    </div>
</div>
