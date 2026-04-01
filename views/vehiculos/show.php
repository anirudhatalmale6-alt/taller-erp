<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="index.php?c=vehiculo" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left me-1"></i>Volver</a>
        <a href="index.php?c=vehiculo&a=edit&id=<?= $vehiculo['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
    </div>
    <div>
        <a href="index.php?c=cita&a=create&vehiculo_id=<?= $vehiculo['id'] ?>&cliente_id=<?= $vehiculo['id_cliente'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Nueva Cita</a>
        <a href="index.php?c=deposito&a=create&vehiculo_id=<?= $vehiculo['id'] ?>&cliente_id=<?= $vehiculo['id_cliente'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i>Nuevo Deposito</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="card form-card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-car-front me-2"></i>Datos del Vehiculo</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" width="120">Matricula</td><td><strong><?= e($vehiculo['matricula']) ?></strong></td></tr>
                    <tr><td class="text-muted">Num. Chasis</td><td><?= e($vehiculo['num_chasis']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Marca</td><td><?= e($vehiculo['marca']) ?></td></tr>
                    <tr><td class="text-muted">Modelo</td><td><?= e($vehiculo['modelo']) ?> <?= e($vehiculo['version_modelo']) ?></td></tr>
                    <tr><td class="text-muted">Ano</td><td><?= e($vehiculo['anio']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Color</td><td><?= e($vehiculo['color']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Potencia</td><td><?= e($vehiculo['potencia']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Num. Motor</td><td><?= e($vehiculo['num_motor']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Emisiones</td><td><?= e($vehiculo['emisiones']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Tipo Aceite</td><td><?= e($vehiculo['tipo_aceite']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Fecha Matric.</td><td><?= e($vehiculo['fecha_matriculacion']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">Ano Fabric.</td><td><?= e($vehiculo['ano_fabricacion']) ?: '-' ?></td></tr>
                    <tr><td class="text-muted">En Venta</td><td><?= ($vehiculo['en_venta'] ?? '') === 'SI' ? '<span class="badge bg-success">SI</span>' : 'NO' ?></td></tr>
                    <tr><td class="text-muted">Vendido</td><td><?= ($vehiculo['vendido'] ?? '') === 'SI' ? '<span class="badge bg-warning">SI</span>' : 'NO' ?></td></tr>
                    <tr><td class="text-muted">Sustitucion</td><td><?= ($vehiculo['sustitucion'] ?? '') === 'SI' ? '<span class="badge bg-info">SI</span>' : 'NO' ?></td></tr>
                    <tr><td class="text-muted">Cliente</td><td><a href="index.php?c=cliente&a=show&id=<?= $vehiculo['id_cliente'] ?>"><?= e($vehiculo['cliente_nombre']) ?></a></td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card table-card h-100">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Historial Completo</h6></div>
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-citas">Citas (<?= count($citas) ?>)</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-depositos">Depositos (<?= count($depositos) ?>)</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-presupuestos">Presupuestos (<?= count($presupuestos) ?>)</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-albaranes">Albaranes (<?= count($albaranes) ?>)</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-facturas">Facturas (<?= count($facturas) ?>)</a></li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="tab-citas">
                        <?php if (empty($citas)): ?><p class="text-muted">Sin citas</p>
                        <?php else: ?>
                            <table class="table table-sm"><thead><tr><th>Numero</th><th>Fecha</th><th>Motivo</th><th>Estado</th></tr></thead><tbody>
                            <?php foreach ($citas as $c): ?>
                                <tr><td><a href="index.php?c=cita&a=show&id=<?= $c['id'] ?>"><?= e($c['numero']) ?></a></td><td><?= fechaHora($c['fecha_cita']) ?></td><td><?= e(substr($c['motivo'] ?? '', 0, 50)) ?></td><td><?= statusBadge($c['estado']) ?></td></tr>
                            <?php endforeach; ?>
                            </tbody></table>
                        <?php endif; ?>
                    </div>
                    <div class="tab-pane fade" id="tab-depositos">
                        <?php if (empty($depositos)): ?><p class="text-muted">Sin depositos</p>
                        <?php else: ?>
                            <table class="table table-sm"><thead><tr><th>Numero</th><th>Fecha</th><th>Estado</th></tr></thead><tbody>
                            <?php foreach ($depositos as $d): ?>
                                <tr><td><a href="index.php?c=deposito&a=show&id=<?= $d['id'] ?>"><?= e($d['numero']) ?></a></td><td><?= fecha($d['fecha']) ?></td><td><?= statusBadge($d['estado']) ?></td></tr>
                            <?php endforeach; ?>
                            </tbody></table>
                        <?php endif; ?>
                    </div>
                    <div class="tab-pane fade" id="tab-presupuestos">
                        <?php if (empty($presupuestos)): ?><p class="text-muted">Sin presupuestos</p>
                        <?php else: ?>
                            <table class="table table-sm"><thead><tr><th>Numero</th><th>Fecha</th><th>Total</th><th>Estado</th></tr></thead><tbody>
                            <?php foreach ($presupuestos as $p): ?>
                                <tr><td><a href="index.php?c=presupuesto&a=show&id=<?= $p['id'] ?>"><?= e($p['numero']) ?></a></td><td><?= fecha($p['fecha']) ?></td><td><?= money($p['total']) ?></td><td><?= statusBadge($p['estado']) ?></td></tr>
                            <?php endforeach; ?>
                            </tbody></table>
                        <?php endif; ?>
                    </div>
                    <div class="tab-pane fade" id="tab-albaranes">
                        <?php if (empty($albaranes)): ?><p class="text-muted">Sin albaranes</p>
                        <?php else: ?>
                            <table class="table table-sm"><thead><tr><th>Numero</th><th>Fecha</th><th>Total</th><th>Estado</th></tr></thead><tbody>
                            <?php foreach ($albaranes as $a): ?>
                                <tr><td><a href="index.php?c=albaran&a=show&id=<?= $a['id'] ?>"><?= e($a['numero']) ?></a></td><td><?= fecha($a['fecha']) ?></td><td><?= money($a['total']) ?></td><td><?= statusBadge($a['estado']) ?></td></tr>
                            <?php endforeach; ?>
                            </tbody></table>
                        <?php endif; ?>
                    </div>
                    <div class="tab-pane fade" id="tab-facturas">
                        <?php if (empty($facturas)): ?><p class="text-muted">Sin facturas</p>
                        <?php else: ?>
                            <table class="table table-sm"><thead><tr><th>Numero</th><th>Tipo</th><th>Fecha</th><th>Total</th><th>Estado</th></tr></thead><tbody>
                            <?php foreach ($facturas as $f): ?>
                                <tr><td><a href="index.php?c=factura&a=show&id=<?= $f['id'] ?>"><?= e($f['numero']) ?></a></td><td><?= ucfirst($f['tipo']) ?></td><td><?= fecha($f['fecha']) ?></td><td><?= money($f['total']) ?></td><td><?= statusBadge($f['estado']) ?></td></tr>
                            <?php endforeach; ?>
                            </tbody></table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
