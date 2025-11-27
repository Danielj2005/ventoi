<?php 

require_once __DIR__ . '/modelo_usuario.php'; // se incluye el modelo principal
require_once __DIR__ . '/alert_model.php'; // se incluye el modelo principal
error_reporting(E_PARSE);

class rol_model extends model_user {
    
    /**********************************************************************************/
    /*************** funciones para verificar permisos de roles de usuario ************/
    /**********************************************************************************/
    // funcion para verificar los permisos de un rol

    public static function verificar_rol($vista){

        $id_rol = self::obtener_id_rol_usuario();
        
        $permiso_rol = mysqli_fetch_array(modeloPrincipal::consultar("SELECT $vista FROM rol WHERE id_rol = $id_rol"));
        $permiso_rol = $permiso_rol[$vista];
        return $permiso_rol;
    }


    // funcion para verificar los premisos de un modulo del  sistema
    public static function permisos_modulos($vista){
        $id_rol = self::obtener_id_rol_usuario();
        
        $permiso_rol = mysqli_fetch_array(modeloPrincipal::consultar("SELECT SUM($vista) AS permiso_vista FROM rol WHERE id_rol = $id_rol"));
        $permiso_rol = $permiso_rol['permiso_vista'];
        return $permiso_rol;
    }



    public static function obtenerPermisosRol (){

        $id_rol_usuario = self::obtener_id_rol_usuario();

        $resultado_consulta = modeloPrincipal::consultar("SELECT F.codigo
            FROM funciones_rol AS RF  
            JOIN funcion AS F ON RF.id_funcion = F.id 
            WHERE RF.id_rol = '$id_rol_usuario'");

        // 2. Inicializar el array de permisos
        $permisos_usuario = [];

        // 3. Iterar sobre todos los resultados y construir el array de claves
        while ($fila = mysqli_fetch_assoc($resultado_consulta)) {
            // Usa el código de la función (ej: 'l_categoria') como clave y asigna true.
            $permisos_usuario["".$fila['codigo'].""] = 1; 
        }

        return $permisos_usuario;
    }



    public static function obtenerPermisosRolById ($id){

        $cantidadTotalFunciones = modeloPrincipal::consultar("SELECT codigo FROM funcion ORDER BY id");

        $resultado_consulta = modeloPrincipal::consultar("SELECT F.codigo
            FROM funciones_rol AS RF  
            JOIN funcion AS F ON RF.id_funcion = F.id 
            WHERE RF.id_rol = '$id' ORDER BY RF.id");
        
        // 2. Inicializar el array de permisos
        $permisos_usuario = [];

        // Crear un array con los permisos que SÍ tiene el rol
        $permisos_activos = [];

        while ($fila = mysqli_fetch_assoc($resultado_consulta)) {
            $permisos_activos[] = $fila['codigo'];
        }

        // 3. Iterar sobre todos los resultados y construir el array de claves
        while ($fila = mysqli_fetch_assoc($cantidadTotalFunciones)) {
            $codigo_funcion = $fila['codigo'];
            // Si el permiso está en la lista de activos, se marca como 1, si no, como 0.
            if (in_array($codigo_funcion, $permisos_activos)) {
                $permisos_usuario[$codigo_funcion] = 1;
            } else {
                $permisos_usuario[$codigo_funcion] = 0;
            }
        }

        return $permisos_usuario;
    }


    
    public static function sumaPermisoRol ($claves_a_verificar, $clavesPermisosRoles){
        $permisos_encontrados = [];

        foreach ($claves_a_verificar as $clave) {
            // Verifica si la clave existe en los permisos del rol y si su valor es 1 (permitido)
            if (isset($clavesPermisosRoles[$clave]) && $clavesPermisosRoles[$clave] == 1) {
                $permisos_encontrados[$clave] = 1;
            } else {
                $permisos_encontrados[$clave] = 0;
            }
        }
        return $permisos_encontrados;
    }


    
    /* 
     * obtener_id_rol_usuario()
     * Busca la id del rol de un usuario que inicio sesión.
     * @return int El id del rol de un usuario. 
     */ 

    public static function obtener_id_rol_usuario(){

        $id_usuario = $_SESSION["id_usuario"]; // se recibe el id del usuario que inició sesión
        $id_rol = modeloPrincipal::consultar("SELECT id_rol FROM usuario WHERE id_usuario = $id_usuario");

        if (!$id_rol) {
            alert_model::alerta_simple(
                "¡Ocurrió un error inesperado!",
                "No se encontró el rol del usuario, por favor verifique e intente nuevamente",
                "error");
        }
        
        $id_rol = mysqli_fetch_array($id_rol)['id_rol'];;
        return $id_rol;
    }



    /** obtener_nombre_rol_usuario($id_rol)
     * Busca y devuelve el nombre de un rol. 
     * @param int $id_rol id del rol para solicitar su nombre.
     * @return string El nombre del rol solicitado.
     */

    public static function obtener_nombre_rol_usuario($id_rol){
        $nombre_rol = mysqli_fetch_array(modeloPrincipal::consultar("SELECT nombre FROM rol WHERE id_rol = $id_rol"))['nombre'];
        return $nombre_rol;
    }



    /**
     * Registra un nuevo rol y sus permisos asociados.
     * Recibe el nombre del rol y una lista variable de permisos encriptados.
     * Solo los permisos con un valor (ID encriptado) serán guardados.
     * @param string $nombre es el nombre de un rol.
     * @param array $permisos_encriptados array con los permisos que posee un rol.
     * @return bool en caso de exito retorna true de lo contrario retornará false
     */
    public static function guardar_permisos_rol($nombre, ...$permisos_encriptados) {
        
        $registrar = modeloPrincipal::InsertSQL("rol", "nombre, estado", "'$nombre', 1");

        if (!$registrar) {
            alert_model::alerta_simple(
                "¡Ocurrió un error inesperado!",
                "No se pudo registrar el rol debido a un error interno, por favor verifique los datos e intente nuevamente",
                "error");
        }

        $id_rol_recien_registrado = self::consultar_id_rol_recien_registrado();

        foreach ($permisos_encriptados as $permiso_encriptado) {
            // Solo procesamos los permisos que no están vacíos
            if (!empty($permiso_encriptado)) {

                $id_funcion = modeloPrincipal::decryptionId($permiso_encriptado);
                
                if (is_numeric($id_funcion) && $id_funcion > 0) {
                    $registrar_permiso = modeloPrincipal::InsertSQL(
                        "funciones_rol", 
                        "id_rol, id_funcion, fecha_asignacion", 
                        "$id_rol_recien_registrado, $id_funcion, NOW()"
                    );
                    
        
                    if (!$registrar_permiso) {
                        alert_model::alerta_simple(
                            "¡Ocurrió un error inesperado!",
                            "No se pudo registrar el permiso con ID: $id_funcion para el rol. Por favor, intente nuevamente.",
                            "error");
                        return false; // Detenemos la ejecución si un permiso falla
                    }
                }
            }

        }
        // Todos los permisos se registraron correctamente
        return true; 
    }

    /**
     * Modifica un rol y sus permisos.
     * Borra los permisos anteriores y guarda los nuevos.
     */
    public static function modificar_permisos_rol($id_rol, $nombre, $estado, $permisos_nuevos_encriptados) {
        // 1. Actualizar el nombre y estado del rol
        $actualizar_rol = modeloPrincipal::UpdateSQL("rol", "nombre = '$nombre', estado = '$estado'", "id_rol = $id_rol");

        if (!$actualizar_rol) {
            alert_model::alerta_simple(
                "¡Ocurrió un error inesperado!",
                "No se pudo actualizar el rol debido a un error interno, por favor verifique los datos e intente nuevamente",
                "error"
            );
        }

        // 2. Borrar todos los permisos antiguos de ese rol
        modeloPrincipal::DeleteSQL("funciones_rol", "id_rol = $id_rol");

        // 3. Insertar los nuevos permisos
        foreach ($permisos_nuevos_encriptados as $permiso_encriptado) {
            if (!empty($permiso_encriptado)) {
                
                $id_funcion = modeloPrincipal::decryptionId($permiso_encriptado);

                $id_funcion = modeloPrincipal::limpiar_cadena($id_funcion);
                
                if (is_numeric($id_funcion) && $id_funcion > 0) {
                    $registrar_permiso = modeloPrincipal::InsertSQL(
                        "funciones_rol", 
                        "id_rol, id_funcion, fecha_asignacion", 
                        "$id_rol, $id_funcion, NOW()"
                    );
                    
                    if (!$registrar_permiso) {
                        // Si falla la inserción de un permiso, es mejor detenerse.
                        return false; 
                    }
                }
            }
        }

        // Si todo fue bien
        return true;
    }

    public static function consultar_id_rol_recien_registrado () {
        $id = mysqli_fetch_array(modeloPrincipal::consultar("SELECT MAX(id_rol) as id FROM rol"))['id'];
        return $id;
    } 


    // funcion para validar si se esta recibiendo datos por post

    public static function texto_permisos_vista($permisos) {
        $texto_permisos = [];

        foreach ($permisos as $key => $value) {
            $texto_permisos[$key] = ($value == 1) ? 'Permitido' : 'Denegado';
        }
        return $texto_permisos;
    }



    public static function generar_bitacora_guardar_rol($permisos_rol) {
        return self::generar_bitacora_rol_html($permisos_rol);
    }

    public static function generar_bitacora_modificar_rol($permisos_originales, $permisos_actuales) {
        return self::generar_bitacora_rol_html($permisos_actuales, $permisos_originales);
    }

    private static function generar_bitacora_rol_html($permisos_actuales, $permisos_originales = null) {
        $modulos_config = [
            'Inventario' => [
                'icon' => 'bi-box-seam',
                'submodulos' => [
                    'Proveedores' => ['r_proveedores', 'm_proveedores', 'l_proveedores', 'h_proveedores'],
                    'Categorías' => ['r_categoria', 'm_categoria', 'l_categoria'],
                    'Presentaciones' => ['r_presentacion', 'm_presentacion', 'l_presentacion'],
                    'Marcas' => ['r_marca', 'm_marca', 'l_marca'],
                    'Productos' => ['r_productos', 'l_productos'],
                    'Entradas' => ['r_entrada', 'l_entrada']
                ]
            ],
            'Ventas' => [
                'icon' => 'bi-currency-dollar',
                'submodulos' => [
                    'Ventas' => ['g_venta', 'l_venta', 'd_venta', 'f_venta', 'est_venta']
                ]
            ],
            'Servicios' => [
                'icon' => 'bi-fork-knife',
                'submodulos' => [
                    'Servicios' => ['r_servicio', 'm_servicio', 'l_servicio']
                ]
            ],
            'Usuarios' => [
                'icon' => 'bi-people-fill',
                'submodulos' => [
                    'Clientes' => ['r_cliente', 'm_cliente', 'l_cliente', 'h_cliente', 'f_cliente'],
                    'Empleados' => ['r_empleado', 'm_empleado', 'l_empleado'],
                    'Roles' => ['r_rol', 'm_rol', 'l_rol']
                ]
            ],
            'Configuración' => [
                'icon' => 'bi-gear-fill',
                'submodulos' => [
                    'Ajustes' => ['m_cant_pregunta_seguridad', 'm_tiempo_sesion', 'm_cant_caracteres', 'm_cant_simbolos', 'm_cant_num', 'intentos_inicio_sesion'],
                    'Bitácora' => ['v_bitacora', 'm_bitacora']
                ]
            ]
        ];

        $nombres_legibles = [
            'r_proveedores' => 'Registrar Proveedores',
            'm_proveedores' => 'Modificar Proveedores',
            'l_proveedores' => 'Listar Proveedores',
            'h_proveedores' => 'Ver Historial de Proveedores',
            'r_categoria' => 'Registrar Categorías',
            'm_categoria' => 'Modificar Categorías',
            'l_categoria' => 'Listar Categorías',
            'r_presentacion' => 'Registrar Presentaciones',
            'm_presentacion' => 'Modificar Presentaciones',
            'l_presentacion' => 'Listar Presentaciones',
            'r_marca' => 'Registrar Marcas',
            'm_marca' => 'Modificar Marcas',
            'l_marca' => 'Listar Marcas',
            'r_productos' => 'Registrar Productos',
            'l_productos' => 'Listar Productos',
            'r_entrada' => 'Registrar Entradas',
            'l_entrada' => 'Listar Entradas',
            'g_venta' => 'Generar Ventas',
            'l_venta' => 'Listar Ventas',
            'd_venta' => 'Ver Detalles de Venta',
            'f_venta' => 'Ver Facturas de Venta',
            'est_venta' => 'Ver Estadísticas de Venta',
            'r_servicio' => 'Registrar Servicios',
            'm_servicio' => 'Modificar Servicios',
            'l_servicio' => 'Listar Servicios',
            'r_cliente' => 'Registrar Clientes',
            'm_cliente' => 'Modificar Clientes',
            'l_cliente' => 'Listar Clientes',
            'h_cliente' => 'Ver Historial de Cliente',
            'f_cliente' => 'Ver Facturas de Cliente',
            'r_empleado' => 'Registrar Empleados',
            'm_empleado' => 'Modificar Empleados',
            'l_empleado' => 'Listar Empleados',
            'r_rol' => 'Registrar Roles',
            'm_rol' => 'Modificar Roles',
            'l_rol' => 'Listar Roles',
            'm_cant_pregunta_seguridad' => 'Modificar Cant. Preguntas Seguridad',
            'm_tiempo_sesion' => 'Modificar Tiempo de Sesión',
            'm_cant_caracteres' => 'Modificar Cant. Caracteres Contraseña',
            'm_cant_simbolos' => 'Modificar Cant. Símbolos Contraseña',
            'm_cant_num' => 'Modificar Cant. Números Contraseña',
            'intentos_inicio_sesion' => 'Modificar Intentos de Sesión',
            'v_bitacora' => 'Consultar Bitácora',
            'm_bitacora' => 'Consultar Movimientos en Bitácora'
        ];

        $html_final = '';

        foreach ($modulos_config as $nombre_modulo => $config) {
            $html_submodulos = '';
            $modulo_tiene_permisos = false;

            foreach ($config['submodulos'] as $nombre_submodulo => $lista_permisos) {
                $html_permisos = '';
                $submodulo_tiene_permisos = false;

                foreach ($lista_permisos as $permiso) {
                    if (isset($permisos_actuales[$permiso]) && $permisos_actuales[$permiso] === 'Permitido') {
                        $submodulo_tiene_permisos = true;
                        $nombre_legible = $nombres_legibles[$permiso] ?? ucwords(str_replace('_', ' ', $permiso));
                        
                        if ($permisos_originales) { // Modo Modificación
                            $original = $permisos_originales[$permiso];
                            $actual = $permisos_actuales[$permiso];
                            if ($original !== $actual) {
                                $color_original = $original === 'Permitido' ? 'success' : 'danger';
                                $color_actual = $actual === 'Permitido' ? 'success' : 'danger';
                                $html_permisos .= '<li class="list-group-item d-flex justify-content-between"><span>'.$nombre_legible.'</span> <span>De <b class="text-'.$color_original.'">'.$original.'</b> a <b class="text-'.$color_actual.'">'.$actual.'</b></span></li>';
                            }else{
                                
                                $color_original = $original === 'Permitido' ? 'success' : 'danger';
                                $html_permisos .= '<li class="list-group-item d-flex justify-content-between text-'.$color_original.'"><span>'.$nombre_legible.'</span> <span> <b>'.$original.'</b> </span></li>';
                            
                            }
                        } else { // Modo Guardar
                            $html_permisos .= '<li class="list-group-item d-flex justify-content-between"><span>'.$nombre_legible.'</span> <span class="text-success">Permitido</span></li>';
                        }
                    }
                }

                if ($submodulo_tiene_permisos && !empty($html_permisos)) {
                    $modulo_tiene_permisos = true;
                    $html_submodulos .= '<p class="fw-bold bg-light ps-2 border-bottom">'.$nombre_submodulo.'</p><ul class="list-group list-group-flush">'.$html_permisos.'</ul>';
                }
            }

            if ($modulo_tiene_permisos) {
                $html_final .= $html_submodulos;
            }
        }

        return $html_final;
    }



    

    /**
     * busca los roles activos y crea los options para una select tag html.
     * retorna una lista de options con los roles activos
     */
    public static function select_options_nombres_roles() {
        
        $oprions_roles = modeloPrincipal::consultar("SELECT * FROM rol WHERE estado = 1");

        while($row = mysqli_fetch_array($oprions_roles)) { ?>

            <option value="<?= modeloPrincipal::encryptionId($row['id_rol']); ?>"> <?= $row["nombre"]; ?></option>

        <?php }
    }

}