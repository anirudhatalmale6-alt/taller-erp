<?php
class Vehiculo extends Model {
    protected static $table = 'vehiculos';
    protected static $fillable = ['matricula', 'num_chasis', 'marca', 'modelo', 'version_modelo', 'color', 'potencia', 'anio', 'num_motor', 'emisiones', 'tipo_aceite', 'fecha_matriculacion', 'ano_fabricacion', 'en_venta', 'vendido', 'sustitucion', 'id_cliente', 'activo'];

    public static function search($q, $clienteId = null) {
        $where = "(matricula LIKE ? OR marca LIKE ? OR modelo LIKE ?) AND activo = 'SI'";
        $params = ["%$q%", "%$q%", "%$q%"];
        if ($clienteId) {
            $where .= " AND id_cliente = ?";
            $params[] = $clienteId;
        }
        return self::findAll($where, $params, 'matricula ASC', '20');
    }

    public static function conCliente($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT v.*, CONCAT(c.nombre, ' ', IFNULL(c.apellidos,'')) as cliente_nombre, c.telefono as cliente_telefono FROM vehiculos v JOIN clientes c ON v.id_cliente = c.id WHERE v.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function porMatricula($matricula) {
        return self::findOne("matricula = ?", [$matricula]);
    }
}
