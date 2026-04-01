<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0"></h6>
    <a href="index.php?c=operario&a=create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Operario</a>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Iniciales</th><th>Nombre</th><th>Seccion</th><th>Telefono</th><th>Email</th><th>Estado</th><th width="100">Acciones</th></tr></thead>
                <tbody>
                <?php foreach ($operarios as $o): ?>
                    <tr class="<?= $o['activo'] !== 'SI' ? 'table-secondary' : '' ?>">
                        <td><code><?= e($o['id_iniciales']) ?></code></td>
                        <td class="fw-semibold"><?= e(Operario::nombreCompleto($o)) ?></td>
                        <td><?= e($o['seccion']) ?></td>
                        <td><?= e($o['telefono']) ?></td>
                        <td><?= e($o['email']) ?></td>
                        <td><?= $o['activo'] === 'SI' ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>' ?></td>
                        <td>
                            <a href="index.php?c=operario&a=edit&id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-pencil"></i></a>
                            <?php if ($o['activo'] === 'SI'): ?>
                                <a href="index.php?c=operario&a=delete&id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete"><i class="bi bi-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
