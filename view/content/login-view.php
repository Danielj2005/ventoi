<?php 
session_start();

include_once "./model/modeloPrincipal.php"; // se incluye el modelo principal
include_once "./model/configuracion_model.php"; // se incluye el modelo de configuracion

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
<html lang="<?= LANG ?>">
    <head>
        <?php 
            // <!-- metadatos -->  
            include_once "./view/inc/meta.php";
        ?>
        <!-- Custom styles for this template-->

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

    </head>
    <body id="page-top" class="index-page bg-dark-subtle" data-bs-theme="dark">

        <main id="main" class="main">

            <!-- Hero Section -->
            <section id="hero" class="hero section dark-background">
            
                <img src="<?= SERVERURL; ?>view/assets/img/wallpaper_I.webp" alt="">
            
                <div class="container animate__animated animate__fadeIn d-none" id="container">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-12 col-md-6 mb-3">
                            <h2 class="d-flex align-items-center">VENTOI</h2>
                            <p class="fs-5">Simplifica la Gestión de Ventas e Inventario de tu Negocio. </p>
                        </div>
            
                        <div class="col-12 col-md-6 mb-3 d-flex justify-content-center">
            
                            <div id="container_logIn" class="col-12 mb-4 mt-5 animate__animated animate__fadeIn glassmorph rounded-4 w-auto">
            
                                <div class="p-3 justify-content-center align-items-center text-center">
                                    <div class="d-flex justify-content-end p-2 col-12">
                                        <a href="./" class="text-white btn-close btn-close-popUp" id="boton_cerrar_iniciar_sesion"></a>
                                    </div>
            
            
                                    <h3 class="text-white">Acceso al Sistema </h3>
                                    <hr class="border border-white w-100"></hr>
            
                                    <form class="SedFormAjax formulario p-0" autocomplete="off" 
                                        action="<?= SERVERURL; ?>controller/usuario/login.php" 
                                        data-type-form="load" 
                                        method="post" 
                                        id="form_logIn">
            
                                        <!-- cédula para iniciar sesion -->
                                        <div class="text-start mb-2" id="grupo__cedula_iniciar_sesion">
                                            <label class="mb-2 text-white">Cédula / RIF <span style="color:#f00;">*</span></label>
                                            <div class="input-group" id="grupo__cedula_iniciar_sesion">
                                                
                                                <select class="input-group-text" name="nation" id="nation" required>
                                                    <option value="V-">V</option>
                                                    <option value="E-">E</option>
                                                    <option value="J-">J</option>
                                                    <option value="P-">P</option>
                                                </select>
                                                
                                                <input class="form-control bg-dark boer-light text-white" type="text" id="dni_logIn" autocomplete="off" name="cedula_iniciar_sesion" placeholder="Ingresa tu Cédula / RIF" title="La cédula / RIF tiene que ser de 7 a 9 dígitos." required pattern="[0-9]{7,10}" min="7" maxlength="10" >
                                                <i class="input-group-text bi bi-person h3 m-0"></i>
                                            </div>
                                        </div>
                                        <p class="text-danger d-none input_error formulario__input-error__cedula_iniciar_sesion" style="width: 19em;">El Documento de Identidad debe tener entre 7 y 10 dígitos.</p>
            
                                        <!-- contraseña para iniciar sesion -->
                                        <div class="text-start mb-2" id="grupo__contraseña_inicio">
                                            <label class="mb-2 text-white">Contraseña <span style="color:#f00;">*</span></label>
                                            <div class="input-group" id="grupo__cedula_iniciar_sesion">
                                                <i class="input-group-text bi bi-lock h3 m-0"></i>

                                                <input 
                                                    class="form-control bg-dark text-light" 
                                                    autocomplete="off" 
                                                    class="contraseña" 
                                                    id="password_logIn" 
                                                    type="password" 
                                                    name="password_logIn" 
                                                    placeholder="Ingresa tu contraseña" 
                                                    title="Contraseña"
                                                    maxlength="60" 
                                                    required>

                                                <button 
                                                    id="btnEyeIcon" 
                                                    type="button" 
                                                    class="input-group-text btn btn-secondary" 
                                                    title="Mostrar contraseña"
                                                    onclick="show_password('eyeIcon', 'password_logIn')"
                                                >
                                                    <i class="bi bi-eye" id="eyeIcon"></i> </button>
                                            </div>
                                        </div>
                                        <p class="text-danger d-none input_error formulario__input-error__contraseña_inicio" style="width: 19em;">La contraseña debe tener entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula, al menos una mayúscula y al menos un caracter no alfanumérico.</p>
            
                                        <!-- Captcha de seguridad -->
            
                                        <div class="col-12 mb-2">
                                            <div style="max-width: 300px;" class="card bg-transparent text-white border-0 mx-auto">
                                                <div class="card-body p-0">
                                                    <label for="respuesta_captcha" class="form-label text-center d-block fw-bold mb-2">
                                                        ¿Cuánto es 4 + 6?
                                                    </label>
            
                                                    <input
                                                        placeholder="¿Cuánto es <?= $_SESSION['numero_1'] ?> + <?= $_SESSION['numero_2'] ?> ?"
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
            
                                        <!-- botones del formulario -->
                                        <div class="text-white">
                                            <p class="text-start fs-6 mb-3">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
            
                                            <div class="d-flex justify-content-around align-items-center align-content-center mb-4"> 
                                                <button type="button" class="btn_show btn btn-outline-primary" id="btn_create_account" title="Crear cuenta">Crear cuenta</button>
                                                <button type="submit" form="form_logIn" class="btn btn-primary">Iniciar Sesión</button>
                                            </div>
            
                                            <div class="col-12 text-center d-flex justify-content-center mb-4">
                                                <button 
                                                    title="Recuperar Contraseña" 
                                                    type="button" 
                                                    class="my-form__signup btn btn-link text-white-50 p-0" data-bs-toggle="modal" data-bs-target="#recuperar_contraseña">
                                                    <i class="bi bi-key me-2"></i>
                                                    ¿Olvidaste tu Contraseña?
                                                </button> 
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
            
            
                            <div id="container_singIn" class="d-none col-12 mb-4 mt-5 animate__animated animate__fadeIn glassmorph rounded-4">
            
                                <div class="p-3 justify-content-center align-items-center text-center">
                                    <div class="d-flex justify-content-end p-2 col-12">
                                        <a href="./" class="text-white btn-close btn-close-popUp" id="boton_cerrar_iniciar_sesion"></a>
                                    </div>
                
                                    <h3 class="text-white">Registro de Usuario </h3>
                                    <hr class="border border-white w-auto"></hr>
            
                                    <!-- formulario de Registro de Usuario -->
                                    <form class="SendFormAjax formulario row m-0" autocomplete="off" action="./controladores/usuario/registrar_usuario.php" data-type-form="save" method="post" id="formulario_registro_usuario">
                                        <!-- cédula del usuario -->
                                        <div class="col-12 col-md-6 mb-3 text-start" id="grupo__cedula">
                                            <label class="text-white label" for="cedula">Cédula <span style="color:#f00;">*</span></label>
            
                                            <div class="input-group">
                                                <i class="input-group-text bi bi-person-vcard h3 m-0"></i>
                                                <input class="form-control bg-dark text-white " type="text" id="cedula" name="cedula" placeholder="Ingresa tu Documento de identidad" title="El Documento de identidad tiene que ser de 7 a 9 dígitos." required maxlength="10" >
                                            </div>
            
                                        </div>
                                        <p class="text-danger d-none input_error formulario__input-error__cedula" style="width: 19em;">El Documento de Identidad debe tener entre 7 y 10 dígitos.</p>
            
                                        <!-- nombre y apellido del usuario -->
                                        <div class="col-12 col-md-6 mb-3 text-start" id="grupo__nombre">
                                            <label class="text-white label" for="nombre">Nombre y Apellido <span style="color:#f00;">*</span></label>
                                            <div class="input-group">
                                                <i class="input-group-text bi bi-person h3 m-0"></i>
                                                <input class="form-control bg-dark text-white" id="nombre" type="text" name="nombre" placeholder="Ingresa tu nombre completo" title="El Nombre solo puede contener Letras, espacios y puede llevar acentos." maxlength="50" required>
                                            </div>
                                            
                                        </div>
                                        <p class="text-danger d-none input_error formulario__input-error__nombre" style="width: 19em;">El Nombre y Apellido debe ser solo caracteres.</p>
            
                                        <!-- correo del usuario -->
                                        <div class="col-12 col-md-6 mb-3 text-start" id="grupo__correo">
                                            <label class="text-white label" for="correo">Correo <span style="color:#f00;">*</span></label>
            
                                            <div class="input-group">
                                                <i class="input-group-text bi bi-envelope h3 m-0"></i>
                                                <input class="form-control bg-dark text-white " id="correo" type="email" name="correo" placeholder="Ingresa tu correo" maxlength="50" required>
                                            </div>
                                        </div>
                                        <!-- teléfono del usuario -->
                                        <div class="col-12 col-md-6 mb-3 text-start" id="grupo__telefono">
                                            <label class=" text-white label" for="telefono">Teléfono <span style="color:#f00;">*</span></label>
                                            
                                            <div class="input-group">
                                                <i class="input-group-text bi bi-phone h3 m-0"></i>
                                                <input class="form-control bg-dark text-white " id="telefono" type="text" name="telefono" placeholder="Ingresa tu número de teléfono" title="El Teléfono solo puede contener 11 números." maxlength="11" required>
                                            </div>
                                        </div>
                                        <p class="text-danger d-none input_error formulario__input-error__telefono" style="width: 19em;">El teléfono debe tener 11 dígitos.</p>
                                        <!-- Contraseña del usuario -->
                                        <div class="col-12 col-md-6 mb-3 text-start" id="grupo__contraseña">
                                            <label class=" text-white label"for="contraseña" class="label">Contraseña <span style="color:#f00;">*</span></label>
                                            
                                            <div class="input-group">
                                                <i class="input-group-text bi bi-lock h3 m-0"></i>
                                                <input class="form-control bg-dark text-white " id="contraseña" class="input__field" type="password" name="contraseña" placeholder="Ingresa tu Contraseña" title="La contraseña tiene que ser de 7 a 16 Caracteres y puede contener números y letras." maxlength="50" required>
                                                <i class="input-group-text bi bi-eye h3 m-0"></i>
                                            </div>
                                        </div>
                                        <p class="text-danger d-none input_error formulario__input-error__contraseña" style="width: 19em;">La contraseña debe tener entre 7 y 16 caracteres, al menos un dígito, al menos una minúscula, al menos una mayúscula y al menos un caracter no alfanumérico.</p>
            
                                        <!-- Repetir contraseña del usuario -->
                                        <div class="col-12 col-md-6 mb-3 grupo__contraseña2 text-start" id="grupo__contraseña2">
                                            <label class=" text-white label"for="contraseña_2_registro" class="label">Repetir Contraseña <span style="color:#f00;">*</span></label>
                                            
                                            <div class="input-group">
                                                <i class="input-group-text bi bi-lock h3 m-0"></i>
                                                <input id="contraseña2" class="form-control bg-dark text-white input__field" type="password" name="contraseña2" placeholder="Ingresa tu contraseña de nuevo" title="Repite la contraseña" maxlength="16" required>
                                                <i class="input-group-text bi bi-eye h3 m-0"></i>
                                            </div>
                                        </div>
                                        <p class="text-danger d-none input_error formulario__input-error__contraseña2" style="width: 19em;">Las contraseñas no coinciden.</p>
            
                                        <!-- seleccionar gimnasio -->
                                        <div class="col-12 mb-3 text-field text-start">
                                            <label class="mb-2 text-white">Selecciona el gimnasio al que te deseas registrar <span style="color:#f00;">*</span></label>
                                            <select class="bg-dark text-white form-select" name="gimnasio" id="select_gym">
                                                <option selected="true" disabled="">Selecciona un gimnasio</option>
                                                <option value="" name="gimnasio">The black panter</option>
                                                <option value="" name="gimnasio">Zumbailo</option>
                                            </select>
                                        </div>
            
                                        <!-- botones del registro de usuario -->
                                        <div class="col-12 my-form__actions text-white mb-2">
                                            <p class="text-start fs-6 mb-3">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
            
                                            <div class="d-flex justify-content-around align-items-center align-content-center mb-4">
                                                <button type="button" class="btn_show btn btn-outline-primary" title="Iniciar Sesión">Iniciar Sesión</button>
                                                <button type="submit" class="btn btn-primary">Registrarse</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
            
                            </div>
            
                        </div>
                    </div>
                </div>
            
            </section>
            <!-- /Hero Section -->
            
        </main>


        <!-- modal recuperar contraseña -->
        <div class="modal fade p-5" id="recuperar_contraseña" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="./api/recuperar_contraseña">
                        <div class="modal-header">
                            <h1 class="modal-title fs-3 text-white" id="exampleModalLabel"><i class="text-white bi bi-key"></i>&nbsp; Recuperar Contraseña</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-start">
                                <label class="mb-3 text-white text-start" for="selecciona_metodo_de_recuperacion">Selecciona el Método de Recuperación<span style="color:#f00;">*</span></label>
                                <select required name="selecciona_metodo_de_recuperacion" id="selecciona_metodo_de_recuperacion" class="form-select">
                                    <option disabled>Selecciona una opción</option>
                                    <option value="correo">Recibir un Código por Correo </option>
                                    <option value="preguntas">Responder las Preguntas de Seguridad</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="aceptar">Aceptar</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Preloader -->
        <div id="preloader"></div>

        <script src="<?= SERVERURL; ?>view/js/jquery-3.6.0.min.js"></script>
        <script src="<?= SERVERURL; ?>view/js/sweetalert2.min.js"></script>
        <script src="<?= SERVERURL; ?>view/vendor/aos/aos.js"></script>
        <script src="<?= SERVERURL; ?>view/vendor/typed.js/typed.umd.js"></script>
        <script src="<?= SERVERURL; ?>view/vendor/purecounter/purecounter_vanilla.js"></script>
        <script src="<?= SERVERURL; ?>view/vendor/waypoints/noframework.waypoints.js"></script>
        <script src="<?= SERVERURL; ?>view/vendor/glightbox/js/glightbox.min.js"></script>
        <script src="<?= SERVERURL; ?>view/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
        <script src="<?= SERVERURL; ?>view/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="<?= SERVERURL; ?>view/vendor/swiper/swiper-bundle.min.js"></script>

        <script src="<?= SERVERURL; ?>view/js/hiddenInput.js"></script>
        <script src="<?= SERVERURL; ?>view/js/login.js"></script>
		<script src="<?= SERVERURL; ?>view/js/SendForm.js"></script>


        <script>
            document.querySelectorAll(".btn_show").forEach((btn => {
                btn.addEventListener('click', () => {
                    document.getElementById('container_singIn').classList.toggle('d-none');
                    document.getElementById('container_logIn').classList.toggle('d-none');
                });
            }));
        </script>

    </body>
</html>
