<?php
require_once ('../../modelo/modeloPrincipal.php');
require_once ('../../modelo/venta_model.php');

# Incluyendo librerias necesarias #
require "./code128.php";

function convert_codification ($cadena):string {
    return mb_convert_encoding("$cadena", 'ISO-8859-1', 'UTF-8');
}


$pdf = new PDF_Code128('P','mm',array(80,258));
$pdf->SetMargins(4,10,4);
$pdf->AddPage();

# Encabezado y datos de la empresa #
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",strtoupper("BAR RESTAURANT Y LUNCHERIA 'LA CHINITA'")),0,'C',false);
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","RIF: V-04608675-5"),0,'C',false);
$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Direccion: Calle 2 entre Av 5 y 6 - Turén Edo. Portuguesa"),0,'C',false);

$pdf->Ln(1);
$pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","------------------------------------------------------"),0,0,'C');
$pdf->Ln(5);

if (!isset($_POST['UIDC']) || !isset($_POST['UIDV']) || !isset($_POST['UIDU'])){
    
    $pdf->Ln(1);
    $pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);

    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Documento: "),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Cliente: "),0,'C',false);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Teléfono: "),0,'C',false);

    $pdf->Ln(1);
    $pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(3);

    # Tabla de productos #
    $pdf->Cell(10,5,iconv("UTF-8", "ISO-8859-1","Cant."),0,0,'C');
    $pdf->Cell(19,5,iconv("UTF-8", "ISO-8859-1","Precio"),0,0,'C');
    $pdf->Cell(15,5,iconv("UTF-8", "ISO-8859-1","Desc."),0,0,'C');
    $pdf->Cell(28,5,iconv("UTF-8", "ISO-8859-1","Total"),0,0,'C');

    $pdf->Ln(3);
    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(3);

    /*----------  Detalles de la tabla  ----------*/
    $pdf->MultiCell(0,4,iconv("UTF-8", "ISO-8859-1","Nombre de producto a vender"),0,'C',false);
    $pdf->Cell(10,4,iconv("UTF-8", "ISO-8859-1","7"),0,0,'C');
    $pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1","$10 USD"),0,0,'C');
    $pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1","$0.00 USD"),0,0,'C');
    $pdf->Cell(28,4,iconv("UTF-8", "ISO-8859-1","$70.00 USD"),0,0,'C');
    $pdf->Ln(4);
    $pdf->Ln(7);
    
    /*----------  Fin Detalles de la tabla  ----------*/
    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');

    $pdf->Ln(5);
    # Impuestos & totales #
    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","SUBTOTAL"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1","+ $70.00 USD"),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","IVA (13%)"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1","+ $0.00 USD"),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","TOTAL A PAGAR"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1","$70.00 USD"),0,0,'C');

    $pdf->Ln(5);
    
    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","TOTAL PAGADO"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1","$100.00 USD"),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","CAMBIO"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1","$30.00 USD"),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","USTED AHORRA"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1","$0.00 USD"),0,0,'C');

    $pdf->Ln(10);

    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","*** Precios de productos incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar este ticket ***"),0,'C',false);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,7,iconv("UTF-8", "ISO-8859-1","Gracias por su compra"),'',0,'C');

    $pdf->Ln(9);

    # Codigo de barras #
    $pdf->Code128(5,$pdf->GetY(),"COD000001V0001",70,20);
    $pdf->SetXY(0,$pdf->GetY()+21);
    $pdf->SetFont('Arial','',14);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","COD000001V0001"),0,'C',false);
    
    # Nombre del archivo PDF #
    $pdf->Output("I","Ticket_Nro_".venta_model::generar_numero($id_venta).".pdf",true);
}

