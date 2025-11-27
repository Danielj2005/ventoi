<?php 
session_start();

// importacion de la conexion a la base de datos y al modelo principal

include_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

$id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario
// validación para verificar que el usuario inicio sesion de manera correcta
model_user::verificar_intento_de_acceso_al_sistema();

include_once "../include/verificacion_primer_inicio_usuario.php"; // se incluyen los modelos necesarios para la vista

$est_venta = modeloPrincipal::verificar_permisos_requeridos(['est_venta']);

// se evalua que este rol tenga el acceso a esta vista

if ($est_venta) {  ?>
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <!-- titulo -->
      <title>Estadísticas | Sistema de Control de Inventario y Venta de la Pollera La Chinita</title>
      <?php
        include_once "../include/meta_include.php"; 
        include_once "../include/css_include.php";
      ?>
    </head>
    <body>
      <!-- ======= Header ======= -->
      <?php  
        include_once "../include/header.php";
        include_once "../include/sliderbar.php"; 
      ?>

      <main id="main" class="main">
        <div class="pagetitle">
          <a class="btn btn-outline-secondary mb-3" href="./">
              <i class="bi bi-chevron-left"></i> 
              <span>Volver al Panel Principal</span>
          </a>
          <h1 class="display-5 fw-bold text-primary mb-4 border-bottom pb-2">
            <i class="bi bi-bar-chart-line-fill me-3"></i> 
            Estadísticas de Productos Vendidos
          </h1>
        </div>
        <section class="section dashboard">
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                <h5 class="card-title">Graficas de productos vendidos unitariamente</h5>
                  <!-- Default Tabs -->

                  <?php //include "../include/listas_estadisticas_include.php"; consultar_registros('estadistica_producto'); ?>


                  <div class="tab-content pt-2" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <div id="barChart" style="min-height: 400px;" class="echart"></div>
                      <script>

                        a = 1;
                        var cantidad = [];
                        var array =[];

                        while (input_producto = document.getElementById('producto'+a).value) {

                          cantidad_producto = document.getElementById('cantidad'+a).value;

                          array.push(input_producto);

                          cantidad.push(cantidad_producto);

                          a ++;
                          
                          document.addEventListener("DOMContentLoaded", () => {
                            echarts.init(document.querySelector("#barChart")).setOption({
                              xAxis: {
                                type: 'category',
                                data: array
                              },
                              yAxis: {
                                type: 'value'
                              },
                              series: [{
                                data: cantidad,
                                type: 'bar'
                              }]
                            });
                          }); 
                        }
                      </script>
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

        include_once("../include/footer.php");
        include_once("../include/scripts_include.php");
      
        model_user::validar_sesion_activa($id_usuario);

        config_model::verificar_actualizacion_configuracion(); 

        ?>
    </body>
  </html>
<?php }else{
  // se registran las acciones del usuario en la bitacora y es redirijido al inicio
  bitacora::intento_de_acceso_a_vista_sin_permisos("estadísticas de servicios");
}