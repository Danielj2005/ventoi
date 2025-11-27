<?php
error_reporting(E_PARSE);
date_default_timezone_set('America/Caracas');

class venta_model extends modeloPrincipal {
    
    public static function obtener_id_venta_recien_registrada(){
        $id_venta = mysqli_fetch_array(modeloPrincipal::consultar("SELECT MAX(id_venta) AS id FROM venta"));
        $id_venta = $id_venta['id'];
        return $id_venta;
    }

    
    public static function insert_sell($fecha_venta,$sub_total_dolar,$sub_total_bs,$total_venta_dolar,$total_venta_bolivares,$id_usuario,$id_cliente) {
        
        $registrar = modeloPrincipal::InsertSQL( "venta","fecha_venta, sub_total_dolares, sub_total_bs, monto_total_dolares, monto_total_bolivares, id_usuario, id_cliente","'$fecha_venta',$sub_total_dolar,$sub_total_bs,$total_venta_dolar,$total_venta_bolivares,$id_usuario,$id_cliente");
    
        if (!$registrar) {
            alert_model::alerta_simple("¡Ocurrió un error inesperado!","No se pudo registrar el venta debido a un error interno o alteracion de la información a registrar, por favor verifique e intente nuevamente","error");
        }
        return $registrar;
    }
    
