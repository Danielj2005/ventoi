<?php
session_start();
include_once "../../modelo/modeloPrincipal.php";
require 'fpdf/fpdf.php';

date_default_timezone_set('America/caracas');

class PDF extends FPDF{
        
    function Header(){
        if (isset($_POST['selected_user'])) {
            $id_usuario = modeloPrincipal::decryptionId($_POST["selected_user"]);
            $id_usuario = modeloPrincipal::limpiar_cadena($id_usuario);
            
            $dataProvider = mysqli_fetch_array(modeloPrincipal::consultar("SELECT * FROM usuario WHERE id_usuario = $id_usuario"));
        }else{
            $dataProvider = ["id_usuario" => "", "cedula" => "", "nombre" => "", "apellido" => "", "telefono" => "", "correo" => "", "direccion" => ""];
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
        // datos del usuario
        $this->Cell(80, 5, self::convert_codification('Datos del Usuario: '),'B',1,'L',0);
        
        $this->setX(10);
        // datos del proveedor
        $this->Cell(80, 5, self::convert_codification('Cédula: '.($dataProvider['cedula'] == "" ? 'No encontado(a)' : $dataProvider['cedula'])),'',1,'L',0);

        $this->Cell(80, 5, self::convert_codification('Nombre: '.($dataProvider['nombre'] == "" && $dataProvider['apellido'] == "" ? 'No encontado(a)' : $dataProvider['nombre'].' '.$dataProvider['apellido'])),'',1,'L',0);

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


if (!isset($_POST['selected_user'])){
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0, 5, $pdf->convert_codification("NO SE ENCONTRARON MOVIMIENTOS REGISTRADOS."),'B',1,'C',0);
    $pdf->Cell(0, 5, $pdf->convert_codification('ASEGURESE DE HABER SELECCIONADO CORRECTAMENTE A UN USUARIO.'),'B',1,'C',0);
    
    $pdf->Output("I","Movimientos de 'usuarioName' (".date('d/m/Y | g:i:a').").pdf",true);
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage("P",[320, 390],"0");
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(5);
$pdf->SetRightMargin(5);


// se definen variable con los tamaños de las celdas para mejor adaptacion
$CellFecha = 40;
$CellAccion = 100;
$CellDescripcion = 150;

$setY = 75;
$pdf->setY($setY);
$pdf->setX(5);
// En esta parte estan los encabezados 
$pdf->SetFont('Arial','B',10);

$pdf->Cell(10, 5, $pdf->convert_codification('Nº'),'LTRB',0,'C',0);
$pdf->Cell($CellFecha, 5, $pdf->convert_codification('Fecha | Hora'),'LTRB',0,'C',0);
$pdf->Cell($CellAccion, 5, $pdf->convert_codification('Acciones'),'LTRB',0,'C',0);
$pdf->Cell($CellDescripcion, 5, $pdf->convert_codification('Descripción'),'LTRB',1,'C',0);

$id = modeloPrincipal::decryptionId($_POST["selected_user"]);
$id = modeloPrincipal::limpiar_cadena($id);

$consulta = modeloPrincipal::consultar("SELECT B.*, U.nombre, U.apellido FROM bitacora AS B
    INNER JOIN usuario AS U ON B.id_usuario = U.id_usuario 
    WHERE U.id_usuario = $d");

// en caso de que no se encuentren proveedores registrados

if (mysqli_num_rows($consulta) < 1 ){
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0, 5, $pdf->convert_codification("NO SE ENCONTRARON MOVIMIENTOS REGISTRADOS."),'B',1,'C',0);
    $pdf->Cell(0, 5, $pdf->convert_codification('ASEGURESE DE HABER SELECCIONADO CORRECTAMENTE A UN USUARIO.'),'B',1,'C',0);
    
    $pdf->Output("I","Movimientos de 'usuarioName' (".date('d/m/Y | g:i:a').").pdf",true);
}


$i = 1;
while ( $mostrar = mysqli_fetch_array($consulta)) { 
    $pdf->SetFont('Arial','',10);
    $pdf->setX(5);

    $pdf->Cell( 10,5, $pdf->convert_codification($i++),'B',0,'C',0);
    $pdf->MultiCell($CellDescripcion, 5, $pdf->convert_codification($mostrar["mensaje"]),1,'C',false);
}

$pdf->Output("I","Movimientos de 'usuarioName' (".date('d/m/Y | g:i:a').").pdf",true);