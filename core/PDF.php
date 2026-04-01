<?php
// PDF Document Generator using TCPDF

require_once __DIR__ . '/../vendor/autoload.php';

function generarPDFDocumento($titulo, $doc, $lineas, $config) {
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    $pdf->SetCreator(APP_NAME);
    $pdf->SetAuthor($config['empresa_nombre']);
    $pdf->SetTitle($titulo . ' ' . $doc['numero']);

    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(true, 25);
    $pdf->AddPage();

    // Company header
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 8, $config['empresa_nombre'], 0, 1);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(0, 4, 'CIF: ' . $config['empresa_cif'], 0, 1);
    $pdf->MultiCell(90, 4, $config['empresa_direccion'], 0, 'L');
    $pdf->Cell(0, 4, 'Tel: ' . $config['empresa_telefono'] . ' | ' . $config['empresa_email'], 0, 1);

    // Document title and number (right side)
    $pdf->SetY(15);
    $pdf->SetX(110);
    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->Cell(85, 10, strtoupper($titulo), 0, 1, 'R');
    $pdf->SetX(110);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(85, 6, $doc['numero'], 0, 1, 'R');
    $pdf->SetX(110);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(85, 5, 'Fecha: ' . date('d/m/Y', strtotime($doc['fecha'])), 0, 1, 'R');

    if (!empty($doc['validez_dias'])) {
        $pdf->SetX(110);
        $pdf->Cell(85, 5, 'Valido hasta: ' . date('d/m/Y', strtotime($doc['fecha'] . ' + ' . $doc['validez_dias'] . ' days')), 0, 1, 'R');
    }
    if (!empty($doc['fecha_vencimiento'])) {
        $pdf->SetX(110);
        $pdf->Cell(85, 5, 'Vencimiento: ' . date('d/m/Y', strtotime($doc['fecha_vencimiento'])), 0, 1, 'R');
    }

    $pdf->Ln(5);

    // Separator
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
    $pdf->Ln(5);

    // Client info
    $y = $pdf->GetY();
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(90, 5, 'CLIENTE', 0, 1);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(90, 5, $doc['cliente_nombre'], 0, 1);
    if (!empty($doc['cif_nif'])) $pdf->Cell(90, 5, 'CIF/NIF: ' . $doc['cif_nif'], 0, 1);
    if (!empty($doc['cliente_direccion'])) $pdf->MultiCell(90, 5, $doc['cliente_direccion'], 0, 'L');
    if (!empty($doc['cliente_telefono'])) $pdf->Cell(90, 5, 'Tel: ' . $doc['cliente_telefono'], 0, 1);
    if (!empty($doc['cliente_email'])) $pdf->Cell(90, 5, $doc['cliente_email'], 0, 1);

    // Vehicle info (right column)
    $pdf->SetY($y);
    $pdf->SetX(110);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(85, 5, 'VEHICULO', 0, 1, 'L');
    $pdf->SetX(110);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(85, 5, $doc['matricula'] . ' - ' . $doc['marca'] . ' ' . $doc['modelo'], 0, 1, 'L');

    $pdf->Ln(8);

    // Line items table header
    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(10, 7, '#', 1, 0, 'C', true);
    $pdf->Cell(80, 7, 'Concepto', 1, 0, 'L', true);
    $pdf->Cell(20, 7, 'Cant.', 1, 0, 'C', true);
    $pdf->Cell(25, 7, 'Precio', 1, 0, 'R', true);
    $pdf->Cell(15, 7, 'Dto.%', 1, 0, 'C', true);
    $pdf->Cell(30, 7, 'Importe', 1, 1, 'R', true);

    // Line items
    $pdf->SetFont('helvetica', '', 8);
    foreach ($lineas as $i => $l) {
        $h = 6;
        // Check page break
        if ($pdf->GetY() + $h > 260) {
            $pdf->AddPage();
        }
        $pdf->Cell(10, $h, $i + 1, 1, 0, 'C');
        $concepto = $l['concepto'];
        if (!empty($l['operario_nombre'])) $concepto .= ' (' . $l['operario_nombre'] . ')';
        $pdf->Cell(80, $h, $concepto, 1, 0, 'L');
        $pdf->Cell(20, $h, number_format($l['cantidad'], 2, ',', ''), 1, 0, 'C');
        $pdf->Cell(25, $h, number_format($l['precio_unitario'], 2, ',', '.') . ' EUR', 1, 0, 'R');
        $pdf->Cell(15, $h, $l['descuento'] > 0 ? number_format($l['descuento'], 1) . '%' : '-', 1, 0, 'C');
        $pdf->Cell(30, $h, number_format($l['importe'], 2, ',', '.') . ' EUR', 1, 1, 'R');
    }

    $pdf->Ln(3);

    // Totals
    $pdf->SetX(120);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(30, 6, 'Subtotal:', 0, 0, 'R');
    $pdf->Cell(30, 6, number_format($doc['subtotal'], 2, ',', '.') . ' EUR', 0, 1, 'R');

    if ($doc['descuento_porcentaje'] > 0) {
        $pdf->SetX(120);
        $pdf->Cell(30, 6, 'Descuento (' . number_format($doc['descuento_porcentaje'], 1) . '%):', 0, 0, 'R');
        $pdf->Cell(30, 6, '-' . number_format($doc['descuento_importe'], 2, ',', '.') . ' EUR', 0, 1, 'R');
    }

    $pdf->SetX(120);
    $pdf->Cell(30, 6, 'IVA (' . number_format($doc['iva_porcentaje'], 1) . '%):', 0, 0, 'R');
    $pdf->Cell(30, 6, number_format($doc['iva_importe'], 2, ',', '.') . ' EUR', 0, 1, 'R');

    $pdf->SetX(120);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(30, 8, 'TOTAL:', 'T', 0, 'R');
    $pdf->Cell(30, 8, number_format($doc['total'], 2, ',', '.') . ' EUR', 'T', 1, 'R');

    // Conditions
    if (!empty($doc['condiciones'])) {
        $pdf->Ln(8);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(0, 5, 'CONDICIONES:', 0, 1);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->MultiCell(0, 4, $doc['condiciones'], 0, 'L');
    }

    // Payment method
    if (!empty($doc['forma_pago'])) {
        $pdf->Ln(3);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(0, 5, 'Forma de pago: ' . ucfirst($doc['forma_pago']), 0, 1);
    }

    // Footer
    $pdf->SetY(-20);
    $pdf->SetFont('helvetica', 'I', 7);
    $pdf->Cell(0, 5, $config['empresa_nombre'] . ' | ' . $config['empresa_cif'] . ' | ' . $config['empresa_telefono'], 0, 1, 'C');
    $pdf->Cell(0, 5, 'Documento generado el ' . date('d/m/Y H:i'), 0, 0, 'C');

    // Output
    $filename = strtolower($titulo) . '_' . $doc['numero'] . '.pdf';
    $pdf->Output($filename, 'I'); // I = inline (browser), D = download
    exit;
}
