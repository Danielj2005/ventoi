<script src="<?= SERVERURL; ?>view/vendor/php-email-form/validate.js"></script>
<script src="<?= SERVERURL; ?>view/vendor/aos/aos.js"></script>
<script src="<?= SERVERURL; ?>view/vendor/typed.js/typed.umd.js"></script>
<script src="<?= SERVERURL; ?>view/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="<?= SERVERURL; ?>view/vendor/waypoints/noframework.waypoints.js"></script>
<script src="<?= SERVERURL; ?>view/vendor/glightbox/js/glightbox.min.js"></script>
<script src="<?= SERVERURL; ?>view/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script src="<?= SERVERURL; ?>view/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="<?= SERVERURL; ?>view/vendor/swiper/swiper-bundle.min.js"></script>

<script src="<?= SERVERURL; ?>view/assets/js/hiddenInput.js"></script>
<script src="<?= SERVERURL; ?>view/assets/js/login.js"></script>


<script>
    document.querySelectorAll(".btn_show").forEach((btn => {
        btn.addEventListener('click', () => {
            document.getElementById('container_singIn').classList.toggle('d-none');
            document.getElementById('container_logIn').classList.toggle('d-none');
        });
    }));
</script>
