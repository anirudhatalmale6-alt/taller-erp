<?php $d = $deposito; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="index.php?c=deposito" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left me-1"></i>Volver</a>
        <a href="index.php?c=deposito&a=edit&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
    </div>
    <div class="d-flex gap-2">
        <?php if ($d['activo'] === 'SI'): ?>
            <a href="index.php?c=deposito&a=convertir&id=<?= $d['id'] ?>" class="btn btn-sm btn-success">
                <i class="bi bi-arrow-right-circle me-1"></i>Crear Presupuesto
            </a>
            <a href="index.php?c=deposito&a=toggleActivo&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-secondary" onclick="return confirm('Desactivar este deposito?')">
                <i class="bi bi-x-circle me-1"></i>Desactivar
            </a>
        <?php else: ?>
            <a href="index.php?c=deposito&a=toggleActivo&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-success">
                <i class="bi bi-check-circle me-1"></i>Activar
            </a>
        <?php endif; ?>
        <a href="index.php?c=deposito&a=pdf&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" target="_blank"><i class="bi bi-file-pdf me-1"></i>PDF</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold"><?= e($d['id_deposito']) ?></h5>
                    <?php if ($d['activo'] === 'SI'): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactivo</span>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td class="text-muted" width="130">Fecha</td><td><strong><?= fecha($d['fecha']) ?></strong></td></tr>
                            <tr><td class="text-muted">Hora</td><td><?= e($d['hora'] ?? '-') ?></td></tr>
                            <tr><td class="text-muted">Kilometros</td><td><?= $d['kilometros'] ? number_format($d['kilometros'], 0, '', '.') : '-' ?></td></tr>
                            <tr><td class="text-muted">Combustible</td><td><?= e($d['nivel_combustible'] ?? '-') ?></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Cliente</h6>
                        <p class="mb-1"><a href="index.php?c=cliente&a=show&id=<?= $d['id_cliente'] ?>"><?= e($d['cliente_nombre']) ?></a></p>
                        <p class="mb-1 small text-muted"><?= e($d['cliente_telefono'] ?? '') ?></p>
                        <h6 class="fw-bold mt-2">Vehiculo</h6>
                        <p class="mb-0"><?= e($d['matricula']) ?> - <?= e($d['marca'] ?? '') ?> <?= e($d['modelo'] ?? '') ?> <?= e($d['color'] ?? '') ?></p>
                    </div>
                </div>

                <!-- Acceptance flags -->
                <hr>
                <h6 class="fw-bold">Autorizaciones del Cliente</h6>
                <div class="row g-2 mb-3">
                    <div class="col-md-4"><span class="badge <?= $d['acepta_presupuesto'] === 'SI' ? 'bg-success' : 'bg-secondary' ?>">Presupuesto: <?= e($d['acepta_presupuesto']) ?></span></div>
                    <div class="col-md-4"><span class="badge <?= $d['acepta_ocultos'] === 'SI' ? 'bg-success' : 'bg-secondary' ?>">Ocultos: <?= e($d['acepta_ocultos']) ?></span></div>
                    <div class="col-md-4"><span class="badge <?= $d['acepta_piezas'] === 'SI' ? 'bg-success' : 'bg-secondary' ?>">Piezas: <?= e($d['acepta_piezas']) ?></span></div>
                    <div class="col-md-4"><span class="badge <?= $d['acepta_conduccion'] === 'SI' ? 'bg-success' : 'bg-secondary' ?>">Conduccion: <?= e($d['acepta_conduccion']) ?></span></div>
                    <div class="col-md-4"><span class="badge <?= $d['acepta_piezas_usadas'] === 'SI' ? 'bg-success' : 'bg-secondary' ?>">Piezas Usadas: <?= e($d['acepta_piezas_usadas']) ?></span></div>
                </div>

                <?php if ($d['descripcion_trabajos']): ?>
                    <hr><h6 class="fw-bold">Descripcion de Trabajos</h6>
                    <p><?= nl2br(e($d['descripcion_trabajos'])) ?></p>
                <?php endif; ?>
                <?php if ($d['observaciones']): ?>
                    <h6 class="fw-bold">Observaciones</h6>
                    <p><?= nl2br(e($d['observaciones'])) ?></p>
                <?php endif; ?>

                <!-- Signatures -->
                <?php if ($d['firma_resguardo']): ?>
                    <hr><h6 class="fw-bold">Firma Resguardo</h6>
                    <img src="<?= e($d['firma_resguardo']) ?>" class="border rounded" style="max-height:150px">
                <?php endif; ?>
                <?php if ($d['firma_presupuesto']): ?>
                    <hr><h6 class="fw-bold">Firma Presupuesto</h6>
                    <img src="<?= e($d['firma_presupuesto']) ?>" class="border rounded" style="max-height:150px">
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card form-card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-camera me-2"></i>Fotos</h6></div>
            <div class="card-body">
                <?php if (empty($fotos)): ?>
                    <p class="text-muted small">Sin fotos</p>
                <?php else: ?>
                    <div class="foto-grid">
                        <?php foreach ($fotos as $f): ?>
                            <div class="foto-item">
                                <img src="<?= e($f['imagen']) ?>" alt="">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form action="index.php?c=foto&a=upload" method="POST" enctype="multipart/form-data" class="mt-3">
                    <?= csrf_field() ?>
                    <input type="hidden" name="matricula" value="<?= e($d['matricula']) ?>">
                    <input type="hidden" name="redirect" value="index.php?c=deposito&a=show&id=<?= $d['id'] ?>">
                    <input type="file" class="form-control form-control-sm" name="fotos[]" multiple accept="image/*">
                    <button type="submit" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-upload me-1"></i>Subir</button>
                </form>
            </div>
        </div>
    </div>
</div>
