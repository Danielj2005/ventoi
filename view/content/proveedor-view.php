<?php 
session_start();

// importacion de la conexion a la base de datos y al modelo principal

include_once "./view/inc/models.php"; // se incluyen los modelos necesarios para la vista

$id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario
// validación para verificar que el usuario inicio sesion de manera correcta
model_user::verificar_intento_de_acceso_al_sistema();

include_once "./view/inc/verificacion_primer_inicio_usuario.php"; // se incluyen los modelos necesarios para la vista

$fecha_actual = date('Y-m-d'); // se guarda la fecha actual para su posterior uso

// se guardan los permisos del rol del usuario que inició sesión
$proveedores = modeloPrincipal::verificar_permisos_requeridos($_SESSION['permisosRequeridos']['proveedor']);
$r_proveedores = modeloPrincipal::verificar_permisos_requeridos(['r_proveedores']);
$l_proveedores = modeloPrincipal::verificar_permisos_requeridos(['l_proveedores']);
$m_proveedores = modeloPrincipal::verificar_permisos_requeridos(['m_proveedores']);
$h_proveedores = modeloPrincipal::verificar_permisos_requeridos(['h_proveedores']);

// se evalua que este rol tenga el acceso a esta vista
if ($proveedores) {  ?>
    
    <!DOCTYPE html>
    <html lang="<?= LANG ?>">
        <head>
            <?php 
                // <!-- metadatos -->  
                include_once "./view/inc/meta.php";
                include_once "./view/inc/css_links.php";
            ?>
        </head>
    <body class="bg-dark-subtle" data-bs-theme="dark">
        <?php 
            // se incluye el header / encabezado a la vista
            include_once "./view/inc/header.php";
            // se incluye el menu lateral a la vista 
            include_once "./view/inc/sidebar.php";
        ?>
        <input type="hidden" id="fecha_actual" name="fecha_actual" value="<?= $fecha_actual ?>">
        <main id="main" class="main">
            <div class="pagetitle row">
                <div class="col-12 col-sm-12 col-md-12 mb-4">
                    <a href="./" class="btn btn-outline-secondary shadow-sm mb-3">
                        <i class="bi bi-chevron-left"></i> 
                        <span>Volver al Panel Principal</span>
                    </a>
                    <h1 class="mt-3 text-white">Gestión de Proveedores</h1>
                </div>
            </div>

            <section class="section dashboard">
                <div class="row">
                    <div class="col-12">
                        <div class="card top-selling">
                            <div class="row p-2 text-center">

                                <?php if ($r_proveedores == 1 && $l_proveedores == 1 ): ?>
                                    <div id="btn_register_container" class="col-12 col-sm-12 mb-3 row m-0 col-md-6">
                                        <button id="btn-toggle" onclick="toggle()" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nuevo Proveedor </button>
                                    </div>
                                <?php endif; ?>

                                <div class="col-12 col-sm-12 mb-3 row m-0 <?= $r_proveedores == 1 && $l_proveedores == 0 ? 'col-md-12' : 'col-md-6'; ?>">
                                    <a class="col-12 btn btn-secondary" target="_blank" href="./view/reportes/lista_proveedores.php">
                                        <i class="bi bi-file-earmark-arrow-down"></i>
                                        <span>Exportar Lista (.PDF)</span>
                                    </a>
                                </div>
                            </div>

                            <hr>

                            <div class="card-body pb-0">

                                <?php if ($l_proveedores == '1'): ?>

                                    <div id="tableListSupliers" class=" table table-responsive">
                                        <h5 class="card-title text-white">Listado de Proveedores Registrados</h5>
                                        <table class="table table-borderless table-striped example" id="example">
                                            <thead>
                                                <tr class="bg-dark-subtle">
                                                    <th class="col text-center" scope="col">#</th>
                                                    <th class="col text-center" scope="col">RIF / Identificación</th>
                                                    <th class="col text-center" scope="col">Razón Social</th>
                                                    <th class="col text-center" scope="col">Ver Detalles</th>
                                                    <?php if ($m_proveedores == '1'): ?>
                                                        <th class="col text-center" scope="col" class="text-center">Modificar</th>
                                                    <?php endif; if ($h_proveedores == '1'): ?>
                                                        <th class="col text-center" scope="col" class="text-center">Historial de Compras</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody> <?php proveedor_model::lista_proveedores_registrados(); ?> </tbody>
                                        </table>
                                    </div>

                                <?php endif; if ($r_proveedores == '1'): ?>

                                    <div id="tableRegisterSupliers" class=" <?= $l_proveedores == 1 ? 'd-none' : ''; ?>">
                                        <h5 class="card-title text-white">Nuevo Proveedor</h5>

                                        <form id="formularioRegistrar" action="../controller/proveedor_controller.php" method="post" class="SendFormAjax row" autocomplete="off" data-type-form="save">
                                            
                                            <input type="hidden" name="modulo" value="Guardar">
                                            <div class="col-12 col-sm-12 col-md-6 mb-3">
                                                <label class="form-label">Cédula / RIF <span style="color:#f00;">*</span></label>
                                                <div class="col-md-4 input-group">
                                                    <select class="input-group-text" id="nacionalidad" name="nacionalidad" required>
                                                        <option value="V-">V</option>
                                                        <option value="E-">E</option>
                                                        <option value="G-">G</option>
                                                        <option value="J-">J</option>
                                                        <option value="P-">P</option>
                                                        <option value="R-">RIF</option>
                                                    </select>
                                                    <input type="text" class="form-control" pattern="[0-9]{7,8}" minlength="6" maxlength="8" placeholder="ingresa la cédula / RIF" name="cedula" id="cedula" required>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-6 mb-3">
                                                <label for="validationDefault02" class="form-label">Nombre <span style="color:#f00;">*</span></label>
                                                <input type="text" class="form-control"  placeholder="ingresa el nombre" id="nombre_proveedor" name="nombre_proveedor" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-6 mb-3">
                                                <label for="validationDefault02" class="form-label">Correo <span style="color:#f00;">*</span></label>
                                                <input type="text" class="form-control" placeholder="ingresa el correo" id="correo" name="correo" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-6 mb-3">
                                                <label for="validationDefault05" class="form-label">Teléfono <span style="color:#f00;">*</span></label>
                                                <input type="text" class="form-control" maxlength="11" name="telefono" placeholder="ingresa el teléfono" id="telefono" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 mb-3">
                                                <label for="validationDefault03" class="form-label">Dirección <span style="color:#f00;">*</span></label>
                                                <input type="text" class="form-control" name="direccion" placeholder="ingresa la dirección" id="direccion" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 mb-3 text-center">
                                                <div class="text-start"> <p>Los campos con <span style="color:#f00;">*</span> son obligatorios</p> </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-12 col-md-12 mb-3 text-center">
                                                <button type="submit" form="formularioRegistrar" class="btn btn-success bi bi-plus">&nbsp;Registrar</button>
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

        <?php 
            include_once "./view/inc/plantillaModalCustom.php";
            include_once "./view/inc/footer.php";
            include_once "./view/inc/script.php";
            
            model_user::validar_sesion_activa($id_usuario);
            
            config_model::verificar_actualizacion_configuracion();
        ?>
        <script>
            // funcion para mostrar y ocultar elementos en proveedores
                    
            const toggle = ()=> {
            const btnToggle = document.getElementById('btn-toggle');
            btnToggle.classList.toggle('btn-success');
            btnToggle.classList.toggle('btn-secondary');

            const titleBtn = [
                '<i class="bi bi-plus-circle"></i> Nuevo Proveedor ',
                `<i class="bi bi-list-columns-reverse"></i> Lista de Proveedores `
            ];

            btnToggle.innerHTML = btnToggle.innerHTML == titleBtn[0] ? titleBtn[1] : titleBtn[0];

            document.getElementById('tableRegisterSupliers').classList.toggle('d-none');
            document.getElementById('tableListSupliers').classList.toggle('d-none');

            document.querySelectorAll('.tableRegisterSupliers').forEach(element => {
                element.classList.toggle('d-none');
            });
            document.querySelectorAll('.setCol').forEach(element => {
                element.classList.toggle('col-md-6');
            });
            };
            // Esta funcionalidad se encarga de mostrar un boton [const btnReportesFechas = document.getElementById('btnReportesFechas');]
            // para generar un reporte por fechas de las entradas registradas en el sistema
            function validateDate (id) {
                
                const btnReportesFechas = document.getElementById('btnReportesFechas_'+id);

                const msjDate = document.querySelector('.showThis_'+id);
                const dateToday = document.getElementById('fecha_actual').value;
                const fechaReporteInicio = document.getElementById(`fechaReporteInicio_${id}`).value;
                const fechaReporteFin = document.getElementById(`fechaReporteFin_${id}`).value;
                
                if (fechaReporteInicio != "" && fechaReporteFin != "") {

                    if (fechaReporteInicio > fechaReporteFin || fechaReporteInicio > dateToday || fechaReporteFin > dateToday) {
                        msjDate.classList.contains('d-none') ? msjDate.classList.remove('d-none') : '';
                        btnReportesFechas.classList.contains('d-none') ? '' : btnReportesFechas.classList.add('d-none');
                    }else{
                        msjDate.classList.contains('d-none') ? '' : msjDate.classList.add('d-none');
                        btnReportesFechas.classList.contains('d-none') ? btnReportesFechas.classList.remove('d-none') : btnReportesFechas.classList.add('d-none');
                    }
                    // Esta funcionalidad se encarga de resetear el input de las fechas seleccionadas para el reporte de entradas
                    // y también se encarga de ocultar nuevamente el boton de generar reporte.
                    btnReportesFechas.addEventListener('click', ()=>{
                        setTimeout(() => {
                            document.getElementById(`fechaReporteInicio_${id}`).value = '';
                            btnReportesFechas.classList.add('d-none');
                        }, 2000);
                    });
                }
            };
        </script>
        </body>
    </html>
<?php }else{
    // se registran las acciones del usuario en la bitacora y es redirijido al inicio
    bitacora::intento_de_acceso_a_vista_sin_permisos("lista de proveedores");
}