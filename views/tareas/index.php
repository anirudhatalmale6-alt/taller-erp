<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0"></h6>
    <a href="index.php?c=tarea&a=create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Tarea</a>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Codigo</th><th>Descripcion</th><th>Seccion</th><th>Familia</th><th>Tiempo Prev.</th><th>Estado</th><th width="100">Acciones</th></tr></thead>
                <tbody>
                <?php foreach ($tareas as $t): ?>
                    <tr class="<?= $t['activo'] !== 'SI' ? 'table-secondary' : '' ?>">
                        <td><code><?= e($t['id_tarea']) ?></code></td>
                        <td class="fw-semibold"><?= e($t['descripcion']) ?></td>
                        <td><span class="badge bg-secondary"><?= ucfirst(e($t['seccion'])) ?></span></td>
                        <td><?= e($t['familia']) ?></td>
                        <td><?= $t['tiempo_previsto'] ?> min</td>
                        <td><?= $t['activo'] === 'SI' ? '<span class="badge bg-success">Activa</span>' : '<span class="badge bg-secondary">Inactiva</span>' ?></td>
                        <td>
                            <a href="index.php?c=tarea&a=edit&id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-pencil"></i></a>
                            <?php if ($t['activo'] === 'SI'): ?>
                                <a href="index.php?c=tarea&a=delete&id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete"><i class="bi bi-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