    public static function sell_only_service($id_servicios, $cantidad_servicios, $precio_servicio_dolar, $precio_servicio_bolivar, $id_venta) {
        try {

            // ****** se regitra la venta de uno o más servicios
            for ($i = 0; $i < count($id_servicios); $i++) {

                $id_service = modeloPrincipal::decryptionId($id_servicios[$i]);

                modeloPrincipal::InsertSQL("detalles_venta", "id_servicio, cantidad_servicio, precio_servicio_dolares, precio_servicio_bolivares, id_venta","$id_service, ".$cantidad_servicios[$i].",".$precio_servicio_dolar[$i].",".$precio_servicio_bolivar[$i].",$id_venta");
    
                $datos_producto = modeloPrincipal::consultar("SELECT D.id_producto, D.cantidad, P.stock_actual
                    FROM detalles_menu AS D 
                    INNER JOIN producto AS P ON D.id_producto = P.id_producto
                    WHERE id_menu = $id_service
                ");

                while ($mostrar = mysqli_fetch_array($datos_producto)) {

                    $cantidad_necesaria = intval($mostrar['cantidad']) * intval($cantidad_servicios[$i]);
                    $id_producto = intval($mostrar['id_producto']);

                    // Se descuenta del stock
                    modeloPrincipal::UpdateSQL("producto", "stock_actual = stock_actual - $cantidad_necesaria", "id_producto = $id_producto");
                }
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public static function sell_only_product($id_productos, $cantidad_productos, $precios_dolar_productos, $precios_bolivares_productos, $id_venta, $id_usuario) {
        // ****** se registra la venta de uno o más productos
        $fecha = date("Y-m-d h:i:s");
        try {
            for ($i = 0; $i < count($id_productos); $i++) {
                    
                $id_producto = modeloPrincipal::decryptionId($id_productos[$i]);
                $id_producto = intval($id_producto);
                $id_producto = modeloPrincipal::limpiar_cadena($id_producto);

                // Se insertan los detalles de una venta con solo productos
                modeloPrincipal::InsertSQL( "detalles_venta","id_producto, cantidad, precio_unidad_dolares, precio_unidad_bolivares, id_venta","".intval($id_producto).",".intval($cantidad_productos[$i]).",".floatval($precios_dolar_productos[$i]).",".floatval($precios_bolivares_productos[$i]).",$id_venta");
                
                // Se descuenta del stock
                modeloPrincipal::UpdateSQL("producto", "stock_actual = stock_actual - ".$cantidad_productos[$i]."","id_producto = $id_producto");
            
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public static function registrar_detalles_pago($id_venta, $id_metodo_pago, $referencia_pago, $precio_dolar, $cantidad_pago) {
        
        try {

            for($i = 0; $i < count($id_metodo_pago); $i++){
                $metodos_pago = [
                    1 => 'Divisa',
                    2 => 'Punto de Venta',
                    3 => 'Transferencia / Pago movíl',
                    4 => 'Bolivares en Efectivo',
                ];
    
                $id_metodo_pago[$i] = $metodos_pago[intval($id_metodo_pago[$i])];
    
                $cantidad_abonada_bolivares = $precio_dolar * $cantidad_pago[$i];
                $cantidad_abonada_bolivares = round( $cantidad_abonada_bolivares, 2);
                $referencia_pago[$i] = $referencia_pago[$i] == "" ? 0 : $referencia_pago[$i];

                if ($referencia_pago[$i] == 0){
                    // se insertan los detalles del metodo de pago de una venta sin referencia
                    modeloPrincipal::InsertSQL( "detalles_pago","id_venta, metodo_pago, cantidad_abonada_dolares, cantidad_abonada_bolivares","$id_venta,'".$id_metodo_pago[$i]."',".$cantidad_pago[$i].",$cantidad_abonada_bolivares");
                    
                } else {
                    // se insertan los detalles del metodo de pago de una venta con referencia
                    modeloPrincipal::InsertSQL( "detalles_pago","id_venta, metodo_pago, referencia, cantidad_abonada_dolares, cantidad_abonada_bolivares","$id_venta,'".$id_metodo_pago[$i]."',".$referencia_pago[$i].",".$cantidad_pago[$i].",$cantidad_abonada_bolivares");
                
                }
    
            }
            return true;
        } catch (Exception $e) {
            return false;
        }

    }
    
    public static function verify_stock_for_service($id_servicios, $cantidad_servicios) {

        for ($i = 0; $i < count($id_servicios); $i++) {

            // Obtener la cantidad de productos necesarios para el servicio
            $id_servicio = modeloPrincipal::decryptionId($id_servicios[$i]);
            $id_servicio = intval($id_servicio);

            $datos_producto_servicio = modeloPrincipal::consultar("SELECT D.id_producto, D.cantidad, M.nombre_platillo
                FROM detalles_menu AS D
                INNER JOIN menu AS M ON D.id_menu = M.id_menu
                WHERE M.id_menu = $id_servicio");
            
            // Iterar sobre los productos necesarios
            while ($row = mysqli_fetch_array($datos_producto_servicio)) {

                $id_producto = intval($row['id_producto']);

                $cantidad_necesaria = intval($row['cantidad']) * intval($cantidad_servicios[$i]);

                $datos_producto = modeloPrincipal::consultar("SELECT P.nombre_producto AS producto, P.stock_actual,
                    M.nombre AS marca,
                    PS.cantidad AS presentacion, R.nombre AS representacion                 
                    FROM producto AS P
                    INNER JOIN presentacion AS PS ON P.id_presentacion = PS.id 
                    INNER JOIN representacion AS R ON R.id = PS.id_representacion
                    INNER JOIN marca AS M ON M.id = P.id_marca
                    WHERE P.id_producto = $id_producto");

                $datos_producto = mysqli_fetch_array($datos_producto);

                if (intval($datos_producto['stock_actual']) < 1) {
                    modeloPrincipal::UpdateSQL("producto", "stock_actual = 0, estado = 0", "id_producto = ".intval($id_producto)."");
                }
                // Verificar si el stock es suficiente
                if (intval($datos_producto['stock_actual']) < $cantidad_necesaria) {
                    alert_model::alerta_simple("¡Ocurrió un error!","El stock del producto ".$datos_producto['producto']. " ".$datos_producto['marca']. " ".$datos_producto['presentacion']. " ".$datos_producto['representacion']." se encuentra por debajo de la cantidad necesaria para dar un servicio de ".$row['nombre_platillo'].", el stock actual es de (".intval($datos_producto['stock_actual']).")","error");
                    exit();
                }
            }
        }
    }

    public static function verify_stock_for_product($id_productos, $cantidad_productos) {

        for ($i = 0; $i < count($id_productos); $i++) {

            $id_producto = modeloPrincipal::decryptionId($id_productos[$i]);
            $id_producto = modeloPrincipal::limpiar_cadena($id_producto);
            $id_producto = intval($id_producto);
            
            $stock_producto = modeloPrincipal::consultar("SELECT P.nombre_producto AS producto, P.stock_actual,
                M.nombre AS marca,
                PS.cantidad AS presentacion, R.nombre AS representacion                 
                FROM producto AS P
                INNER JOIN presentacion AS PS ON P.id_presentacion = PS.id 
                INNER JOIN representacion AS R ON R.id = PS.id_representacion
                INNER JOIN marca AS M ON M.id = P.id_marca
                WHERE P.id_producto = $id_producto
            ");

            if (mysqli_num_rows($stock_producto) > 0) {

                $stock_producto = mysqli_fetch_array($stock_producto);
                
                if ($stock_producto['stock_actual'] < 1) {
                    modeloPrincipal::UpdateSQL("producto", "stock_actual = 0, estado = 0", "id_producto = $id_producto");
                }
                
                if ($stock_producto['stock_actual'] < $cantidad_productos[$i]) {
                    alert_model::alerta_simple("¡Ocurrio un error!","El stock del producto `".$stock_producto['producto'] . " ".$stock_producto['marca']. " ".$stock_producto['presentacion']. " ".$stock_producto['representacion']."` se encuentra por debajo de la cantidad seleccionada, el stock actual es de (".intval($stock_producto['stock_actual']).")","error");
                    exit();
                }
            }else{
                alert_model::alerta_simple("¡Ocurrio un error!", "El producto no existe en el inventario","error");
                exit();
            }
            
        } 
    }


    /**********************************************************************************/
    /***************** funciones para generar el codigo de las ventas *****************/
    /**********************************************************************************/
    /*---------- Funcion para generar el codigo de las ventas ----------*/
    public static function generar_numero($num){
        $id_generate = "0";
        $vueltas = 8 - strlen($num);
        for ($i=0; $i < 7; $i++) { 

            if (strlen($id_generate) < $vueltas){
                $id_generate .= "0";
            }else{
                $id_generate .= $num;
                break;
            }
        }
        return $id_generate;
    }


    public static function lista_ventas_realizadas($fecha_inicio, $fecha_fin){
        
        if($fecha_inicio == "" && $fecha_fin == ""){
            $ventas_realizadas = modeloPrincipal::consultar("SELECT V.id_venta, C.cedula, C.nombre, 
                V.monto_total_bolivares, V.monto_total_dolares, V.fecha_venta, V.id_usuario, C.id_cliente
                FROM venta as V 
                INNER JOIN cliente as C ON V.id_cliente = C.id_cliente 
                ORDER BY V.id_venta DESC LIMIT 100");
            
        }else{
            $ventas_realizadas = modeloPrincipal::consultar("SELECT V.id_venta, C.cedula, C.nombre, 
                V.monto_total_bolivares, V.monto_total_dolares, V.fecha_venta, V.id_usuario, C.id_cliente
                FROM venta as V 
                INNER JOIN cliente as C ON V.id_cliente = C.id_cliente 
                WHERE V.fecha_venta BETWEEN '$fecha_inicio' AND '$fecha_fin' 
                ORDER BY V.id_venta DESC LIMIT 100");
        }
                
        $d_venta = modeloPrincipal::verificar_permisos_requeridos(['d_venta']);
        $f_venta = modeloPrincipal::verificar_permisos_requeridos(['f_venta']);

        if(mysqli_num_rows($ventas_realizadas) > 0){
            $i = 1 ;

            while($row = mysqli_fetch_array($ventas_realizadas)){ ?>
                <tr>
                    <td class="text-center col"><?= $i++ ?></td> 
                    <td class="text-center col"><?= self::generar_numero( $row['id_venta']) ?></td> 
                    <td class="text-center col"><?= $row['cedula'] ?></td> 
                    <td class="text-center col"><?= $row['nombre'] ?></td> 
                    <td class="text-center col"><?= $row['monto_total_dolares'].' $' ?></td> 
                    <td class="text-center col"><?= $row['monto_total_bolivares'].' bs' ?></td> 
                    <td class="text-center col"><?= date ("d-m-Y h:i:a",strtotime($row['fecha_venta'])) ?></td> 
                    <?php if ($d_venta == '1') :?>
                        <td class="text-center col">
                            <button 
                                class="btn_modal btn btn-info " 
                                value="<?= modeloPrincipal::encryptionId($row['id_venta']) ?>" 
                                modal="ventaDetalles" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modal">
                                    <i class="bi bi-eye"></i> 
                            </button>
                        </td> 
                    <?php endif; ?>
                    <?php if ($f_venta == '1') :?>
                        <td class="text-center col">
                            <form action="./reportes/factura_cliente.php" method="post" target="_blank">
                                
                                <input type="hidden" name="UIDC" value="<?= modeloPrincipal::encryptionId($row["id_cliente"]); ?>">
                                <input type="hidden" name="UIDV" value="<?= modeloPrincipal::encryptionId($row["id_venta"]); ?>">
                                <input type="hidden" name="UIDU" value="<?= modeloPrincipal::encryptionId($row["id_usuario"]); ?>">
                                
                                <button class="btn btn-primary bi bi-file-text"></button>
                        
                            </form>
                        </td> 
                    <?php endif; ?>
                </tr>
            <?php } 
        }
    }
    public static function lista_ventas_diarias(){

        $ventas_del_dia = modeloPrincipal::consultar("SELECT V.id_venta, C.cedula, C.nombre, V.monto_total_bolivares, 
            V.monto_total_dolares, V.fecha_venta 
            FROM venta as V 
            INNER JOIN cliente as C ON C.id_cliente = V.id_cliente 
            WHERE DATE(V.fecha_venta) = DATE(NOW()) 
            ORDER BY V.fecha_venta DESC 
            LIMIT 100 ");   

        while($row = mysqli_fetch_array($ventas_del_dia)){ ?>
            <tr>
                <td class="text-center col"></td> 
                <td class="text-center col"><?= self::generar_numero($row['id_venta']) ?></td> 
                <td class="text-center col"><?= $row['cedula'] ?></td> 
                <td class="text-center col"><?= $row['nombre'] ?></td> 
                <td class="text-center col"><?= $row['monto_total_dolares'].' $' ?></td> 
                <td class="text-center col"><?= $row['monto_total_bolivares'].' bs' ?></td> 
                <td class="text-center col"><?= date("d-m-Y | g:i:a",strtotime($row['fecha_venta'])) ?></td> 
                <td class="text-center col">
                    <button 
                        class="btn_modal btn btn-info bi bi-eye" 
                        modal="ventaDetalles" 
                        value="<?= modeloPrincipal::encryptionId($row['id_venta']) ?>" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modal">
                    </button>
                </td> 
            </tr>
        <?php }
    }

    public static function totales_ventas_del_dia () {
        $monto_ventas_del_dia = mysqli_fetch_array(modeloPrincipal::consultar("SELECT 
            ROUND(sum(V.monto_total_dolares),2) as total_de_ventas_dolares,
            ROUND(sum(V.monto_total_bolivares),2) as total_de_ventas_bs
            FROM venta as V 
            WHERE DATE(V.fecha_venta) = DATE(NOW()) 
            ORDER BY V.id_venta DESC"));

        $total_del_dia_en_dolares = $monto_ventas_del_dia['total_de_ventas_dolares'];
        $total_del_dia_en_bs = $monto_ventas_del_dia['total_de_ventas_bs'];

        return [
            'dolares' => $total_del_dia_en_dolares,
            'bs' => $total_del_dia_en_bs
        ];
    }


    public static function totales_ventas() {
        $totales_ventas = mysqli_fetch_array(modeloPrincipal::consultar("SELECT 
            ROUND(sum(monto_total_dolares) , 2) AS total_de_ventas_dolares,
            ROUND(sum(monto_total_bolivares) , 2) AS total_de_ventas_bs
            FROM venta 
            ORDER BY id_venta DESC"));

        $total_de_ventas_dolares = $totales_ventas['total_de_ventas_dolares'];
        $total_de_ventas_bs = $totales_ventas['total_de_ventas_bs'];

        return [
            'dolares' => $total_de_ventas_dolares,
            'bs' => $total_de_ventas_bs
        ];
    }

}