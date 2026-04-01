<?php
class TareaCatalogo extends Model {
    protected static $table = 'tareas';
    protected static $fillable = ['id_tarea', 'descripcion', 'seccion', 'familia', 'tiempo_previsto', 'activo'];

    public static function activas() {
        return self::findAll("activo = 'SI'", [], 'seccion ASC, descripcion ASC');
    }

    public static function porSeccion() {
        $tareas = self::activas();
        $agrupadas = [];
        foreach ($tareas as $t) {
            $agrupadas[$t['seccion']][] = $t;
        }
        return $agrupadas;
    }
}
