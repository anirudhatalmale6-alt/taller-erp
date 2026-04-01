<?php $c = $cita; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="index.php?c=cita" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left me-1"></i>Volver</a>
        <a href="index.php?c=cita&a=edit&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
    </div>
    <div class="d-flex gap-2">
        <?php if (in_array($c['estado'], ['pendiente'])): ?>
            <a href="index.php?c=cita&a=estado&id=<?= $c['id'] ?>&estado=confirmada" class="btn btn-sm btn-info btn-status" data-status="Confirmada"><i class="bi bi-check me-1"></i>Confirmar</a>
        <?php endif; ?>
        <?php if (in_array($c['estado'], ['pendiente','confirmada'])): ?>
            <a href="index.php?c=cita&a=convertir&id=<?= $c['id'] ?>" class="btn btn-sm btn-success btn-status" data-status="Deposito">
                <i class="bi bi-arrow-right-circle me-1"></i>Crear Deposito
            </a>
        <?php endif; ?>
        <?php if (in_array($c['estado'], ['pendiente','confirmada','en_curso'])): ?>
            <a href="index.php?c=cita&a=estado&id=<?= $c['id'] ?>&estado=cancelada" class="btn btn-sm btn-outline-danger btn-status" data-status="Cancelada"><i class="bi bi-x me-1"></i>Cancelar</a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card form-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold"><?= e($c['numero']) ?></h5>
                    <?= statusBadge($c['estado']) ?>
                </div>
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted" width="130">Fecha/Hora</td><td><strong><?= fechaHora($c['fecha_cita']) ?></strong></td></tr>
                    <tr><td class="text-muted">Duracion</td><td><?= $c['duracion_estimada'] ?> min</td></tr>
                    <tr><td class="text-muted">Operario</td><td><?= e($c['operario_nombre'] ?? 'Sin asignar') ?></td></tr>
                </table>
                <?php if ($c['motivo']): ?>
                    <h6 class="fw-bold mt-3">Motivo</h6>
                    <p><?= nl2br(e($c['motivo'])) ?></p>
                <?php endif; ?>
                <?php if ($c['notas']): ?>
                    <h6 class="fw-bold mt-3">Notas</h6>
                    <p class="text-muted"><?= nl2br(e($c['notas'])) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card form-card mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-2"><i class="bi bi-person me-2"></i>Cliente</h6>
                <p class="mb-1"><a href="index.php?c=cliente&a=show&id=<?= $c['id_cliente'] ?>"><?= e($c['cliente_nombre']) ?></a></p>
                <small class="text-muted"><?= e($c['cliente_telefono']) ?> | <?= e($c['cliente_email']) ?></small>
            </div>
        </div>
        <div class="card form-card">
            <div class="card-body">
                <h6 class="fw-bold mb-2"><i class="bi bi-car-front me-2"></i>Vehiculo</h6>
                <p class="mb-1"><a href="index.php?c=vehiculo&a=show&id=<?= $c['id_vehiculo'] ?>"><?= e($c['matricula']) ?> - <?= e($c['marca']) ?> <?= e($c['modelo']) ?></a></p>
            </div>
        </div>
    </div>
</div>
