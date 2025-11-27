<?php 

$primer_inicio = $_SESSION['primer_inicio'];

if($primer_inicio == '1'){
    echo "<script type='text/javascript'>
            window.location.href='./mi_perfil.php';
        </script>";
    exit();
}