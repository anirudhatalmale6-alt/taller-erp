<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card form-card">
            <div class="card-header"><h6 class="mb-0 fw-bold">Cambiar Contrasena</h6></div>
            <div class="card-body">
                <form method="POST" action="index.php?c=auth&a=password">
                    <?= csrf_field() ?>
                    <div class="mb-3"><label class="form-label">Contrasena Actual</label><input type="password" class="form-control" name="current_password" required></div>
                    <div class="mb-3"><label class="form-label">Nueva Contrasena</label><input type="password" class="form-control" name="new_password" required minlength="6"></div>
                    <div class="mb-3"><label class="form-label">Confirmar Contrasena</label><input type="password" class="form-control" name="confirm_password" required></div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-key me-1"></i>Cambiar Contrasena</button>
                </form>
            </div>
        </div>
    </div>
</div>
