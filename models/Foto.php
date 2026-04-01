<?php
class Foto extends Model {
    protected static $table = 'fotos_cab';
    protected static $fillable = ['id_foto', 'matricula', 'descripcion', 'fecha', 'hora', 'imagen', 'activo'];

    public static function porMatricula($matricula) {
        return self::findAll("matricula = ? AND activo = 'SI'", [$matricula], 'fecha DESC, hora DESC');
    }
}
