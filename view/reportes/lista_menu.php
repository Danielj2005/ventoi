<?php
session_start();
include_once "../../modelo/modeloPrincipal.php";
require 'fpdf/fpdf.php';


class PDF extends FPDF{
    function Header(){        

        $this->setY(20);
        $this->setX(10);
        $this->SetFont('times', 'B', 13);
        $this->Cell(0,5,utf8_decode("BAR-RESTAURANT"),0,0,"C");
        
        $this->setY(25);
        $this->setX(10);
        $this->Cell(0,5,utf8_decode("LA CHINITA"),0,0,"C");

        $this->setY(40);
        $this->setX(10);
        $this->Cell(0,5,utf8_decode("Lista de Productos"),0,0,"C");

        $this->Ln(50);
    }

    function Footer(){
        $this->SetFont('helvetica', 'B', 8);
        $this->SetY(-15);
        $this->Cell(100,5,utf8_decode('Página ').$this->PageNo().' / {nb}',0,0,'L');
        $this->Cell(100,5,date('d/m/Y | g:i:a') ,00,1,'R');
        $this->Line(5,287,215,287);
        $this->Cell(0,5,utf8_decode("© Todos los derechos reservados."),0,0,"C");
            
    }

}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('P',[220,297],0);
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(5);
$pdf->SetRightMargin(5);

$consulta = modeloPrincipal::consultar("SELECT nombre_producto,precio_compra,stock,nombre AS categoria FROM producto AS P 
    INNER JOIN categoria AS C ON P.id_categoria = C.id_categoria ORDER BY nombre ASC");

// en caso de que no se encuentren proveedores registrados

if (mysqli_num_rows($consulta) < 1 ){
    $pdf->Ln();

    $pdf->setY(60);
    $pdf->setX(5);
    
    // En esta parte estan los encabezados 
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10, 5, utf8_decode('Nº'),'B',0,'C',0);
    $pdf->Cell(50, 5, utf8_decode('PRODUCTO'),'B',0,'C',0);
    $pdf->Cell(45, 5, utf8_decode('PRECIO DE COMPRA'),'B',0,'C',0);
    $pdf->Cell(45, 5, utf8_decode('CANTIDAD'),'B',0,'C',0);
    $pdf->Cell(50, 5, utf8_decode('CATEGORÍA'),'B',0,'C',0);
    $pdf->SetFont('Arial','',10);

    $pdf->Cell(210, 5, utf8_decode('NO SE ENCONTRARON PRODUCTOS REGISTRADOS.'),'B',1,'C',0);
    $pdf->Cell(210, 5, utf8_decode('ASEGURESE DE HABER REGISTRADO CORRECTAMENTE LOS PRODUCTOS.'),'B',1,'C',0);
    
    $pdf->Output("I","Listado de Productos (".date('d/m/Y | g:i:a').").pdf",true);
}

$pdf->setY(60);
$pdf->setX(10);

// En esta parte estan los encabezados 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(10, 5, utf8_decode('Nº'),'B',0,'C',0);
$pdf->Cell(50, 5, utf8_decode('PRODUCTO'),'B',0,'C',0);
$pdf->Cell(45, 5, utf8_decode('PRECIO DE COMPRA'),'B',0,'C',0);
$pdf->Cell(45, 5, utf8_decode('CANTIDAD'),'B',0,'C',0);
$pdf->Cell(50, 5, utf8_decode('CATEGORÍA'),'B',0,'C',0);
$pdf->Ln();


$pdf->SetFont('Arial','',8);
$i = 1;
while ( $mostrar = mysqli_fetch_array($consulta)) { 
    
    $pdf->setX(10);
    $pdf->Cell(10, 5, utf8_decode($i++),'B',0,'C',0);
    $pdf->Cell(50, 5, utf8_decode($mostrar["nombre_producto"]),'B',0,'C',0);
    $pdf->Cell(45, 5, utf8_decode($mostrar["precio_compra"].'$'),'B',0,'C',0);
    $pdf->Cell(45, 5, utf8_decode($mostrar["stock"]),'B',0,'C',0);
    $pdf->Cell(50, 5, utf8_decode($mostrar["categoria"]),'B',1,'C',0);
    
} 
$pdf->Output("I","Listado de Productos (".date('d/m/Y | g:i:a').").pdf",true);