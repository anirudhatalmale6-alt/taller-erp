<?php
class Documento extends Model {
    protected static $table = 'doc_cab';
    protected static $fillable = ['id_doc', 'matricula', 'descripcion', 'fecha', 'hora', 'imagen', 'activo'];

    public static function porMatricula($matricula) {
        return self::findAll("matricula = ? AND activo = 'SI'", [$matricula], 'fecha DESC, hora DESC');
    }
}
