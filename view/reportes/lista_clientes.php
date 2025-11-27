<?php
session_start();

include_once "../../modelo/modeloPrincipal.php";
require_once 'fpdf/fpdf.php';

date_default_timezone_set('America/caracas');

class PDF extends FPDF{
    function Header(){
        
        
        $this->Image('img/logo.png',10,5,33);

        $this->setY(10);
        $this->setX(10);
        $this->SetFont('times', 'B', 13);
        $this->SetDrawColor(255,255,255);
        $this->SetTextColor(255,255,255);
        $this->Cell(400,5,self::convert_codification("."),0,1,"C");
        $this->SetDrawColor(0,0,0);
        $this->SetTextColor(0,0,0);
        $this->Cell(0,5,self::convert_codification("BAR RESTAURANT Y LUNCHERIA 'LA CHINITA'"),0,1,"C");
        
        $this->setY(15);
        $this->setX(10);
        $this->Cell(0,5,self::convert_codification(" "),0,1,"C");
        $this->Cell(0,5,self::convert_codification("PISTA DE BAILE Y MESA DE POOL"),0,1,"C");
        $this->Cell(0,7,self::convert_codification("Calle 2 entre Av 5 y 6 - Turén Edo. Portuguesa"),0,1,'C');

        $this->setY(25);
        $this->setX(10);
        
        $this->setY(30);
        $this->setX(10);
        $this->Cell(0,7,self::convert_codification("RIF: J-04608675-5"),0,1,'C');
        
        $this->setY(40);
        $this->setX(10);
        $this->Cell(0,5,self::convert_codification("Lista de Clientes"),0,0,"C");

        $this->Ln(50);
    }

    function Footer(){
        $this->SetFont('helvetica', 'B', 8);
        $this->SetY(-15);
        $this->Cell(100,5,self::convert_codification('Página ').$this->PageNo().' / {nb}',0,0,'L');
        $this->Cell(100,5,date('d/m/Y | g:i:a') ,00,1,'R');
        $this->Line(5,287,215,287);
        $this->Cell(0,5,self::convert_codification("© Todos los derechos reservados."),0,0,"C");
            
    }

    public static function convert_codification ($cadena):string {
        return mb_convert_encoding("$cadena", 'ISO-8859-1', 'UTF-8');
    }
    

}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(5);
$pdf->SetRightMargin(5);

$consulta = modeloPrincipal::consultar("SELECT * FROM cliente ORDER BY nombre ASC");

// en caso de que no se encuentren proveedores registrados

$pdf->setY(60);
$pdf->setX(10);

// En esta parte estan los encabezados 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10, 5, $pdf->convert_codification('Nº'),'B',0,'C',0);
$pdf->Cell(60, 5, $pdf->convert_codification('Cédula'),'B',0,'C',0);
$pdf->Cell(60, 5, $pdf->convert_codification('Nombre y Apellido'),'B',0,'C',0);
$pdf->Cell(60, 5, $pdf->convert_codification('Teléfono'),'B',1,'C',0);
$pdf->SetFont('Arial','',10);

if (mysqli_num_rows($consulta) < 1 ){

    $pdf->Cell(0, 5, $pdf->convert_codification('NO SE ENCONTRARON CLIENTES REGISTRADOS.'),'B',1,'C',0);
    $pdf->Cell(0, 5, $pdf->convert_codification('ASEGURESE DE HABER REGISTRADO CORRECTAMENTE LOS CLIENTES.'),'B',1,'C',0);
    
    $pdf->Output("I","Listado de Clientes (".date('d/m/Y | g:i:a').").pdf",true);
}

$pdf->SetFont('Arial','',8);
$i = 1;
while ( $mostrar = mysqli_fetch_array($consulta)) { 
    
    $pdf->setX(10);

    $pdf->Cell(10, 5, $pdf->convert_codification($i++),'B',0,'C',0);
    $pdf->Cell(60, 5, $pdf->convert_codification($mostrar["cedula"]),'B',0,'C',0);
    $pdf->Cell(60, 5, $pdf->convert_codification($mostrar["nombre"]),'B',0,'C',0);
    $pdf->Cell(60, 5, $pdf->convert_codification($mostrar["telefono"]),'B',1,'C',0);
    
} 
$pdf->Output("I","Listado de Clientes (".date('d/m/Y | g:i:a').").pdf",true);