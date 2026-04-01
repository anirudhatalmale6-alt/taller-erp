<div class="topbar">
    <div class="d-flex align-items-center">
        <button class="btn btn-link text-dark d-lg-none me-2" id="openSidebar">
            <i class="bi bi-list fs-4"></i>
        </button>
        <h5 class="mb-0 fw-bold"><?= e($pageTitle ?? 'Dashboard') ?></h5>
    </div>
    <div class="d-flex align-items-center gap-3">
        <span class="text-muted small d-none d-md-inline"><?= date('d/m/Y H:i') ?></span>
        <div class="dropdown">
            <button class="btn btn-link text-dark dropdown-toggle text-decoration-none" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i>
                <span class="d-none d-md-inline"><?= e(currentUser()['nombre'] ?? '') ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="index.php?c=auth&a=password"><i class="bi bi-key me-2"></i>Cambiar Contrasena</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="index.php?c=auth&a=logout"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesion</a></li>
            </ul>
        </div>
    </div>
</div>
