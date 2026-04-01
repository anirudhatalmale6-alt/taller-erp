<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="index.php?c=cliente" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left me-1"></i>Volver</a>
        <a href="index.php?c=cliente&a=edit&id=<?= $cliente['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
    </div>
    <div>
        <a href="index.php?c=cita&a=create&cliente_id=<?= $cliente['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Cita</a>
        <a href="index.php?c=vehiculo&a=create&cliente_id=<?= $cliente['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Vehiculo</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="card form-card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Datos del Cliente</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" width="100">NIF</td><td><?= e($cliente['nif']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Telefono</td><td><?= e($cliente['telefono']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Email</td><td><?= e($cliente['email']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Direccion</td><td><?= e($cliente['direccion']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Poblacion</td><td><?= e($cliente['poblacion']) ?> <?= e($cliente['cpostal']) ?></td></tr>
                    <tr><td class="text-muted">Provincia</td><td><?= e($cliente['provincia']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Pais</td><td><?= e($cliente['pais']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Alta</td><td><?= fecha($cliente['fecha_alta']) ?: '-' ?></td></tr>
                </table>
                <?php $redes = array_filter([$cliente['redsocial1'], $cliente['redsocial2'], $cliente['redsocial3'], $cliente['redsocial4']]); ?>
                <?php if ($redes): ?>
                    <hr><h6 class="fw-bold small">Redes Sociales</h6>
                    <?php foreach ($redes as $r): ?><small class="d-block text-muted"><?= e($r) ?></small><?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card table-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-car-front me-2"></i>Vehiculos (<?= count($vehiculos) ?>)</h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($vehiculos)): ?>
                    <div class="text-center text-muted py-4">No tiene vehiculos registrados</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>Matricula</th><th>Marca</th><th>Modelo</th><th>Color</th><th>Ano</th><th></th></tr></thead>
                            <tbody>
                            <?php foreach ($vehiculos as $v): ?>
                                <tr>
                                    <td><strong><?= e($v['matricula']) ?></strong></td>
                                    <td><?= e($v['marca']) ?></td>
                                    <td><?= e($v['modelo']) ?></td>
                                    <td><?= e($v['color']) ?></td>
                                    <td><?= e($v['anio']) ?: '-' ?></td>
                                    <td><a href="index.php?c=vehiculo&a=show&id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-info btn-action"><i class="bi bi-eye"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Document History -->
<div class="card table-card">
    <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-folder2 me-2"></i>Historial de Documentos</h6></div>
    <div class="card-body">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-depositos">Depositos (<?= count($depositos) ?>)</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-presupuestos">Presupuestos/Ordenes (<?= count($presupuestos) ?>)</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-albaranes">Albaranes (<?= count($albaranes) ?>)</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-facturas">Facturas (<?= count($facturas) ?>)</a></li>
        </ul>
        <div class="tab-content pt-3">
            <div class="tab-pane fade show active" id="tab-depositos">
                <?php if (empty($depositos)): ?><p class="text-muted">Sin depositos</p>
                <?php else: ?>
                    <table class="table table-sm table-hover"><thead><tr><th>Numero</th><th>Matricula</th><th>Fecha</th></tr></thead><tbody>
                    <?php foreach ($depositos as $d): ?>
                        <tr><td><a href="index.php?c=deposito&a=show&id=<?= $d['id'] ?>"><?= e($d['id_deposito']) ?></a></td><td><?= e($d['matricula']) ?></td><td><?= fecha($d['fecha']) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody></table>
                <?php endif; ?>
            </div>
            <div class="tab-pane fade" id="tab-presupuestos">
                <?php if (empty($presupuestos)): ?><p class="text-muted">Sin presupuestos</p>
                <?php else: ?>
                    <table class="table table-sm table-hover"><thead><tr><th>Numero</th><th>Tipo</th><th>Matricula</th><th>Fecha</th><th>Total</th></tr></thead><tbody>
                    <?php foreach ($presupuestos as $p): ?>
                        <tr><td><a href="index.php?c=presupuesto&a=show&id=<?= $p['id'] ?>"><?= e($p['id_pre_ord']) ?></a></td><td><span class="badge bg-secondary"><?= e($p['tipo_doc']) ?></span></td><td><?= e($p['matricula']) ?></td><td><?= fecha($p['fecha']) ?></td><td><?= money($p['total']) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody></table>
                <?php endif; ?>
            </div>
            <div class="tab-pane fade" id="tab-albaranes">
                <?php if (empty($albaranes)): ?><p class="text-muted">Sin albaranes</p>
                <?php else: ?>
                    <table class="table table-sm table-hover"><thead><tr><th>Numero</th><th>Matricula</th><th>Fecha</th><th>Total</th></tr></thead><tbody>
                    <?php foreach ($albaranes as $a): ?>
                        <tr><td><a href="index.php?c=albaran&a=show&id=<?= $a['id'] ?>"><?= e($a['id_albaran']) ?></a></td><td><?= e($a['matricula']) ?></td><td><?= fecha($a['fecha']) ?></td><td><?= money($a['total']) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody></table>
                <?php endif; ?>
            </div>
            <div class="tab-pane fade" id="tab-facturas">
                <?php if (empty($facturas)): ?><p class="text-muted">Sin facturas</p>
                <?php else: ?>
                    <table class="table table-sm table-hover"><thead><tr><th>Numero</th><th>Matricula</th><th>Fecha</th><th>Total</th><th>Estado</th></tr></thead><tbody>
                    <?php foreach ($facturas as $f): ?>
                        <tr><td><a href="index.php?c=factura&a=show&id=<?= $f['id'] ?>"><?= e($f['id_factura']) ?></a></td><td><?= e($f['matricula']) ?></td><td><?= fecha($f['fecha']) ?></td><td><?= money($f['total']) ?></td><td><?= statusBadge($f['estado']) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody></table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
