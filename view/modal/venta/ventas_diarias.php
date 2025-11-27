<?php
require_once "../../../modelo/modeloPrincipal.php"; 
require_once "../../../modelo/venta_model.php"; 

$id_venta = modeloPrincipal::decryptionId($_POST['id']);
$id = modeloPrincipal::limpiar_cadena($id_venta);

$datos_venta = modeloPrincipal::consultar("SELECT C.cedula, C.nombre, C.telefono,
    U.cedula AS empleado_cedula, U.nombre AS empleado_nombre, 
    U.apellido AS empleado_apellido, U.correo, U.telefono AS empleado_telefono, 
    V.fecha_venta, V.sub_total_dolares, V.sub_total_bs,
    V.monto_total_dolares, V.monto_total_bolivares,
    V.id_venta
    FROM venta AS V 
    INNER JOIN cliente AS C ON C.id_cliente = V.id_cliente 
    INNER JOIN usuario AS U ON U.id_usuario = V.id_usuario 
    WHERE V.id_venta = $id");

$datos_venta = mysqli_fetch_array($datos_venta);

$detalles_venta_productos = modeloPrincipal::consultar("SELECT
	P.nombre_producto AS producto,
    PS.cantidad AS presentacion, R.nombre AS representacion,
    M.nombre AS marca, 
    DV.cantidad,
    round((SELECT MAX(dolar) AS dolar FROM dolar) * P.precio_venta, 2) AS precio
    FROM detalles_venta AS DV
    INNER JOIN producto AS P ON P.id_producto = DV.id_producto
    INNER JOIN presentacion AS PS ON P.id_presentacion = PS.id 
    INNER JOIN representacion AS R ON R.id = PS.id_representacion
    INNER JOIN marca AS M ON M.id = P.id_marca
    WHERE DV.id_venta = $id");

$detalles_venta = modeloPrincipal::consultar("SELECT M.nombre_platillo, DV.cantidad_servicio,
	round((SELECT MAX(dolar) AS dolar FROM dolar) * M.precio_dolar, 2) * DV.cantidad_servicio AS precio
    FROM detalles_venta AS DV
    INNER JOIN menu AS M ON M.id_menu = DV.id_servicio
    INNER JOIN detalles_menu AS DM ON DM.id_menu = M.id_menu
    WHERE DV.id_venta = $id");

$cantidades = mysqli_fetch_array( modeloPrincipal::consultar("SELECT cantidad_servicio, cantidad 
    FROM detalles_venta WHERE id_venta = $id"));

$cant_productos = $cantidades['cantidad'] == "" ? 0 : $cantidades['cantidad'];
$cant_servicios = $cantidades['cantidad_servicio'] == "" ? 0 : $cantidades['cantidad_servicio'];

?>


<div class="d-flex justify-content-center m-0 p-0 mb-3">
    <img src="./img/logo.png" alt="Logo La Chinita" style="max-width: 60px; height: auto;">
</div>
<h6 class="text-center fw-bold">BAR RESTAURANT Y LUNCHERIA 'LA CHINITA'</h6>
<p class="text-center m-0 small">RIF: V-04608675-5</p>
<p class="text-center m-0 small">Calle 2 entre Av 5 y 6 - Villa Bruzual Portuguesa</p>

<hr class="dotted-separator">

<div class="d-flex justify-content-between small">
    <span class="fw-bold">CLIENTE</span>
    <span>TOTAL ART.: <?= $cant_productos + $cant_servicios ?></span>
</div>
<ul class="list-unstyled small m-0">
    <li><span class="fw-bold">RIF/C.I.:</span> <?= $datos_venta['cedula']; ?></li>
    <li><span class="fw-bold">Razón Social:</span> <?= $datos_venta['nombre']; ?></li>
</ul>   

<div class="d-flex justify-content-between small mt-2">
    <span class="fw-bold">EMPLEADO</span>
    <span>Nº FACTURA: <?= venta_model::generar_numero($datos_venta['id_venta']); ?></span>
</div>
<ul class="list-unstyled small m-0">
    <li><span class="fw-bold">Vendedor:</span> <?= $datos_venta['empleado_nombre'].' '.$datos_venta['empleado_apellido']; ?></li>
    <li><span class="fw-bold">Fecha/Hora:</span> <?= date ("d-m-Y g:i:a", strtotime($datos_venta['fecha_venta'])); ?></li>
</ul>

<hr class="dotted-separator">

<div class="d-flex small fw-bold">
    <span style="width: 50%;">DESCRIPCIÓN</span>
    <span class="text-center" style="width: 20%;">CANT</span>
    <span class="text-end" style="width: 30%;">PRECIO (Bs)</span>
</div>

<?php while($row = mysqli_fetch_array($detalles_venta_productos)){ ?>
    <div class="d-flex justify-content-between small">
        <span style="width: 50%;"><?= $row['producto'].' '.$row['marca'].' '.$row['presentacion'].' '.$row['representacion'] ?></span>
        <span class="text-center" style="width: 20%;"><?= $row['cantidad']; ?></span>
        <span class="text-end" style="width: 30%;"><?= number_format($row['precio'], 2); ?></span>
    </div>
<?php } ?>

<?php while($row = mysqli_fetch_array($detalles_venta)){ ?>
    <div class="d-flex justify-content-between small">
        <span style="width: 50%;">SERVICIO: <?= $row['nombre_platillo']?></span>
        <span class="text-center" style="width: 20%;"><?= $row['cantidad_servicio']; ?></span>
        <span class="text-end" style="width: 30%;"><?= number_format($row['precio'], 2); ?></span>
    </div>
<?php } ?>

<hr class="dotted-separator">
<div class="d-flex justify-content-between small">
    <span class="fw-bold">SUBTOTAL</span>
    <span class="fw-bold">Bs <?= number_format($datos_venta['sub_total_bs'], 2); ?></span>
</div>

<div class="d-flex justify-content-between small">
    <span>IVA (16,00%)</span>
    <span>Bs <?= number_format(round($datos_venta['sub_total_bs'] * 0.16, 2), 2); ?></span>
</div>

<div class="d-flex justify-content-between small">
    <span>Exento</span>
    <span>Bs 0.00</span>
</div>

<hr class="dotted-separator">

<div class="d-flex justify-content-between fs-5 fw-bold">
    <span>TOTAL A PAGAR</span>
    <span>Bs <?= number_format($datos_venta['monto_total_bolivares'], 2); ?></span>
</div>

<hr class="dotted-separator">