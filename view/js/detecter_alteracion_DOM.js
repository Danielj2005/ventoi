
document.addEventListener("DOMContentLoaded", function() {
    // Selecciona el nodo objetivo para observar (por ejemplo, el body)
    const targetNode = document.body;

    // Opciones de configuración para observar cambios
    const config = { childList: true, subtree: true };

    // Callback que se ejecuta cuando hay mutaciones en el DOM

    const callback = function(mutationsList, observer) {

        for (let mutation of mutationsList) {

            if (mutation.type === 'childList') {

                mutation.addedNodes.forEach(node => {

                    if (node.nodeType === 1) { // Es un elemento HTML

                        // Aquí puedes comprobar si el nodo coincide con lo que buscas, por ejemplo un modal con una clase o id específico

                        if (node.matches('#root') || node.querySelector('#root')) {

                            console.log('¡Se detectó un modal insertado en el DOM!', node);

                            // Puedes ejecutar aquí el código que necesites para procesar ese modal

                        }

                    }

                });

            }

        }

    };


    // Crea un observer con el callback
    const observer = new MutationObserver(callback);


    // Empieza a observar el nodo con las configuraciones indicadas
    observer.observe(targetNode, config);

    // Si alguna vez quieres dejar de observar:
    // observer.disconnect();

});