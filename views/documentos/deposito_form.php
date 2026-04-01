<?php $d = $deposito; $isEdit = !empty($d); ?>
<div class="card form-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><?= $isEdit ? 'Editar Deposito ' . e($d['id_deposito']) : 'Nuevo Deposito' ?></h6>
        <a href="index.php?c=deposito" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php?c=deposito&a=<?= $isEdit ? 'update' : 'store' ?>" id="depositoForm">
            <?= csrf_field() ?>
            <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $d['id'] ?>"><?php endif; ?>

            <div class="row g-3">
                <!-- Cliente -->
                <div class="col-md-6">
                    <label class="form-label">Cliente *</label>
                    <select class="form-select select2" name="id_cliente" id="clienteSelect" required onchange="cargarVehiculosMatricula(this.value, 'matriculaSelect', '<?= e($selectedMatricula) ?>')">
                        <option value="">Seleccionar cliente...</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($selectedCliente == $c['id']) ? 'selected' : '' ?>><?= e(Cliente::nombreCompleto($c)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Matricula (vehiculo) -->
                <div class="col-md-6">
                    <label class="form-label">Vehiculo (Matricula) *</label>
                    <select class="form-select" name="matricula" id="matriculaSelect" required>
                        <option value="">Seleccionar vehiculo...</option>
                    </select>
                </div>

                <!-- Fecha + Hora -->
                <div class="col-md-3">
                    <label class="form-label">Fecha *</label>
                    <input type="date" class="form-control" name="fecha" value="<?= e($d['fecha'] ?? date('Y-m-d')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hora</label>
                    <input type="time" class="form-control" name="hora" value="<?= e($d['hora'] ?? date('H:i')) ?>">
                </div>

                <!-- Kilometros + Combustible -->
                <div class="col-md-3">
                    <label class="form-label">Kilometros</label>
                    <input type="number" class="form-control" name="kilometros" value="<?= e($d['kilometros'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nivel Combustible</label>
                    <select class="form-select" name="nivel_combustible">
                        <?php foreach (['vacio','1/4','1/2','3/4','lleno'] as $n): ?>
                            <option value="<?= $n ?>" <?= ($d['nivel_combustible'] ?? '1/2') === $n ? 'selected' : '' ?>><?= $n ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Operario -->
                <div class="col-md-6">
                    <label class="form-label">Operario</label>
                    <select class="form-select" name="id_operario">
                        <option value="">Sin asignar</option>
                        <?php foreach ($operarios as $op): ?>
                            <option value="<?= $op['id'] ?>" <?= ($d['id_operario'] ?? '') == $op['id'] ? 'selected' : '' ?>><?= e(Operario::nombreCompleto($op)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Descripcion Trabajos -->
                <div class="col-12">
                    <label class="form-label">Descripcion de Trabajos</label>
                    <textarea class="form-control" name="descripcion_trabajos" rows="3" placeholder="Describir los trabajos a realizar..."><?= e($d['descripcion_trabajos'] ?? '') ?></textarea>
                </div>

                <!-- Observaciones -->
                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea class="form-control" name="observaciones" rows="3"><?= e($d['observaciones'] ?? '') ?></textarea>
                </div>

                <!-- Acceptance flags -->
                <div class="col-12">
                    <label class="form-label fw-bold">Autorizaciones del Cliente</label>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="acepta_presupuesto" id="aceptaPresupuesto" <?= ($d['acepta_presupuesto'] ?? 'SI') === 'SI' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="aceptaPresupuesto">Acepta Presupuesto</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="acepta_ocultos" id="aceptaOcultos" <?= ($d['acepta_ocultos'] ?? 'NO') === 'SI' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="aceptaOcultos">Acepta Ocultos</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="acepta_piezas" id="aceptaPiezas" <?= ($d['acepta_piezas'] ?? 'NO') === 'SI' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="aceptaPiezas">Acepta Piezas</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="acepta_conduccion" id="aceptaConduccion" <?= ($d['acepta_conduccion'] ?? 'NO') === 'SI' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="aceptaConduccion">Acepta Conduccion</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="acepta_piezas_usadas" id="aceptaPiezasUsadas" <?= ($d['acepta_piezas_usadas'] ?? 'NO') === 'SI' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="aceptaPiezasUsadas">Acepta Piezas Usadas</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Firma Resguardo -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Firma Resguardo</label>
                    <div class="signature-pad" id="signaturePadResguardo">
                        <canvas id="signatureCanvasResguardo"></canvas>
                    </div>
                    <input type="hidden" name="firma_resguardo_data" id="firmaResguardoData">
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2 clearSignatureBtn" data-canvas="signatureCanvasResguardo">
                        <i class="bi bi-eraser me-1"></i>Limpiar
                    </button>
                    <?php if (!empty($d['firma_resguardo'])): ?>
                        <div class="mt-2"><img src="<?= e($d['firma_resguardo']) ?>" class="border rounded" style="max-height:100px"></div>
                    <?php endif; ?>
                </div>

                <!-- Firma Presupuesto -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Firma Presupuesto</label>
                    <div class="signature-pad" id="signaturePadPresupuesto">
                        <canvas id="signatureCanvasPresupuesto"></canvas>
                    </div>
                    <input type="hidden" name="firma_presupuesto_data" id="firmaPresupuestoData">
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2 clearSignatureBtn" data-canvas="signatureCanvasPresupuesto">
                        <i class="bi bi-eraser me-1"></i>Limpiar
                    </button>
                    <?php if (!empty($d['firma_presupuesto'])): ?>
                        <div class="mt-2"><img src="<?= e($d['firma_presupuesto']) ?>" class="border rounded" style="max-height:100px"></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Guardar Cambios' : 'Crear Deposito' ?></button>
                <a href="index.php?c=deposito" class="btn btn-outline-secondary ms-2">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var clienteId = '<?= $selectedCliente ?>';
    var matriculaVal = '<?= e($selectedMatricula) ?>';
    if (clienteId) cargarVehiculosMatricula(clienteId, 'matriculaSelect', matriculaVal);

    // Signature pads
    var pads = {};
    ['Resguardo', 'Presupuesto'].forEach(function(tipo) {
        var canvas = document.getElementById('signatureCanvas' + tipo);
        if (canvas) {
            canvas.width = canvas.parentElement.offsetWidth - 4;
            canvas.height = 200;
            pads[tipo] = new SignaturePad(canvas, { backgroundColor: 'rgb(255,255,255)' });
        }
    });

    // Clear buttons
    document.querySelectorAll('.clearSignatureBtn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var canvasId = this.getAttribute('data-canvas');
            var tipo = canvasId.replace('signatureCanvas', '');
            if (pads[tipo]) pads[tipo].clear();
        });
    });

    // On submit, capture signatures
    document.getElementById('depositoForm').addEventListener('submit', function() {
        if (pads['Resguardo'] && !pads['Resguardo'].isEmpty()) {
            document.getElementById('firmaResguardoData').value = pads['Resguardo'].toDataURL();
        }
        if (pads['Presupuesto'] && !pads['Presupuesto'].isEmpty()) {
            document.getElementById('firmaPresupuestoData').value = pads['Presupuesto'].toDataURL();
        }
    });
});

// Load vehiculos by client, using matricula as value
function cargarVehiculosMatricula(clienteId, selectId, selectedVal) {
    var sel = document.getElementById(selectId);
    sel.innerHTML = '<option value="">Cargando...</option>';
    fetch('index.php?c=api&a=vehiculosCliente&id=' + clienteId)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            sel.innerHTML = '<option value="">Seleccionar vehiculo...</option>';
            data.forEach(function(v) {
                var opt = document.createElement('option');
                opt.value = v.matricula;
                opt.textContent = v.matricula + ' - ' + v.marca + ' ' + v.modelo;
                if (v.matricula === selectedVal) opt.selected = true;
                sel.appendChild(opt);
            });
        })
        .catch(function() {
            sel.innerHTML = '<option value="">Error al cargar</option>';
        });
}
</script>
