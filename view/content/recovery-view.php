<?php 
session_start();
// importacion de la conexion a la base de datos y al modelo de usuario

include_once "./view/inc/models.php"; // se incluyen los modelos necesarios para la vista

$valores["usuario_bloqueado"] = 0;

if (!isset($_SESSION['ARC']) || $_SESSION['ARC'] !== "ecDAuKiplp8=") {
	header('Location: login');
	exit();
}

// if (!isset($_POST['correo_recuperar_contraseña']) || $_POST['correo_recuperar_contraseña'] == "") {
// 	header("Location: login"); 
// 	exit();
// }

// se genera un Número aleatorio entre 1 y la cantidad de Preguntas de seguridad que tiene el sistema
$NP = intval(modeloprincipal::limpiar_cadena($_SESSION['CPS']));

$correo = modeloprincipal::limpiar_cadena($_POST['correo_recuperar_contraseña']); // se obtiene el correo del usuario que desea recuperar la contraseña

$datos_usuario = model_user::consulta_usuario_condicion("id_usuario, bloqueado","correo = '$correo'");


// se obtiene la configuracion de la base de datos
$configuracion = ['caracteres' => config_model::obtener_dato('c_caracteres'),
	'simbolos' => config_model::obtener_dato('c_simbolos'),
	'numeros' => config_model::obtener_dato('c_numeros')];


// se obtiene el resultado de la consulta y la guardamos en un array
$datos_usuario = mysqli_fetch_array($datos_usuario); 

$id_usuario = $datos_usuario['id_usuario'];

