<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion - <?= APP_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #111318; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Roboto Condensed', 'Segoe UI', system-ui, sans-serif; }
        .login-card { background: #22262b; border: 1px solid #2d3139; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.5); max-width: 420px; width: 100%; padding: 2.5rem; }
        .login-logo { width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6, #6366f1); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; }
        .login-logo i { font-size: 2.5rem; color: #fff; }
        h4 { color: #f3f4f6; }
        .text-muted { color: #6b7280 !important; }
        .form-label { color: #9ca3af; }
        .form-control { background: #2a2e35; border-color: #363b44; color: #e4e6ea; }
        .form-control:focus { background: #2a2e35; border-color: #3b82f6; color: #f3f4f6; box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.2); }
        .form-control::placeholder { color: #6b7280; }
        .input-group-text { background: #272b31; border-color: #363b44; color: #6b7280; }
        .btn-login { background: linear-gradient(135deg, #3b82f6, #6366f1); border: none; padding: 0.75rem; font-weight: 600; font-size: 1.05rem; color: #fff; }
        .btn-login:hover { background: linear-gradient(135deg, #2563eb, #4f46e5); color: #fff; }
        .alert-danger { background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.3); color: #f87171; }
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
            <button type="submit" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesion
            </button>
        </form>
        <p class="text-center text-muted mt-3 small mb-0">admin@taller.com / admin123</p>
    </div>
</body>
</html>
