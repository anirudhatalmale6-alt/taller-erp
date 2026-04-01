<?php
class FotoController extends Controller {

    public function upload() {
        $this->requireAuth();
        csrf_check();

        $matricula = sanitize($_POST['matricula'] ?? '');
        $redirectUrl = $_POST['redirect'] ?? 'index.php?c=dashboard';

        if (empty($_FILES['fotos']['name'][0])) {
            flash('error', 'No se selecciono ningun archivo');
            redirect($redirectUrl);
        }

        $dir = UPLOAD_PATH . 'fotos/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $uploaded = 0;
        foreach ($_FILES['fotos']['tmp_name'] as $i => $tmpName) {
            if ($_FILES['fotos']['error'][$i] !== UPLOAD_ERR_OK) continue;
            if ($_FILES['fotos']['size'][$i] > UPLOAD_MAX_SIZE) continue;

            $ext = strtolower(pathinfo($_FILES['fotos']['name'][$i], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) continue;

            $filename = $matricula . '_' . time() . '_' . $i . '.' . $ext;
            if (move_uploaded_file($tmpName, $dir . $filename)) {
                Foto::insert([
                    'matricula' => $matricula,
                    'imagen' => 'uploads/fotos/' . $filename,
                    'fecha' => date('Y-m-d'),
                    'hora' => date('H:i:s'),
                    'activo' => 'SI',
                ]);
                $uploaded++;
            }
        }

        flash('success', $uploaded . ' foto(s) subida(s)');
        redirect($redirectUrl);
    }

    public function delete() {
        $this->requireAuth();
        $id = (int)$this->getParam('id');
        $foto = Foto::findById($id);
        if ($foto) {
            $file = __DIR__ . '/../' . $foto['imagen'];
            if (file_exists($file)) unlink($file);
            Foto::delete($id);
        }
        flash('success', 'Foto eliminada');
        back();
    }
}
