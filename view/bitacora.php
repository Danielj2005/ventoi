<?php 
session_start();
include_once "../modelo/modeloPrincipal.php"; // se incluye el modelo principal
include_once "../modelo/modelo_usuario.php";  // se incluye el modelo de usuario
include_once "../modelo/rol_model.php"; // se incluye el modelo rol
include_once "../modelo/bitacora_model.php"; // se incluye el modelo de bitacora
include_once "../modelo/configuracion_model.php"; // se incluye el modelo de configuracion

$id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario
// validación para verificar que el usuario inicio sesion de manera correcta
model_user::verificar_intento_de_acceso_al_sistema();

include_once "../include/verificacion_primer_inicio_usuario.php";

$bitacora = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['bitacora']);

// se evalua que este rol tenga el acceso a esta vista
if ($bitacora) {  ?>
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <!-- titulo -->
      <title>Bitácora de Eventos</title> 
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

        if(isset($_POST["selected_user"])){
          $id = modeloPrincipal::decryptionId($_POST["selected_user"]);
          $id = modeloPrincipal::limpiar_cadena($id);
        }else{
          $id = "";
        }

        if($id !== ""){
          $consulta = modeloPrincipal::consultar("SELECT B.*, U.nombre, U.apellido FROM bitacora AS B
            INNER JOIN usuario AS U ON B.id_usuario = U.id_usuario 
            WHERE U.id_usuario = $id ORDER BY id DESC");
        }else{
          $consulta = modeloPrincipal::consultar("SELECT B.*, U.nombre, U.apellido FROM bitacora AS B
            INNER JOIN usuario AS U ON B.id_usuario = U.id_usuario ORDER BY id DESC LIMIT 100");
        }
      ?>

      <main id="main" class="main">
        <div class="pagetitle">
          <a class="btn btn-outline-secondary mb-3" href="./">
            <i class="bi bi-chevron-left"></i> 
            <span>Volver al Panel Principal</span>
          </a>

          <h1 class="display-4 fw-bold mb-4 border-bottom pb-2">
            <i class="bi bi-journal-text me-3 text-secondary"></i> 
            Bitácora de Eventos del Sistema
          </h1>
        </div>

        <section class="section dashboard">
          <div class="row">
            <div class="col-12">
              <div class="card top-selling pb-3">
                <div class="card-body pb-0">
                  <h5 class="card-title fw-bold mb-3">
                    <i class="bi bi-clock-history me-2 text-secondary"></i>
                    Historial de Actividad Reciente
                  </h5>

                  <div>
                    <form method="post" class="row mb-3" id="" action="#!">
                      <label class="form-label">Selecciona un Usuario para ver todos sus movimientos</label>
                      <div class="col-12 col-sm-12 col-md-9 mb-3">
                        <select onchange="bitacora_user()" name="selected_user" id="selected_user" class="form-select Select select">
                          <option value="" selected>seleccione un usuario</option>
                          <?php model_user::options_usuarios(); ?>
                        </select>
                      </div>
                      <div class="hidden d-none col-12 col-sm-12 col-md-3 mb-3">
                        <button class="btn btn-primary bi bi-file-text">&nbsp; Buscar Movimientos</button>
                      </div>
                    </form>
                  </div>

                  <div>
                    <div class="table table-responsive">
                      <table class="table table-striped datatable" id="example">
                        <thead>
                          <tr>
                            <th class="text-center col" scope="col">#</th>
                            <th class="text-center col" scope="col">Acción</th>
                            <th class="text-center col" scope="col">Usuario</th>
                            <th class="col text-center" scope="col">Fecha y Hora</th>
                            <th class="text-center col" scope="col">Detalles</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                              while( $row = mysqli_fetch_assoc($consulta)) { ?>
                                <tr>
                                  <th class="col" scope="col"></th>
                                  <th class="col" scope="col"><?= $row['accion'] ?></th>
                                  <th class="col" scope="col"><?= $row['nombre'].' '.$row['apellido'] ?></th>
                                  <th class="col" scope="col"><?= date('d-m-Y | g:i a', strtotime($row['fecha_hora'])) ?></th>
                                  <th class="text-center col" scope="col">
                                    <button 
                                      modal="bitacora"
                                      class="btn_modal btn bi bi-eye btn-info"
                                      value="<?= modeloPrincipal::encryptionId($row["id"]); ?>"
                                      data-bs-toggle="modal" 
                                      data-bs-target="#modal">
                                    </button>
                                  </th>
                                </tr>
                            <?php } ?>  
                          </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>

      <script type="text/javascript">
        const bitacora_user = () => {

          const selectUsers = document.getElementById('selected_user');
          const btnUserMovements = document.querySelector('.hidden');
  
          btnUserMovements.classList.toggle('d-none');
          console.log(`${selectUsers.value}`);
  
          btnUserMovements.addEventListener('click', () => {
            btnUserMovements.classList.toggle('d-none');
          });
        };
      </script>

      <?php 
        include_once "./modal/plantillaModalCustom.php"; 
        modalCustom ();
        // se incluye el footer / pie de pagina a la vista
        include_once "../include/footer.php";
        // se incluyen los script de javascript a la vista 
        include_once "../include/scripts_include.php";
      
        model_user::validar_sesion_activa($id_usuario);
        
        config_model::verificar_actualizacion_configuracion(); 
        ?>
    </body>
  </html>
<?php }else{
  // se registran las acciones del usuario en la bitacora y es redirijido al inicio
  bitacora::intento_de_acceso_a_vista_sin_permisos("bitácora");
}