$preguntas = modeloPrincipal::consultar("SELECT pregunta 
	FROM seguridad AS S 
	INNER JOIN preguntas_secretas AS P ON P.id_pregunta = S.id_seguridad
	WHERE P.id_usuario = '$id_usuario' AND P.numero_pregunta = '$NP'");

?>
<!DOCTYPE html>
<html lang="<?= LANG ?>">
    <head>
        <?php 
            // <!-- metadatos -->  
            include_once "./view/inc/meta.php";
        ?>

        <link href="<?= SERVERURL; ?>view/css/bootstrap-icons.css" rel="stylesheet">
        <link href="<?= SERVERURL; ?>view/css/sweetalert2.min.css" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

        <link href="<?= SERVERURL; ?>view/css/animate.min.css" rel="stylesheet">

        <style> 
            .logo{
                width : 70px !important;
                height : 70px !important;
            }
        </style>

        <link href="<?= SERVERURL; ?>view/css/indexStyle.css" rel="stylesheet">
        <link href="<?= SERVERURL; ?>view/css/login.css" rel="stylesheet">

		<script src="./view/js/jquery-3.6.0.min.js"></script>
		<script src="./view/js/bootstrap.min.js"></script>
		<script src="./view/js/SendForm.js"></script>
		<script src="./view/js/sweetalert2.min.js"></script>
		<script src="./view/js/recuperar_contraseña.js"></script>
		<script src="./view/js/hiddeInput.js"></script>

		<script>
			$(document).ready(function () {
				SendFormAjax();
			});
		</script>
	</head>
    <body id="page-top" class="index-page bg-dark-subtle" data-bs-theme="dark">
		<main id="main" class="main">

            <!-- Hero Section -->
            <section id="hero" class="hero section dark-background">
            
                <img src="<?= SERVERURL; ?>view/assets/img/wallpaper_I.webp" alt="">
            
                <div class="container animate__animated animate__fadeIn" id="container">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-12 col-md-6 mb-3">
                            <h2 class="d-flex align-items-center">VENTOI</h2>
                            <p class="fs-5">Simplifica la Gestión de Ventas e Inventario de tu Negocio. </p>
                        </div>
            
                        <div class="col-12 col-md-6 mb-3 d-flex justify-content-center">
            
                            <div id="container_logIn" class="mb-4 animate__animated animate__fadeIn glassmorph rounded-4">
            
                                <div class="p-3 justify-content-center align-items-center text-center">
                                    <div class="d-flex justify-content-end p-2 col-12">
                                        <a href="./" class="text-white btn-close btn-close-popUp" id="boton_cerrar_iniciar_sesion"></a>
                                    </div>
								
									<?php
										if (mysqli_num_rows($preguntas) < 1) : ?>
											<div class="text-center mb-5">
												<h5>Usuario sin preguntas!</h5>
											</div>
											<p class="mb-4 text-center" style="font-size: 1em; text-wrap: balance;">
												No tienes preguntas asignadas. Por favor, verifique que su usuario cuente con alguna pregunta.
											</p>
											<p class="mb-4 text-center" style="font-size: 1em; text-wrap: balance;">
												Si aún tiene problemas, Por favor contacte al administrador del sistema para restablecer el acceso.
											</p>


											<div class="text-center row justify-content-center">
												<div class="col-12 col-sm-12 col-md-6 col-lg-12 mb-4">
													<a href="./login" class="btn btn-danger bi bi-arrow-bar-left" title="Volver">&nbsp;Volver al inicio</a>
												</div>
											</div>

									<?php else: $pregunta = mysqli_fetch_array($preguntas); ?>

										<div class="text-center mb-5">
											<h2>Cambiar Contraseña</h2>
										</div>
									

										<div id="verificar_respuestas" class="w-100 text-center">
											<form id="form_respuestas" method="post" action="./controller/usuario/recuperar_contraseña.php" class="SendFormAjax" data-type-form="load">
												<p class="mb-4" style="font-size: 1em; text-wrap: balance;">Por favor, complete el siguiente formulario para cambiar su contraseña</p>
												
												<input form="form_respuestas" type="hidden" name="modulo" value="verificar_preguntas">
												<input form="form_respuestas" type="hidden" id="UUID" name="UUID" value="<?= modeloPrincipal::encryptionId($id_usuario); ?>">
												<input form="form_respuestas" type="hidden" id="NPU" name="NPU" value="<?= modeloPrincipal::encryptionId($NP); ?>">
												
												<div class="mb-4 text-start">
													<h6>Responde la pregunta de seguridad <span style="color:#f00;">*</span></h6>
													<p class="text-center" style="text-wrap: balance;"><strong><?= modeloPrincipal::decryption($pregunta['pregunta']); ?></strong></p>
													<input form="form_respuestas" class="form-control form-control-sm" type="text" id="respuesta_seguridad" name="respuesta_seguridad" placeholder="Ingresa tu respuesta" required pattern="[A-Za-zÁÉÍÚÓáéíóú ]{3,50}" maxlength="50" title="Respuesta.">
												</div>

												<div class="text-center row justify-content-center">									
													<div class="col-12 mb-4 text-start">
														<p>Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
													</div>

													<div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-4">
														<a href="../index.php" class="btn btn-danger bi bi-arrow-bar-left" title="Volver">&nbsp;Volver al inicio</a>
													</div>

													<div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-4">
														<button form="form_respuestas" type="submit" class="btn btn-success">&nbsp;Verificar</button>
													</div>
												</div>
											</form>
										</div>

										<div id="cambiar_contraseña" class="d-none">
											<div class="formulario text-center mb-3">
												<form method="post" action="./controller/usuario/recuperar_contraseña.php" class="SendFormAjax" data-type-form="update">
													<p>Escribe una contraseña nueva</p>
													<div class="text-start mb-4 position-relative" id="grupo__nueva_contraseña">
														<label class="mb-2">Nueva contraseña <span style="color: #f00;">*</span> </label>
														
														<input type="hidden" name="modulo" value="cambiar_contraseña">
														<input type="hidden" name="UUID" value="<?= modeloPrincipal::encryptionId($id_usuario); ?>">

														<div class="input-group mb-3">
															<span class="input-group-text bi bi-lock"></span>

															<input class="p-1 passw form-control" required="" placeholder="ingresa la nueva contraseña" autocomplete="off" pattern="[!@#$%A-Za-zñÑÁÉÍÚÓáéíóú0-9]{3,16}" type="password" name="nueva_contraseña" id="nueva_contraseña">
															
															<button type="button" onclick="show_password('eyeIconPassword', 'nueva_contraseña')" id="eyeIconPassword" class="input-group-text btn btn-secondary bi bi-eye"> </button>
														</div>
													</div>
													<p class="text-danger d-none input_error formulario__input-error__nueva_contraseña" style="width: 19em;">La contraseña debe tener entre 7 y 16 caracteres, al menos un dígito, al menos una minúscula, al menos una mayúscula y al menos un caracter no alfanumérico.</p>
													
													<div class="text-start mb-4 position-relative" id="grupo__repite_nueva_contraseña2">
														<label class="mb-2">Repita la contraseña <span style="color:#f00;">*</span></label>
														<div class="input-group mb-3">
															<span class="input-group-text bi bi-lock"></span>

															<input class="p-1 passw form-control" required="" placeholder="repite la contraseña" autocomplete="off" pattern="[!@#$%A-Za-zñÑÁÉÍÚÓáéíóú0-9]{3,16}" type="password" name="repite_nueva_contraseña2" id="repite_nueva_contraseña2">
															
															<button type="button" onclick="show_password('eyeIcon', 'repite_nueva_contraseña2')" id="eyeIcon" class="input-group-text btn btn-secondary bi bi-eye"> </button>
														</div>
													</div>
													<p class="text-danger d-none input_error formulario__input-error__repite_nueva_contraseña2" style="width: 19em;">Las contraseñas no coinciden.</p>
													
													<div class="form-group label-floating text-start">
											
														<p class="form-p alert-danger mb-2">los requisitos de seguridad para la  <span style="color:#f00;">contraseña</span> son:</p>
														<ul>
															<li>Puede contener al menos 1 número y 1 letra.</li>
															<li>Puede contener al menos <?= $configuracion['simbolos'] ?> de estos caracteres: !@#$%</li>
															<li>Debe tener entre <?= $configuracion['caracteres'] ?> y 60 caracteres.</li>
														</ul>
													</div>

													<div class="text-center mb-3 row justify-content-center">								
														
														<div class="col-12 mb-4 text-start">
															<p>Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
														</div>

														<div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-4">
															<a href="../index.php" class="btn btn-danger bi bi-arrow-bar-left" title="Volver"> Cancelar</a>
														</div>
														
														<div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-4">
															<button type="submit" class="btn btn-success text-black-hover text-white">Guardar</button>
														</div>
													</div>
												</form>
											</div>
										</div>
									<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            </section>
            <!-- /Hero Section -->
            
        </main>
		<div class="msjFormSend"></div>


        <script src="<?= SERVERURL; ?>view/js/jquery-3.6.0.min.js"></script>
        <script src="<?= SERVERURL; ?>view/js/sweetalert2.min.js"></script>

        <script src="<?= SERVERURL; ?>view/js/hiddenInput.js"></script>
        <script src="<?= SERVERURL; ?>view/js/login.js"></script>
		<script src="<?= SERVERURL; ?>view/js/SendForm.js"></script>
		<?php
			/** se verifica si el usuario esta bloqueado: * la cuenta es bloqueada luego de tres intentos fallidos de inicio de sesión */
			if ($datos_usuario["bloqueado"] == 1) {
				alert_model::alert_redirect("¡Cuenta bloqueada!","Su cuenta ha sido bloqueada debido a tres intentos fallidos de inicio de sesión, por favor contacte al administrador del sistema para restablecer el acceso.","warning","./vista/recuperar_contraseña.php");
				exit();
			}
		?>
	</body>
</html>
