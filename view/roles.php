<?php 
session_start();
include_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

// validación para verificar que el usuario inicio sesion de manera correcta
$id_usuario = $_SESSION['id_usuario'];
model_user::verificar_intento_de_acceso_al_sistema();

include_once "../include/verificacion_primer_inicio_usuario.php"; // se incluyen los modelos necesarios para la vista

$r_rol = modeloPrincipal::verificar_permisos_requeridos(["r_rol"]);
$m_rol = modeloPrincipal::verificar_permisos_requeridos(["m_rol"]);
$l_rol = modeloPrincipal::verificar_permisos_requeridos(["l_rol"]);

// se evalua que este rol tenga el acceso a esta vista
if ($m_rol || $l_rol) {  

	$estado = (!isset($_POST['estado_rol'])) ? '1' : $_POST['estado_rol'];

	$consulta = modeloPrincipal::consultar("SELECT id_rol, nombre, estado
		FROM rol WHERE id_rol != 1 AND estado = $estado");
?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<!-- titulo -->
			<title>Gestión de Roles</title>
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
					<h1 class="display-4 fw-bold mb-4 border-bottom pb-2">
						<i class="bi bi-person-lines-fill me-3 text-secondary"></i> 
						Gestión de Roles
					</h1>
				</div>

				<section class="section dashboard">
					<div class="card">
						<div class="row text-center p-2 align-items-center m-0">
							<?php if ($r_rol == '1' && $l_rol == '1'): ?>
								<div class="col-12 col-sm-12 col-md-6 mb-1">
									<a 
										class="col-12 btn btn-success" 
										href="./<?= $r_rol == 1 ? 'registrar_rol.php' : 'roles.php' ?>">
											<i class="bi bi-plus-circle"></i>
											Registrar un Nuevo Rol
									</a>
								</div>
							<?php endif; ?>

							<div class="col-12 mb-1 <?= $r_rol == 1 && $l_rol == 0 ? 'col-md-12' : 'col-md-6'; ?>">
								<form action="./roles.php" method="post">
									<input type="hidden" name="estado_rol" value="<?= ($estado == '0') ? "1" : "0"?>">
									<button type="submit" class="col-12 btn btn-secondary">
										<?= ($estado == '0') ? "Roles activos" : "Roles inactivos"?>
									</button>
								</form>
							</div>
						</div>

						<hr>

						<div class="card-body pb-1">
							<h5 class="card-title fw-bold d-flex align-items-center mb-3">
								<?php if ($estado == '1'): ?>
									<i class="bi bi-person-check-fill me-2 text-success"></i> 
									Lista de Roles Activos
								<?php else: ?>
									<i class="bi bi-person-x-fill me-2 text-danger"></i> 
									Lista de Roles Inactivos
								<?php endif; ?>
							</h5>
							<div class="table table-responsive">
								<table class="table datatable table-striped" id="example">
									<thead>
										<tr>
											<th class="text-center col" scope="col">Nº </th>
											<th class="text-center col" scope="col">Nombre</th>
											<th class="text-center col" scope="col">Ver Detalles</th>
											<?php if ($m_rol): ?>
												<th class="text-center col" scope="col">Modificar</th>
												<th class="text-center col" scope="col"><?= ($estado == '0') ? 'Activar' : 'Desactivar'; ?></th>
											<?php endif; ?>
										</tr>
									</thead>
									<tbody>
										<?php
											while($row = mysqli_fetch_assoc($consulta)) { ?>
												<tr>
													<th class="text-center col" scope="col"></th>
													<th class="text-center col" scope="col"><?= $row['nombre'] ?></th>
													<th class="text-center col" scope="col">
														<button 
															modal="rolDetalles" 
															class="btn_modal btn bi bi-eye btn-info" 
															value="<?= modeloPrincipal::encryptionId($row["id_rol"]); ?>" 
															data-bs-toggle="modal" 
															data-bs-target="#modal">
														</button>
													</th>
													<?php if ($m_rol == '1') { ?>
														<th class="text-center col" scope="col">
															<button 
																modal="rolModificar"
																data-bs-toggle="modal" 
																data-bs-target="#modal" 
																class="btn_modal btn btn-warning  <?= ICONO_MODIFICAR ?>" 
																value="<?= modeloPrincipal::encryptionId($row["id_rol"]); ?>">
															</button>
														</th>
														<th class="text-center col" scope="col">
															<form action="../controlador/rol.php" method="post" class="SendFormAjax" data-type-form="update_estate">
																<input name="modulo" type="hidden" value="<?= ($estado == '1') ? 'activo' : 'inactivo'; ?>">
																<input name="UIDR" type="hidden" value="<?= modeloPrincipal::encryptionId($row["id_rol"]); ?>">
																<button 
																	class="btn bi <?= ($row['estado'] == '0') ? 'bi-check-circle btn-success' : 'bi-x-circle btn-danger'; ?>" >
																</button>
															</form>
														</th>
													<?php } ?>
												</tr>
										<?php } ?>  
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</section>
			</main>
			
			<!-- se incluye el script para seleccionar las casillas de verificacion -->
			<script type="text/javascript" src="./js/funcion_seleccionar_casillas.js"></script>

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
<?php }else if ($r_rol && $m_rol == 0 && $l_rol == 0) { 
	header('Location: ./registrar_rol.php');
}else{
	// se registran las acciones del usuario en la bitacora y es redirijido al inicio
	bitacora::intento_de_acceso_a_vista_sin_permisos('lista de roles');
}