<?php 
session_start();
require_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

include_once "../include/verificacion_primer_inicio_usuario.php";

$settings = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['ajustes']);

if ($settings) {  
?>
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <!-- titulo -->
      <title>Configuración del Sistema</title>
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
      ?>

      <main id="main" class="main">
        <div class="pagetitle">
          <a class="btn btn-outline-secondary mb-3" href="./">
            <i class="bi bi-chevron-left"></i> 
            <span>Volver al Panel Principal</span>
          </a>

          <h1>
            Configuración del sistema
            <form id="backUp" action="../controlador/configuracion_controlador.php" method="post" class="SendFormAjax row" autocomplete="off" data-type-form="load">
              <input type="hidden" name="modulo" id="input" class="form-control" value="backup">
                
              <div class="col-12 col-sm-12 col-md-12 mt-3 mb-3 text-center">
                <button form="backUp" name="insertar" class="btn btn-dark">Guardar copia de seguridad de la base de datos</button>
              </div>
            </form>
          </h1>
        </div>
        
        <section class="section dashboard">
          <div class="card top-selling overflow-auto"> 
            <div class="card-body pb-0">
              
              <div class="row justify-content-center align-items-center mb-3 text-center">
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 mt-3 mb-3 text-center">
                  <button id="btn_config_product" type="button" name="config_product" class="press_change_view btn btn-primary">Configuración de Productos</button>
                </div>
                
                <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-3 mb-3 text-center">
                  <button id="btn_config_session" type="button" name="config_session" class="press_change_view btn btn-outline-primary">Configuración de Sesión</button>
                </div>
                
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 mt-3 mb-3 text-center">
                  <button id="btn_config_pass" type="button" name="config_pass" class="press_change_view btn btn-outline-primary">Configuración de Contraseña</button>
                </div>
              </div>

              <fieldset class="mb-4">
                <div class="row m-0">
                  <div id="config_product" class="col-12 col-sm-12 col-md-12 mb-3">
                    <h4 class="card-title"> Configuración de Productos </h4>
                    <form action="../controlador/configuracion_controlador.php" method="post" class="SendFormAjax row" autocomplete="off" data-type-form="save">
                      <input type="hidden" name="modulo" value="producto">

                      <div class="row mb-3">
                        <div class="col-12 col-sm-12 col-md-6 mb-3">
                          <label class="form-label control-label">Porcentaje del IVA <span style="color:#f00;">*</span> </label>

                          <div class="input-group mb-3">
                            <input min="1" max="100" value="<?= config_model::obtener_dato('porcentaje_iva') ?>" type="number" class="p-1 form-control" id="porcentaje_iva" name="porcentaje_iva" placeholder="Ingresa el porcentaje del IVA">
                            <span class="input-group-text bi bi-percent"></span>
                          </div>
                          
                        </div>

                        <div class="col-12 col-sm-12 col-md-6 mb-3">
                          <label class="form-label control-label">Porcentaje de ganancia por producto <span style="color:#f00;">*</span> </label>
                          
                          <div class="input-group mb-3">
                            <input min="1" max="100" value="<?= config_model::obtener_dato('porcentaje_ganancia') ?>" type="number" class="p-1 form-control" id="porcentaje_ganancia" name="porcentaje_ganancia" placeholder="Ingresa el porcentaje de ganancia">
                            <span class="input-group-text bi bi-percent"></span>
                          </div>

                        </div>
                        
                      </div>
                      

                      <div class="col-12 mb-1">
                        <div class="form-group">
                            <p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
                        </div>
                      </div>
                      
                      <div class="col-12 col-sm-12 col-md-12 mt-3 mb-3 text-center">
                        <button type="submit" name="insertar" class="btn btn-success">Guardar</button>
                      </div>
                    </form>
                  </div>

                  <div id="config_session" class="d-none col-12 col-sm-12 col-md-12 mb-3">
                    <h4 class="card-title"> Configuración de Sesión</h4>
                    <form action="../controlador/configuracion_controlador.php" method="post" class="SendFormAjax row" autocomplete="off" data-type-form="save">
                      <input type="hidden" name="modulo" value="sesion">

                      <div class="row mb-3  justify-content-center">
                        <div class="col-12 col-sm-12 col-md-4 mb-3">
                          <label class="form-label control-label">Tiempo de inactividad de sesión <span style="color:#f00;">*</span> </label>

                          <div class="input-group mb-3">
                            <span class="input-group-text bi bi-clock"></span>
                            <input class="form-control" type="number" name="tiempo_inactividad" min="1" max="60" value="<?= config_model::obtener_dato('tiempo_inactividad') ?>">
                            <span class="input-group-text">Minutos</span>
                          </div>
                          
                        </div>

                        <div class="col-12 col-sm-12 col-md-4 mb-3">
                          <label class="form-label control-label">Cantidad de preguntas de seguridad <span style="color:#f00;">*</span> </label>
                          <input class="form-control" type="number" name="c_preguntas" min="3" max="4" value="<?= config_model::obtener_dato('c_preguntas') ?>">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4 mb-3">
                          <label class="form-label control-label">Intentos de inicio de sesión <span style="color:#f00;">*</span> </label>
                          <input class="form-control" type="number" name="intentos_inicio_sesion" min="1" max="5" value="<?= config_model::obtener_dato('intentos_inicio_sesion') ?>">
                        </div>
                      </div>
                      
                      

                      <div class="col-12 mb-1">
                        <div class="form-group">
                            <p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
                        </div>
                      </div>
                      
                      <div class="col-12 col-sm-12 col-md-12 mt-3 mb-3 text-center">
                        <button type="submit" name="insertar" class="btn btn-success">Guardar</button>
                      </div>
                    </form>
                  </div>

                  <div id="config_pass" class="d-none col-12 col-sm-12 col-md-12 mb-3">
                    <h4 class="card-title"> Configuración de Contraseña</h4>
                    <form action="../controlador/configuracion_controlador.php" method="post" class="SendFormAjax row" autocomplete="off" data-type-form="save">
                      <input type="hidden" name="modulo" value="Contraseña">

                      <div class="row mb-3 justify-content-center">
                        <div class="col-12 col-sm-12 col-md-4 mb-3">
                          <label class="form-label control-label">Longitud <span style="font-size:1rem; color:#f00;">*</span> </label>
                          <input class="form-control" type="number" name="c_caracteres" min="1" max="16" value="<?= config_model::obtener_dato('c_caracteres') ?>">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4 mb-3">
                          <label class="form-label control-label">Cantidad de símbolos <span style="font-size:1rem; color:#f00;">*</span> (! @ # $ %)</label>
                          <input class="form-control" type="number" name="c_simbolos" min="1" max="3" value="<?= config_model::obtener_dato('c_simbolos') ?>">
                        </div>

                        <div class="col-12 col-sm-12 col-md-4 mb-3">
                          <label class="form-label control-label">Cantidad de números <span style="font-size:1rem; color:#f00;">*</span> </label>
                          <input class="form-control" type="number" name="c_numeros" min="1" max="3" value="<?= config_model::obtener_dato('c_numeros') ?>">
                        </div>
                      </div>
                      

                      <div class="col-12 mb-1">
                        <div class="form-group">
                            <p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
                        </div>
                      </div>
                      
                      <div class="col-12 col-sm-12 col-md-12 mt-3 mb-3 text-center">
                        <button type="submit" name="insertar" class="btn btn-success">Guardar</button>
                      </div>
                    </form>
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
        </section>
      </main>
      <?php 
        include_once "./modal/plantillaModalCustom.php";  
        modalCustom ();

        // se incluye el footer / pie de pagina a la vista
        include_once "../include/footer.php" ;
        // se incluyen los script de javascript a la vista 
        include_once "../include/scripts_include.php" ; 
      
        model_user::validar_sesion_activa($id_usuario);
        config_model::verificar_actualizacion_configuracion(); 

        ?>
        
      <script type="text/javascript">
        const btn_view = document.querySelectorAll('.press_change_view');

        const view_config_product = document.getElementById('config_product');
        const view_config_session = document.getElementById('config_session');
        const view_config_pass = document.getElementById('config_pass');

        const btn_config_product = document.getElementById('btn_config_product');
        const btn_config_session = document.getElementById('btn_config_session');
        const btn_config_pass = document.getElementById('btn_config_pass');

        const hidden_display = (view) => {
          view.forEach (div => {
            if (!div.classList.contains('d-none')) {
              div.classList.add('d-none');
            }
          });
        };

        const show_display = (view) => {
          if (view.classList.contains('d-none')) {
            view.classList.remove('d-none');
          }
        };

        const btn_not_active = (btn_active) => {
          btn_active.forEach (btn => {
            if (!btn.classList.contains('btn-outline-primary')) {
              btn.classList.remove('btn-primary');
              btn.classList.add('btn-outline-primary');
            }
          });
        };

        btn_view.forEach(btn => {
          btn.addEventListener('click', function() {

            if (btn.name === 'config_session') {
              hidden_display([view_config_product, view_config_pass]);
              btn_not_active([btn_config_product, btn_config_pass]);
              
              view_config_session.classList.remove('d-none');
              
              btn_config_session.classList.remove('btn-outline-primary');
              btn_config_session.classList.add('btn-primary');

            }

            if (btn.name === 'config_product') {
              hidden_display([view_config_session, view_config_pass]);
              btn_not_active([btn_config_session, btn_config_pass]);
              
              view_config_product.classList.remove('d-none');
              
              btn_config_product.classList.remove('btn-outline-primary');
              btn_config_product.classList.add('btn-primary');

            }

            if (btn.name === 'config_pass') {
              hidden_display([view_config_product, view_config_session]);
              btn_not_active([btn_config_product, btn_config_session]);
              
              view_config_pass.classList.remove('d-none');
              
              btn_config_pass.classList.remove('btn-outline-primary');
              btn_config_pass.classList.add('btn-primary');

            }
          });
        });
        
      </script>
    </body>
  </html>
<?php }else{
  // se registran las acciones del usuario en la bitacora y es redirijido al inicio
  bitacora::intento_de_acceso_a_vista_sin_permisos("de Configuración del sistema");
}