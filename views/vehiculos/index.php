<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET">
        <input type="hidden" name="c" value="vehiculo">
        <input type="text" class="form-control" name="q" value="<?= e($search) ?>" placeholder="Buscar por matricula, marca, modelo..." style="width: 320px;">
        <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        <?php if ($search): ?><a href="index.php?c=vehiculo" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a><?php endif; ?>
    </form>
    <a href="index.php?c=vehiculo&a=create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Vehiculo</a>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Matricula</th><th>Marca</th><th>Modelo</th><th>Cliente</th><th>Color</th><th>Ano</th><th width="120">Acciones</th></tr></thead>
                <tbody>
                <?php if (empty($vehiculos)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No se encontraron vehiculos</td></tr>
                <?php endif; ?>
                <?php foreach ($vehiculos as $v): ?>
                    <tr>
                        <td><a href="index.php?c=vehiculo&a=show&id=<?= $v['id'] ?>" class="fw-semibold text-decoration-none"><?= e($v['matricula']) ?></a></td>
                        <td><?= e($v['marca']) ?></td>
                        <td><?= e($v['modelo']) ?></td>
                        <td><a href="index.php?c=cliente&a=show&id=<?= $v['id_cliente'] ?>"><?= e($v['cliente_nombre']) ?></a></td>
                        <td><?= e($v['color']) ?></td>
                        <td><?= e($v['anio']) ?></td>
                        <td>
                            <a href="index.php?c=vehiculo&a=edit&id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-pencil"></i></a>
                            <a href="index.php?c=vehiculo&a=show&id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-info btn-action"><i class="bi bi-eye"></i></a>
                            <a href="index.php?c=vehiculo&a=delete&id=<?= $v['id'] ?>&csrf_token=<?= $_SESSION['csrf_token'] ?? '' ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete"><i class="bi bi-trash"></i></a>
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
                <a class="page-link" href="index.php?c=vehiculo&page=<?= $i ?>&q=<?= urlencode($search) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>
