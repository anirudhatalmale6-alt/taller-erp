<?php
class Deposito extends Model {
    protected static $table = 'dep_cab';
    protected static $fillable = ['id_deposito', 'matricula', 'id_cliente', 'kilometros', 'nivel_combustible', 'fecha', 'hora', 'id_operario', 'descripcion_trabajos', 'observaciones', 'acepta_presupuesto', 'acepta_ocultos', 'acepta_piezas', 'acepta_conduccion', 'acepta_piezas_usadas', 'firma_resguardo', 'firma_presupuesto', 'activo', 'created_by'];

    public static function conRelaciones($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT d.*, CONCAT(cl.nombre, ' ', IFNULL(cl.apellidos,'')) as cliente_nombre, cl.telefono as cliente_telefono, cl.email as cliente_email, v.marca, v.modelo, v.color
            FROM dep_cab d
            LEFT JOIN clientes cl ON d.id_cliente = cl.id
            LEFT JOIN vehiculos v ON d.matricula = v.matricula
            WHERE d.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
