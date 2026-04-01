<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
        <a href="index.php?c=proyecto" class="btn btn-sm <?= !$estadoFiltro ? 'btn-primary' : 'btn-outline-secondary' ?>">Todos</a>
        <?php foreach (['planificacion','en_curso','pausado','completado','cancelado'] as $e): ?>
            <a href="index.php?c=proyecto&estado=<?= $e ?>" class="btn btn-sm <?= $estadoFiltro === $e ? 'btn-primary' : 'btn-outline-secondary' ?>"><?= ucfirst(str_replace('_',' ',$e)) ?></a>
        <?php endforeach; ?>
    </div>
    <a href="index.php?c=proyecto&a=create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Proyecto</a>
</div>

<div class="row g-3">
<?php foreach ($proyectos as $p): ?>
    <div class="col-md-6 col-xl-4">
        <div class="card form-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="fw-bold mb-0"><a href="index.php?c=proyecto&a=show&id=<?= $p['id'] ?>" class="text-decoration-none"><?= e($p['descripcion'] ?: $p['id_proyecto']) ?></a></h6>
                        <small class="text-muted"><?= e($p['id_proyecto']) ?></small>
                    </div>
                    <?= statusBadge($p['estado']) ?>
                </div>
                <p class="small mb-2"><?= e($p['cliente_nombre']) ?> | <?= e($p['matricula']) ?> <?= e($p['marca'] ?? '') ?> <?= e($p['modelo'] ?? '') ?></p>
                <div class="progress mb-2" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: <?= $p['progreso'] ?>%"></div>
                </div>
                <div class="d-flex justify-content-between small text-muted">
                    <span><?= $p['progreso'] ?>% completado</span>
                    <span><?= money($p['total']) ?></span>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php if (empty($proyectos)): ?>
    <div class="col-12"><div class="text-center text-muted py-5">No hay proyectos</div></div>
<?php endif; ?>
</div>
