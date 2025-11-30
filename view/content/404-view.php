<!DOCTYPE html>
<html lang="<?= LANG ?>">
    <head>
        <?php 
            // <!-- metadatos -->  
            include_once "./view/inc/meta.php";
        ?>
        <!-- Custom styles for this template-->

        <link href="<?= SERVERURL; ?>view/css/bootstrap-icons.css" rel="stylesheet">
        <link href="<?= SERVERURL; ?>view/css/sweetalert2.min.css" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    </head>
    <body id="page-top" class="index-page bg-dark-subtle" data-bs-theme="dark">

        <main class="main">
            
            <div class="text-center my-5 d-flex justify-content-center">
                <div class="text-center my-5">
                    <h1 class="error mx-auto" data-text="404">404</h1>
                    <h5 class="lead text-gray-800 mb-5">Página no encontrada</h5>
                    <a class="btn btn-outldanger btn-outlinedanger btn-outline-danger" href="<?= SERVERURL.'/login'; ?>">&larr; Volver al Panel</a>
                </div>
            </div>

        </main>
    </body>
</html>
