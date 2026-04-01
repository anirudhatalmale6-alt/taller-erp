<?php
class ConfigController extends Controller {

    public function index() {
        $this->requireAuth();
        $this->requireRole(['admin']);
        $config = getConfig();

        $this->view('config/index', ['pageTitle' => 'Configuracion', 'config' => $config]);
    }

    public function update() {
        $this->requireAuth();
        $this->requireRole(['admin']);
        csrf_check();

        $data = $_POST;

        // Handle logo upload
        if (!empty($_FILES['empresa_logo']['name'])) {
            $dir = UPLOAD_PATH . 'img/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['empresa_logo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif','svg'])) {
                $filename = 'logo.' . $ext;
                move_uploaded_file($_FILES['empresa_logo']['tmp_name'], $dir . $filename);
                $data['empresa_logo'] = 'uploads/img/' . $filename;
            }
        }

        Configuracion::update(1, $data);
        flash('success', 'Configuracion actualizada');
        redirect('index.php?c=config');
    }
}
