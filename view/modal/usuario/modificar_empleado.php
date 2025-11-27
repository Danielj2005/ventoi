<?php
// se importan los archivos de configuracion de la base de datos y modelo principal
include_once("../../../modelo/modeloPrincipal.php");

$id_usuario = modeloPrincipal::decryptionId($_POST['id']);

$existe = mysqli_fetch_assoc(modeloPrincipal::consultar("SELECT U.id_usuario, U.cedula, U.nombre,
    U.apellido, U.telefono, U.estado, R.id_rol, R.nombre AS nombre_rol 
    FROM usuario AS U
    INNER JOIN rol AS R ON R.id_rol = U.id_rol
    WHERE id_usuario = $id_usuario"));

?>

<form id="modalSendForm" 
    action="../controlador/usuario_controller.php"
    method="post"
    class="SendFormAjax row mb-4" 
    autocomplete="off" 
    data-type-form="update">

    <input type="hidden" name="UIDTM" id="id_usuario" value="<?= modeloPrincipal::encryptionId($existe["id_usuario"]); ?>">
    <input type="hidden" name="modulo" value="caracteristicas_de_acceso">

    <h6 class="fw-bold text-primary border-bottom pb-2">Información Básica del Empleado</h6>

    <div class="col-12 col-md-4">
        <label for="cedula_user" class="form-label">Cédula de Identidad</label>
        <input type="text" 
            value="<?= $existe['cedula'] ?>" 
            class="form-control bg-body-secondary" 
            id="cedula_user" 
            name="cedula_user"
            readonly>
    </div>

    <div class="col-12 col-md-4">
        <label for="nombre_completo" class="form-label">Nombres y Apellidos</label>
        <input type="text" 
            value="<?= $existe['nombre'] . ' ' . $existe['apellido'] ?>" 
            class="form-control bg-body-secondary" 
            id="nombre_completo" 
            name="nombre_completo"
            readonly>
    </div>
    
    <div class="col-12 col-md-4">
        <label for="telefono_user" class="form-label">Teléfono</label>
        <input type="text" 
            value="<?= $existe['telefono'] ?? 'N/A' ?>" placeholder="ingresa el teléfono del usuario" 
            class="form-control bg-body-secondary" 
            id="telefono_user" 
            name="telefono_user"
            required>
    </div>

    <h6 class="fw-bold text-success border-bottom pb-2 mt-4">Configuración de Acceso</h6>

    <div class="col-12 col-md-6">
        <label for="cambiar_estado" class="form-label">
            Estado Actual: 
            <span class="fw-bold <?= ($existe['estado'] == 1) ? 'text-success' : 'text-danger'; ?>">
                <?= ($existe['estado'] == 1) ? 'ACTIVO' : 'INACTIVO'; ?>
            </span>
        </label>
        <select class="form-select" name="cambiar_estado" id="cambiar_estado">
            <option value="1" <?= ($existe['estado'] == 1) ? 'selected' : ''; ?>>Activar Empleado</option>
            <option value="0" <?= ($existe['estado'] == 0) ? 'selected' : ''; ?>>Inactivar Empleado</option>
        </select>
    </div>

    <div class="col-12 col-md-6">
        <label for="asignar_rol" class="form-label">
            Rol Asignado: <strong class="text-info"><?= $existe['nombre_rol'] ?></strong>
        </label>
        <select class="form-select" name="asignar_rol" id="asignar_rol">
            <?php
            // Se asume que modeloPrincipal::consultar devuelve un resultado mysqli válido.
            $roles = modeloPrincipal::consultar("SELECT id_rol, nombre FROM rol WHERE estado = 1 AND id_rol != 1");

            while ($row = mysqli_fetch_array($roles)) { ?>
                <option <?= $existe['id_rol'] == $row['id_rol'] ? 'selected' : '' ?> value="<?= $row['id_rol']; ?>"> 
                    <?= $row['nombre']; ?> 
                </option>
            <?php } ?>
        </select>
    </div>
</form>

<div class="text-center mt-1 pt-3 border-top">
    <h6 class="fw-bold text-danger mb-3">Acción de Mantenimiento</h6>
    
    <form id="resetPasswordForm" 
        action="../controlador/usuario_controller.php" 
        method="post" 
        class="SendFormAjax d-inline-block" 
        autocomplete="off" 
        data-type-form="update">
        
        <input type="hidden" name="modulo" value="resetear_contraseña">
        <input type="hidden" name="UUIDU" value="<?= modeloPrincipal::encryptionId($existe["id_usuario"]); ?>">

        <button type="submit" class="btn btn-warning shadow-sm" title="Restablecer la contraseña del usuario a un valor por defecto.">
            <i class="bi bi-key-fill me-2"></i>
            Resetear Contraseña
        </button>
    </form>
</div>
