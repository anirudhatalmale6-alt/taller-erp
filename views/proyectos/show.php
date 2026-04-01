<?php $d = $doc; ?>
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <div>
        <a href="index.php?c=proyecto" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left me-1"></i>Volver</a>
        <a href="index.php?c=proyecto&a=edit&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
    </div>
    <div class="d-flex gap-2">
        <?php if ($d['estado'] !== 'completado'): ?>
            <a href="index.php?c=proyecto&a=estado&id=<?= $d['id'] ?>&estado=en_curso" class="btn btn-sm btn-info"><i class="bi bi-play me-1"></i>En Curso</a>
            <a href="index.php?c=proyecto&a=estado&id=<?= $d['id'] ?>&estado=completado" class="btn btn-sm btn-success"><i class="bi bi-check-lg me-1"></i>Completar</a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card form-card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="fw-bold mb-0"><?= e($d['descripcion'] ?: $d['id_proyecto']) ?></h4>
                    <?= statusBadge($d['estado']) ?>
                </div>
                <p class="text-muted mb-2"><?= e($d['id_proyecto']) ?> | <?= e($d['cliente_nombre']) ?> | <?= e($d['matricula']) ?> <?= e($d['marca'] ?? '') ?> <?= e($d['modelo'] ?? '') ?></p>
                <div class="progress mb-2" style="height: 10px;"><div class="progress-bar bg-primary" style="width: <?= $d['progreso'] ?>%"></div></div>
                <small class="text-muted"><?= $d['progreso'] ?>% completado | Fecha: <?= fecha($d['fecha']) ?></small>
                <?php if ($d['notas']): ?><hr><p><?= nl2br(e($d['notas'])) ?></p><?php endif; ?>
            </div>
        </div>

        <?php if (!empty($lineas)): ?>
        <div class="card form-card">
            <div class="card-header"><h6 class="mb-0 fw-bold">Lineas de Detalle</h6></div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0"><thead class="table-light"><tr><th>#</th><th>Descripcion</th><th>Importe</th></tr></thead><tbody>
                <?php foreach ($lineas as $i => $l): ?>
                    <tr><td><?= $i+1 ?></td><td><?= e($l['descripcion']) ?><?php if (!empty($l['tarea_nombre'])): ?> <small class="text-muted">- <?= e($l['tarea_nombre']) ?></small><?php endif; ?></td><td class="fw-bold"><?= money($l['importe']) ?></td></tr>
                <?php endforeach; ?>
                </tbody></table>
                <div class="p-3 text-end"><strong>Total: <?= money($d['total']) ?></strong></div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="col-lg-4">
        <div class="card form-card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-camera me-2"></i>Fotos</h6></div>
            <div class="card-body">
                <?php if (!empty($fotos)): ?>
                    <div class="foto-grid"><?php foreach ($fotos as $f): ?><div class="foto-item"><img src="<?= e($f['imagen']) ?>"></div><?php endforeach; ?></div>
                <?php else: ?><p class="text-muted small">Sin fotos</p><?php endif; ?>
                <form action="index.php?c=foto&a=upload" method="POST" enctype="multipart/form-data" class="mt-3">
                    <?= csrf_field() ?>
                    <input type="hidden" name="matricula" value="<?= e($d['matricula']) ?>">
                    <input type="hidden" name="redirect" value="index.php?c=proyecto&a=show&id=<?= $d['id'] ?>">
                    <input type="file" class="form-control form-control-sm" name="fotos[]" multiple accept="image/*">
                    <button type="submit" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-upload me-1"></i>Subir</button>
                </form>
            </div>
        </div>
    </div>
</div>
