const ICONS = {
    modify: '<i class="bi bi-pencil-square"></i> &nbsp;',
    list: '<i class="bi bi-list-columns-reverse"></i> &nbsp;',
    reg: '<i class="bi bi-plus-circle"></i> &nbsp;',

}

const dataModal = {
    // modales del módulo presentaciones
    listaPresentacion : {
        modalUrl : "./view/modal/producto/lista_presentacion.php",
        modalTitle : `${ICONS.list} Lista de Presentaciones Registradas`,
        modalSendForm : true,
        modalSize : 'modal-lg',
        modalDataTable : true,
        modalClassTable : "tablePresentationOfProducts"
    },
    registrarPresentacion : {
        modalUrl : "./view/modal/producto/registrarPresentacion.php",
        modalTitle :  `${ICONS.reg} Registrar Nueva Presentaciones`,
        modalSendForm : true,
    },

    // modales del módulo Categorias
    registrarCategoria : {
        modalUrl : "./view/modal/producto/registrarCategoria.php",
        modalTitle :  `${ICONS.reg} Registrar Nueva Categoría`,
        modalSendForm : true,
    },

    listaCategoria : {
        modalUrl : "./view/modal/producto/lista_categoria.php",
        modalTitle : `${ICONS.list} Lista de Categorías Registradas`,
        modalSize : 'modal-lg',
        modalSendForm : true,
        modalDataTable : true,
        modalClassTable : "tableCategoryOfProducts"
    },

    // modales del módulo Marcas
    listaMarca : {
        modalUrl : "./view/modal/producto/lista_marcas.php",
        modalTitle : `${ICONS.list} Lista de Marcas Registradas`,
        modalSize : 'modal-lg',
        modalSendForm : true,
        modalDataTable : true,
        modalClassTable : "tableTrademarkOfProducts"
    },
    registrarMarca : {
        modalUrl : "./view/modal/producto/registrarMarca.php",
        modalTitle : `${ICONS.reg} Registrar Nueva Marca`,
        modalSendForm : true,
    },

    // modales del módulo proveedores
    proveedorDetalles : {
        modalUrl : "./view/modal/proveedor/detalles.php",
        modalTitle : `${ICONS.list} Detalles del Proveedor`,
    },
    proveedorModificar : {
        modalUrl : "./view/modal/proveedor/modificar.php",
        modalTitle : `${ICONS.modify} Modificar Proveedor`,
        modalSendForm : true,
    },
    proveedorHistorial : {
        modalUrl : "./view/modal/proveedor/historial.php",
        modalTitle : `${ICONS.list} Detalles de Compras al Proveedor`,
        modalSize : 'modal-xl',
        modalDataTable : true,
        modalClassTable : "tableProvider"
    },

    // modales del módulo entradas
    detallesEntrada : {
        modalUrl : "./view/modal/producto/detalles_entrada.php",
        modalTitle : '<i class="bi bi-list-columns-reverse"></i> &nbsp; Detalles de la Compra',
        modalSize : 'modal-xl',
        modalDataTable : true,
        modalClassTable : "tableDetailsEntry"
    },

    // modales del módulo bitácora
    bitacora : {
        modalUrl : "./view/modal/bitacora/detalles_bitacora.php",
        modalTitle : '<i class="bi bi-list-columns-reverse"></i>&nbsp;Detalle de la Bitácora',
        modalDataTable : true,
        modalClassTable : "tableDetailsBitacora",
        modalSize: "modal-lg"
    },

    // modales del módulo Servicio
    servicioModificar : {
        modalUrl : "./view/modal/servicio/modificar_servicio.php",
        modalTitle : `${ICONS.modify} Modificar Servicio`,
        modalSendForm : true,
        modalSize: "modal-lg",
        modalDataTable : true,
        modalClassTable : "tableModifyService",
    },
    servicioDetalles : {
        modalUrl : "./view/modal/servicio/detalles.php",
        modalTitle : `${ICONS.list} Detalles del Servicio`,
        modalDataTable : true,
        modalClassTable : "tableDetailsService",
        modalSize: "modal-lg"
    },

    // modales del módulo Venta
    ventaDetalles : {
        modalUrl : "./view/modal/venta/ventas_diarias.php",
        modalTitle : `${ICONS.list} Detalles de la Venta`,
    },

    // modales del módulo Cliente
    clienteModificar : {
        modalUrl : "./view/modal/cliente/modificar_cliente.php",
        modalTitle : `${ICONS.modify} Modificar Servicio`,
        modalSendForm : true,
    },
    clienteHistorial : {
        modalUrl : "./view/modal/cliente/historial_clientes.php",
        modalTitle : `${ICONS.list} Detalles del Cliente`,
        modalDataTable : true,
        modalClassTable : "tableDetailsClient",
        modalSize: "modal-lg"
    },

    // modales del módulo usuario
    usuarioModificar : {
        modalUrl : "./view/modal/usuario/modificar_empleado.php",
        modalTitle : `${ICONS.modify} Configurar Acceso y Rol de Empleado`,
        modalSendForm : true,
        modalSize: "modal-lg"
    },

    modificarInfoPersonalUsuario : {
        modalUrl : "./view/modal/usuario/modificar_info_personal_usuario.php",
        modalTitle : `${ICONS.modify} Configuración de la información personal del usuario`,
        modalSendForm : true
    },

    passwordUser : {
        modalUrl : "./view/modal/usuario/modificar_contraseña_usuario.php",
        modalTitle : `${ICONS.modify} Configuración o Cambio de la Contraseña`,
        modalSendForm : true
    },

    preguntasSeguridad : {
        modalUrl : "./view/modal/usuario/modificar_preguntas_seguridad.php",
        modalTitle : `${ICONS.modify} Configuración de las Preguntas y Respuestas de Seguridad`,
        modalSendForm : true,
        modalSize: "modal-lg"
    },



    // modales del módulo roles
    rolDetalles : {
        modalUrl : "./view/modal/rol/permisos_rol.php",
        modalTitle : `${ICONS.list} Detalles de Acceso del Rol`,
        modalSize: "modal-xl"
    },
    rolModificar : {
        modalUrl : "./view/modal/rol/modificar_rol.php",
        modalTitle : `${ICONS.modify} Configurar Acceso del Rol`,
        modalSendForm : true,
        modalSize: "modal-xl",
        modalModule: "modify-rol"
    },



};

//     "datos_usuario": '<i class="bi bi-person-circle"></i> &nbsp; Actualizar datos de la cuenta del usuario',
//     "modificar_info_personal_usuario": '<i class="bi bi-person-plus"></i> &nbsp; Actualizar información personal',
//     "preguntas_seguridad": '<i class="bi bi-shield-plus"></i> &nbsp; Actualizar preguntas de seguridad del usuario',
