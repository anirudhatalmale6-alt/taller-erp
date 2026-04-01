<?php
class Cliente extends Model {
    protected static $table = 'clientes';
    protected static $fillable = ['nombre', 'apellidos', 'nif', 'direccion', 'cpostal', 'poblacion', 'provincia', 'pais', 'telefono', 'email', 'redsocial1', 'redsocial2', 'redsocial3', 'redsocial4', 'fecha_alta', 'fecha_modificacion', 'activo'];

    public static function search($q) {
        return self::findAll("(nombre LIKE ? OR apellidos LIKE ? OR nif LIKE ? OR telefono LIKE ?) AND activo = 'SI'", ["%$q%", "%$q%", "%$q%", "%$q%"], 'nombre ASC', '20');
    }

    public static function getVehiculos($clienteId) {
        return Vehiculo::findAll("id_cliente = ? AND activo = 'SI'", [$clienteId], 'marca ASC, modelo ASC');
    }

    public static function nombreCompleto($c) {
        return trim($c['nombre'] . ' ' . ($c['apellidos'] ?? ''));
    }
}
