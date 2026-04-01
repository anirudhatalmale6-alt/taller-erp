<?php
class EstadisticaController extends Controller {

    public function index() {
        $this->requireAuth();
        $db = getDB();

        // Revenue by month (last 12 months)
        $facturacionMes = $db->query("SELECT DATE_FORMAT(fecha, '%Y-%m') as mes, SUM(total) as total FROM factura_cab WHERE estado = 'pagada' AND fecha >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY mes ORDER BY mes")->fetchAll();

        // Document counts
        $docCounts = [
            'citas' => Cita::count(),
            'depositos' => Deposito::count(),
            'presupuestos' => Presupuesto::count("tipo_doc = 'PRESUPUESTO'"),
            'ordenes' => Presupuesto::count("tipo_doc = 'ORDEN'"),
            'albaranes' => Albaran::count(),
            'facturas' => Factura::count(),
            'proyectos' => Proyecto::count(),
        ];

        // Invoice status distribution
        $facturaEstados = $db->query("SELECT estado, COUNT(*) as total FROM factura_cab GROUP BY estado")->fetchAll();

        // Top clients by revenue
        $topClientes = $db->query("SELECT CONCAT(cl.nombre, ' ', IFNULL(cl.apellidos,'')) as nombre, SUM(f.total) as total FROM factura_cab f JOIN clientes cl ON f.id_cliente = cl.id WHERE f.estado = 'pagada' GROUP BY f.id_cliente ORDER BY total DESC LIMIT 10")->fetchAll();

        // Work by section
        $trabajoPorSeccion = $db->query("SELECT t.seccion, COUNT(*) as total FROM pre_ord_det pd LEFT JOIN tareas t ON pd.id_tarea = t.id WHERE t.seccion IS NOT NULL GROUP BY t.seccion ORDER BY total DESC")->fetchAll();

        // Vehicle brands
        $marcas = $db->query("SELECT marca, COUNT(*) as total FROM vehiculos WHERE activo = 'SI' AND marca != '' GROUP BY marca ORDER BY total DESC LIMIT 10")->fetchAll();

        $this->view('estadisticas/index', [
            'pageTitle' => 'Estadisticas',
            'facturacionMes' => $facturacionMes,
            'docCounts' => $docCounts,
            'facturaEstados' => $facturaEstados,
            'topClientes' => $topClientes,
            'trabajoPorSeccion' => $trabajoPorSeccion,
            'marcas' => $marcas,
            'extraJs' => ['https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js'],
        ]);
    }
}
