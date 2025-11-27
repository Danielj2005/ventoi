<?php
error_reporting(E_PARSE);

class alert_model {

    /**********************************************************************************/
    /*********************** funciones para crear alertas con sweet alert *************/
    /**********************************************************************************/

    /*------------------- funcion para crear una alerta con sweet alert con parametros ---------------*/


    public static function alerta_simple ($title, $text, $icon){
        echo '<script type="text/javascript">
                Swal.fire({
                    title: "'.$title.'",
                    text: "'.$text.'",
                    icon: "'.$icon.'",
                    confirmButtonText: "Aceptar"
                });
            </script>';
    }

    public static function alert_reset_forms ($title, $text, $icon, $condition = "$('.SendFormAjax')[0].reset();"){
        echo "<script>
                Swal.fire({
                    title: '$title',
                    text: '$text',
                    icon: '$icon',
                    confirmButtonText: 'Aceptar'
            });
            $condition
            </script>";
    }

    public static function alerta_condicional ($title, $text, $icon, $condition = "location.reload();", $reset_forms = "$('.SendFormAjax')[0].reset();"){
        echo "<script>
                Swal.fire({
                    title: '$title',
                    text: '$text',
                    icon: '$icon',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $condition
                    }else {                       
                        $condition
                    } 
                });
                $reset_forms
            </script>";
    }


    public static function alerta_simple_reset_de_formularios($title, $text, $icon, $condition = "$('.SendFormAjax')[0].reset();"){
        self::alert_reset_forms($title, $text, $icon);
    }

    public static function alert_reload ($title, $text, $icon) {
        self::alerta_condicional($title, $text, $icon);
    }

    public static function alert_redirect ($title, $text, $icon, $url) {
        // se verifica si la url es diferente a la vista de inicio de sesion
        
        self::alerta_condicional($title, $text, $icon, "window.location = '".$url."';");
        
    }


    public static function alert_reg_success(){
        echo '<script type="text/javascript">
            Swal.fire({
                title:"¡Registro Exitoso!",
                text:"Los Datos Se Registraron Correctamente",
                icon: "success",
                confirmButtonText: "Aceptar"
            
            }).then((result) => {
                if (result.isConfirmed) {    
                    location.reload();
                } else {    
                    location.reload();
                } 
            });
            $(".SendFormAjax")[0].reset();
        </script>';
    }

    public static function alert_reg_success_and_close_modal(){
        
        self::alert_reset_forms("¡Registro Exitoso!","Los datos se Registraron Correctamente","success", "document.getElementById('btnCloseModal').click();");
        
    }
    public static function alert_reg_error(){
        echo '<script type="text/javascript">
            Swal.fire({
                title: "¡Ocurrio un error!",
                text: "los datos no se pudieron registrar, verifique he intente de nuevo ",
                icon: "error",
                confirmBottonText: "Aceptar"
            });
        </script>';
    }

    public static function alert_mod_success(){
        echo '<script type="text/javascript">
            Swal.fire({
                title: "¡Modificación exitosa!",
                text: "Los datos se modificaron correctamente",
                icon: "success",
                confirmButtonText: "Aceptar"
            }).then((result) => {
                if (result.isConfirmed) {   
                    location.reload();
                } else {    
                    location.reload();
                } 
            });
        </script>';
    }
    
    public static function alert_mod_success_and_close_modal(){
        
        self::alert_reset_forms("¡Modificación exitosa!","Los datos se modificaron correctamente","success", "document.getElementById('btnCloseModal').click();");
        
    }

    public static function alert_mod_error(){
        
        echo'<script type="text/javascript">
            Swal.fire({
                title: "¡Ocurrio un error!",
                text: "Los datos no se modificaron, verifique he intente nuevamente",
                icon: "error",
                confirmBottonText: "Aceptar"
            });
        </script>';
    }

    
    public static function alert_fields_empty(){
        echo '<script type="text/javascript">
                Swal.fire({ 
                    title: "¡Ocurrio un error!",
                    text: "Exiten campos obligatorios que estan vacíos",
                    icon: "error", 
                    confirmButtonColor: "#036cbd",
                    confirmButtonText: "Aceptar"  
                });
            </script>';
    }

    public static function alert_of_format_wrong($campo){
        echo '<script type="text/javascript">
            Swal.fire({
                title: "¡Ocurrio un error!",
                text: "El campo '.$campo.' no cumple con el formato establecido",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
        </script>';
    }

    public static function alert_register_exist($campo = "La información"){
    // se verifica si el campo es un string o un array
    
        echo '<script type="text/javascript">
                Swal.fire({
                    title:"¡Ocurrió un error!",
                    text:"'.$campo.' ingresada ya se encuentra registrada(o) en el sistema. le sugerimos revisar los datos o utilizar una información diferente",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
        </script>'; 
    }
}