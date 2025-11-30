<?php

require_once "../../../model/modeloPrincipal.php"; 
require_once "../../../model/proveedor_model.php"; 

$id_proveedor = modeloPrincipal::decryptionId($_POST["id"]);
$id_proveedor = modeloPrincipal::limpiar_cadena($id_proveedor);

$consulta = modeloPrincipal::consultar("SELECT
    E.total_dolar, E.total_bs, D.dolar AS tasa,
    E.fecha_entrada, U.nombre AS usuario
    FROM entrada AS E
    INNER JOIN proveedor AS PROV ON PROV.id_proveedor = E.id_proveedor 
    INNER JOIN usuario AS U ON U.id_usuario = E.id_usuario 
    INNER JOIN dolar AS D ON D.id_dolar = E.id_dolar 
    WHERE PROV.id_proveedor = $id_proveedor");

$datos_proveedor = mysqli_fetch_array( modeloPrincipal::consultar("SELECT nombre FROM proveedor WHERE id_proveedor = $id_proveedor"));
$nombre_proveedor = $datos_proveedor['nombre'];

if (mysqli_num_rows($consulta) < 1) {
    ?> 
    <div class="row justify-content-center" role="alert">
        <div class="alert alert-danger w-auto" role="alert">
            No se ha encontrado un historial asociado a este proveedor, asegurese de haberle hecho alguna compra al menos una vez.
        </div>
    </div>
    <?php
    exit();
}
?>

<legend class="mb-3 text-center text-white"> Nombre del proveedor :  <b>"<?= $nombre_proveedor ?>"</b> </legend>
<div class="table-responsive text-white">
    <table class="table table-striped table-borderless tableProvider pt-2">
        <thead>
            <tr>
                <th class="col text-center" scope="col">#</th>
                <th class="col text-center" scope="col">Fecha y Hora</th>
                <th class="col text-center" scope="col">Total ($)</th>
                <th class="col text-center" scope="col">Total (Bs)</th>
                <th class="col text-center" scope="col">Tasa de Cambio</th>
                <th class="col text-center" scope="col">Registrado por</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                while ($row = mysqli_fetch_array($consulta)) { ?>    
                    <tr>
                        <td class="col text-center"></td>
                        <td class="text-center col"><?= date('d-m-Y g:i:a', strtotime($row["fecha_entrada"])); ?></td> 
                        <td class="col text-center"><?= $row["total_dolar"] == 0 ? '0.$' : $row["total_dolar"].' $' ;  ?></td>
                        <td class="col text-center"><?= $row["total_bs"].'bs'; ?></td>
                        <td class="col text-center"><?= $row["tasa"].'bs'; ?></td>
                        <td class="col text-center"><?= $row["usuario"]; ?></td>
                    </tr>
            <?php } ?>
        </tbody>
    </table>
</div>