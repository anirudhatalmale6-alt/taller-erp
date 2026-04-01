<?php
class Presupuesto extends Model {
    protected static $table = 'pre_ord_cab';
    protected static $fillable = ['id_pre_ord', 'tipo_doc', 'matricula', 'id_deposito', 'id_cliente', 'importe', 'firma', 'aceptado', 'activo', 'iva_porcentaje', 'iva_importe', 'descuento_porcentaje', 'descuento_importe', 'total', 'condiciones', 'notas', 'fecha', 'created_by'];

    public static function conRelaciones($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT p.*, CONCAT(cl.nombre, ' ', IFNULL(cl.apellidos,'')) as cliente_nombre, cl.telefono as cliente_telefono, cl.email as cliente_email, cl.nif, cl.direccion as cliente_direccion, v.marca, v.modelo
            FROM pre_ord_cab p
            LEFT JOIN clientes cl ON p.id_cliente = cl.id
            LEFT JOIN vehiculos v ON p.matricula = v.matricula
            WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getLineas($id) {
        $doc = self::findById($id);
        if (!$doc) return [];
        return self::query("SELECT d.*, t.descripcion as tarea_nombre FROM pre_ord_det d LEFT JOIN tareas t ON d.id_tarea = t.id WHERE d.id_pre_ord = ? ORDER BY d.orden", [$doc['id_pre_ord']]);
    }

    public static function getApuntes($detId) {
        return self::query("SELECT a.* FROM pre_ord_apu a WHERE a.id_pre_ord_det = ? ORDER BY a.id", [$detId]);
    }
}
