<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-people"></i></div>
                <div>
                    <div class="text-muted small">Clientes</div>
                    <div class="fs-4 fw-bold"><?= $stats['clientes'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-car-front"></i></div>
                <div>
                    <div class="text-muted small">Vehiculos</div>
                    <div class="fs-4 fw-bold"><?= $stats['vehiculos'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-calendar-check"></i></div>
                <div>
                    <div class="text-muted small">Citas Hoy</div>
                    <div class="fs-4 fw-bold"><?= $stats['citas_hoy'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-currency-euro"></i></div>
                <div>
                    <div class="text-muted small">Facturado (Mes)</div>
                    <div class="fs-4 fw-bold"><?= moneyRaw($stats['facturacion_mes']) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card border-start border-warning border-3">
            <div class="card-body">
                <div class="text-muted small">Depositos Abiertos</div>
                <div class="fs-5 fw-bold"><?= $stats['depositos_abiertos'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card border-start border-info border-3">
            <div class="card-body">
                <div class="text-muted small">Presupuestos Pendientes</div>
                <div class="fs-5 fw-bold"><?= $stats['presupuestos_pendientes'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card border-start border-danger border-3">
            <div class="card-body">
                <div class="text-muted small">Facturas Pendientes</div>
                <div class="fs-5 fw-bold"><?= $stats['facturas_pendientes'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card border-start border-primary border-3">
            <div class="card-body">
                <a href="index.php?c=cita&a=create" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-plus-lg me-1"></i>Nueva Cita
                </a>
                <a href="index.php?c=deposito&a=create" class="btn btn-outline-primary btn-sm w-100 mt-2">
                    <i class="bi bi-plus-lg me-1"></i>Nuevo Deposito
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Today's appointments -->
    <div class="col-lg-6">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-check me-2"></i>Citas de Hoy</h6>
                <a href="index.php?c=agenda" class="btn btn-sm btn-outline-primary">Ver Agenda</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($citasHoy)): ?>
                    <div class="text-center text-muted py-4">No hay citas para hoy</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>Hora</th><th>Cliente</th><th>Vehiculo</th><th>Estado</th></tr></thead>
                            <tbody>
                            <?php foreach ($citasHoy as $cita): ?>
                                <tr>
                                    <td><strong><?= fecha($cita['fecha_cita'], 'H:i') ?></strong></td>
                                    <td><?= e($cita['cliente_nombre']) ?></td>
                                    <td><?= e($cita['matricula']) ?> <?= e($cita['marca']) ?></td>
                                    <td><?= statusBadge($cita['estado']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent documents -->
    <div class="col-lg-6">
        <div class="card table-card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Documentos Recientes</h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recientes)): ?>
                    <div class="text-center text-muted py-4">No hay documentos recientes</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>Tipo</th><th>Numero</th><th>Fecha</th><th>Estado</th></tr></thead>
                            <tbody>
                            <?php foreach ($recientes as $doc): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary"><?= ucfirst(e($doc['doc_tipo'])) ?></span>
                                    </td>
                                    <td>
                                        <a href="index.php?c=<?= e($doc['doc_tipo']) ?>&a=show&id=<?= e($doc['cliente_id']) ?>">
                                            <?= e($doc['numero']) ?>
                                        </a>
                                    </td>
                                    <td><?= fecha($doc['doc_fecha']) ?></td>
                                    <td><?= statusBadge($doc['estado']) ?></td>
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
