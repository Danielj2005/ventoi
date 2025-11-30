<aside id="sidebar" class="sidebar bg-dark">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- apartado de página principal -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="<?= SERVERURL; ?>dashboard">
                <i class="bi bi-speedometer2"></i>
                <span>Panel de Control</span>
            </a>
        </li>

        <!-- módulo de Inventario -->
        
        <li class="nav-item">

            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-box-seam-fill"></i>
                <span>Inventario</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                
                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>producto">
                        <i class="bi bi-circle"></i>
                        <span>Gestión de Productos</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>entrada">
                        <i class="bi bi-circle"></i>
                        <span>Registro de Compras</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>proveedor">
                        <i class="bi bi-circle"></i>
                        <span>Gestión de Proveedores</span>
                    </a>
                </li>
                
            </ul>
        </li>

        <!-- módulo de servicios -->

        <li class="nav-item">
            <a href="gestion_servicios.php" class="nav-link collapsed">
                <i class="bi bi-person-workspace"></i>
                <span> Gestión de Servicios</span>
            </a>
        </li>

        <!-- módulo de ventas -->

        <li class="nav-item">
            
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-currency-dollar"></i>
                <span>Ventas</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">


                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>generar_venta.php">
                        <i class="bi bi-circle"></i>
                        <span>Generar venta</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>venta.php">
                        <i class="bi bi-circle"></i>
                        <span>Historial de Ventas</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>estadisticas_generales.php">
                        <i class="bi bi-graph-up"></i>
                        <span>Análisis de Ventas</span>
                    </a>
                </li>
                
            </ul>
        </li>

        <!-- módulo de usuarios -->
        
        <li class="nav-item">

            <a class="nav-link collapsed" data-bs-target="#user-list" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people-fill"></i>
                <span>Usuarios</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="user-list" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            
                <!-- modulo de clientes -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>cliente.php">
                        <i class="bi bi-circle"></i>
                        <span>Clientes</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>empleados.php">
                        <i class="bi bi-circle"></i>
                        <span>Empleados</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>roles.php">
                        <i class="bi bi-circle"></i>
                        <span>Gestión de Roles</span>
                    </a>
                </li>

            </ul>
        </li>
        
        <!-- apartado del perfil de usuario  -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="<?= SERVERURL; ?>mi_perfil.php">
                <i class="bi bi-person-fill"></i>
                <span>Mi Perfil</span>
            </a>
        </li>

        <!-- módulo de configuracion -->
        
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#setting-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-gear-fill"></i>
                <span>Configuración General</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="setting-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            
                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>configuracion.php">
                        <i class="bi bi-circle"></i>
                        <span>Ajustes del Sistema</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>bitacora.php">
                        <i class="bi bi-circle"></i>
                        <span>Bitácora</span>
                    </a>
                </li>
            </ul>
        </li>
            

        <!-- apartado de ayuda  -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#ayuda-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-question-circle-fill"></i>
                <span>Soporte y Documentación</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="ayuda-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>manuales/MANUAL_DE_USUARIO_CHINITA.pdf" target="_blank">
                        <i class="bi bi-book"></i>
                        <span>Manual de Usuario</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>manuales/MANUAL_DE_INSTALACION_CHINITA.pdf" target="_blank">
                        <i class="bi bi-wrench"></i>
                        <span>Guía de Instalación Técnica</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= SERVERURL; ?>manuales/MANUAL_DE_SISTEMA_CHINITA.pdf" target="_blank">
                        <i class="bi bi-laptop"></i>
                        <span>Manual de Referencia</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <button class="nav-link collapsed btn-exit-system">
                <i class="bi bi-box-arrow-right"></i>
                <span>Cerrar Sesión</span>
            </button>
        </li>
    </ul>
</aside>