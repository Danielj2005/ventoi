<?php

// Función para obtener el icono de permisos según el valor de $permisosVista y $limitePermisos
// Parámetros:
function obtenerIconoPermisos($permisosVista, $limitePermisos) {
    if ($permisosVista == 0) {
        return 'bi-x-circle-fill text-danger';
    } elseif ($permisosVista > 0 && $permisosVista < $limitePermisos) {
        return 'bi-dash-circle-fill text-primary';
    } elseif ($permisosVista == $limitePermisos) {
        return 'bi-check-circle-fill text-success';
    } else {
        return ''; // Icono por defecto si no coincide con ningún caso
    }
}
