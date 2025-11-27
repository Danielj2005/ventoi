<?php 
session_start();

include_once "../../../modelo/modeloPrincipal.php"; // se incluye el modelo principal
include_once "../../../modelo/cliente_model.php"; // se incluye el modelo principal
include_once "../../../modelo/rol_model.php"; // se incluye el modelo principal
include_once "../../../modelo/venta_model.php"; // se incluye el modelo principal

$id_usuario = $_SESSION['id_usuario'];

$id_cliente = modeloPrincipal::decryptionId($_POST['id']);
$id_cliente = modeloPrincipal::limpiar_cadena($id_cliente);

$nombre_query = modeloPrincipal::consultar("SELECT nombre FROM cliente WHERE id_cliente = $id_cliente");
$nombre_cliente = mysqli_fetch_array($nombre_query)['nombre'];

$historial_cliente = modeloPrincipal::consultar(
    "SELECT V.id_venta, V.fecha_venta, V.monto_total_dolares,
        V.monto_total_bolivares,
        V.id_usuario, C.nombre 
        FROM venta AS V 
        INNER JOIN cliente AS C ON C.id_cliente = V.id_cliente 
        WHERE C.id_cliente = $id_cliente ORDER BY V.id_venta DESC
    ");

if (mysqli_num_rows($historial_cliente) < 1) { ?> 
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill flex-shrink-0 me-2"></i>
        <div>
            Este cliente no tiene un historial de compras registrado.
        </div>
    </div>
<?php exit(); } ?>


<h5 class="fw-bold mb-3 d-flex align-items-center text-primary">
    <i class="bi bi-person-bounding-box me-2"></i>
    Historial de Compras de: <?php echo $nombre_cliente; ?>
</h5>

<div class="table-responsive" id="modalSendForm">
    <table class="table table-striped table-hover table-sm example tableDetailsClient">
        <thead class="table-light">
            <tr>
                <th class="text-center" scope="col" style="width: 5%;">N.°</th>
                <th class="text-center" scope="col">N.° Factura</th>
                <th class="text-end" scope="col">Total (USD)</th>
                <th class="text-end" scope="col">Total (Bs.)</th>
                <th class="text-center" scope="col">Fecha y Hora</th>

                <?php if ($permisos['facturaCliente'] == '1') { ?>
                    <th class="text-center" scope="col" style="width: 10%;">Factura</th>
                <?php } ?>
            </tr>
        </thead>

        <tbody>
            <?php 
            $i = 1; // Contador para la columna N.°
            while ($mostrar = mysqli_fetch_array($historial_cliente)) { 
            ?>
                <tr>
                    <td class="text-center fw-bold"><?= $i++; ?></td>
                    
                    <td class="text-center"><?= venta_model::generar_numero($mostrar['id_venta']); ?></td>
                    
                    <td class="text-end fw-bold"><?= number_format($mostrar["monto_total_dolares"], 2).' $'; ?></td>
                    <td class="text-end fw-bold"><?= number_format($mostrar["monto_total_bolivares"], 2).' bs'; ?></td>
                    
                    <td class="text-center small"><?= date("d-m-Y | g:i:a", strtotime($mostrar["fecha_venta"])); ?></td>
                
                    <?php if ($permisos['facturaCliente'] == '1') { ?>
                        <td class="text-center">
                            <form method="post" action="./reportes/factura_cliente.php" target="_blank">
                                <input type="hidden" name="UIDV" value="<?= modeloPrincipal::encryptionId($mostrar["id_venta"]) ?>">
                                <input type="hidden" name="UIDC" value="<?= modeloPrincipal::encryptionId($id_cliente) ?>">
                                <input type="hidden" name="UIDU" value="<?= modeloPrincipal::encryptionId($mostrar["id_usuario"]) ?>">

                                <button type="submit" class="btn btn-sm btn-info text-white" title="Ver Factura">
                                    <i class="bi bi-file-earmark-text"></i>
                                </button>
                            </form>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>