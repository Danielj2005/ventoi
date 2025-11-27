<?php

$titleCards = ["Usuarios", "Planes", "Mensualidades", "Pagos", "Empresas", "Bitácora", "Configuración"];

$iconCards = ["people", "box-seam", "calendar", "currency-dollar", "building", "clock-history", "gear"];

$cantRegCards = ["14", "46", "44", "463", "14", "46314", "1"];

$footerCard = ["usuarios registrados", "planes registrados", "mensualidades registradas",
    "pagos registrados", "Gimnasios registrados", "movimientos del sistema.", "Configuración del sistema"];

$path = ["/user", "/plan", "/monthlyPayment", "/payments", "/enterprise", "/binnacle", "/setting"];

?>

<div class="text-white-50 mb-4"> <h1> Panel Principal </h1> </div> 

<section class="section dashboard">
    <div class="row">

        <?php foreach($titleCards as $index => $title) { ?>
            <div class="col-12 col-md-4 mb-3">
                <div class="card bg-<?= $colorCard[$index]; ?> text-<?= $colorText[$index]; ?>">
                    <div class="card-body">
                        <h5 class="card-title <?= $classTitle[$index]; ?>"> <a href="<?= $path[$index]; ?>"> <?= $title; ?> </a> </h5>
                        
                        <h2 class="card-text">
                            <i class="fs-1 bi bi-<?= $iconCards[$index]; ?>"></i>&nbsp; <?= $cantRegCards[$index]; ?>
                        </h2>
                        <p class="card-text"><small><?= $footerCard[$index]; ?></small></p>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
</section>