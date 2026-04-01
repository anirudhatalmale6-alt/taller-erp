<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
        <a href="index.php?c=cita" class="btn btn-sm <?= !$estado ? 'btn-primary' : 'btn-outline-secondary' ?>">Todas</a>
        <?php foreach (['pendiente','confirmada','en_curso','completada','cancelada'] as $e): ?>
            <a href="index.php?c=cita&estado=<?= $e ?>" class="btn btn-sm <?= $estado === $e ? 'btn-primary' : 'btn-outline-secondary' ?>"><?= ucfirst($e) ?></a>
        <?php endforeach; ?>
    </div>
    <a href="index.php?c=cita&a=create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Cita</a>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Numero</th><th>Fecha/Hora</th><th>Cliente</th><th>Vehiculo</th><th>Operario</th><th>Estado</th><th width="150">Acciones</th></tr></thead>
                <tbody>
                <?php if (empty($citas)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No hay citas</td></tr>
                <?php endif; ?>
                <?php foreach ($citas as $c): ?>
                    <tr>
                        <td><a href="index.php?c=cita&a=show&id=<?= $c['id'] ?>" class="fw-semibold"><?= e($c['numero']) ?></a></td>
                        <td><?= fechaHora($c['fecha_cita']) ?></td>
                        <td><?= e($c['cliente_nombre']) ?></td>
                        <td><?= e($c['matricula']) ?> <?= e($c['marca']) ?></td>
                        <td><?= e($c['operario_nombre'] ?? '-') ?></td>
                        <td><?= statusBadge($c['estado']) ?></td>
                        <td>
                            <a href="index.php?c=cita&a=show&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-info btn-action"><i class="bi bi-eye"></i></a>
                            <a href="index.php?c=cita&a=edit&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-pencil"></i></a>
                            <?php if (in_array($c['estado'], ['pendiente','confirmada'])): ?>
                                <a href="index.php?c=cita&a=convertir&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-success btn-action btn-status" data-status="Deposito" title="Convertir a Deposito"><i class="bi bi-arrow-right-circle"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
