<?php 
session_start();

include_once "./view/inc/models.php"; // se incluye el modelo principal

$id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario

// validación para verificar que el usuario inicio sesion de manera correcta
model_user::verificar_intento_de_acceso_al_sistema();

include_once "./view/inc/verificacion_primer_inicio_usuario.php"; // se incluyen los modelos necesarios para la vista

$permiso_venta = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['venta']);

// se evalua que este rol tenga el acceso a esta vista
if (!$permiso_venta) { 
    // se procesan los permisos de la vista de productos en caso de no contar con permisos para el módulo de ventas
    $l_productos = modeloPrincipal::verificar_permisos_requeridos(['l_productos']);
    $l_categoria = modeloPrincipal::verificar_permisos_requeridos(['l_categoria']);
    $l_presentacion = modeloPrincipal::verificar_permisos_requeridos(['l_presentacion']);
    $l_marca = modeloPrincipal::verificar_permisos_requeridos(['l_marca']);

}


?>
<!DOCTYPE html>
<html lang="<?= LANG ?>">
    <head>
        <?php 
            // <!-- metadatos -->  
            include_once "./view/inc/meta.php";
            include_once "./view/inc/css_links.php";
        ?>

        <style> 
            .logo{
                width : 10rem !important;
                height : 10rem !important;
            }
        </style>

    </head>
    <body id="page-top" class="index-page bg-dark-subtle" data-bs-theme="dark">
        <?php 
            // <!-- metadatos -->  
            include_once "./view/inc/header.php";
            include_once "./view/inc/sidebar.php";
            
        ?>

        
        <main id="main" class="main">
            <div class="text-white-50 mb-4"> <h1> Panel Principal</h1> </div> 

            <section class="section dashboard">
                <div class="row">
                    <?php 
                        if ($permiso_venta) :
                            $total_ventas_del_dia = venta_model::totales_ventas_del_dia();

                            $total_hoy_dolar = $total_ventas_del_dia['dolares'];
                            $total_hoy_bs = $total_ventas_del_dia['bs'];
                    ?>

                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title text-white-50">Total de Ventas del Día</h5>
                                    
                                    <div class="row">

                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-1">
                                            <div class="input-group mb-2">
                                                <span class="input-group-text" id="basic-addon1">Total en Dólares (USD)</span>
                                                <input type="text" class="form-control" disabled id="TotalUSD" readOnly value="<?= ($total_hoy_dolar == "") ? 0 : $total_hoy_dolar ?>">
                                                <span class="input-group-text" id="basic-addon1">$</span>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-1">
                                            <div class="input-group mb-2">
                                                <span class="input-group-text" id="basic-addon1">Total en Bolívares (Bs.)</span>
                                                <input type="text" class="form-control" disabled id="TotalBS" readOnly value="<?= ($total_hoy_bs == "") ? '0' : $total_hoy_bs ?>">
                                                <span class="input-group-text" id="basic-addon1">Bs.</span> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                            <div class="card top-selling overflow-auto mb-4">
                                
                                <div class="card-body">
                                    <h5 class="mb-3 card-title text-white-50"> Ventas Recientes </h5>
                                    
                                    <div class="table-responsive overflow-hidden">
                                        <table class="table example table-hover table-striped mb-5" id="example">
                                            <thead>
                                                <tr class="bg-dark-subtle">
                                                    <th class="text-center" scope="col">N.°</th>
                                                    <th class="text-center" scope="col">N.° de Factura</th>
                                                    
                                                    <th class="text-start" scope="col">Cédula/RIF Cliente</th>
                                                    <th class="text-start" scope="col">Cliente</th>
                                                    
                                                    <th class="text-end" scope="col">Total (USD)</th>
                                                    <th class="text-end" scope="col">Total (Bs)</th>
                                                    
                                                    <th class="text-center" scope="col">Fecha y Hora</th>
                                                    <th class="text-center" scope="col" style="width: 8%;">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody> <?php venta_model::lista_ventas_diarias(); ?> </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php
                        else:

                            if ($l_categoria > 0 || $l_presentacion > 0 || $l_marca > 0) : ?>
                                <!-- listado de Categoría -->

                                <div id="card_gestion_productos" class="col-12 mb-3 pagetitle text-center row m-0 p-0 justify-content-around">

                                    <?php if ($l_categoria): ?>

                                        <div id="" class="text-center col-12 col-md-3 fs-4 border card">
                                            <h3 class="text-center mt-2 titulosH fs-3">Categorías</h3>
                                            <div class="justify-content-center text-center mb-2">
                                                
                                                <div class="text-center mb-2">
                                                    <button 
                                                        modal="listaCategoria" 
                                                        id="btn_ver_listas_categoria" 
                                                        type="button" 
                                                        class="btn_modal btn btn btn-secondary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modal"><i class="bi bi-list-columns-reverse"></i> Lista de Categorías
                                                    </button>
                                                </div>
                                                
                                            </div>
                                        </div>   

                                    <?php endif; if ($l_presentacion): ?>

                                        <div id="" class="text-center col-12 col-sm-12 col-md-4 fs-4 border card">
                                            <h3 class=" text-center mt-2 titulosH fs-3">Presentaciones</h3>

                                            <div class="justify-content-center text-center mb-2">
                                                <div class="text-center mb-2">
                                                    <button 
                                                        modal="listaPresentacion" 
                                                        id="btn_ver_listas_presentacion" 
                                                        type="button" 
                                                        class="btn_modal btn btn btn-secondary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modal">
                                                            <i class="bi bi-list-task"></i>
                                                            Lista de Presentaciones
                                                    </button>                               
                                                </div>
                                            </div>
                                        </div>   

                                    <?php endif; if ($l_marca): ?>

                                        <div id="" class="text-center col-12 col-sm-12 col-md-3 fs-4 card border">
                                            <h3 class="text-center mt-2 titulosH fs-3">Marcas</h3>
                                            
                                            <div class="justify-content-around text-center mb-2">
                                                <div class="text-center mb-2">
                                                    <button 
                                                        modal="listaMarca" 
                                                        id="btn_ver_listas_marca" 
                                                        type="button" 
                                                        class="btn_modal btn btn btn-secondary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modal">
                                                            <i class="bi bi-list-columns-reverse"></i>
                                                            Lista de Marcas
                                                    </button>
                                                </div>
                                            </div>
                                        </div>  

                                    <?php endif; ?>              
                                </div>
                                
                                <!-- registro y listado de productos -->
        
                                <div class="col-12 mb-3 pagetitle text-center">
                                    <div class="card">
                                        <div class="card-body row p-3">
                                            <h3 id="titleModuleProducts" class="my-3 col-12 fs-3 titulosH">Inventario de Productos</h3>
                                            <div id="tableListProducts" class="justify-content-between align-items-center table table-responsive">
                                                <table class="table example mb-3" id="">
                                                    <thead>
                                                        <tr>
                                                            <th class="col text-center" scope="col">N.º</th>
                                                            <th class="col text-center" scope="col">Código</th>
                                                            <th class="col text-center" scope="col">Producto</th>
                                                            <th class="col text-center" scope="col">Stock</th>
                                                            <th class="col text-center" scope="col">Precio de Venta</th>
                                                            <th class="col text-center" scope="col">Última Entrada</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php producto_model::lista(); ?>  
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                    <?php   endif; 
                        endif;
                    ?>
                </div>
            </section>
        </main>

        
        <?php  
            include_once "./view/inc/plantillaModalCustom.php";
            include_once "./view/inc/footer.php";
            include_once "./view/inc/script.php";

            if ($permisos_venta): ?>
                
                <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
                <script src="./view/js/scanQr.js" type="text/javascript"></script>

                <script src="./view/js/añadir_producto.js"></script>

        <?php endif;  ?>
    </body>
</html>
