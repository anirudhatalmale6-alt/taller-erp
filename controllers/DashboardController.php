<?php
class DashboardController extends Controller {

    public function index() {
        $this->requireAuth();

        $db = getDB();

        $stats = [
            'clientes' => Cliente::count("activo = 'SI'"),
            'vehiculos' => Vehiculo::count("activo = 'SI'"),
            'citas_hoy' => Cita::count("DATE(fecha_cita) = CURDATE() AND estado IN ('pendiente','confirmada','en_curso')"),
            'depositos_abiertos' => Deposito::count("activo = 'SI'"),
            'presupuestos_pendientes' => Presupuesto::count("tipo_doc = 'PRESUPUESTO' AND activo = 'SI' AND aceptado = 'NO'"),
            'facturas_pendientes' => Factura::count("estado IN ('borrador','enviada')"),
        ];

        // Revenue this month
        $stmt = $db->query("SELECT COALESCE(SUM(total), 0) as total FROM factura_cab WHERE estado = 'pagada' AND MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())");
        $stats['facturacion_mes'] = $stmt->fetch()['total'];

        // Today's appointments
        $citasHoy = Cita::hoy();

        // Recent documents
        $recientes = $db->query("
            (SELECT 'deposito' as doc_tipo, id_deposito as numero, fecha as doc_fecha, activo as estado, id_cliente FROM dep_cab ORDER BY created_at DESC LIMIT 5)
            UNION ALL
            (SELECT 'presupuesto', id_pre_ord, fecha, activo, id_cliente FROM pre_ord_cab WHERE tipo_doc = 'PRESUPUESTO' ORDER BY created_at DESC LIMIT 5)
            UNION ALL
            (SELECT 'albaran', id_albaran, fecha, activo, id_cliente FROM albaran_cab ORDER BY created_at DESC LIMIT 5)
            UNION ALL
            (SELECT 'factura', id_factura, fecha, estado, id_cliente FROM factura_cab ORDER BY created_at DESC LIMIT 5)
            ORDER BY doc_fecha DESC LIMIT 10
        ")->fetchAll();

        $this->view('dashboard/index', [
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
            'citasHoy' => $citasHoy,
            'recientes' => $recientes,
        ]);
    }
}
