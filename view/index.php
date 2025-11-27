<?php 
session_start();

// importacion de la conexion a la base de datos y al modelo principal
include_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

$id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario

// validación para verificar que el usuario inicio sesion de manera correcta
model_user::verificar_intento_de_acceso_al_sistema();

include_once "../include/verificacion_primer_inicio_usuario.php"; // se incluyen los modelos necesarios para la vista


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- titulo -->
    <title>Panel de Control</title>
    <!-- metadatos -->  
    <?php include_once "../include/meta_include.php";
      // estilos y librerias css
      include_once "../include/css_include.php";
    ?>
  </head>
  <body>
    <?php   
      include_once "../include/header.php"; 
      include_once "../include/sliderbar.php";

      $total_ventas_del_dia = venta_model::totales_ventas_del_dia();

      $total_hoy_dolar = $total_ventas_del_dia['dolares'];
      $total_hoy_bs = $total_ventas_del_dia['bs'];
    ?>

    <main id="main" class="main">
      <div class="pagetitle"> <h1> Panel de Control </h1> </div> 
      <section class="section dashboard">
        <div class="row">

          <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Total de Ventas del Día</h5>

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
            <div class="row">
              <div class="col-12">
                <div class="card top-selling overflow-auto mb-4">
                    
                  <div class="card-body">
                    <h5 class="mb-3 card-title"> Ventas Recientes </h5>
                    
                    <div class="table-responsive">
                      <table class="table example table-hover table-striped mb-0" id="example">
                        <thead>
                          <tr>
                            <th class="text-center" scope="col" style="width: 5%;">N.°</th>
                            <th class="text-center" scope="col">N.° de Factura</th>
                            
                            <th class="text-start" scope="col">Cédula/RIF Cliente</th>
                            <th class="text-start" scope="col">Cliente</th>
                            
                            <th class="text-end" scope="col" style="width: 10%;">Total (USD)</th>
                            <th class="text-end" scope="col" style="width: 10%;">Total (Bs.)</th>
                            
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
            </div> 
          </div>
        </div>
      </section>
    </main>
    
    <?php   
      include_once "./modal/plantillaModalCustom.php";  
      modalCustom ();

      include_once "../include/footer.php";
      include_once "../include/scripts_include.php";
      
      model_user::validar_sesion_activa($id_usuario);
      
      config_model::verificar_actualizacion_configuracion(); 
    ?>
  </body>
</html>
