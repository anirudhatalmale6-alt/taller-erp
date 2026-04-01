<?php
class Operario extends Model {
    protected static $table = 'operarios';
    protected static $fillable = ['id_iniciales', 'nombre', 'apellidos', 'telefono', 'email', 'clave', 'seccion', 'activo'];

    public static function activos() {
        return self::findAll("activo = 'SI'", [], 'nombre ASC');
    }

    public static function nombreCompleto($o) {
        return trim($o['nombre'] . ' ' . ($o['apellidos'] ?? ''));
    }
}
