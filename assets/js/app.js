// Taller ERP - Global JS

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle (mobile)
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const openBtn = document.getElementById('openSidebar');
    const closeBtn = document.getElementById('closeSidebar');

    if (openBtn) {
        openBtn.addEventListener('click', () => {
            sidebar.classList.add('show');
            overlay.classList.add('show');
        });
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }
    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // Initialize Select2 on all .select2 elements
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });
    }

    // Delete confirmation with SweetAlert2
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href') || this.dataset.url;
            Swal.fire({
                title: 'Confirmar eliminacion',
                text: 'Esta accion no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e53e3e',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Eliminar'
            }).then(result => {
                if (result.isConfirmed) window.location.href = url;
            });
        });
    });

    // Status change confirmation
    document.querySelectorAll('.btn-status').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            const status = this.dataset.status || 'actualizar';
            Swal.fire({
                title: 'Cambiar estado',
                text: 'Cambiar estado a: ' + status,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar'
            }).then(result => {
                if (result.isConfirmed) window.location.href = url;
            });
        });
    });

    // Auto-dismiss alerts after 5 seconds
    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 5000);
    });
});

// AJAX helper
function ajaxGet(url, callback) {
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(callback)
        .catch(err => console.error('Error:', err));
}

// Format money
function formatMoney(amount) {
    return parseFloat(amount || 0).toFixed(2).replace('.', ',') + ' \u20AC';
}

// Load vehicles for a client by ID (returns vehicle IDs - used in cita forms)
function cargarVehiculos(clienteId, selectId, selectedId) {
    const select = document.getElementById(selectId);
    if (!select) return;
    select.innerHTML = '<option value="">Seleccionar vehiculo...</option>';
    if (!clienteId) return;

    ajaxGet('index.php?c=ajax&a=vehiculosCliente&id=' + clienteId, function(data) {
        data.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v.id;
            opt.textContent = v.matricula + ' - ' + (v.marca || '') + ' ' + (v.modelo || '');
            if (selectedId && v.id == selectedId) opt.selected = true;
            select.appendChild(opt);
        });
    });
}

// Load vehicles for a client by matricula (returns matricula values - used in document forms)
function cargarVehiculosMatricula(clienteId, selectId, selectedVal) {
    var sel = document.getElementById(selectId);
    if (!sel) return;
    sel.innerHTML = '<option value="">Cargando...</option>';
    if (!clienteId) { sel.innerHTML = '<option value="">Seleccionar vehiculo...</option>'; return; }

    fetch('index.php?c=ajax&a=vehiculosCliente&id=' + clienteId)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            sel.innerHTML = '<option value="">Seleccionar vehiculo...</option>';
            data.forEach(function(v) {
                var opt = document.createElement('option');
                opt.value = v.matricula;
                opt.textContent = v.matricula + ' - ' + (v.marca || '') + ' ' + (v.modelo || '');
                if (v.matricula === selectedVal) opt.selected = true;
                sel.appendChild(opt);
            });
        })
        .catch(function() {
            sel.innerHTML = '<option value="">Error al cargar</option>';
        });
}
