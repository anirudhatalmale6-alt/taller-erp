<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
        <a href="index.php?c=<?= $tipo ?>" class="btn btn-sm <?= ($filtro_activo === null || $filtro_activo === '') ? 'btn-primary' : 'btn-outline-secondary' ?>">Todos</a>
        <a href="index.php?c=<?= $tipo ?>&activo=SI" class="btn btn-sm <?= $filtro_activo === 'SI' ? 'btn-success' : 'btn-outline-success' ?>">Activos</a>
        <a href="index.php?c=<?= $tipo ?>&activo=NO" class="btn btn-sm <?= $filtro_activo === 'NO' ? 'btn-secondary' : 'btn-outline-secondary' ?>">Inactivos</a>
    </div>
    <a href="index.php?c=<?= $tipo ?>&a=create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo</a>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Vehiculo</th>
                        <?php if (!empty($showTotal)): ?><th>Total</th><?php endif; ?>
                        <th>Activo</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($docs)): ?>
                    <tr><td colspan="<?= !empty($showTotal) ? 7 : 6 ?>" class="text-center text-muted py-4">No hay registros</td></tr>
                <?php endif; ?>
                <?php foreach ($docs as $d): ?>
                    <?php $docNum = $d['id_pre_ord'] ?? $d['id_albaran'] ?? $d['id_factura'] ?? $d['id_proyecto'] ?? ''; ?>
                    <tr>
                        <td><a href="index.php?c=<?= $tipo ?>&a=show&id=<?= $d['id'] ?>" class="fw-semibold"><?= e($docNum) ?></a></td>
                        <td><?= fecha($d['fecha']) ?></td>
                        <td><?= e($d['cliente_nombre']) ?></td>
                        <td><?= e($d['matricula']) ?> <?= e($d['marca'] ?? '') ?></td>
                        <?php if (!empty($showTotal)): ?><td class="fw-bold"><?= money($d['total']) ?></td><?php endif; ?>
                        <td>
                            <?php if ($d['activo'] === 'SI'): ?>
                                <span class="badge bg-success">SI</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">NO</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?c=<?= $tipo ?>&a=show&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-info btn-action"><i class="bi bi-eye"></i></a>
                            <a href="index.php?c=<?= $tipo ?>&a=edit&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-pencil"></i></a>
                            <a href="index.php?c=<?= $tipo ?>&a=pdf&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger btn-action" target="_blank"><i class="bi bi-file-pdf"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($pagination['totalPages'] > 1): ?>
<nav class="mt-3">
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
            <li class="page-item <?= $i == $pagination['page'] ? 'active' : '' ?>">
                <a class="page-link" href="index.php?c=<?= $tipo ?>&page=<?= $i ?>&activo=<?= urlencode($filtro_activo ?? '') ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>