$id_usuario = modeloPrincipal::decryptionId($_POST['UIDU']);
// se consultan los datos del usuario
$datos_usuario = mysqli_fetch_array(modeloPrincipal::consultar("SELECT cedula, nombre, apellido, telefono
    FROM usuario WHERE id_usuario = $id_usuario"));

$id_venta = modeloPrincipal::decryptionId($_POST['UIDV']);

$fecha_venta = mysqli_fetch_array(modeloPrincipal::consultar("SELECT fecha_venta FROM venta WHERE id_venta = $id_venta"));

$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Fecha: ".date("d/m/Y" ,strtotime($fecha_venta['fecha_venta']))." Hora: ".date("h:s A ",strtotime($fecha_venta['fecha_venta']))),0,'C',false);
$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Cajero: ".$datos_usuario['nombre']." ".$datos_usuario['apellido']),0,'C',false);
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",strtoupper("Ticket Nro: ".venta_model::generar_numero($id_venta))),0,'C',false);
$pdf->SetFont('Arial','',9);

$pdf->Ln(1);
$pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","------------------------------------------------------"),0,0,'C');
$pdf->Ln(5);

$id_cliente = modeloPrincipal::decryptionId($_POST['UIDC']);

$datos_cliente = mysqli_fetch_array(modeloPrincipal::consultar("SELECT cedula, nombre, telefono
    FROM cliente WHERE id_cliente = $id_cliente"));

$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Documento: ".$datos_cliente['cedula'].""),0,'C',false);
$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Cliente: ".$datos_cliente['nombre'].""),0,'C',false);
$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Teléfono: ".$datos_cliente['telefono'].""),0,'C',false);

$pdf->Ln(1);
$pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
$pdf->Ln(3);



# Tabla de productos #
$pdf->Cell(26.66,5,iconv("UTF-8", "ISO-8859-1","Cant."),0,0,'C');
$pdf->Cell(26.66,5,iconv("UTF-8", "ISO-8859-1","Precio"),0,0,'C');
$pdf->Cell(26.66,5,iconv("UTF-8", "ISO-8859-1","Total"),0,0,'C');

$pdf->Ln(3);
$pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
$pdf->Ln(3);


$detalles_venta_productos = modeloPrincipal::consultar("SELECT P.nombre_producto, DV.cantidad, DV.precio_unidad_dolares,
    DV.precio_unidad_bolivares 
    FROM detalles_venta as DV 
    INNER JOIN producto as P ON P.id_producto = DV.id_producto 
    WHERE DV.id_venta = $id_venta");

// $detalles_venta_servicios = modeloPrincipal::consultar("SELECT M.nombre_platillo, DV.cantidad_servicio, 
//     DV.precio_servicio_dolares, DV.precio_servicio_bolivares, M.descripcion FROM detalles_venta as DV
//     INNER JOIN menu as M ON M.id_menu = DV.id_servicio WHERE DV.id_venta = $id");
if(mysqli_num_rows($detalles_venta_productos) > 0){
    while($row =  mysqli_fetch_array($detalles_venta_productos)){

        /*----------  Detalles de la tabla  ----------*/
        $pdf->MultiCell(0,4,convert_codification($row['nombre_producto']),0,'C',false);
        $pdf->Cell(26.66,4,convert_codification($row['cantidad']),0,0,'C');
        $pdf->Cell(26.66,4,convert_codification($row['precio_unidad_dolares']." $"),0,0,'C');
        $pdf->Cell(26.66,4,convert_codification($row['precio_unidad_bolivares']." bs"),0,1,'C');

    }
}

$detalles_venta_servicios = modeloPrincipal::consultar("SELECT M.nombre_platillo, DV.cantidad_servicio,
    DV.precio_servicio_dolares, DV.precio_servicio_bolivares 
    FROM detalles_venta as DV 
    INNER JOIN menu as M ON M.id_menu = DV.id_servicio 
    WHERE DV.id_venta =$id_venta");

if(mysqli_num_rows($detalles_venta_servicios) > 0){
    while($row =  mysqli_fetch_array($detalles_venta_servicios)){

        /*----------  Detalles de la tabla  ----------*/
        $pdf->MultiCell(0,4,convert_codification($row['nombre_platillo']),0,'C',false);
        $pdf->Cell( 26.66,4,convert_codification($row['cantidad_servicio']),0,0,'C');
        $pdf->Cell(26.66,4,convert_codification($row['precio_servicio_dolares']." $"),0,0,'C');
        $pdf->Cell(26.66,4,convert_codification($row['precio_servicio_bolivares']." bs"),0,1,'C');

    }
}
$pdf->Ln(4);
$pdf->Ln(7);
    


/*----------  Fin Detalles de la tabla  ----------*/
$subTotal = mysqli_fetch_array(modeloPrincipal::consultar("SELECT sub_total_bs,
    sub_total_dolares, ROUND((sub_total_bs * 0.16),2) AS iva,
    monto_total_dolares, monto_total_bolivares
    FROM venta 
    WHERE id_venta = $id_venta"));


$pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');

$pdf->Ln(5);

# Impuestos & totales #
$pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
$pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","SUBTOTAL"),0,0,'C');
$pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1",$subTotal['sub_total_bs']." bs"),0,0,'C');

$pdf->Ln(5);

$pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
$pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","IVA (16%)"),0,0,'C');
$pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1", $subTotal['iva']." bs"),0,0,'C');

$pdf->Ln(5);

    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');

    $pdf->Ln(5);

    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","TOTAL A PAGAR"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1",$subTotal['monto_total_bolivares']." bs"),0,0,'C');

    $pdf->Ln(5);
    
    $pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
    $pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","TOTAL PAGADO"),0,0,'C');
    $pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1",$subTotal['monto_total_bolivares']." bs"),0,0,'C');

    $pdf->Ln(15);

    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","*** Precios de productos incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar este ticket ***"),0,'C',false);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,7,iconv("UTF-8", "ISO-8859-1","Gracias por su compra"),'',0,'C');

    $pdf->Ln(9);

    # Codigo de barras #
    $pdf->Code128(5,$pdf->GetY(),venta_model::generar_numero($id_venta),70,20);
    $pdf->SetXY(0,$pdf->GetY()+21);
    $pdf->SetFont('Arial','',14);
    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",venta_model::generar_numero($id_venta)),0,'C',false);
    
    # Nombre del archivo PDF #
    $pdf->Output("I","Ticket_Nro_".venta_model::generar_numero($id_venta).".pdf",true);