<!-- Custom styles for this template-->

<link href="<?= SERVERURL; ?>view/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= SERVERURL; ?>view/css/bootstrap-icons.css" rel="stylesheet">
<link href="<?= SERVERURL; ?>view/css/sweetalert2.min.css" rel="stylesheet">

<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<link href="<?= SERVERURL; ?>view/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="<?= SERVERURL; ?>view/css/toastify.css" rel="stylesheet">

<link href="<?= SERVERURL; ?>view/css/select2.min.css" rel="stylesheet">

<link href="<?= SERVERURL; ?>view/css/style2.css" rel="stylesheet">

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
    const URL_API = "./view/inc/api.php";
</script>

<div class="msjFormSend"></div>