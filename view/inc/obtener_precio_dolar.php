<?php
error_reporting(E_PARSE);
date_default_timezone_set('America/Caracas');

function getPrice() {
    try {
        // Inicializar cURL
        $ch = curl_init();

        // Configurar la URL y otras opciones de cURL
        curl_setopt($ch, CURLOPT_URL, "https://www.bcv.org.ve/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desactivar verificación SSL (no recomendado en producción)

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar si hubo un error
        if (curl_errno($ch)) {
            echo 'Error en la URL: ' . curl_error($ch);
            exit;
        }

        // Cerrar cURL
        curl_close($ch);

        // Cargar el HTML en DOMDocument
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Ignorar errores de HTML
        $dom->loadHTML($response);
        libxml_clear_errors();

        // Buscar el precio del dólar en el HTML
        $xpath = new DOMXPath($dom);
        $precioDolar = $xpath->query("//div[@id='dolar']//strong"); // Ajusta el XPath según la estructura del HTML

        if ($precioDolar->length > 0) {
            $precioDolar = trim($precioDolar->item(0)->nodeValue);
            $precioDolar = str_replace(",", ".", $precioDolar); // Reemplazar coma por punto
            $precio_calculado = mb_substr($precioDolar, 0, 6);
            $precio_calculado = floatval($precio_calculado);
            return $precio_calculado;
        } else {
            echo "No se pudo encontrar el precio del dólar.";
        }
    } catch (Exception $error) {
        error_log('Error fetching data: ' . $error->getMessage());
        throw $error;
    }
}

// Example usage:
try {   
    $datos['existe'] = 0;

    $price = getPrice();
    
    if (!is_float($price)) {
        $datos = json_encode($datos);
        echo $datos;
        exit();
    }else {
        $datos['existe'] = 1;
        $datos['usd_price_bs'] = $price;
        $datos = json_encode($datos);
        echo $datos;
    }
} catch (Exception $error) {
    error_log('Ocurrio un error al obtener el precio del dolar: ' . $error->getMessage());
}