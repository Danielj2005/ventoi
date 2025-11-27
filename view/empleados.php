<?php 
session_start();
require_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

$id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario
// validación para verificar que el usuario inicio sesion de manera correcta
model_user::verificar_intento_de_acceso_al_sistema();

include_once "../include/verificacion_primer_inicio_usuario.php";

$usuarios = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['usuario']);

// se guardan los permisos del rol del usuario que inició sesión
$r_empleado = modeloPrincipal::verificar_permisos_requeridos(['r_empleado']);
$m_empleado = modeloPrincipal::verificar_permisos_requeridos(['m_empleado']);
$l_empleado = modeloPrincipal::verificar_permisos_requeridos(['l_empleado']);

// se evalua que este rol tenga el acceso a esta vista
if ($usuarios) {  ?>

	<!DOCTYPE html>
	<html lang="en">
		<head>
			<!-- titulo -->
			<title>Empleados</title>
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
					<h1 class="my-3">Empleados</h1>
				</div>

				<section class="section dashboard">
					<div class="card">
						
						<div class="row text-center p-2 justify-content-center">
							<?php if ($r_empleado == '1' && $l_empleado == '1'): ?>

								<div class="col-12 mb-3 row m-0 col-md-6">
									<button id="btn_register" onclick="toggle()" class="col-12 btn btn-success">
										<i class="bi bi-plus-circle"></i>
										Registrar un Empleado
									</button>
								</div>
								
							<?php endif; ?>

							<div class="col-12 mb-3 <?= $r_empleado == 1 && $l_empleado == 0 ? 'col-md-12' : 'col-md-6'; ?>">
								<a class="col-12 btn btn-secondary" target="_blank" href="./reportes/lista_empleados.php">
									<i class="bi bi-file-text"></i>
									<span>Exportar Lista (.PDF)</span>
								</a>
							</div>
						</div>

						<hr>

						<div class="card-body pb-3">
								
							<?php if ($l_empleado == 1) { ?> 
								<div class="hidden">
									<h5 class="card-title">Lista de Empleados</h5>

									<div class="table-responsive">
										<table class="table datatable table-striped table-borderless table-hover " id="example">
											<thead>
												<tr>
													<th class="text-center col" scope="col">#</th>
													<th class="text-center col" scope="col">Cédula</th>
													<th class="text-center col" scope="col">Nombre y Apellido</th>
													<th class="text-center col" scope="col">Teléfono</th>
													<?php if ($m_empleado == '1'): ?>
														<th scope="col" class="text-center col">Modificar</th>
														<th scope="col" class="text-center col">Estado</th>
													<?php endif; ?>
												</tr>
											</thead>
											<tbody>
												<?php model_user::lista_de_usuarios(); ?>  
											</tbody>
										</table>
									</div>
								</div>
							<?php } ?>

							<?php if ($r_empleado == 1): ?> 
								<div class="hidden <?= $l_empleado == 1 ? 'd-none' : ''; ?>">
									<h5 class="card-title">Registro de Empleados</h5>

									<form id="registro_empleado" autocomplete="off" action="../controlador/usuario_controller.php" method="post" class="SendFormAjax" data-type-form="save">
										<input type="hidden" name="modulo" value="Guardar">
										<div class="row">

											<div class="mb-3 col-sm-6">
												<label class="control-label">Cédula <span style="color:#f00;">*</span></label>
												<div class="input-group">
													<select name="nacionalidad" class="form-select-sm col-sm-3 input-group-text" aria-label="Default select example">
														<option name="nacionalidad" value="V-">V</option>
														<option name="nacionalidad" value="E-">E</option>
													</select>
													<input class="form-control" required pattern="[0-9]{7,8}" type="text" name="cedula" id="cedula" maxlength="8" placeholder="Ingrese la Cédula">
												</div>
											</div>
									
											<div class="mb-3 col-sm-6 ">
												<label class="control-label">Nombre <span style="color:#f00;">*</span></label>
												<input form="registro_empleado" type="text" pattern="[A-Za-zñÑÁÉÍÚÓáéíóú ]{4,100}" maxlength="100" required="" placeholder="Ingresa el Nombre" class="form-control" id="nombre" name="nombre">
											</div>

											<div class="mb-3 col-sm-6 label-floathing form-group">
												<label class="control-label">Apellido <span style="color:#f00;">*</span></label>
												<input form="registro_empleado" type="text" pattern="[A-Za-zñÑÁÉÍÚÓáéíóú ]{4,100}" maxlength="100" required="" placeholder="Ingrese el Apellido" class="form-control" id="apellido" name="apellido">
											</div>

											<div class="mb-3 col-sm-6 label-floathing form-group">
												<label class="control-label">Correo <span style="color:#f00;">*</span></label>
												<input form="registro_empleado" type="text" pattern="[A-Za-zÁÉÍÚÓáéíóúñÑ@.0-9]{11,200}" maxlength="200" required="" placeholder="Ingrese el Correo" class="form-control" id="correo" name="correo">
											</div>

											<div class="mb-3 col-sm-6 label-floathing form-group">
												<label class="control-label">Teléfono <span style="color:#f00;">*</span></label>
												<input form="registro_empleado" type="text" pattern="[0-9]{11}" maxlength="11" required="" placeholder="Ingrese el Teléfono" class="form-control" id="telefono" name="telefono">
											</div>
											
											<div class="mb-3 col-sm-6 label-floathing form-group">
												<label class="control-label">Dirección <span style="color:#f00;">*</span></label>
												<input form="registro_empleado" type="text" maxlength="250" required="" placeholder="Ingrese la Dirección" class="form-control" id="direccion" name="direccion">
											</div>

											<div class="mb-3 col-sm-12 label-floathing">
												<div class="form-group">
													<label class="control-label">Tipo de Usuario <span style="color:#f00;">*</span></label>
													<select  class="form-select" name="id_tipo" id="id_tipo">
														<option disabled="disabled" selected="true" class="form-control" >selecciona una opción</option>
														<?php rol_model::select_options_nombres_roles(); ?>  
													</select>
												</div>
											</div>
										</div>
										<div class="col-12 mb-1">
											<div class="form-group">
												<p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
											</div>
										</div>
										<div class="col-12 mb-1 justify-content-center text-center">
											<button form="registro_empleado" type="submit" class="btn btn-success bi bi-plus">&nbsp;Registrar</button>
										</div>
									</form>
									
								</div>
							<?php endif; ?>
						</div>
					</div>
				</section>
			</main>
			
			<script>
				
				// funcion para mostrar y ocultar elementos en empleados
				const titlex = ['Registrar un Empleado','Ver lista de empleados registrados'];
				const btnToggle = document.getElementById('btn_register');

				const toggle = ()=>{
					btnToggle.classList.toggle('bi-list-columns-reverse');
					btnToggle.classList.toggle('btn-success');
					btnToggle.classList.toggle('bi-person-plus');
					btnToggle.classList.toggle('btn-secondary');
					btnToggle.textContent = btnToggle.textContent.trim() == titlex[0] ? ' '+titlex[1] : ' '+titlex[0];

					const hiddenElements = document.querySelectorAll('.hidden');
					hiddenElements.forEach(element => {
						element.classList.toggle('d-none');
					});
				};
			</script>
			<?php
                include_once "./modal/plantillaModalCustom.php"; 

                modalCustom();
			
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
	bitacora::intento_de_acceso_a_vista_sin_permisos("lista de empleados");
}