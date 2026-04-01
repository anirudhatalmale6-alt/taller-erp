<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #fff; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 420px; width: 100%; padding: 2.5rem; }
        .login-logo { width: 80px; height: 80px; background: linear-gradient(135deg, #e53e3e, #dd6b20); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; }
        .login-logo i { font-size: 2.5rem; color: #fff; }
        .form-control:focus { border-color: #e53e3e; box-shadow: 0 0 0 0.2rem rgba(229,62,62,0.15); }
        .btn-login { background: linear-gradient(135deg, #e53e3e, #dd6b20); border: none; padding: 0.75rem; font-weight: 600; font-size: 1.05rem; }
        .btn-login:hover { background: linear-gradient(135deg, #c53030, #c05621); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <i class="bi bi-wrench-adjustable"></i>
        </div>
        <h4 class="text-center mb-1 fw-bold"><?= APP_NAME ?></h4>
        <p class="text-center text-muted mb-4">Sistema de Gestion de Taller</p>

        <?php if ($error = flash('error')): ?>
            <div class="alert alert-danger py-2"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?c=auth&a=login">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control" name="email" placeholder="admin@taller.com" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Contrasena</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" name="password" placeholder="••••••" required>
                </div>
            </div>
            <button type="submit" class="btn btn-login btn-primary w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesion
            </button>
        </form>
        <p class="text-center text-muted mt-3 small mb-0">admin@taller.com / admin123</p>
    </div>
</body>
</html>
