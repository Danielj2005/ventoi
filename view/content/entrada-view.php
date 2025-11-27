<?php

// permisos basados en roles

$entrada = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['producto']['entrada']);
$r_entrada = modeloPrincipal::verificar_permisos_requeridos(['r_entrada']);
$l_entrada = modeloPrincipal::verificar_permisos_requeridos(['l_entrada']);


$fecha_actual = date('Y-m-d');

$tipoCompra = !isset($_POST['tipoCompra']) ? 0 : $_POST['tipoCompra'];

$fecha1 = !isset($_POST['fecha_inicio']) ? '' : $_POST['fecha_inicio'];
$fecha2 = !isset($_POST['fecha_fin']) ? '' : $_POST['fecha_fin']; 

?>

<div class="pagetitle row">
    <div class="col-12">
        <a class="btn btn-outline-secondary mb-3" href="./dashboard">
            <i class="bi bi-chevron-left"></i> 
            <span>Volver al Panel Principal</span>
        </a>

        <?php if ($l_entrada == 1) { ?>
            <!-- Se define y se decide condicionalmente el titulo de la vista -->
                
            <h1 class="text-white tituloUno my-3">Historial de Compras</h1>

        <?php }else if ($r_entrada == 1) { ?>

            <h1 class="text-white tituloUno my-3">Registro de Compras</h1>

        <?php } ?>

        <div id="cardEntries" class="col-12 col-sm-12 col-md-6 pagetitle text-center card-body">
            <div class="accordion" id="entriesAccordion">
                <div class="accordion-item">
                    <h3 class="accordion-header my-1 col fs-4 text-center">

                        <button class="accordion-button collapsed" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#entriesCard" 
                            aria-expanded="true" aria-controls="collapseOne">¿Qué es una Entrada de productos?&nbsp;
                            <i class="text-dark mx-2 fs-5 bi bi-exclamation-circle-fill"></i>
                        </button>
                    </h3>

                    <div id="entriesCard" aria-expanded="true" aria-controls="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <p class="text-wrap-balance">
                                Las entradas de productos al inventario se originan por compras a proveedores o por adquisiciones (compras) realizadas directamente por el personal (por cuenta propia) para cubrir necesidades operacionales urgentes y asegurar la continuidad del servicio al cliente.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="card top-selling ">
                <div class="row text-center p-2 align-items-center">

                    <?php if ($r_entrada == 1 && $l_entrada == 1 ): ?>

                        <div class="col-12 col-sm-12 col-md-6 mb-2">
                            <button id="btn-toggle" onclick="toggle()" class="col-12 btnHiddenElements btn btn-success"><i class="bi bi-plus-circle"></i> Registrar Entrada </button>
                        </div>

                    <?php endif; ?>
                    
                    <div class="col-12 col-sm-12 mb-2 <?= $r_entrada == 0 && $l_entrada == 1 ? 'col-md-12 ' : 'col-md-6' ?>">
                        <div class="col-12 dropdown">
                            <button class="col-12 btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-file-text"></i>
                                <span>Exportar Entradas</span>
                            </button>

                            <ul class="dropdown-menu">
                                <li> <hr class="dropdown-divider"> </li>
                                <li class="p-2 text-center">
                                    <a class="btn btn-outline-success" target="_blank" href="./reportes/lista_entradas.php">
                                        <i class="bi bi-file-text"></i> 
                                        <span>Exportar Entradas</span>
                                    </a>
                                </li>
                                <li> <hr class="dropdown-divider"> </li>
                                <li> <hr class="dropdown-divider"> </li>
                                <li class="p-2 text-center">
                                    <label class="dropdown-item">Exportar Por Fecha</label>
                                    <form action="./reportes/lista_de_entradas_por_fechas.php" method="post" class="p-2 row mb-3" id="" target="_blank">
                                        
                                        <div class="input-group mb-3 justify-content-center">
                                            <label class="input-group-text text-start control-label">Fecha de inicio &nbsp;<span class="text-danger">*</span></label>
                                            <input class="reportDates form-control" type="date" id="fechaReporteInicio" name="fechaReporteInicio">
                                        </div>
                                        
                                        <div class="input-group mb-3 justify-content-center">
                                            <label class="input-group-text text-start control-label">Fecha de fin &nbsp;<span class="text-danger">*</span></label>
                                            <input class="reportDates form-control" value="<?= date('Y-m-d') ?>" type="date" id="fechaReporteFin" name="fechaReporteFin">
                                        </div>
                                        
                                        <div class="input-group mb-3 justify-content-center">
                                            <p class="text-wrap-balance showThis d-none alert alert-danger" id="mensajefechaReporteInicio" style="width: fit-content;">
                                                La Fecha de inicio no puede ser posterior a la Fecha de fin.
                                                <br>
                                                Las fechas seleccionadas no pueden ser posteriores a la Fecha actual.
                                            </p>
                                        </div>

                                        <div class="col-12 col-sm-12 col-md-12 mb-3 text-center">
                                            <button type="submit" class="d-none btn btn-outline-success" id="btnReportesFechas">
                                                <i class="bi bi-file-text"></i>
                                                <span>Generar Reporte (PDF)</span>
                                            </button>
                                        </div>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <hr>
                
                <div class="card-body">

                    <?php if ($l_entrada == 1) : ?>

                        <div id="tableListEntries" class="">
                            <input type="hidden" id="fecha_actual" name="fecha_actual" value="<?= $fecha_actual ?>">

                            <form method="post" class="show row" id="rango_fechas">
                                
                                <h5 class="text-white card-title">Historial de Compras</h5>

                                <div class="col-12 col-sm-12 col-md-12 mb-1">
                                    <p class="alert alert-info" style="width: fit-content;">
                                        Selecciona el rango de fechas para consultar el historial de entradas.
                                    </p>
                                </div>

                                <div class="col-12 col-sm-12 col-md-5 mb-3">
                                    <div class="input-group justify-content-center">
                                        <span class="input-group-text">Fecha de inicio</span>
                                        <input class="form-control" onchange="dateValidate()" type="date" id="fecha_inicio" name="fecha_inicio">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-4 mb-3">
                                    <div class="input-group justify-content-center">
                                        <span class="input-group-text">Fecha de fin</span>
                                        <input class="form-control" onchange="dateValidate()" value="<?= date('Y-m-d') ?>" type="date" id="fecha_fin" name="fecha_fin">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-3 mb-2 text-center">
                                    <button type="submit" disabled class="btn btn-outline-secondary bi bi-search" id="btn_fechas">&nbsp; Buscar por Fecha</button>
                                </div>

                                <div class="col-12 col-sm-12 col-md-12 mb-3">
                                    <!-- mensajes -->
                                    <p class="alert alert-danger d-none" id="mensaje_fecha_iguales" style="width: fit-content;">
                                        La Fecha de inicio no puede ser posterior a la Fecha de fin.
                                        <br>
                                        Las fechas seleccionadas no pueden ser posteriores a la Fecha actual.
                                    </p>
                                    <p class="alert alert-secondary <?= ($fecha1 == "" && $fecha2 == "") ? 'd-none' : '' ?>" style="width: fit-content;">
                                        Historial de Compras
                                        <br>
                                        Fecha de inicio: <b> <?php echo date ("d-m-Y",strtotime($fecha1)); ?> </b> 
                                        <br> 
                                        Fecha de fin: <b><?php echo date ("d-m-Y",strtotime($fecha2)); ?> </b> 
                                    </p>
                                </div>
                            </form>

                            
                            <form method="post" class="show row my-3" id="tipo_compra">
                                <div class="col-12 col-sm-12 col-md-12 mb-2 text-center">
                                    <input class="form-control" value="<?= $tipoCompra == 0 ? 1 : 0?>" type="hidden" name="tipoCompra">
                                    <button type="submit" class="btn btn-outline-<?= $tipoCompra == 0 ? "success" : "danger" ?> bi bi-<?= $tipoCompra == 0 ? "person" : "truck" ?>">&nbsp;<?= $tipoCompra == 0 ? "Ver Adquisiciones Propias" : "Ver Compras a Proveedores" ?></button>
                                </div>
                            </form>
                    
                    
                            <?php if ($l_entrada == 1 ): ?>

                                <div class="table-responsive">
                                    <table class="table table-striped example" id="example">
                                        <thead>
                                            <tr>
                                                <th class="col text-center" scope="col">N.º</th>
                                                <th class="col text-center" scope="col"><?= $tipoCompra == 1 ? "Cédula" : "Cédula o RIF" ?></th>
                                                <th class="col text-center" scope="col"><?= $tipoCompra == 1 ? "Usuario" : "Proveedor" ?></th>
                                                <th class="col text-center" scope="col">Total ($)</th>
                                                <th class="col text-center" scope="col">Total (Bs)</th>
                                                <th class="col text-center" scope="col">Tasa de Cambio</th>
                                                <th class="col text-center" scope="col">Fecha y Hora</th>
                                                <th class="col text-center" scope="col">Ver Detalles</th>
                                                <th class="col text-center" scope="col">Reporte (PDF)</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php
                                                if ($tipoCompra == 1){
                                                    $consulta = modeloPrincipal::consultar("SELECT U.cedula, U.nombre, U.apellido, 
                                                        E.total_dolar, E.total_bs, E.fecha_entrada, E.id_entrada, D.dolar AS tasa
                                                        FROM entrada AS E
                                                        INNER JOIN dolar AS D ON D.id_dolar = E.id_dolar 
                                                        INNER JOIN usuario AS U ON U.id_usuario = E.id_usuario 
                                                        ORDER BY E.fecha_entrada DESC 
                                                        LIMIT 100
                                                    ");
                                                }else if($tipoCompra == 0){
                                                    $consulta = modeloPrincipal::consultar("SELECT PROV.nombre, PROV.cedula_rif,
                                                        E.total_dolar, E.total_bs,
                                                        E.fecha_entrada, E.id_entrada, D.dolar AS tasa
                                                        FROM entrada AS E
                                                        INNER JOIN dolar AS D ON D.id_dolar = E.id_dolar 
                                                        INNER JOIN proveedor AS PROV ON PROV.id_proveedor = E.id_proveedor 
                                                        ORDER BY E.fecha_entrada DESC 
                                                        LIMIT 100
                                                    ");
                                                }else if($fecha1 !== "" && $fecha2 !== ""){
                                                    $consulta = modeloPrincipal::consultar("SELECT PROV.nombre, E.total_dolar, E.total_bs,
                                                        E.fecha_entrada, E.id_entrada, D.dolar AS tasa
                                                        FROM entrada AS E 
                                                        INNER JOIN proveedor AS PROV ON PROV.id_proveedor = E.id_proveedor 
                                                        INNER JOIN dolar AS D ON D.id_dolar = E.id_dolar 
                                                        WHERE E.fecha_entrada 
                                                        BETWEEN DATE('$fecha1') AND DATE('$fecha2') 
                                                        ORDER BY E.fecha_entrada DESC
                                                    ");
                                                }

                                                // se guardan los datos en un array y se imprime
                                                while ( $mostrar = mysqli_fetch_array($consulta)) { ?>    
                                                    <tr>
                                                        <td class="col text-center"></td>
                                                        <td class="col text-center"><?= $tipoCompra == 1 ? $mostrar["cedula"] : $mostrar["cedula_rif"] ?></td>
                                                        <td class="col text-center"><?= $tipoCompra == 1 ? $mostrar["nombre"]." ".$mostrar["apellido"] : $mostrar["nombre"]; ?></td>
                                                        <td class="col text-center"><?= $mostrar["total_dolar"].' $'; ?></td>
                                                        <td class="col text-center"><?= $mostrar["total_bs"].' Bs.'; ?></td>
                                                        <td class="col text-center"><?= $mostrar["tasa"].' Bs.'; ?></td>

                                                        <td class="col text-center"><?= date('d-m-Y h:i:a',strtotime($mostrar["fecha_entrada"])); ?></td>

                                                        <td class="col text-center" scope="col">
                                                            <button 
                                                                modal="detallesEntrada" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modal" 
                                                                class="btn_modal btn btn-info"
                                                                value="<?= modeloPrincipal::encryptionId($mostrar["id_entrada"]); ?>">
                                                                    <i class="bi bi-eye"></i> 
                                                            </button>
                                                        </td>
                                                        
                                                        <td class="col text-center" scope="col">
                                                            <form action="./reportes/detalles_de_entrada.php" method="post" target="_blank">
                                                                <input type="hidden" name="UIDE" value="<?= modeloPrincipal::encryptionId($mostrar["id_entrada"]); ?>">
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="bi bi-file-text"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                            <?php endif; ?>

                        </div>

                    <?php endif;  if ($r_entrada == 1) : ?>

                        <div id="tableRegisterEntries" class="show <?= $l_entrada == 1 ? 'd-none' : '' ?>">
                            
                            <form action="../controlador/registrar_entrada.php" method="post" class="SendFormAjax row" autocomplete="off" data-type-form="save">
                                <input type="hidden" name="id_dolar" id="dolar" value="<?= modeloPrincipal::obtener_id_precio_dolar(); ?>">
                                <input type="hidden" name="modulo" value="Guardar">

                                <label class="form-label">Tipo de Compra <span style="color:#f00;">*</span></label>
                                <div class="col-12 col-md-12 mb-3">
                                    <select onchange="dataBuyEntries()" name="tipo_compra" id="tipo_compra_id" class="form-select ">
                                        <option selected disabled>Seleccione una opción</option>
                                        <option value="adquisicion_propia">Compra Directa (Personal)</option>
                                        <option value="compra_proveedor">Compra a Proveedor</option>
                                    </select>
                                </div>
                                
                                <fieldset id="datProvider" class="row m-0 p-0 d-none">
                                    <h5 class="card-title">Información del Proveedor</h5> 
                                    <!-- datos del proveedor al que se le compró -->
                                    <div class="col-12 col-sm-6 col-md-6 mb-3">
                                        <label class="form-label">Cédula o RIF <span style="color:#f00;">*</span></label>
                                        <div class="col-md-4 input-group">
                                            <select class="input-group-text" id="nacionalidad" name="nacionalidad">
                                                <option value="V-">V</option>
                                                <option value="R-">RIF</option>
                                                <option value="J-">J</option>
                                                <option value="E-">E</option>
                                            </select>
                                            <input type="text" class="form-control" minlength="7" maxlength="8" placeholder="ingresa la cédula / RIF" onblur="buscar_proveedor()"; name="cedula" id="cedula">
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-6 mb-3">
                                        <label for="validationDefault02" class="form-label">Nombre <span style="color:#f00;">*</span></label>
                                        <input type="text" class="form-control" minlength="3" maxlength="80" placeholder="ingresa el nombre" id="nombre_proveedor" name="nombre_proveedor">
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-6 mb-3">
                                        <label for="validationDefault02" class="form-label">Correo <span style="color:#f00;">*</span></label>
                                        <input type="text" class="form-control"  minlength="10" maxlength="150" placeholder ="ingresa el correo" id="correo" name="correo">
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-6 mb-3">
                                        <label   class="form-label">Teléfono <span style="color:#f00;">*</span></label>
                                        <input type="text" class="form-control" minlength="11" maxlength="11"  name="telefono" placeholder="ingresa el teléfono" id="telefono">
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-12 mb-3">
                                        <label for="validationDefault03" class="form-label">Dirección <span style="color:#f00;">*</span></label>
                                        <input type="text" class="form-control" minlength="3" maxlength="250" name="direccion" placeholder="ingresa la dirección" id="direccion">
                                    </div>
                                </fieldset>

                                <!-- datos de el (los) producto(s) comprados al proveedor -->

                                <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-1 row m-0">
                                    <h5 class="col-12 col-sm-12 col-md-8 mb-3 card-title">Productos de la Entrada</h5>

                                    <div class="col-12 col-sm-12 col-md-4 mb-3 text-center">
                                        <button modal="registrar_producto" url="./modal/producto/registrar.php" type="button" class="btn_modal btn btn-primary bi bi-plus" data-bs-toggle="modal" data-bs-target="#modal">&nbsp;Registar Nuevo Producto</button>
                                    </div>

                                    <label class="form-label">Producto <span style="color:#f00;">*</span></label>
                                    <div class="col-12 col-md-9 mb-3">
                                        <select name="producto" id="producto_id" class="select form-select SelectTwo">
                                            <option selected>Seleccione un producto</option>
                                            <?php producto_model::options(); ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-12 col-sm-12 col-md-3 mb-3">
                                        <button type="button" name="btn_producto" class="btn btn-success bi bi-plus btn_add">Añadir a la Entrada</button>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-1 row m-0">
                                    <div class="row justify-content-around">
                                        <h5 class="card-title col-12 mb-2">Lista de productos</h5>

                                        <div class="col-12 table-responsive m-0 p-0">
                                            <table class="table table-borderless table-striped" id="">
                                                <thead>
                                                    <tr>
                                                        <th class="col text-center" scope="col">Producto</th>
                                                        <th class="col text-center" scope="col">Cantidad</th>
                                                        <th class="col text-center" scope="col">Costo ($)</th>
                                                        <th class="col text-center" scope="col">Costo (Bs.)</th>
                                                        <th class="col text-center" scope="col">Precio Venta ($)</th>
                                                        <th class="col text-center" scope="col">Quitar</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="lista_producto"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <hr class="divider">

                                <div class="col-12 col-sm-12 col-md-6 mt-1 mb-1">
                                    <div class="input-group mb-3 justify-content-center">
                                        <label class="input-group-text">Fecha de Entrada &nbsp; <span style="color:#f00;"> *</span> </label>
                                        <input class="form-control" value="<?= date("Y-m-d"); ?>" required type="date" id="fecha_entrada" name="fecha_entrada">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-6 mt-1 mb-1">
                                    <div class="input-group mb-3 justify-content-center">
                                        <label class="input-group-text">Hora de Entrada &nbsp; <span style="color:#f00;"> *</span> </label>
                                        <input class="form-control" value="<?=  $fecha2 = date("H:i:s"); ?>" required type="time" id="hora_entrada" name="hora_entrada">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-12 my-3">
                                    <h5 class="card-title col-12">Inversión Total</h5>
                                    
                                    <input id="total_Dolar" type="hidden" class="totalDolar" name="totalDolar">
                                    <input id="total_Bolivar" type="hidden" class="totalBolivar" name="totalBolivar">

                                    <table class="table table-striped table-borderless overflow-x-auto">
                                        <tbody>
                                            <tr>
                                                <td class="fs-4 text-success text-center col">Total ($): <strong> <span id="totalDolar">0</span> </strong></td> 
                                                <td class="fs-4 text-success text-center col">Total (Bs.): <strong> <span id="totalBolivar">0</span> </strong></td> 
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="col-12 mb-1">
                                    <div class="form-group">
                                        <p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-12 mt-1 text-center">
                                    <button name="insertar" class="btn btn-success">&nbsp;Registrar entrada</button>
                                </div>
                            </form>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>


<script type="text/javascript">
    // mostrar u ocultar el campo de datos del proveedor segun el tipo de compra seleccionado
    const dataBuyEntries = () => {
        const tipoCompra = document.querySelector('#tipo_compra_id').value;
        const datProvider = document.querySelector('#datProvider');

        if (tipoCompra === 'compra_proveedor' && datProvider.classList.contains('d-none')) {
            datProvider.classList.remove('d-none');
        }else{
            datProvider.classList.add('d-none');
        }
    };

    const toggle = ()=> {
        const btnToggle = document.getElementById('btn-toggle');
        btnToggle.classList.toggle('btn-success');
        btnToggle.classList.toggle('btn-secondary');

        const titleBtn = [
            '<i class="bi bi-plus-circle"></i> Registrar Entrada ',
            `<i class="bi bi-list-columns-reverse"></i> Lista de Entradas `
        ];

        btnToggle.innerHTML = btnToggle.innerHTML == titleBtn[0] ? titleBtn[1] : titleBtn[0];

        document.getElementById('tableRegisterEntries').classList.toggle('d-none');
        document.getElementById('tableListEntries').classList.toggle('d-none');

        document.querySelectorAll('.tableRegisterEntries').forEach(element => {
            element.classList.toggle('d-none');
        });
        document.querySelectorAll('.setCol').forEach(element => {
            element.classList.toggle('col-md-6');
        });
    };
</script>

<script src="./js/convertir_dolar_bs.js"></script>

<script src="./js/rango_fechas.js"></script>