<?php
session_start();
include_once "../../modelo/modeloPrincipal.php";
require 'fpdf/fpdf.php';

date_default_timezone_set('America/caracas');

class PDF extends FPDF{
        
    function Header(){

        $this->Image('img/logo.png',10,5,33);


        if (isset($_POST['UID'])) {
            $id_proveedor = modeloPrincipal::decryptionId($_POST["UID"]);
            $id_proveedor = modeloPrincipal::limpiar_cadena($id_proveedor);
            
            $dataProvider = mysqli_fetch_array(modeloPrincipal::consultar("SELECT * FROM proveedor WHERE id_proveedor = $id_proveedor"));
        }else{
            $dataProvider = ["id_proveedor" => "", "cedula_rif" => "", "nombre" => "", "telefono" => "", "correo" => "", "direccion" => ""];
        }
        

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
        
        $this->SetFont('Arial','',10);

        $this->setY(40);
        // datos del proveedor
        $this->Cell(80, 5, self::convert_codification('Datos del Proveedor: '),'B',1,'L',0);
        
        $this->setX(10);
        // datos del proveedor
        $this->Cell(80, 5, self::convert_codification('Cédula / RIF: '.($dataProvider['cedula_rif'] == "" ? 'No encontado(a)' : $dataProvider['cedula_rif'])),'',1,'L',0);

        $this->Cell(80, 5, self::convert_codification('Nombre: '.($dataProvider['nombre'] == "" ? 'No encontado(a)' : $dataProvider['nombre'])),'',1,'L',0);

        $this->Cell(80, 5, self::convert_codification('Teléfono: '.($dataProvider['telefono'] == "" ? 'No encontado(a)' : $dataProvider['telefono'])),'',1,'L',0);

        $this->Cell(80, 5, self::convert_codification('Correo: '.($dataProvider['correo'] == "" ? 'No encontado(a)' : $dataProvider['correo'])),'',1,'L',0);

        $this->Cell(80, 5, self::convert_codification('Dirección: '.($dataProvider['direccion'] == "" ? 'No encontado(a)' : $dataProvider['direccion'])),'',1,'L',0);

        $this->Ln(50);
    }

    function Footer(){
        $this->SetFont('helvetica', 'B', 10);
        $this->SetY(-20);
        $this->Cell(190,5,self::convert_codification('Página ').$this->PageNo().' / {nb}',0,0,'L');
        $this->Cell(190,5,date('d/m/Y | g:i:a') ,00,1,'R');
        $this->SetY(-15);
        $this->Line(5, 485,390,485);
        $this->SetY(-10);
        $this->Cell(400,5,self::convert_codification("© Todos los derechos reservados."),0,0,"C");       
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


// se definen variable con los tamaños de las celdas para mejor adaptacion
$CellTotalDolar = 30;
$CellTotalBS = 30;
$CellCotización = 35;
$CellFecha = 45;
$CellUsuario = 50;



$setY = 75;
$pdf->setY($setY);
$pdf->setX(5);
// En esta parte estan los encabezados 
$pdf->SetFont('Arial','B',10);

$pdf->Cell(10, 5, $pdf->convert_codification('Nº'),'LTRB',0,'C',0);
$pdf->Cell($CellFecha, 5, $pdf->convert_codification('Fecha y Hora'),'LTRB',0,'C',0);
$pdf->Cell($CellTotalDolar, 5, $pdf->convert_codification('Total ($)'),'LTRB',0,'C',0);
$pdf->Cell($CellTotalBS, 5, $pdf->convert_codification('Total (Bs)'),'LTRB',0,'C',0);
$pdf->Cell($CellCotización, 5, $pdf->convert_codification('Tasa de Cambio'),'LTRB',0,'C',0);
$pdf->Cell($CellUsuario, 5, $pdf->convert_codification('Registrado por'),'LTRB',1,'C',0);


if (!isset($_POST['UID'])){
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0, 5, $pdf->convert_codification('NO SE SELECCIONO CORRECTAMENTE A UN PROVEEDOR.'),'B',1,'C',0);
    $pdf->Cell(0, 5, $pdf->convert_codification('ASEGURESE DE HABER SELECCIONADO CORRECTAMENTE LAS FECHAS Y DE NO ALTERAR LA INFORMACIÓN DE LA LISTA.'),'B',1,'C',0);
    
    $pdf->Output("I","Listado de Entradas (".date('d/m/Y | g:i:a').").pdf",true);
}

$fechaReporteInicio = $_POST['fechaReporteInicio'];
$fechaReporteFin = $_POST['fechaReporteFin'];

$id = modeloPrincipal::decryptionId($_POST["UID"]);
$id = modeloPrincipal::limpiar_cadena($id);

$consulta = modeloPrincipal::consultar("SELECT
    E.total_dolar, E.total_bs, D.dolar AS tasa,
    E.fecha_entrada, U.nombre AS usuario
    FROM entrada AS E
    INNER JOIN proveedor AS PROV ON PROV.id_proveedor = E.id_proveedor 
    INNER JOIN usuario AS U ON U.id_usuario = E.id_usuario 
    INNER JOIN dolar AS D ON D.id_dolar = E.id_dolar 
    WHERE PROV.id_proveedor = $id
");


// en caso de que no se encuentren proveedores registrados

if (mysqli_num_rows($consulta) < 1 ){
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0, 5, $pdf->convert_codification("NO SE ENCONTRARON ENTRADAS REGISTRADAS."),'B',1,'C',0);
    $pdf->Cell(0, 5, $pdf->convert_codification('ASEGURESE DE HABER SELECCIONADO CORRECTAMENTE LAS FECHAS.'),'B',1,'C',0);
    
    $pdf->Output("I","Listado de Entradas (".date('d/m/Y | g:i:a').").pdf",true);
}


$i = 1;
while ( $mostrar = mysqli_fetch_array($consulta)) { 
    $pdf->SetFont('Arial','',10);
    
    $pdf->setX(5);

    $pdf->Cell( 10,5, $pdf->convert_codification($i++),'B',0,'C',0);
    $pdf->Cell($CellFecha, 5, $pdf->convert_codification(date('d-m-Y | g:i:a', strtotime($mostrar["fecha_entrada"]))),'B',0,'C',0);
    $pdf->Cell($CellTotalDolar, 5, $pdf->convert_codification($mostrar["total_dolar"].' $'),'B',0,'C',0);
    $pdf->Cell($CellTotalBS, 5, $pdf->convert_codification($mostrar["total_bs"].' bs'),'B',0,'C',0);
    $pdf->Cell($CellCotización, 5, $pdf->convert_codification($mostrar["tasa"].' bs'),'B',0,'C',0);
    $pdf->Cell($CellUsuario, 5, $pdf->convert_codification($mostrar["usuario"]),'B',1,'C',0);

}

$pdf->Output("I","Listado detallado de Entradas por fechas (".date('d/m/Y | g:i:a').").pdf",true);