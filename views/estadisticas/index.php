<!-- Document count cards -->
<div class="row g-3 mb-4">
    <?php
    $icons = ['citas'=>'calendar-check','depositos'=>'inbox','presupuestos'=>'calculator','albaranes'=>'clipboard-check','facturas'=>'receipt','ordenes'=>'file-text','proyectos'=>'kanban'];
    $colors = ['citas'=>'info','depositos'=>'warning','presupuestos'=>'primary','albaranes'=>'success','facturas'=>'danger','ordenes'=>'secondary','proyectos'=>'dark'];
    foreach ($docCounts as $key => $count): ?>
    <div class="col-6 col-lg">
        <div class="card stat-card text-center py-3">
            <div class="card-body py-2">
                <i class="bi bi-<?= $icons[$key] ?> text-<?= $colors[$key] ?> fs-4"></i>
                <div class="fs-4 fw-bold"><?= $count ?></div>
                <small class="text-muted"><?= ucfirst($key) ?></small>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-3 mb-4">
    <!-- Revenue chart -->
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2"></i>Facturacion Mensual</h6></div>
            <div class="card-body"><canvas id="revenueChart" height="300"></canvas></div>
        </div>
    </div>
    <!-- Invoice status -->
    <div class="col-lg-4">
        <div class="card form-card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2"></i>Estado Facturas</h6></div>
            <div class="card-body"><canvas id="statusChart" height="300"></canvas></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Top clients -->
    <div class="col-lg-6">
        <div class="card table-card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-trophy me-2"></i>Top Clientes por Facturacion</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead><tr><th>#</th><th>Cliente</th><th>Total Facturado</th></tr></thead>
                    <tbody>
                    <?php foreach ($topClientes as $i => $tc): ?>
                        <tr><td><?= $i+1 ?></td><td><?= e($tc['nombre']) ?></td><td class="fw-bold"><?= money($tc['total']) ?></td></tr>
                    <?php endforeach; ?>
                    <?php if (empty($topClientes)): ?><tr><td colspan="3" class="text-center text-muted py-3">Sin datos</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Vehicle brands -->
    <div class="col-lg-6">
        <div class="card form-card">
            <div class="card-header"><h6 class="mb-0 fw-bold"><i class="bi bi-car-front me-2"></i>Marcas de Vehiculos</h6></div>
            <div class="card-body"><canvas id="brandsChart" height="250"></canvas></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue chart
    var revData = <?= json_encode($facturacionMes) ?>;
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: revData.map(r => r.mes),
            datasets: [{
                label: 'Facturacion (EUR)',
                data: revData.map(r => parseFloat(r.total)),
                backgroundColor: 'rgba(229, 62, 62, 0.7)',
                borderColor: '#e53e3e',
                borderWidth: 1
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });

    // Status chart
    var statusData = <?= json_encode($facturaEstados) ?>;
    var statusColors = { borrador: '#6b7280', enviada: '#3b82f6', pagada: '#10b981', vencida: '#ef4444', anulada: '#1f2937' };
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusData.map(s => s.estado.charAt(0).toUpperCase() + s.estado.slice(1)),
            datasets: [{ data: statusData.map(s => s.total), backgroundColor: statusData.map(s => statusColors[s.estado] || '#6b7280') }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Brands chart
    var brandsData = <?= json_encode($marcas) ?>;
    new Chart(document.getElementById('brandsChart'), {
        type: 'bar',
        data: {
            labels: brandsData.map(b => b.marca),
            datasets: [{ label: 'Vehiculos', data: brandsData.map(b => b.total), backgroundColor: 'rgba(59, 130, 246, 0.7)' }]
        },
        options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } } }
    });
});
</script>
