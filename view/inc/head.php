<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta content="sistema de control de Ventas y Inventario" name="description">
<meta content="Abastos, Gestión de Inventario, Ventas" name="keywords">
<meta content="Daniel Barrueta" name="author">

<title> <?= COMPANY ?> </title>

<!-- Favicons -->
<link href="<?= SERVERURL; ?>/view/assets/img/logo.webp" rel="shortcut icon" type="image/x-icon">

<!-- Custom fonts for this template-->
<link href="<?= SERVERURL; ?>view/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!-- Custom styles for this template-->

<link href="<?= SERVERURL; ?>view/css/bootstrap-icons.css" rel="stylesheet">
<link href="<?= SERVERURL; ?>view/css/sweetalert2.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<?php 
// se obtiene la configuracion de la base de datos
$configuracion = ['iva' => config_model::obtener_dato('porcentaje_iva'),
    'ganancia' => config_model::obtener_dato('porcentaje_ganancia')];
?>
<!-- se obtiene el porcentaje del iva y de la ganancia para los productos -->
<script type="text/javascript">
    const IVA = <?= $configuracion['iva'] / 100 ?> ;
    const PORCENTAJE_GANANCIA = <?= $configuracion['ganancia'] / 100 ?>;
    // url de la api del router
    const URL_API = "../include/api.php";
</script>

<div class="msjFormSend"></div>