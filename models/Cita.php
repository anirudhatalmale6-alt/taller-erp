<?php
class Cita extends Model {
    protected static $table = 'citas';
    protected static $fillable = ['numero', 'id_cliente', 'id_vehiculo', 'matricula', 'fecha_cita', 'duracion_estimada', 'motivo', 'estado', 'id_operario', 'notas', 'created_by'];

    public static function hoy() {
        return self::query("SELECT c.*, CONCAT(cl.nombre, ' ', IFNULL(cl.apellidos,'')) as cliente_nombre, v.matricula, v.marca, v.modelo
            FROM citas c
            JOIN clientes cl ON c.id_cliente = cl.id
            JOIN vehiculos v ON c.id_vehiculo = v.id
            WHERE DATE(c.fecha_cita) = CURDATE()
            ORDER BY c.fecha_cita ASC");
    }

    public static function paraCalendario($inicio, $fin) {
        return self::query("SELECT c.*, CONCAT(cl.nombre, ' ', IFNULL(cl.apellidos,'')) as cliente_nombre, v.matricula
            FROM citas c
            JOIN clientes cl ON c.id_cliente = cl.id
            JOIN vehiculos v ON c.id_vehiculo = v.id
            WHERE c.fecha_cita BETWEEN ? AND ?
            ORDER BY c.fecha_cita ASC", [$inicio, $fin]);
    }
}
