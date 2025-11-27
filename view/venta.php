<?php 
session_start();
// importacion de la conexion a la base de datos y al modelo de usuario

include_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

$id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario
// validación para verificar que el usuario inicio sesion de manera correcta
model_user::verificar_intento_de_acceso_al_sistema();

include_once "../include/verificacion_primer_inicio_usuario.php"; // se incluyen los modelos necesarios para la vista

$permiso_venta = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['venta']);

$d_venta = modeloPrincipal::verificar_permisos_requeridos(['d_venta']);
$l_venta = modeloPrincipal::verificar_permisos_requeridos(['l_venta']);
$f_venta = modeloPrincipal::verificar_permisos_requeridos(['f_venta']);

// se evalua que este rol tenga el acceso a esta vista
if ($permiso_venta) { ?>
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <!-- titulo -->
      <title>Ventas</title>
      <?php 
        // se incluyen los meta datos 
        include_once "../include/meta_include.php"; 
        // se incluyen los estilos css y sus librerias a la vista
        include_once "../include/css_include.php"; 
      ?>
    </head>
    <body>

      <?php 

        // se incluye el header / encabezado a la vista
        include_once "../include/header.php";
        // se incluye el menu lateral a la vista 
        include_once "../include/sliderbar.php"; 
      
        $totales_ventas = venta_model::totales_ventas();

        $total_hoy_en_dolares = $totales_ventas['dolares'];
        $total_hoy_en_bolivares = $totales_ventas['bs'];

        $fecha_actual = date('Y-m-d');  

        $fecha_inicio = $_POST['fecha_inicio'];  
        $fecha_fin = $_POST['fecha_fin'];

      ?>

      <main id="main" class="main">
        <div class="pagetitle">
          <a class="btn btn-outline-secondary mb-3" href="./">
              <i class="bi bi-chevron-left"></i> 
              <span>Volver al Panel Principal</span>
          </a>
          <h1> Ventas </h1>
        </div> 
        <section class="section dashboard">
          <div class="row">

            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Total Generado</h5>
                  <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 mb-3">
                      <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Monto Total (USD)</span>
                        <input type="text" class="form-control" disabled id="TotalUSD" readOnly value="<?= ($total_hoy_en_dolares == "") ? 0 : $total_hoy_en_dolares ?>">
                        <span class="input-group-text" id="basic-addon1">$</span>
                      </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 mb-3">
                      <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Monto Total (BS)</span>
                        <input type="text" class="form-control" disabled id="TotalBS" readOnly value="<?= ($total_hoy_en_bolivares == "") ? 0 : $total_hoy_en_bolivares ?>">
                        <span class="input-group-text" id="basic-addon1">BS</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-12">
              <div class="card top-selling overflow-auto">
                <div class="card-body pb-0">
                  <h5 class="card-title">Listado de Ventas</h5>
                  <!-- este input se usa para la validacion de las fechas Nota: no borrar por favor! -->
                  <input type="hidden" id="fecha_actual" name="fecha_actual" value="<?= $fecha_actual; ?>">
                  <form method="post" class="row mb-3" id="rango_fechas">
                    
                    <div class="col-12 col-sm-12 col-md-12 mb-3">
                      <p class="alert alert-info bi bi-exclamation-circle" style="width: fit-content;">
                        &nbsp; Seleciona un rango de fechas para ver las ventas realizadas en esas fechas
                      </p>
                    </div>

                    <div class="col-12 col-sm-12 col-md-4 mb-3">
                      <div class="input-group mb-3 justify-content-center">
                        <span class="input-group-text">Desde</span>
                        <input class="form-control" onchange="dateValidate()" type="date" id="fecha_inicio" name="fecha_inicio">
                      </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-4 mb-3">
                      <div class="input-group mb-3 justify-content-center">
                        <span class="input-group-text">Hasta</span>
                        <input class="form-control" onchange="dateValidate()" value="<?= date('Y-m-d') ?>" type="date" id="fecha_fin" name="fecha_fin">
                      </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-4 mb-3 text-center">
                      <button type="submit" disabled class="btn btn-outline-secondary bi bi-search" id="btn_fechas">&nbsp; Buscar Fecha</button>
                    </div>

                    <div class="col-12 col-sm-12 col-md-12 mb-3">
                      <!-- mensajes -->
                      <p class="alert alert-danger d-none" id="mensaje_fecha_iguales" style="width: fit-content;">La fecha de inicio no puede ser mayor a la fecha de fin y ninguno puede ser mayor a la fecha actual.</p>
                      <p class="alert alert-secondary <?= ($fecha_inicio == "" && $fecha_fin == "") ? 'd-none' : '' ?>" style="width: fit-content;">
                        Fecha inicial: <b> <?php echo date ("d-m-Y",strtotime($fecha_inicio)); ?> </b>   Fecha final: <b><?php echo date ("d-m-Y",strtotime($fecha_fin)); ?> </b> 
                      </p>
                    </div>
                  </form>

                  <div class="p-3 table-responsive">
                    <table class="table table-striped example" id="example">
                      <thead>
                        <tr>
                            <th class="col text-center" scope="col" style="width: 5%;">N.°</th>
                            <th class="col text-center" scope="col">N.° de Factura</th>
                            
                            <th class="col text-center" scope="col">Cédula/RIF Cliente</th>
                            <th class="col text-center" scope="col">Cliente</th>
                            
                            <th class="col text-center" scope="col" style="width: 10%;">Total (USD)</th>
                            <th class="col text-center" scope="col" style="width: 10%;">Total (Bs.)</th>
                            
                            <th class="col text-center" scope="col">Fecha y Hora</th>
                            
                            
                          <?php if ($d_venta == '1') : ?>
                            <th class="col text-center" scope="col" style="width: 8%;">Detalles</th>
                          <?php endif; ?>
                          
                          <?php if ($f_venta == '1') :?>
                            <th class="col text-center" scope="col" style="width: 8%;">Ver Facturas</th>
                          <?php endif; ?>

                        </tr>
                      </thead>
                      <tbody id="tbody">
                        <?php venta_model::lista_ventas_realizadas($fecha_inicio, $fecha_fin); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
      
      <script src="./js/rango_fechas.js"></script>

      <?php 

        include_once "./modal/plantillaModalCustom.php"; 
        modalCustom ("modal-xl");
        
        include_once "../include/footer.php"; 
        include_once "../include/scripts_include.php";
        model_user::validar_sesion_activa($id_usuario);

        config_model::verificar_actualizacion_configuracion();

      ?>

    </body>
  </html>
<?php }else{
  // se registran las acciones del usuario en la bitacora y es redirijido al inicio
  bitacora::intento_de_acceso_a_vista_sin_permisos("lista de ventas realizadas");
}