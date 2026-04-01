<?php $d = $doc; $isEdit = !empty($d); ?>
<div class="card form-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><?= $isEdit ? 'Editar Proyecto ' . e($d['id_proyecto']) : 'Nuevo Proyecto' ?></h6>
        <a href="index.php?c=proyecto" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php?c=proyecto&a=<?= $isEdit ? 'update' : 'store' ?>" id="docForm">
            <?= csrf_field() ?>
            <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $d['id'] ?>"><?php endif; ?>

            <div class="row g-3 mb-4">
                <div class="col-md-6"><label class="form-label">Descripcion</label><input type="text" class="form-control" name="descripcion" value="<?= e($d['descripcion'] ?? '') ?>"></div>
                <div class="col-md-3"><label class="form-label">Cliente *</label>
                    <select class="form-select select2" name="id_cliente" required onchange="cargarVehiculosMatricula(this.value, 'matriculaSelect', '<?= e($selectedMatricula) ?>')">
                        <option value="">Seleccionar...</option>
                        <?php foreach ($clientes as $c): ?><option value="<?= $c['id'] ?>" <?= ($selectedCliente == $c['id']) ? 'selected' : '' ?>><?= e(Cliente::nombreCompleto($c)) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label">Vehiculo (Matricula) *</label><select class="form-select" name="matricula" id="matriculaSelect" required><option value="">Seleccionar...</option></select></div>
                <div class="col-md-3"><label class="form-label">Fecha</label><input type="date" class="form-control" name="fecha" value="<?= e($d['fecha'] ?? date('Y-m-d')) ?>"></div>
                <div class="col-md-3"><label class="form-label">Progreso %</label><input type="number" class="form-control" name="progreso" value="<?= e($d['progreso'] ?? 0) ?>" min="0" max="100"></div>
                <?php if ($isEdit): ?>
                <div class="col-md-3"><label class="form-label">Estado</label>
                    <select class="form-select" name="estado"><?php foreach (['planificacion','en_curso','pausado','completado','cancelado'] as $e): ?><option value="<?= $e ?>" <?= ($d['estado'] ?? '') === $e ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ',$e)) ?></option><?php endforeach; ?></select>
                </div>
                <?php endif; ?>
                <div class="col-12"><label class="form-label">Notas</label><textarea class="form-control" name="notas" rows="2"><?= e($d['notas'] ?? '') ?></textarea></div>
            </div>

            <!-- Detail Lines -->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center py-2">
                    <h6 class="mb-0 fw-bold">Lineas de Detalle</h6>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addLinea()"><i class="bi bi-plus-lg me-1"></i>Agregar</button>
                </div>
                <div class="card-body p-0"><div class="table-responsive"><table class="table table-sm mb-0"><thead><tr class="table-light"><th>Descripcion</th><th width="80">Cant.</th><th width="100">Importe</th><th width="40"></th></tr></thead><tbody id="lineasBody"></tbody></table></div></div>
            </div>

            <div class="row justify-content-end"><div class="col-md-4"><table class="table table-sm">
                <tr><td>Subtotal</td><td class="text-end fw-bold" id="subtotalDisplay">0,00 &euro;</td></tr>
                <tr><td>IVA <input type="number" class="form-control form-control-sm d-inline" id="iva_porcentaje" name="iva_porcentaje" value="<?= e($d['iva_porcentaje'] ?? 21) ?>" step="0.01" style="width:70px"> %</td><td class="text-end" id="ivaDisplay">0,00 &euro;</td></tr>
                <tr class="table-primary"><td class="fw-bold fs-5">TOTAL</td><td class="text-end fw-bold fs-5" id="totalDisplay">0,00 &euro;</td></tr>
            </table></div></div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Guardar' : 'Crear Proyecto' ?></button>
                <a href="index.php?c=proyecto" class="btn btn-outline-secondary ms-2">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var clienteId = '<?= $selectedCliente ?>';
    var matriculaVal = '<?= e($selectedMatricula) ?>';
    if (clienteId) cargarVehiculosMatricula(clienteId, 'matriculaSelect', matriculaVal);
    <?php foreach ($lineas ?? [] as $l): ?>addLinea(<?= json_encode($l) ?>);<?php endforeach; ?>
    calcularTotales();
});
function cargarVehiculosMatricula(clienteId, selectId, selectedVal) {
    var sel = document.getElementById(selectId);
    sel.innerHTML = '<option value="">Cargando...</option>';
    fetch('index.php?c=ajax&a=vehiculosCliente&id=' + clienteId)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            sel.innerHTML = '<option value="">Seleccionar vehiculo...</option>';
            data.forEach(function(v) {
                var opt = document.createElement('option');
                opt.value = v.matricula;
                opt.textContent = v.matricula + ' - ' + (v.marca||'') + ' ' + (v.modelo||'');
                if (v.matricula === selectedVal) opt.selected = true;
                sel.appendChild(opt);
            });
        }).catch(function() { sel.innerHTML = '<option value="">Error</option>'; });
}
</script>
