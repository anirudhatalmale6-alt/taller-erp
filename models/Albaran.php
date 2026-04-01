<?php
class Albaran extends Model {
    protected static $table = 'albaran_cab';
    protected static $fillable = ['id_albaran', 'tipo_doc', 'matricula', 'id_deposito', 'id_cliente', 'importe', 'firma', 'aceptado', 'activo', 'iva_porcentaje', 'iva_importe', 'descuento_porcentaje', 'descuento_importe', 'total', 'notas', 'fecha', 'created_by'];

    public static function conRelaciones($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT a.*, CONCAT(cl.nombre, ' ', IFNULL(cl.apellidos,'')) as cliente_nombre, cl.telefono as cliente_telefono, cl.email as cliente_email, cl.nif, cl.direccion as cliente_direccion, v.marca, v.modelo
            FROM albaran_cab a
            LEFT JOIN clientes cl ON a.id_cliente = cl.id
            LEFT JOIN vehiculos v ON a.matricula = v.matricula
            WHERE a.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getLineas($id) {
        $doc = self::findById($id);
        if (!$doc) return [];
        return self::query("SELECT d.*, t.descripcion as tarea_nombre FROM albaran_det d LEFT JOIN tareas t ON d.id_tarea = t.id WHERE d.id_albaran = ? ORDER BY d.orden", [$doc['id_albaran']]);
    }
}
