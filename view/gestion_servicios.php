<?php 
session_start();

require_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

$id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario
// validación para verificar que el usuario inicio sesion de manera correcta
model_user::verificar_intento_de_acceso_al_sistema();

include_once "../include/verificacion_primer_inicio_usuario.php"; // se incluyen los modelos necesarios para la vista

$permiso_servicios = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['servicio']);

// se guardan los permisos del rol del usuario que inició sesión
$r_servicio = modeloPrincipal::verificar_permisos_requeridos(['r_servicio']);
$l_servicio = modeloPrincipal::verificar_permisos_requeridos(['l_servicio']);
$m_servicio = modeloPrincipal::verificar_permisos_requeridos(['m_servicio']);

if ($permiso_servicios) { ?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<!-- titulo -->
			<title>Gestión de Servicios</title>

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
					<a href="./" class="btn btn-outline-secondary shadow-sm mb-3">
						<i class="bi bi-chevron-left"></i> 
						<span>Volver al Panel Principal</span>
					</a>

					<h1 class="titulosH my-2">Gestión de Servicios</h1> 
				</div>

				<section class="section dashboard">
					<div class="row">
						<div class="col-12">
							<div class="card top-selling">

								<?php if ($r_servicio == 1 && $l_servicio == 1) : ?>

									<div class="row p-2 text-center">
										<div class="col-12 col-sm-12 col-md-12 ">
											<button id="btn_register" type="button" onclick="toggle()" class="w-100 shadow-sm btn btn-success">
												<i class="bi bi-plus-circle"></i> 
												<span>Registrar Nuevo Servicio</span>
											</button>
										</div>
									</div>
									<hr>

								<?php endif; ?>
								
								<div class="card-body pb-3">

									<?php if ($l_servicio == 1) : ?>

										<div class="hidden">
											<h5 class="card-title">Lista de servicios</h5>
											<table class="table table-striped table-responsive datatable" id="example">
												<thead>
													<tr>
														<th class="col text-center"scope="col">#</th>
														<th class="col text-center"scope="col">Nombre</th>
														<th class="col text-center"scope="col">Precio de Venta ($)</th>
														<th class="col text-center" scope="col">Detalles</th>
	
														<?php if ($m_servicio == 1) : ?>
															<th class="col text-center" scope="col">Modificar</th>
															<th class="col text-center" scope="col">Estado</th>
														<?php endif; ?>
													</tr>
												</thead>

												<tbody> <?php servicio_model::lista(); ?> </tbody>
											</table>
										</div>

									<?php endif; if ($r_servicio == 1) : ?>

										<div class="hidden <?= $l_servicio == 1 ? 'd-none' : ''; ?>">

											<h3 class="text-center my-1 p-3">Registro de Servicios</h3>

											<form method="post" action="../controlador/servicio_controlador.php" class="SendFormAjax" autocomplete="off" data-type-form="save">
												<input type="hidden" name="dolar" id="precioDolar" value="<?= $precio_dolar_actual; ?>">
												<input type="hidden" name="modulo" value="Guardar">
	
												<div class="col-12 mb-1">
													<h5 class="card-title"> Datos del Servicio </h5>
													<div class="row mt-2">
														<div class="col-md-6">
															<label class="form-label">Nombre del Servicio <span style="color:#f00;">*</span> </label>
															<div class="col-md-4 input-group">
																<input type="text" class="form-control" placeholder="ingresa el nombre del servicio" name="nombre_platillo" id="nombre_platillo" required>
															</div>
														</div>
																
														<div class="col-md-6">
															<label class="form-label">Descripción <span style="color:#f00;">*</span> </label>
															<input type="text" class="form-control" placeholder="ingresa la descripción" id="descripcion" name="descripcion" required>
														</div>
													</div>
												</div>
												
												<div class="col-12 mb-3 row m-0">
													<h5 class="card-title">Productos del Servicio</h5>
													<div class="col-12 col-sm-12 col-md-9">
														<select name="producto" id="producto_id" class="form-select select">
															<option value="" selected>seleccione una opción</option>
															<?php producto_model::options("1"); ?>
														</select>
													</div>
													<div class="col-12 col-sm-12 col-md-3">
														<button type="button" name="btn_producto" class="btn_add btn btn-success">
															<i class="bi bi-plus-circle me-2"></i> 
															<span>Añadir producto</span>
														</button>
													</div>
												</div>
	
												<div class="col-12 mb-3">
													<div class="table-responsive">
														<table class="table table-striped table-hover table-sm">
															<thead>
																<tr>
																	<th class="col text-start" scope="col">Producto</th>
																	<th class="col text-center" scope="col">Cantidad a Agregar al Servicio</th>
																	<th class="col text-center" scope="col">Quitar</th>
																</tr>
															</thead>
															<tbody id="lista_producto"> </tbody>
														</table>
													</div>
												</div>
	
												<div class="col-12 mb-1 mt-1"> 
													<h5 class="card-title fw-bold mb-3">
														<i class="bi bi-currency-dollar"></i> 
														Definición del Precio de Venta
													</h5>
	
													<div class="row g-3">
														<div class="col-12 col-sm-6 text-start">
															<label class="form-label">
																Precio de Venta (USD) 
																<span class="text-danger">*</span>
															</label>

															<input 
																type="text" 
																class="form-control text-end" 
																id="precio_dolar_servicio" 
																name="precio_dolar" 
																placeholder="0.00" 
																step="0.01" 
																min="0.01" 
																required
															>
														</div>
														<div class="col-12 col-sm-6 text-start">
															<label for="precio_bolivar_servcio" class="form-label">
																Costo Estimado (VES)
															</label>
															<input 
																type="text" 
																class="form-control text-end bg-light fw-bold" 
																readonly 
																id="precio_bolivar_servcio" 
																name="precio_bolivar" 
																placeholder="0.00"
																value=""
																title="Este campo se calcula automáticamente en base a la tasa de cambio."
															>
														</div>
													</div>
												</div>

												<div class="col-12 mb-3 mt-3">
													<p class="text-muted small fs-5">
														<span class="text-danger">*</span> Indica que el campo es obligatorio.
													</p>
												</div>
												
												<div class="col-12 text-center">
													<button type="submit" class="btn btn-success">
														<i class="bi bi-save me-2"></i>
														<span>Registrar Servicio</span>
													</button>
												</div>
											</form>
										</div>

									<?php endif; ?>
								</div>
							</div>
						</div>
				</div>
				</section>

			</main>
		
			<script>
				// funcion para agregar mas productos al modificar un servicio
				const addProductOnService = () => {
					$.ajax({
						data: '',
						url:  "../include/tr_producto_modificar_servicio.php",
						type:  'post',
						success:function(valores){
							$(`#tableModifyService`).append(valores);
							btn_selectores_in_modal();
						},
						error: function(){
							Swal.fire("ocurrio un error!","la solicitud no pudo ser procesada","error");
						}
					});
				};
				// funcionalidad para calcular automaticamente el precio en bs de un servicio en base a la tasa del dia
				const input_dolar = document.getElementById('precio_dolar_servicio');
				const input_bs = document.getElementById('precio_bolivar_servcio');
				input_dolar.addEventListener('keyup',(e) => {
					e.preventDefault();
					let tasa = document.getElementById('tasa_dolar').textContent;
					tasa = parseFloat(tasa);
					input_bs.value = (input_dolar.value * tasa).toFixed(2);
				});
				
				// funcion para mostrar y ocultar elementos en proveedores
				const titlex = ['Registrar un nuevo servicio','Ver lista de servicios registrados'];
				const btnToggle = document.getElementById('btn_register');

				const toggle = ()=>{
					btnToggle.classList.toggle('bi-list-columns-reverse');
					btnToggle.classList.toggle('btn-secondary');
					btnToggle.classList.toggle('bi-plus');
					btnToggle.classList.toggle('btn-success');
					btnToggle.textContent = btnToggle.textContent.trim() == titlex[0] ? ' '+titlex[1] : ' '+titlex[0];
					
					const hiddenElements = document.querySelectorAll('.hidden');
					hiddenElements.forEach(element => {
						element.classList.toggle('d-none');
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
	bitacora::intento_de_acceso_a_vista_sin_permisos("lista de servicios");
}