<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
        <a href="index.php?c=deposito" class="btn btn-sm <?= ($filtro_activo === null || $filtro_activo === '') ? 'btn-primary' : 'btn-outline-secondary' ?>">Todos</a>
        <a href="index.php?c=deposito&activo=SI" class="btn btn-sm <?= $filtro_activo === 'SI' ? 'btn-success' : 'btn-outline-success' ?>">Activos</a>
        <a href="index.php?c=deposito&activo=NO" class="btn btn-sm <?= $filtro_activo === 'NO' ? 'btn-secondary' : 'btn-outline-secondary' ?>">Inactivos</a>
    </div>
    <a href="index.php?c=deposito&a=create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Deposito</a>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>N Deposito</th><th>Fecha</th><th>Hora</th><th>Cliente</th><th>Matricula</th><th>Activo</th><th width="150">Acciones</th></tr></thead>
                <tbody>
                <?php if (empty($depositos)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No hay depositos</td></tr>
                <?php endif; ?>
                <?php foreach ($depositos as $d): ?>
                    <tr>
                        <td><a href="index.php?c=deposito&a=show&id=<?= $d['id'] ?>" class="fw-semibold"><?= e($d['id_deposito']) ?></a></td>
                        <td><?= fecha($d['fecha']) ?></td>
                        <td><?= e($d['hora'] ?? '') ?></td>
                        <td><?= e($d['cliente_nombre']) ?></td>
                        <td><?= e($d['matricula']) ?> <?= e($d['marca'] ?? '') ?> <?= e($d['modelo'] ?? '') ?></td>
                        <td>
                            <?php if ($d['activo'] === 'SI'): ?>
                                <span class="badge bg-success">SI</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">NO</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?c=deposito&a=show&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-info btn-action"><i class="bi bi-eye"></i></a>
                            <a href="index.php?c=deposito&a=edit&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-pencil"></i></a>
                            <?php if ($d['activo'] === 'SI'): ?>
                                <a href="index.php?c=deposito&a=convertir&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-success btn-action" title="Crear Presupuesto"><i class="bi bi-arrow-right-circle"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
