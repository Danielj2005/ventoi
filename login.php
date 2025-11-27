<?php 
session_start();
include_once "./modelo/modeloPrincipal.php"; // se incluye el modelo principal
include_once "./modelo/configuracion_model.php"; // se incluye el modelo de configuracion

$_SESSION["intentos_sesion"] = 1;

$_SESSION['numero_1'] = rand(1, 8);
$_SESSION['numero_2'] = rand(1, 5);

$_SESSION['captcha'] = $_SESSION['numero_1'] + $_SESSION['numero_2'];

// se obtiene la Cantidad de Preguntas de Seguridad que tiene el sistema
$CPS = intval(mysqli_fetch_array(config_model::consultar("c_preguntas"))['c_preguntas']); 
// $CPS = intval($CPS['c_preguntas']); 
$CPS = rand(1, $CPS);

$_SESSION['ARC'] = 'ecDAuKiplp8=';
$_SESSION['CPS'] = $CPS;

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Sistema de Control de Inventario y Venta">
		<meta name="keywords" content="Sistema de Control de Inventario, Sistema de Control de Venta">
		<meta name="author" content="DANIEL BARRUETA - MANUEL TORREZ - LUISA SALAS - ANGEL ALIBARDI">

		<title>Sistema de Control de Inventario y Venta</title>

		<link rel="shortcut icon" href="vista/img/favion.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="vista/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="vista/css/bootstrap-icons.css">
		<link rel="stylesheet" type="text/css" href="vista/css/sweetalert2.min.css">
		<link rel="stylesheet" type="text/css" href="vista/css/login.css">
	</head>
	<body class="" data-bs-theme="dark">
		<div class="justify-content-center">

			<!-- fomulario de inicio de sesión -->
			<div class="vh-100 d-flex justify-content-center align-items-center">
				<div class="col-12 col-sm-10 col-md-6 col-lg-4 glassmorph rounded-4 p-4 my-3 mx-auto">
					<form method="POST" action="controlador/login.php" class="SendFormAjax" data-type-form="load">
						<div class="row">

							<div class="col-12 text-center mb-4">
								<h3 class="fw-bold">Acceso al Sistema</h3>
							</div>


							<div class="col-12 mb-3">
								<label for="correo" class="form-label fw-bold">Correo Electrónico</label>
								<div class="input-group shadow-sm">
									<span class="input-group-text">
										<i class="bi bi-envelope"></i> </span>
									<input 
										type="email" 
										class="form-control" 
										id="correo" 
										name="correo" 
										placeholder="ejemplo@dominio.com" 
										aria-label="Correo Electrónico"
										required 
										autocomplete="email" 
									>
								</div>
							</div>

							<div class="col-12 mb-3">
								<label for="pswd" class="form-label fw-bold">Contraseña</label>
								<div class="input-group shadow-sm">
									<span class="input-group-text">
										<i class="bi bi-lock-fill"></i> </span>
									<input 
										type="password" 
										class="form-control" 
										id="pswd" 
										name="contraseña" 
										placeholder="Ingresa tu contraseña"
										aria-label="Contraseña" 
										required
										autocomplete="current-password"
									>
									<button 
										id="btnEyeIcon" 
										type="button" 
										class="input-group-text btn btn-secondary" 
										title="Mostrar contraseña"
										onclick="show_password('eyeIcon', 'pswd')"
									>
										<i class="bi bi-eye" id="eyeIcon"></i> </button>
								</div>
							</div>

							<div class="col-12 mb-4">
								<div style="max-width: 300px;" class="card bg-transparent text-white border-0 mx-auto">
									<div class="card-body p-0">
										<label for="respuesta_captcha" class="form-label text-center d-block fw-bold mb-2">
											¿Cuánto es <?= $_SESSION['numero_1'] ?> + <?= $_SESSION['numero_2'] ?>?
										</label>
										
										<input 
											placeholder="Ingresa la respuesta aquí" 
											type="number" 
											id="respuesta_captcha" 
											name="respuesta_captcha" 
											autocomplete="off" 
											min="1" 
											max="20" 
											pattern="[0-9]*" 
											required 
											class="form-control border-2 text-center rounded-3 shadow-sm"
										>
									</div>
								</div>
							</div>

							<div class="row col-12 mb-1 text-center justify-content-center">
								<div class="col-12 mb-3 text-center">
									<button type="submit" class="btn btn-primary mt-2">
										<i class="bi bi-box-arrow-in-right"></i>
										<span>Iniciar Sesión</span>
									</button>
								</div>

								<div class="col-12 text-center">
									<button 
										type="button" 
										class="btn btn-link text-white-50 p-0" 
										data-bs-toggle="modal" 
										data-bs-target="#recuperar_contraseña"
									>
										<i class="bi bi-key me-2"></i> 
										¿Olvidaste tu Contraseña?
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			
		</div>

		<div class="msjFormSend"></div>

		<!-- modal recuperar contraseña -->
		<div class="modal fade p-5" id="recuperar_contraseña" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<form method="post" action="./vista/recuperar_contraseña.php">
						<div class="modal-header">
							<h1 class="modal-title fs-3 " id="exampleModalLabel"> Recuperar acceso</h1>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="">
								<label class="mb-3 ">Correo <span class="text-danger"> * </span></label>
								<input type="text" class="input__field form-control" name="correo_recuperar_contraseña" id="correo_recuperar_contraseña" placeholder="ingresa tu correo">
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary" id="enviar">Enviar</button>
							<button type="button" class="btn btn-danger" data-bs-dismiss="modal">cancelar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<script type="text/javascript" src="vista/js/jquery-3.6.0.min.js"></script>
		<script type="text/javascript" src="vista/js/bootstrap.min.js"></script>
		<script src="vista/js/SendForm.js"></script>
		<!-- <script src="vista/js/sweet-alert.min.js"></script> -->
		<script src="vista/js/sweetalert2.min.js"></script>
		<script src="vista/js/hiddeInput.js"></script>
	</body>
</html>