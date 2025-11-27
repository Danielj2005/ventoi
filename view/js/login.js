(function() {
    "use strict";

    /**
     * Preloader
     */
    const preloader = document.querySelector('#preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            preloader.remove();
            setTimeout(() => {
                document.getElementById('container').classList.toggle('d-none');

            },1000);
        });
    }

})();