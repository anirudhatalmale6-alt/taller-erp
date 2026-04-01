<?php
// Base Model class

class Model {
    protected static $table = '';
    protected static $fillable = [];

    public static function getDB() {
        return getDB();
    }

    public static function findById($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM " . static::$table . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function findAll($where = '', $params = [], $order = 'id DESC', $limit = '') {
        $db = self::getDB();
        $sql = "SELECT * FROM " . static::$table;
        if ($where) $sql .= " WHERE $where";
        $sql .= " ORDER BY $order";
        if ($limit) $sql .= " LIMIT $limit";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findOne($where, $params = []) {
        $db = self::getDB();
        $sql = "SELECT * FROM " . static::$table . " WHERE $where LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public static function count($where = '', $params = []) {
        $db = self::getDB();
        $sql = "SELECT COUNT(*) as total FROM " . static::$table;
        if ($where) $sql .= " WHERE $where";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetch()['total'];
    }

    public static function paginate($page = 1, $perPage = 20, $where = '', $params = [], $order = 'id DESC') {
        $db = self::getDB();
        $offset = ($page - 1) * $perPage;
        $total = self::count($where, $params);

        $sql = "SELECT * FROM " . static::$table;
        if ($where) $sql .= " WHERE $where";
        $sql .= " ORDER BY $order LIMIT $perPage OFFSET $offset";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage),
        ];
    }

    public static function insert($data) {
        $db = self::getDB();
        $fields = array_intersect_key($data, array_flip(static::$fillable));
        $fields = self::sanitizeFields($fields);
        $cols = implode(', ', array_keys($fields));
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        $sql = "INSERT INTO " . static::$table . " ($cols) VALUES ($placeholders)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array_values($fields));
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = self::getDB();
        $fields = array_intersect_key($data, array_flip(static::$fillable));
        $fields = self::sanitizeFields($fields);
        $sets = implode(', ', array_map(fn($k) => "$k = ?", array_keys($fields)));
        $sql = "UPDATE " . static::$table . " SET $sets WHERE id = ?";
        $params = array_values($fields);
        $params[] = $id;
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    protected static function sanitizeFields($fields) {
        foreach ($fields as $k => $v) {
            if ($v === '' && (
                str_contains($k, 'fecha') || str_contains($k, 'date') ||
                str_contains($k, 'kilometros') || str_contains($k, 'importe') ||
                str_contains($k, 'total') || str_contains($k, 'iva') ||
                str_contains($k, 'descuento') || str_contains($k, 'precio') ||
                str_contains($k, 'ano') || str_contains($k, 'anio') ||
                str_contains($k, 'tiempo') || str_contains($k, 'potencia')
            )) {
                $fields[$k] = null;
            }
        }
        return $fields;
    }

    public static function delete($id) {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM " . static::$table . " WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function query($sql, $params = []) {
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function exec($sql, $params = []) {
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    // Copy detail lines from one document to another (using per-type _det tables)
    public static function copiarLineas($origenTabla, $origenCampoDoc, $origenDocId, $destinoTabla, $destinoCampoDoc, $destinoDocId) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM $origenTabla WHERE $origenCampoDoc = ? ORDER BY orden");
        $stmt->execute([$origenDocId]);
        $lineas = $stmt->fetchAll();

        foreach ($lineas as $l) {
            $db->prepare("INSERT INTO $destinoTabla ($destinoCampoDoc, id_tarea, id_tarticulo, cantidad, descripcion, precio, importe, precio_coste, fecha, tiempo_asignado, tiempo_realizado, finalizado, orden) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)")
               ->execute([$destinoDocId, $l['id_tarea'], $l['id_tarticulo'], $l['cantidad'], $l['descripcion'], $l['precio'], $l['importe'], $l['precio_coste'], $l['fecha'], $l['tiempo_asignado'], $l['tiempo_realizado'], $l['finalizado'], $l['orden']]);
        }
        return count($lineas);
    }

    // Recalculate document totals from its detail lines (using per-type _det tables)
    public static function recalcularTotales($cabTabla, $cabId, $detTabla, $detCampoDoc, $detDocId) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT COALESCE(SUM(importe), 0) as subtotal FROM $detTabla WHERE $detCampoDoc = ?");
        $stmt->execute([$detDocId]);
        $subtotal = (float)$stmt->fetch()['subtotal'];

        $docStmt = $db->prepare("SELECT iva_porcentaje, descuento_porcentaje FROM $cabTabla WHERE id = ?");
        $docStmt->execute([$cabId]);
        $doc = $docStmt->fetch();

        $descuentoPorc = (float)($doc['descuento_porcentaje'] ?? 0);
        $descuentoImporte = $subtotal * ($descuentoPorc / 100);
        $baseImponible = $subtotal - $descuentoImporte;
        $ivaPorcentaje = (float)($doc['iva_porcentaje'] ?? 21);
        $ivaImporte = $baseImponible * ($ivaPorcentaje / 100);
        $total = $baseImponible + $ivaImporte;

        $db->prepare("UPDATE $cabTabla SET importe = ?, descuento_importe = ?, iva_importe = ?, total = ? WHERE id = ?")
           ->execute([$subtotal, $descuentoImporte, $ivaImporte, $total, $cabId]);

        return ['importe' => $subtotal, 'descuento_importe' => $descuentoImporte, 'iva_importe' => $ivaImporte, 'total' => $total];
    }
}
