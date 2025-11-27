<?php
require "./code128.php";

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Bar Restaurante La Chinita', 0, 1, 'C');
        $this->Cell(0, 10, 'Turen, Estado Portuguesa', 0, 1, 'C');
        $this->Cell(0, 10, '--------------------------------', 0, 1, 'C');
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

function createTicket() {
    $pdf = new PDF();
    $pdf->AddPage();
    
    // Información de la factura
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Fecha: ' . date('Y-m-d'), 0, 1);
    $pdf->Cell(0, 10, 'Hora: ' . date('H:i:s'), 0, 1);
    $pdf->Cell(0, 10, 'Factura No: 00123', 0, 1);
    $pdf->Cell(0, 10, '--------------------------------', 0, 1);
    
    // Detalles de los productos
    $pdf->Cell(0, 10, 'Producto         Cantidad   Precio', 0, 1);
    $pdf->Cell(0, 10, '--------------------------------', 0, 1);
    $pdf->Cell(0, 10, 'Cerveza          2          $5.00', 0, 1);
    $pdf->Cell(0, 10, 'Comida           1          $10.00', 0, 1);
    $pdf->Cell(0, 10, '--------------------------------', 0, 1);
    $pdf->Cell(0, 10, 'Total:          $20.00', 0, 1);
    
    // Generar código de barras
    $barcode = new PDF_Code128('P','mm',array(80,258));

    $barcode->AddPage();
    $barcode->SetFont('helvetica', '', 12);
    // $barcode->write1DBarcode('00123', 'C128', '', '', '', 18, 0.4, '', 'N');
    
    // Guardar el código de barras como imagen
    $barcodeFilename = 'barcode.png';
    $barcode->Output($barcodeFilename, 'F');

    // Agregar código de barras al PDF
    $pdf->Image($barcodeFilename, 10, $pdf->GetY(), 100);
    
    // Guardar el PDF
    $pdf->Output('ticket_factura.pdf', 'F');
}

createTicket();
?>