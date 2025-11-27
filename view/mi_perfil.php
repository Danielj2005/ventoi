<?php 
session_start();
include_once "../include/modelos_include.php";

$id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario
// validación para verificar que el usuario inicio sesion de manera correcta
model_user::verificar_intento_de_acceso_al_sistema();

$VISTA_PRIMER_INICIO = $_SESSION['primerInicio'] == '0' ? '' : 'toggle-sidebar';

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- titulo -->
    <title>Mi Perfil</title>
    <!-- metadatos -->  
    <?php 
      include_once "../include/meta_include.php";
      // ======= estilos y librerias css ======= 
      include_once "../include/css_include.php"; 
    ?>    
  </head>
  <body class="<?= $VISTA_PRIMER_INICIO; ?>">

    <?php
      include_once "../include/header.php"; 
      include_once "../include/sliderbar.php";
    ?>
    
    <main id="main" class="main">
      <div class="pagetitle">
        <h1 class="display-4 fw-bold text-start mb-3">
          <i class="bi bi-person-circle me-3 text-primary"></i> 
          Mi Perfil de Usuario
        </h1>
      </div>
      <section class="section dashboard">
        <div class="row">
          <div class="col-lg-12">
            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-body pb-3">
                    <div class="mi_informacion_container col-12 col-lg-12 my-3">

                      <fieldset class="row mb-3">
                          <legend class="col-12 col-sm-12"><i class="bi bi-person"></i> &nbsp;Información personal</legend>
                          <div class="col-12 col-sm-6 col-md-6 col-lg-6 mb-3">
                            <div class="form-group">
                                <label class="control-label">Cédula</label>
                                <input type="text" pattern="[0-9\-]{1,30}" class="bg-secondary-subtle form-control" value="<?= $_SESSION['dataUsuario']['dni']; ?>" id="cedula" name="cedula" readOnly="true" maxlength="9">
                            </div>
                          </div>
                          <div class="col-12 col-sm-6 col-md-6 col-lg-6 mb-3">
                            <div class="form-group">
                              <label class="control-label">Nombres</label>
                              <input type="text" pattern="[A-Za-zÁÉÍÚÓáéíóúñÑ ]{3,30}" class="bg-secondary-subtle form-control" value="<?= $_SESSION['dataUsuario']['nombre']; ?>" id="nombres" name="nombres" readOnly="true" maxlength="30">
                            </div>
                          </div>
                          <div class="col-12 col-sm-6 col-md-6 col-lg-6 mb-3">
                            <div class="form-group">
                              <label class="control-label text-black">Apellidos</label>
                                <input type="text" pattern="[A-Za-zÁÉÍÚÓáéíóúñÑ ]{3,30}" class="bg-secondary-subtle form-control" value="<?= $_SESSION['dataUsuario']['apellido']; ?>" id="apellido" name="apellido" readOnly="true" maxlength="30">
                            </div>
                          </div>
                          <div class="col-12 col-sm-6 col-md-6 col-lg-6 mb-3">
                            <div class="form-group">
                              <label class="control-label">Correo</label>
                              <input type="email" pattern="[A-Za-zÁÉÍÚÓáéíóúñÑ\@\.\0-9]{3,30}" class="bg-secondary-subtle form-control" value="<?= $_SESSION['dataUsuario']['correo']; ?>" id="email" name="email" readOnly="true" maxlength="30">
                            </div>
                          </div>
                          <div class="col-12 col-sm-6 col-md-6 col-lg-6 mb-3">
                              <div class="form-group">
                                  <label class="control-label text-black">Teléfono</label>
                                  <input type="text" pattern="[0-9]{11}" class="bg-secondary-subtle form-control" value="<?= $_SESSION['dataUsuario']['telefono']; ?>" id="telefono" name="telefono"  readOnly="true" maxlength="11">
                              </div>
                          </div>
                          <div class="col-12 col-sm-6 col-md-6 col-lg-6 mb-3">
                              <div class="form-group">
                                  <label class="control-label">Dirección</label>
                                  <input type="text" maxlength="250" required="" placeholder="Ingrese la Dirección" value="<?= $_SESSION['dataUsuario']["direccion"]  ?>" class="bg-secondary-subtle form-control" readOnly="true" id="direccion" name="direccion">
                              </div>
                          </div>
                          
                          <div class="col-12 mb-3 text-center d-flex justify-content-end">
                              <button type="button" modal='modificarInfoPersonalUsuario' class="btn_modal btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#modal">
                                <i class='bi bi-person-circle'></i> Actualizar Información
                              </button>
                          </div>
                      </fieldset>

                      <hr>
                      <fieldset class="row mb-4">
                        <div class="col-12 col-md-6 mb-3">
                          <legend><i class="bi bi-person-circle"></i> &nbsp; Datos de la Cuenta</legend>
                          <div class="col-12 mb-3">
                            <div class="form-group">
                                <label class="control-label">Nombre de Usuario</label>
                                <input type="text" pattern="[A-Za-zÁÉÍÚÓáéíóúñÑ\@\.\0-9]{3,30}" class="bg-secondary-subtle form-control" value="<?= modeloPrincipal::ocultar_info($_SESSION['dataUsuario']['correo']); ?>" id="nombre_usuario" name="nombre_usuario" readOnly="true" maxlength="30">
                            </div>
                          </div>
                          <div class="col-12 mb-3">
                              <div class="form-group">
                                  <label class="control-label">Tipo de Usuario</label>
                                  <input type="text" pattern="[A-Za-zÁÉÍÚÓáéíóúñÑ]{3,30}" class="bg-secondary-subtle form-control" value="<?= $_SESSION['nombreRolUsuario'] ?>" id="tipo_usuario" name="tipo_usuario" readOnly="true" maxlength="30">
                              </div>
                          </div>
                          <div class="col-12 mb-2 text-center d-flex justify-content-center">
                              <button type="submit" modal='passwordUser' class="btn_modal btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#modal">
                                <i class='bi bi-key'></i>
                                Actualizar Contraseña
                              </button>
                          </div>
                        </div>

                        <div class="col-12 col-md-6 mb-3 d-inline-block justify-content-center align-items-center">
                            <h5 class="mb-3"><i class="bi bi-shield-fill"></i> &nbsp; Actualizar Preguntas de Seguridad</h5>
                              
                            <div class="col-12 mb-2 text-center">
                                <button modal="preguntasSeguridad" class="btn_modal btn btn-success" data-bs-toggle="modal" data-bs-target="#modal">
                                  <i class="bi bi-shield"></i> Actualizar Preguntas y Respuestas
                                </button>
                            </div>
                        </div>

                      </fieldset>

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
    
      if($_SESSION['dataUsuario']['primerInicio'] == '1'){
        echo "<script type='text/javascript'>
                setTimeout(() => {
                  Swal.fire({
                      title: '¡Atención!',
                      text: 'Es su primer inicio de sesión, por favor cambie su contraseña y sus preguntas de seguridad.',
                      icon: 'warning',
                      confirmButtonColor: '#10478e',
                      confirmButtonText: 'Aceptar'
                  });
                }, 300);
            </script>";
      }
      
      model_user::validar_sesion_activa($id_usuario);

      config_model::verificar_actualizacion_configuracion(1); 

    ?>
  </body>
</html>