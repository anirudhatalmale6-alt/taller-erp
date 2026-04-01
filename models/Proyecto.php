<?php
class Proyecto extends Model {
    protected static $table = 'proyecto_cab';
    protected static $fillable = ['id_proyecto', 'descripcion', 'matricula', 'num_doc_presupuesto', 'num_doc_factura', 'importe', 'num_mto_mecanica', 'num_mto_chapa', 'num_mto_pintura', 'num_mto_tapiceria', 'num_mto_usadas_mecanica', 'num_mto_usadas_chapa', 'num_mto_usadas_pintura', 'num_mto_usadas_tapiceria', 'id_cliente', 'estado', 'progreso', 'activo', 'iva_porcentaje', 'iva_importe', 'total', 'fecha', 'notas', 'created_by'];

    public static function conRelaciones($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT p.*, CONCAT(cl.nombre, ' ', IFNULL(cl.apellidos,'')) as cliente_nombre, v.matricula as veh_matricula, v.marca, v.modelo
            FROM proyecto_cab p
            LEFT JOIN clientes cl ON p.id_cliente = cl.id
            LEFT JOIN vehiculos v ON p.matricula = v.matricula
            WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getLineas($id) {
        $doc = self::findById($id);
        if (!$doc) return [];
        return self::query("SELECT d.*, t.descripcion as tarea_nombre FROM proyecto_det d LEFT JOIN tareas t ON d.id_tarea = t.id WHERE d.id_proyecto = ? ORDER BY d.orden", [$doc['id_proyecto']]);
    }

    public static function getApuntes($detId) {
        return self::query("SELECT * FROM proyecto_apu WHERE id_proyecto_det = ? ORDER BY id", [$detId]);
    }
}
