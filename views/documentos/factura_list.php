<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
        <a href="index.php?c=factura" class="btn btn-sm <?= !$estadoFiltro ? 'btn-primary' : 'btn-outline-secondary' ?>">Todas</a>
        <?php foreach (['borrador','enviada','pagada','vencida','anulada'] as $e): ?>
            <a href="index.php?c=factura&estado=<?= $e ?>" class="btn btn-sm <?= $estadoFiltro === $e ? 'btn-primary' : 'btn-outline-secondary' ?>"><?= ucfirst($e) ?></a>
        <?php endforeach; ?>
    </div>
    <a href="index.php?c=factura&a=create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Factura</a>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Numero</th><th>Fecha</th><th>Cliente</th><th>Vehiculo</th><th>Total</th><th>Estado</th><th width="140">Acciones</th></tr></thead>
                <tbody>
                <?php if (empty($facturas)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No hay facturas</td></tr>
                <?php endif; ?>
                <?php foreach ($facturas as $f): ?>
                    <tr>
                        <td><a href="index.php?c=factura&a=show&id=<?= $f['id'] ?>" class="fw-semibold"><?= e($f['id_factura']) ?></a></td>
                        <td><?= fecha($f['fecha']) ?></td>
                        <td><?= e($f['cliente_nombre']) ?></td>
                        <td><?= e($f['matricula']) ?> <?= e($f['marca'] ?? '') ?></td>
                        <td class="fw-bold"><?= money($f['total']) ?></td>
                        <td><?= statusBadge($f['estado']) ?></td>
                        <td>
                            <a href="index.php?c=factura&a=show&id=<?= $f['id'] ?>" class="btn btn-sm btn-outline-info btn-action"><i class="bi bi-eye"></i></a>
                            <a href="index.php?c=factura&a=edit&id=<?= $f['id'] ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-pencil"></i></a>
                            <a href="index.php?c=factura&a=pdf&id=<?= $f['id'] ?>" class="btn btn-sm btn-outline-danger btn-action" target="_blank"><i class="bi bi-file-pdf"></i></a>
                            <?php if ($f['estado'] !== 'pagada' && $f['estado'] !== 'anulada'): ?>
                                <a href="index.php?c=factura&a=estado&id=<?= $f['id'] ?>&estado=pagada" class="btn btn-sm btn-outline-success btn-action"><i class="bi bi-check-circle"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
