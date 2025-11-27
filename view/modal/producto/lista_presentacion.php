<?php 
session_start();

require_once "../../../model/modeloPrincipal.php";
require_once "../../../model/presentacion_model.php";
require_once "../../../model/rol_model.php";

?>
<div class="table table-responsive">
    <table class="table table-striped tablePresentationOfProducts mb-3" id="tablePresentationOfProducts">
        <thead>
            <tr>
                <th class="col text-center" scope="col">#</th>
                <th class="col text-center" scope="col">Nombre</th>
                <th class="col text-center" scope="col">Descripción</th>
                <?php if (modeloPrincipal::verificar_permisos_requeridos(['m_presentacion'])) { ?>
                    <th class="col text-center" scope="col">Estado</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php presentacion_model::lista(); ?>  
        </tbody>
    </table>
</div>