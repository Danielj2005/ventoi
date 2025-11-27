<?php 
session_start();

// importacion de la conexion a la base de datos y al modelo principal

include_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

$id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario
// validación para verificar que el usuario inicio sesion de manera correcta
model_user::verificar_intento_de_acceso_al_sistema();

include_once "../include/verificacion_primer_inicio_usuario.php"; // se incluyen los modelos necesarios para la vista

$g_venta = modeloPrincipal::verificar_permisos_requeridos(['g_venta']);

// se evalua que este rol tenga el acceso a esta vista
if ($g_venta) {  ?>

  <!DOCTYPE html>
  <html lang="en">
    <head>
      <!-- titulo -->
      <title>Generar Venta</title>
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
          <h1 class="h3 fw-bold mb-0">
            Generar Venta
          </h1>
        </div>

        <section class="section dashboard">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <form id="formulario" action="../controlador/generar_venta_controlador.php" method="post" class="SendFormAjax p-3" autocomplete="off" data-type-form="save">
                    <input type="hidden" name="dolar" id="precioDolar" value="<?= $precio_dolar_actual; ?>">
                    <input type="hidden" name="modulo" value="Guardar">
                    
                    <!-- datos del cliente -->

                    <fieldset class="row">
                      <h5 class="card-title col-12 mb-3" >Datos del Cliente</h5>
                      <div class="row mb-3" id="datos_cliente">

                        <div class="col-12 col-md-6 mb-2">

                          <label class="form-label">Cédula <span style="color:#f00;">*</span> </label>

                          <div class="input-group mb-2">
                            <input type="hidden" name="id_cliente" id="id_cliente">
                            <select class="input-group-text" name="nacionalidad" id="nacionalidad" required>
                              <option value="V-">V</option>
                              <option value="E-">E</option>
                            </select>
                            
                            <input type="text" class="form-control" placeholder="ingresa la cédula" onblur="buscar_datos_cliente()"; minlength="7" maxlength="8" name="cedula" id="cedula" required>
                            
                          </div>

                          <p class="d-none w-auto alert alert-danger" id="mensaje_cedula"> La cédula debe ser de 7-8 dígitos</p>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                          <label class="form-label">Nombre y Apellido <span style="color:#f00;">*</span></label>
                          <input type="text" pattern="[A-Za-zñÑÁÉÍÚÓáéíóú ]{4,255}" required="" placeholder="Ingresa el nombre y apellido" class="form-control mb-2" id="nombre" name="nombre">
                          <p class="d-none w-auto alert alert-danger" id="mensaje_nombre"> El nombre y apellido solo puede contener caracteres alfabeticos con una longitud máxima de 255 caracteres</p>
                        </div>

                        <div class="col-12 col-md-12 mb-2">
                          <label class="form-label">Teléfono <span style="color:#f00;">*</span></label>
                          <input type="text" maxlength="11" pattern="[0-9]{11}" required="" placeholder="Ingrese el teléfono" class="form-control mb-2" id="telefono" name="telefono">
                          <p class="d-none w-auto alert alert-danger" id="mensaje_telefono"> El número de teléfono debe ser de 11 dígitos</p>
                        </div>

                      </div>
                    </fieldset>

                    <!-- Servicios disponibles -->

                    <fieldset class="row mb-1"> 
                      <h5 class="card-title">Servicios disponibles</h5>

                      <div class="col-12 col-md-9 mb-4">
                        <select class="form-select select select2" name="add_servicio" id="">
                          <option value="" selected>seleccione una opción</option>
                          <?php servicio_model::options(); ?>
                        </select>
                      </div>

                      <div class="col-12 col-md-3 mb-3 justify-content-center d-flex">
                        <button type="button" name="btn_add_servicio" class="btn_add btn btn-success bi bi-plus">&nbsp; Añadir servicio</button>
                      </div>

                      <div class="col-12 mb-3 p-0">
                        <h5 class="mb-2">Lista de servicios seleccionados</h5>
                        
                        <div class="table-responsive">
                          <table class="table-hover table table-borderless table-striped" id="">
                            <thead>
                              <tr class="bg-secondary-light">
                                <th class="col text-start" scope="col">Servicio</th>
                                <th class="col text-center" scope="col">Cantidad</th>
                                <th class="col text-center" scope="col">Costo ($)</th>
                                <th class="col text-center" scope="col">Costo (Bs)</th>
                                <th class="col text-center" scope="col">Quitar</th>
                              </tr>
                            </thead>
                            <tbody id="lista_add_servicio"> </tbody>
                          </table>
                        </div>
                      </div>
                    </fieldset>

                    <!-- productos disponibles -->

                    <fieldset class="row"> 
                      <h5 class="card-title col-12">Productos disponibles &nbsp; </h5>

                      <div class="col-12 col-md-9 mb-4">
                        <select name="producto" id="producto_id" class="form-select select">
                          <option value="" selected>seleccione una opción</option>
                          <?php producto_model::options(1); ?>
                        </select>
                      </div>

                      <div class="col-12 col-md-3 mb-3 justify-content-center d-flex">
                        <button type="button" name="btn_producto" class="btn btn-success bi bi-plus btn_add">&nbsp; Añadir producto</button>
                      </div>

                      <div class="col-12 mb-2 p-0">
                        <h5 class="">Lista de productos seleccionados</h5>
                        <div class="table-responsive mb-3"> 
                          <table class="table table-striped table-borderless">
                            <thead>
                              <tr class="bg-secondary-light">
                                <th style="width: 10rem;" class="col text-center" scope="col">Producto</th>
                                <th style="width: 10rem;" class="col text-center col-md-2" scope="col">Cantidad</th>
                                <th style="width: 10rem;" class="col text-center col-md-2" scope="col">Costo ($)</th>
                                <th style="width: 10rem;" class="col text-center col-md-2" scope="col">Costo (Bs)</th>
                                <th class="col text-center col-md-2" scope="col">Quitar</th>
                              </tr>
                            </thead>
                            <tbody id="lista_producto"> </tbody>
                          </table>
                        </div>
                      </div>
                    </fieldset>

                    <!-- métodos de pago -->

                    <fieldset class="row align-items-center">

                      <legend class="col-12 col-md-8 mb-3 card-title">Método de Pago</legend>
                      <div class="col-12 col-md-4 mb-3 justify-content-center d-flex">
                        <button type="button" class="btn btn-primary bi bi-plus-lg" onclick="añadir_metodo_pago()"> Añadir método</button>
                      </div>

                      <div class="col-12">
                        <div class="table-responsive row mb-3"> 
                          <table class="tableMetodo table table-striped" id="metodos_pago">
                            <thead>
                              <tr class="bg-secondary-light">
                                <th style="width: 10rem;" class="col text-center" scope="col">Método de pago</th>
                                <th style="width: 10rem;" class="col text-center" scope="col">Cantidad a pagar ($)</th>
                                <th style="width: 10rem;" class="col text-center" scope="col">Nº referencia</th>
                                <th class="col text-center" scope="col">Quitar</th>
                              </tr>
                            </thead>
                            <tbody id="tabla_metodo_pago">
                              <tr id="metodo_1">
                                <td class="text-center col">
                                  <select style="width: 16rem;" name="metodo_pago[]" id="metodo_pago_1" class="form-select selector_metodo_pago" onchange="habilitar_referencia('metodo_pago_1','num_referencia_1')">
                                    <option selected>seleccione una opción</option>
                                    <option value="1">Divisa</option>
                                    <option value="2">Punto de Venta</option>
                                    <option value="3">Transferencia / Pago movíl</option>
                                    <option value="4">Bolivares en Efectivo</option>
                                  </select>
                                </td>
                                <td class="text-center col">
                                  <input
                                    style="width: 10rem;"  
                                    type="text" 
                                    class="form-control" 
                                    id="cantidad_1" 
                                    name="monto_pagar[]" 
                                    placeholder="MONTO A PAGAR" 
                                    required>
                                </td>
                                <td class="text-center col">
                                  <input
                                    style="width: 10rem;" 
                                    type="text" 
                                    class="form-control bg-dark-subtle" 
                                    readOnly 
                                    id="num_referencia_1" 
                                    name="num_referencia[]" 
                                    maxlength="20" 
                                    minlength="7" 
                                    placeholder="número de referencia">
                                </td>
                                <td class="text-center col">
                                  <button type="button" class="btn btn-sm btn-danger bi bi-trash" onclick="quitar_metodo(1)"></button>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </fieldset>

                    <!-- total de la cuenta -->

                    <fieldset class="row justify-content-center">

                      <legend class="card-title col-12">Cuenta</legend>
                      
                      <div class="row justify-content-center align-items-center bg-light">
                          
                        <input type="hidden" id="totalDolar" name="sub_total_dolar" value="0">
                        <input type="hidden" id="totalBolivar" name="sub_total_bs" value="0">
                        <input type="hidden" id="totalDolar_iva" name="totalDolar_iva" value="0">
                        <input type="hidden" id="totalBolivar_iva" name="totalBolivar_iva" value="0">

                        <div class="col-12 col-md-6 mb-3 text-center">
                          <p class="fs-4 text-success"> Monto a pagar ($): </p> 
                          <span class="fs-4 text-success"> <strong id="strong_dolares"></strong> $</span>
                        </div>

                        <div class="col-12 col-md-6 mb-3 text-center">
                          <p class="fs-4 text-success"> Monto a pagar (Bs) </p>
                          <span class="fs-4 text-success"> <strong id="strong_bolivares"></strong> Bs</span>
                        </div>

                      </div>
                    </fieldset>

                    <div class="col-12 mb-1">
                      <div class="form-group">
                          <p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
                      </div>
                    </div>
                    
                    <div class="text-center">
                      <button type="submit" class="btn btn-success">Generar Venta</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>

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

        
      <script type="text/javascript">
            
        // inicializar la libreria Select2 
        $('.Select').select2();

        // función para añadir un metodo de pago 
        let i = 2;
        function añadir_metodo_pago(){
          // este tr será añadido a la tabla 
          let tr = `<tr id="metodo_${i}">
                      <td class="text-center col">
                        <select style="width: 16rem;" name="metodo_pago[]" id="metodo_pago_${i}" class="form-select selector_metodo_pago" onchange="habilitar_referencia('metodo_pago_${i}','num_referencia_${i}')">
                          <option value="" selected>seleccione una opción</option>
                          <option value="1">Divisa</option>
                          <option value="2">Punto de Venta</option>
                          <option value="3">Transferencia / Pago movíl</option>
                          <option value="4">Bolivares en Efectivo</option>
                        </select>
                      </td>
                      <td class="text-center col">
                        <input style="width: 10rem;" type="text" class="form-control" id="cantidad_${i}" name="monto_pagar[]" placeholder="MONTO A PAGAR" required>
                      </td>
                      <td class="text-center col">
                        <input style="width: 10rem;" type="text" class="form-control bg-dark-subtle" readOnly id="num_referencia_${i}" name="num_referencia[]" maxlength="20" minlength="7" placeholder="número de referencia">
                      </td>
                      <td class="text-center col">
                        <button type="button" class="btn btn-sm btn-danger bi bi-trash" onclick="quitar_metodo(${i++})"></button>
                      </td>
                    </tr>`;
  
          $('#tabla_metodo_pago').append(tr);
        }

        function quitar_metodo(num){
          let tr = document.getElementById(`metodo_${num}`);
          tr.remove();
        }
        
        // estafuncion sirve para habilitar e inhabilitar el input de el número de referencia de un pago 
        function habilitar_referencia (id_del_selector,input_num_referencia){

          let id_selector = document.getElementById(id_del_selector).value;
          let input_referencia = document.getElementById(input_num_referencia);

          if (id_selector === '3') {
            input_referencia.classList.remove('bg-dark-subtle');
            input_referencia.removeAttribute('readOnly');
          }else{
            input_referencia.classList.add('bg-dark-subtle');
            input_referencia.setAttribute('readOnly','');
          }
        }
      
      </script>
    </body>
  </html>
<?php }else{
  // se registran las acciones del usuario en la bitacora y es redirijido al inicio
  bitacora::intento_de_acceso_a_vista_sin_permisos("generar venta");
}