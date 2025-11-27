<?php
session_start();

include_once "../../modelo/modeloPrincipal.php";
require 'fpdf/fpdf.php';

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
        $this->Cell(0,5,self::convert_codification("Lista de Empleados"),0,0,"C");

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
$pdf->AddPage('P',[275,320],0);
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(5);
$pdf->SetRightMargin(5);

$id_usuario = $_SESSION['user_id'];

$consulta = modeloPrincipal::consultar("SELECT id_usuario, cedula, nombre, apellido, telefono, correo, direccion, estado FROM usuario 
    WHERE id_usuario != '$id_usuario' AND id_rol != 1 ORDER BY nombre ASC");
// en caso de que no se encuentren proveedores registrados

$pdf->setY(60);
$pdf->setX(5);

// En esta parte estan los encabezados 
$pdf->SetFont('Arial','B',8);
$pdf->Cell(10, 5, $pdf->convert_codification('Nº'),'B',0,'C',0);
$pdf->Cell(20, 5, $pdf->convert_codification('Cédula / RIF'),'B',0,'C',0);
$pdf->Cell(50, 5, $pdf->convert_codification('Nombre y Apellido'),'B',0,'C',0);
$pdf->Cell(60, 5, $pdf->convert_codification('Correo Electrónico'),'B',0,'C',0);
$pdf->Cell(80, 5, $pdf->convert_codification('Dirección'),'B',0,'C',0);
$pdf->Cell(25, 5, $pdf->convert_codification('Teléfono'),'B',0,'C',0);
$pdf->Cell(20, 5, $pdf->convert_codification('Estado'),'B',0,'C',0);
$pdf->Ln();


if (mysqli_num_rows($consulta) < 1 ){

    $pdf->SetFont('Arial','',10);

    $pdf->Cell(210, 5, $pdf->convert_codification('NO SE ENCONTRARON EMPLEADOS REGISTRADOS.'),'B',1,'C',0);
    $pdf->Cell(210, 5, $pdf->convert_codification('ASEGURESE DE HABER REGISTRADO CORRECTAMENTE LOS EMPLEADOS.'),'B',1,'C',0);
    
    $pdf->Output("I","Listado de Empleados (".date('d/m/Y | g:i:a').").pdf",true);
}


$pdf->SetFont('Arial','',8);
$i = 1;
while ( $mostrar = mysqli_fetch_array($consulta)) {
    
    $pdf->setX(5);
    
    $pdf->Cell(10, 5, $pdf->convert_codification($i++),'B',0,'C',0);
    $pdf->Cell(20, 5, $pdf->convert_codification($mostrar["cedula"]),'B',0,'L',0);
    $pdf->Cell(50, 5, $pdf->convert_codification($mostrar["nombre"].' '.$mostrar["apellido"]),'B',0,'L',0);
    $pdf->Cell(60, 5, $pdf->convert_codification($mostrar["correo"]),'B',0,'L',0);
    $pdf->Cell(80, 5, $pdf->convert_codification($mostrar["direccion"]),'B',0,'L',0);
    $pdf->Cell(25, 5, $pdf->convert_codification($mostrar["telefono"]),'B',0,'C',0);
    $pdf->Cell(20, 5, $pdf->convert_codification(($mostrar["estado"] == 1) ? 'Activo' : 'InactivoO'),'B',1,'C',0);
    
} 
$pdf->Output("I","Listado de Empleados (".date('d/m/Y | g:i:a').").pdf",true);