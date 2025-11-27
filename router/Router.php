<?php
namespace App\Router;

use alert_model;
use modeloPrincipal;
use producto_model;
use servicio_model;
use proveedor_model;

class Router {
    
    // Almacena el ID desencriptado
    protected $id;
    protected $dni;

    // Almacena el nombre del módulo solicitado
    protected $module;

    public function __construct(array $post_data) {
        // Inicializa el módulo desde POST
        $this->module = $post_data['module'] ?? null;

        $this->dni = $post_data['cedula'] ?? null;
        
        // Intenta desencriptar el ID si existe
        $encrypted_id = $post_data['id'] ?? null;
        if ($encrypted_id) {
            $this->id = modeloPrincipal::decryptionId($encrypted_id);
        }
    }

    /**
     * Valida el ID y ejecuta la función del modelo.
     */
    protected function executeModelCall(callable $callback) {
        // Validación consolidada
        if (isset($this->id) && is_numeric($this->id)) {
            // Llama a la función del modelo con el ID
            echo call_user_func($callback, $this->id);
            
        } else if (isset($this->dni) && is_string($this->dni)) {
            // Llama a la función del modelo con el ID
            echo call_user_func($callback, $this->dni);

        }else  if ($this->id == "") {
            echo call_user_func($callback,  "");
        }else {
            // Error único para ID faltante
            alert_model::alerta_simple(
                "Error de Parámetro", 
                "Falta el identificador (ID) necesario para procesar la solicitud.", 
                "warning"
            );
        }
    }

    /**
     * Mapea el módulo a la acción y la ejecuta.
     */
    public function route() {
        if (!$this->module) {
            alert_model::alerta_simple("Faltan Datos", "No se recibió la solicitud de módulo esperada. Verifique la petición.", "error");
            return;
        }

        // Mapeo de módulos a acciones específicas
        switch ($this->module) {
            // endpoint de productos
            case 'productos_compra_a_proveedores':
                $this->executeModelCall([producto_model::class, 'productos_compra_a_proveedores']);
                break;
                
            case 'añadir_productos_a_servicio':
                $this->executeModelCall([producto_model::class, 'añadir_productos_a_servico']);
                break;
                
            case 'añadir_productos_a_venta':
                $this->executeModelCall([producto_model::class, 'añadir_productos_a_venta']);
                break;
                
            case 'añadir_productos_para_registrar':
                $this->executeModelCall([producto_model::class, 'añadir_productos_para_registrar']);
                break;

            // endpoint de servicios
            case 'añadir_servico_a_venta':
                $this->executeModelCall([servicio_model::class, 'añadir_servico_a_venta']);
                break;

            // endpoint de buscar datos de proveedor
            case 'buscar_proveedor_compra':
                $this->executeModelCall([proveedor_model::class, 'buscar_proveedor_compra_por_dni']);
                break;

            // endpoint de buscar datos de cliente
            case 'buscar_datos_cliente':
                $this->executeModelCall([proveedor_model::class, 'buscar_datos_cliente']);
                break;

            // endpoint de buscar datos de cliente
            default:
                alert_model::alerta_simple(
                    "Solicitud Inválida",
                    "El módulo de solicitud especificado ({$this->module}) no existe o no está permitido.",
                    "error"
                );
                break;
        }
    }
}