<?php
class Factura extends Model {
    protected static $table = 'factura_cab';
    protected static $fillable = ['id_factura', 'tipo_doc', 'matricula', 'id_deposito', 'id_cliente', 'importe', 'firma', 'aceptado', 'activo', 'iva_porcentaje', 'iva_importe', 'descuento_porcentaje', 'descuento_importe', 'total', 'forma_pago', 'condiciones', 'notas', 'fecha', 'fecha_vencimiento', 'estado', 'created_by'];

    public static function conRelaciones($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT f.*, CONCAT(cl.nombre, ' ', IFNULL(cl.apellidos,'')) as cliente_nombre, cl.telefono as cliente_telefono, cl.email as cliente_email, cl.nif, cl.direccion as cliente_direccion, v.marca, v.modelo
            FROM factura_cab f
            LEFT JOIN clientes cl ON f.id_cliente = cl.id
            LEFT JOIN vehiculos v ON f.matricula = v.matricula
            WHERE f.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getLineas($id) {
        $doc = self::findById($id);
        if (!$doc) return [];
        return self::query("SELECT d.*, t.descripcion as tarea_nombre FROM factura_det d LEFT JOIN tareas t ON d.id_tarea = t.id WHERE d.id_factura = ? ORDER BY d.orden", [$doc['id_factura']]);
    }
}
