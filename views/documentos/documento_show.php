<?php $d = $doc; $docNum = $d['id_pre_ord'] ?? $d['id_albaran'] ?? $d['id_factura'] ?? ''; ?>
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <div>
        <a href="index.php?c=<?= $tipo ?>" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left me-1"></i>Volver</a>
        <a href="index.php?c=<?= $tipo ?>&a=edit&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
    </div>
    <div class="d-flex gap-2">
        <?php if (!empty($nextAction) && $d['activo'] === 'SI'): ?>
            <a href="index.php?c=<?= $tipo ?>&a=convertir&id=<?= $d['id'] ?>" class="btn btn-sm btn-success">
                <i class="bi bi-arrow-right-circle me-1"></i><?= $nextLabel ?>
            </a>
        <?php endif; ?>
        <a href="index.php?c=<?= $tipo ?>&a=pdf&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" target="_blank"><i class="bi bi-file-pdf me-1"></i>PDF</a>
        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer me-1"></i>Imprimir</button>
        <?php if (!empty($d['cliente_telefono'])): ?>
            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $d['cliente_telefono']) ?>?text=<?= urlencode('Le envio el documento ' . $docNum) ?>" class="btn btn-sm btn-outline-success" target="_blank"><i class="bi bi-whatsapp me-1"></i>WhatsApp</a>
        <?php endif; ?>
    </div>
</div>

<div class="card form-card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <?php $config = getConfig(); ?>
                <h4 class="fw-bold"><?= e($config['empresa_nombre']) ?></h4>
                <p class="mb-0 small text-muted">
                    <?= e($config['empresa_cif']) ?><br>
                    <?= nl2br(e($config['empresa_direccion'])) ?><br>
                    <?= e($config['empresa_telefono']) ?> | <?= e($config['empresa_email']) ?>
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <h3 class="fw-bold text-uppercase"><?= ucfirst(e($tipo)) ?></h3>
                <h5><?= e($docNum) ?></h5>
                <p class="mb-0">
                    Fecha: <strong><?= fecha($d['fecha']) ?></strong><br>
                    <?php if ($d['activo'] === 'SI'): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactivo</span>
                    <?php endif; ?>
                    <?php if (($d['aceptado'] ?? '') === 'SI'): ?>
                        <span class="badge bg-primary">Aceptado</span>
                    <?php endif; ?>
                    <?php if (!empty($d['estado'])): ?>
                        <?= statusBadge($d['estado']) ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="fw-bold text-muted small text-uppercase">Cliente</h6>
                <p class="mb-0">
                    <strong><?= e($d['cliente_nombre']) ?></strong><br>
                    <?= e($d['nif'] ?? '') ?><br>
                    <?= e($d['cliente_direccion'] ?? '') ?><br>
                    <?= e($d['cliente_telefono'] ?? '') ?> | <?= e($d['cliente_email'] ?? '') ?>
                </p>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold text-muted small text-uppercase">Vehiculo</h6>
                <p class="mb-0">
                    <strong><?= e($d['matricula']) ?></strong> - <?= e($d['marca'] ?? '') ?> <?= e($d['modelo'] ?? '') ?>
                </p>
            </div>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr><th width="60">#</th><th>Descripcion</th><th width="80">Cant.</th><th width="100">Precio</th><th width="110">Importe</th></tr>
                </thead>
                <tbody>
                <?php if (empty($lineas)): ?>
                    <tr><td colspan="5" class="text-center text-muted">Sin lineas de detalle</td></tr>
                <?php endif; ?>
                <?php foreach ($lineas as $i => $l): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <?= e($l['descripcion']) ?>
                            <?php if (!empty($l['tarea_nombre'])): ?><br><small class="text-muted"><?= e($l['tarea_nombre']) ?></small><?php endif; ?>
                        </td>
                        <td class="text-center"><?= number_format($l['cantidad'], 2, ',', '') ?></td>
                        <td class="text-end"><?= money($l['precio']) ?></td>
                        <td class="text-end fw-bold"><?= money($l['importe']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end">
            <div class="col-md-4">
                <table class="table table-sm">
                    <tr><td>Subtotal</td><td class="text-end"><?= money($d['importe']) ?></td></tr>
                    <?php if (($d['descuento_porcentaje'] ?? 0) > 0): ?>
                        <tr><td>Descuento (<?= number_format($d['descuento_porcentaje'], 1) ?>%)</td><td class="text-end">-<?= money($d['descuento_importe']) ?></td></tr>
                    <?php endif; ?>
                    <tr><td>IVA (<?= number_format($d['iva_porcentaje'], 1) ?>%)</td><td class="text-end"><?= money($d['iva_importe']) ?></td></tr>
                    <tr class="table-primary fs-5"><td class="fw-bold">TOTAL</td><td class="text-end fw-bold"><?= money($d['total']) ?></td></tr>
                </table>
            </div>
        </div>

        <?php if (!empty($d['condiciones'])): ?>
            <hr><h6 class="fw-bold">Condiciones</h6>
            <p class="small"><?= nl2br(e($d['condiciones'])) ?></p>
        <?php endif; ?>
        <?php if (!empty($d['notas'])): ?>
            <h6 class="fw-bold">Notas</h6>
            <p class="small text-muted"><?= nl2br(e($d['notas'])) ?></p>
        <?php endif; ?>
        <?php if (!empty($d['forma_pago'])): ?>
            <p><strong>Forma de Pago:</strong> <?= ucfirst(e($d['forma_pago'])) ?></p>
        <?php endif; ?>
    </div>
</div>
