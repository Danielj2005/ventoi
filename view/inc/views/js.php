

<!-- datatable js files -->
<script src="<?= SERVERURL; ?>view/js/jquery.dataTables.min.js"></script>
<script src="<?= SERVERURL; ?>view/js/datatables.min.js"></script>
<script src="<?= SERVERURL; ?>view/js/dataTables.bootstrap5.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var t = $('#example').DataTable( { 
            language: {
				url: '<?= SERVERURL; ?>view/js/dataTables-Español.json'
			},
            lengthMenu: [[5, 10, 15, 20, 25, 50, 100, -1], [5, 10, 15, 20, 25, 50, 100, "Todos"]],
            responsive: true,
        } );

        t.on( 'order.dt search.dt', function () {
            let i = 1;
    
            t.cells(null, 0, {search:'applied', order:'applied'}).every( function (cell) {
                this.data(i++);
            } );
        } ).draw();
    } );
    
    function dataTable(classTable = "example"){
        var t = $(`.${classTable}`).DataTable( { 
            language: {
                url: '<?= SERVERURL; ?>view/js/dataTables-Español.json'
            },
            lengthMenu: [[5, 10, 15, 20, 25, 50, 100, -1], [5, 10, 15, 20, 25, 50, 100, "Todos"]],
            responsive: true,
        } );

        t.on( 'order.dt search.dt', function () {
            let i = 1;
            t.cells(null, 0, {search:'applied', order:'applied'}).every( function (cell) {
                this.data(i++);
            } );
        } ).draw();
    }
    
</script>


<!-- Vendor JS Files -->
<script src="<?= SERVERURL; ?>view/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="<?= SERVERURL; ?>view/assets/vendor/chart.js/chart.umd.js"></script>
<script src="<?= SERVERURL; ?>view/assets/vendor/echarts/echarts.min.js"></script>
<script src="<?= SERVERURL; ?>view/assets/vendor/quill/quill.min.js"></script>
<script src="<?= SERVERURL; ?>view/assets/vendor/tinymce/tinymce.min.js"></script>

<!-- Template Main JS File -->
<script src="<?= SERVERURL; ?>view/js/mainScript.js"></script>

<!-- lógica de los modales -->
<script src="<?= SERVERURL; ?>view/js/configModal.js"></script>
<script src="<?= SERVERURL; ?>view/js/modal.js"></script>
<script src="<?= SERVERURL; ?>view/js/get_url.js"></script>

<!-- <script src="./js/sweet-alert.min.js"></script> -->
<script src="<?= SERVERURL; ?>view/js/tiempo_inactividad.js"></script>
<script src="<?= SERVERURL; ?>view/js/hiddeInput.js"></script>
<script src="<?= SERVERURL; ?>view/js/dolar.js"></script>
<script src="<?= SERVERURL; ?>view/js/validacion_formularios.js"></script>

<script src="<?= SERVERURL; ?>view/js/SendForm.js"></script> <!-- procesamiento de peticiones CRUD del usuario -->
<script src="<?= SERVERURL; ?>view/js/buscar_proveedor.js"></script> <!--  script para llamar la información de un proveedor -->
<script src="<?= SERVERURL; ?>view/js/buscar_datos_cliente.js"></script> <!--  script para llamar la información de un cliente -->
<script src="<?= SERVERURL; ?>view/js/procesamiento_de_dinero.js"></script> <!-- script para calcular los montos totales de un producto -->
<script src="<?= SERVERURL; ?>view/js/select2.min.js"></script> <!-- libreria selec2 -->
<script src="<?= SERVERURL; ?>view/js/cerrar_sesion.js"></script> <!-- script para cerrar sesion -->
<script src="<?= SERVERURL; ?>view/js/toastify.js"></script> <!-- script para import la libreria de alertas toastify -->

<script src="<?= SERVERURL; ?>view/js/añadir_elemento_lista.js"></script>

<script type="text/javascript">
    // inicializar la libreria Select2 
    $('.SelectTwo').select2();

    // funcion para eliminar un elemento del html
    document.addEventListener('DOMContentLoaded', () => {
        const alertHistoryt = document.querySelectorAll('.alert-history');
        alertHistoryt.forEach(alert => {
            alert.addEventListener('click', () => {
                // alert con toastify library
                Toastify({
                    text: ' Este proveedor se encuentra sin un historial de compras.',
                    className: "bi bi-exclamation-triangle-fill fs-5",
                    duration: 3000,
                    style: {
                        background: "#6c757d",
                    }
                }).showToast();
            });
        });
        
    });
</script>