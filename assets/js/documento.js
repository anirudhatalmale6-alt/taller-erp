// Line items management for documents (Presupuesto, Albaran, Factura, Proyecto)

var lineaCounter = 0;

function addLinea(data) {
    data = data || {};
    lineaCounter++;
    var idx = lineaCounter;

    var html = '<tr class="linea-row" id="linea-' + idx + '">' +
        '<td><input type="hidden" name="lineas[' + idx + '][id]" value="' + (data.id || '') + '">' +
        '<input type="hidden" name="lineas[' + idx + '][id_tarea]" value="' + (data.id_tarea || '') + '">' +
        '<input type="text" class="form-control form-control-sm" name="lineas[' + idx + '][descripcion]" value="' + escapeHtml(data.descripcion || data.concepto || '') + '" required placeholder="Descripcion"></td>' +
        '<td><input type="number" class="form-control form-control-sm linea-cantidad" name="lineas[' + idx + '][cantidad]" value="' + (data.cantidad || 1) + '" step="0.01" min="0.01" style="width:80px" onchange="calcularLinea(this)"></td>' +
        '<td><input type="number" class="form-control form-control-sm linea-precio" name="lineas[' + idx + '][precio]" value="' + (data.precio || data.precio_unitario || 0) + '" step="0.01" min="0" style="width:100px" onchange="calcularLinea(this)"></td>' +
        '<td><span class="linea-importe fw-bold">0,00</span><input type="hidden" name="lineas[' + idx + '][importe]" class="linea-importe-input" value="' + (data.importe || 0) + '"></td>' +
        '<td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLinea(' + idx + ')"><i class="bi bi-trash"></i></button></td></tr>';

    document.getElementById('lineasBody').insertAdjacentHTML('beforeend', html);

    // Calculate initial amount
    var row = document.getElementById('linea-' + idx);
    calcularLinea(row.querySelector('.linea-cantidad'));

    return idx;
}

function removeLinea(idx) {
    var row = document.getElementById('linea-' + idx);
    if (row) row.remove();
    calcularTotales();
}

function calcularLinea(input) {
    var row = input.closest('tr');
    var cantidad = parseFloat(row.querySelector('.linea-cantidad').value) || 0;
    var precio = parseFloat(row.querySelector('.linea-precio').value) || 0;
    var importe = cantidad * precio;

    row.querySelector('.linea-importe').textContent = importe.toFixed(2).replace('.', ',');
    row.querySelector('.linea-importe-input').value = importe.toFixed(2);

    calcularTotales();
}

function calcularTotales() {
    var subtotal = 0;
    document.querySelectorAll('.linea-importe-input').forEach(function(input) {
        subtotal += parseFloat(input.value) || 0;
    });

    var descuentoPorc = parseFloat(document.getElementById('descuento_porcentaje')?.value) || 0;
    var descuentoImporte = subtotal * (descuentoPorc / 100);
    var baseImponible = subtotal - descuentoImporte;
    var ivaPorc = parseFloat(document.getElementById('iva_porcentaje')?.value) || 21;
    var ivaImporte = baseImponible * (ivaPorc / 100);
    var total = baseImponible + ivaImporte;

    setText('subtotalDisplay', subtotal.toFixed(2).replace('.', ',') + ' \u20AC');
    setText('descuentoDisplay', descuentoImporte.toFixed(2).replace('.', ',') + ' \u20AC');
    setText('ivaDisplay', ivaImporte.toFixed(2).replace('.', ',') + ' \u20AC');
    setText('totalDisplay', total.toFixed(2).replace('.', ',') + ' \u20AC');
}

function setText(id, text) {
    var el = document.getElementById(id);
    if (el) el.textContent = text;
}

function escapeHtml(str) {
    var div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML.replace(/"/g, '&quot;');
}

// Search tasks from catalog
function buscarTareaCatalogo() {
    var q = prompt('Buscar tarea en el catalogo:');
    if (!q) return;

    ajaxGet('index.php?c=ajax&a=tareas&q=' + encodeURIComponent(q), function(tareas) {
        if (tareas.length === 0) {
            Swal.fire('Sin resultados', 'No se encontraron tareas con ese nombre', 'info');
            return;
        }
        if (tareas.length === 1) {
            addLinea({
                id_tarea: tareas[0].id,
                descripcion: tareas[0].descripcion,
                cantidad: 1,
                precio: 0
            });
            return;
        }
        // Show selection
        var html = '<select class="form-select" id="tareaSelect">';
        tareas.forEach(function(t) {
            html += '<option value="' + t.id + '" data-descripcion="' + escapeHtml(t.descripcion) + '">' + escapeHtml(t.text) + '</option>';
        });
        html += '</select>';

        Swal.fire({
            title: 'Seleccionar Tarea',
            html: html,
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Agregar'
        }).then(function(result) {
            if (result.isConfirmed) {
                var sel = document.getElementById('tareaSelect');
                var opt = sel.options[sel.selectedIndex];
                addLinea({
                    id_tarea: sel.value,
                    descripcion: opt.dataset.descripcion,
                    cantidad: 1,
                    precio: 0
                });
            }
        });
    });
}

// Init: listen for IVA and discount changes
document.addEventListener('DOMContentLoaded', function() {
    var ivaInput = document.getElementById('iva_porcentaje');
    var descInput = document.getElementById('descuento_porcentaje');
    if (ivaInput) ivaInput.addEventListener('change', calcularTotales);
    if (descInput) descInput.addEventListener('change', calcularTotales);
});
