<?php 
session_start();

require_once "../../../model/modeloPrincipal.php";

$id = modeloPrincipal::decryptionId($_POST['id']);
$id = modeloPrincipal::limpiar_cadena($id);

$detalles_entrada = modeloPrincipal::consultar("SELECT PS.cantidad AS presentacion, R.nombre AS representacion,
    P.id_producto, P.codigo, P.nombre_producto, 
    C.nombre AS categoria,
    D.cantidad_comprada, D.precio_unitario_dolar AS precio_dolar, D.precio_unitario_bs AS precio_bs,
    M.nombre AS marca, 
    U.nombre AS usuario
    FROM detalles_entrada AS D 
    INNER JOIN entrada AS E ON E.id_entrada = D.id_entrada 
    INNER JOIN producto AS P ON P.id_producto = D.id_producto 
    INNER JOIN usuario AS U ON U.id_usuario = E.id_usuario 
    INNER JOIN categoria AS C ON C.id_categoria = P.id_categoria 
    INNER JOIN marca AS M ON M.id = P.id_marca
    INNER JOIN presentacion AS PS ON PS.id = P.id_presentacion
    INNER JOIN representacion AS R ON R.id = PS.id_representacion
    WHERE D.id_entrada = $id");

$proveedor = mysqli_fetch_array(modeloPrincipal::consultar("SELECT PV.nombre AS proveedor
    FROM entrada AS E
    INNER JOIN proveedor AS PV ON PV.id_proveedor = E.id_proveedor
    WHERE E.id_entrada = $id"));

$proveedor = $proveedor['proveedor'];
?>

<div class="table-responsive">
    <label class="col-form-label">Nombre proveedor: <b><?= $proveedor ?></b></label>
    <table class="table table-borderless table-striped tableDetailsEntry" id="example">
        <thead>
            <tr>
                <th class="col text-center" scope="col">N.º</th>
                <th class="col text-center" scope="col">Código</th>
                <th class="col text-center" scope="col">Producto</th>
                <th class="col text-center" scope="col">Unidades Ingresadas</th>
                <th class="col text-center" scope="col">Costo ($)</th>
                <th class="col text-center" scope="col">Costo (Bs.)</th>
                <th class="col text-center" scope="col">Registrado por</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $i = 1;
            // se guardan los datos en un array y se imprime
            while ( $mostrar = mysqli_fetch_array($detalles_entrada)) { ?>    
                <tr>
                    <td class="col text-center"></td>
                    <td class="col text-center"><?= $mostrar["codigo"] ?></td>
                    <td class="text-start">
                        <p class="text-<?=  $mostrar["cantidad_comprada"] == 0 ? "danger" : "primary" ?>  fw-bold mb-1">
                            <span class="fw-bold">Nombre:</span> <?= $mostrar["nombre_producto"]?>
                        </p>
                        <small class="d-block text-dark">
                            <span class="fw-bold">Marca:</span>  <?= $mostrar["marca"] ?>
                        </small>
                        <small class="d-block text-muted">
                            <span class="fw-bold">Formato:</span> <?= $mostrar["presentacion"] . ' / ' . $mostrar["representacion"] ?>
                        </small>
                        <small class="d-block text-muted">
                            <span class="fw-bold">Categoria:</span> <?= $mostrar["categoria"] ?>
                        </small>
                    </td>
                    <td class="col text-center"><?= $mostrar["cantidad_comprada"]; ?></td>
                    <td class="col text-center"><?= $mostrar["precio_dolar"].' $'; ?></td>
                    <td class="col text-center"><?= $mostrar["precio_bs"].' bs'; ?></td>
                    <td class="col text-center"><?= $mostrar["usuario"]; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>