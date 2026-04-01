<?php
class Configuracion extends Model {
    protected static $table = 'configuracion';
    protected static $fillable = ['empresa_nombre', 'empresa_cif', 'empresa_direccion', 'empresa_telefono', 'empresa_email', 'empresa_logo', 'iva_porcentaje', 'moneda', 'prefijo_cita', 'prefijo_deposito', 'prefijo_presupuesto', 'prefijo_albaran', 'prefijo_factura', 'prefijo_proforma', 'prefijo_proyecto', 'condiciones_presupuesto', 'condiciones_factura'];
}
