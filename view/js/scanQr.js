// --- VARIABLES GLOBALES ---
let html5QrCode = null;
const qrCodeRegionId = "reader";
const inputFieldId = "code";
const startButtonId = "startButton";
const fileInputId = "qr-code-file-input";
const resultContainerId = "result";


// --- FUNCIONES DE ACCIÓN ---

/**
 * @description Función llamada al detectar un código (cámara o imagen).
 * @param {string} decodedText - El texto (código) decodificado del QR/Barcode.
 */
function onScanSuccess(decodedText) {
    // 1. Detener el escáner (solo si se usó la cámara)
    if (html5QrCode && html5QrCode.isScanning) {
        html5QrCode.stop().then(() => {
            document.getElementById(qrCodeRegionId).style.display = 'none';
            document.getElementById(startButtonId).style.display = 'block';
        }).catch((err) => {
            console.error("Error al detener el escáner: ", err);
        });
    }

    // 2. Rellenar el input y forzar la búsqueda
    const codigoInput = document.getElementById(inputFieldId);
    codigoInput.value = decodedText;
    // document.getElementById(resultContainerId).innerHTML = `Código escaneado: <b>${decodedText}</b>`;
    
    // 3. Llamar a la función de búsqueda
    // buscarProducto();
}


/**
 * @description Inicia el proceso de escaneo usando la cámara.
 */
function iniciarEscaneo() {
    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode(qrCodeRegionId);
    }

    // Ocultar botón y mostrar área del escáner
    document.getElementById(startButtonId).style.display = 'none';
    document.getElementById(qrCodeRegionId).style.display = 'block';

    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        formatsToSupport: [
            Html5QrcodeSupportedFormats.QR_CODE,
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.EAN_13
        ]
    };
    
    // Inicia el escaneo. Usa "environment" (cámara trasera) por defecto.
    html5QrCode.start(
        { facingMode: "environment" },
        config,
        onScanSuccess,
        (errorMessage) => { /* Silenciar mensajes de escaneo continuo */ }
    ).catch((err) => {
        document.getElementById(resultContainerId).innerHTML = `<span style="color: red;">Error al iniciar la cámara. Intenta con la opción de imagen.</span>`;
        document.getElementById(qrCodeRegionId).style.display = 'none';
        document.getElementById(startButtonId).style.display = 'block';
    });
}


/**
 * @description Función para la búsqueda en la base de datos (DB).
 */
function buscarProducto() {
    const codigo = document.getElementById(inputFieldId).value;
    
    if (codigo) {
        // --- LÓGICA DE BÚSQUEDA AJAX O FETCH API AQUÍ ---
        console.log(`Buscando en la base de datos el código: ${codigo}`);
        document.getElementById(resultContainerId).innerHTML += `<p style="color: blue;">Iniciando búsqueda para: ${codigo}</p>`;
        // La búsqueda debería ejecutarse y el input enfocarse de nuevo
        document.getElementById(inputFieldId).focus(); 
        document.getElementById(inputFieldId).select(); // Selecciona el texto para que el próximo escaneo lo sobrescriba
    }
}


// --- INICIALIZACIÓN DE EVENT LISTENERS ---

document.addEventListener('DOMContentLoaded', () => {
    // 1. Listener para iniciar el escaneo con cámara
    document.getElementById(startButtonId).addEventListener('click', iniciarEscaneo);

    
    // 2. Listener para Escaneo de Imagen
    // Permite al usuario seleccionar un archivo de imagen para decodificar.
    // document.getElementById(fileInputId).addEventListener('change', (e) => {
    //     const file = e.target.files[0];
    //     if (!file) return;

    //     // Limpiamos la instancia anterior si existía
    //     if (!html5QrCode) {
    //         html5QrCode = new Html5Qrcode(qrCodeRegionId);
    //     }

    //     document.getElementById(resultContainerId).innerHTML = 'Decodificando imagen...';
        
    //     // Decodificar el archivo de imagen seleccionado
    //     html5QrCode.scanFile(file, true)
    //         .then(onScanSuccess)
    //         .catch((err) => {
    //             document.getElementById(resultContainerId).innerHTML = `<span style="color: red;">Error al decodificar la imagen: ${err}</span>`;
    //         });
    // });


    // 3. Soporte para Escáner de Códigos de Barra USB
    // El escáner USB simula la escritura del código seguido de la tecla ENTER (keyCode 13).
    // Usamos 'keyup' para capturar la pulsación.
    
    const codigoInput = document.getElementById(inputFieldId);
    
    codigoInput.addEventListener('keyup', (e) => {
        // Verificamos si la tecla presionada es ENTER (keyCode 13)
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault(); // Evita la acción predeterminada de ENTER (ej. envío de formulario)
            
            // Si el código se introduce por el escáner USB, su valor ya estará en el input
            // Llamamos directamente a la función de búsqueda.
            if (codigoInput.value.length > 0) {
                 // Añadimos un pequeño timeout, ya que a veces el valor se procesa un poco lento
                setTimeout(buscarProducto, 50); 
            }
        }
    });
    
    // Enfocar el campo de código al cargar la página para que el escáner USB funcione inmediatamente
    codigoInput.focus();
});