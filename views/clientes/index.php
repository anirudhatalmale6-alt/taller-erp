<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <form class="d-flex gap-2" method="GET">
            <input type="hidden" name="c" value="cliente">
            <input type="text" class="form-control" name="q" value="<?= e($search) ?>" placeholder="Buscar cliente..." style="width: 280px;">
            <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
            <?php if ($search): ?>
                <a href="index.php?c=cliente" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            <?php endif; ?>
        </form>
    </div>
    <a href="index.php?c=cliente&a=create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Cliente
    </a>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>CIF/NIF</th>
                        <th>Telefono</th>
                        <th>Email</th>
                        <th>Ciudad</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($clientes)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No se encontraron clientes</td></tr>
                <?php endif; ?>
                <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td>
                            <a href="index.php?c=cliente&a=show&id=<?= $c['id'] ?>" class="fw-semibold text-decoration-none">
                                <?= e(Cliente::nombreCompleto($c)) ?>
                            </a>
                        </td>
                        <td><?= e($c['nif']) ?></td>
                        <td><?= e($c['telefono']) ?></td>
                        <td><?= e($c['email']) ?></td>
                        <td><?= e($c['poblacion']) ?></td>
                        <td>
                            <a href="index.php?c=cliente&a=edit&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary btn-action" title="Editar"><i class="bi bi-pencil"></i></a>
                            <a href="index.php?c=cliente&a=show&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-info btn-action" title="Ver"><i class="bi bi-eye"></i></a>
                            <a href="index.php?c=cliente&a=delete&id=<?= $c['id'] ?>&csrf_token=<?= $_SESSION['csrf_token'] ?? '' ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete" title="Eliminar"><i class="bi bi-trash"></i></a>
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
                <a class="page-link" href="index.php?c=cliente&page=<?= $i ?>&q=<?= urlencode($search) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>
