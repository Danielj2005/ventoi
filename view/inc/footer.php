
<div class="modal fade" id="dolarUpdate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update_proveedor" action="../controlador/dolar.php" method="post" class="SendFormAjax" data-type-form="update">   
                <input type="hidden" name="manera" value="manual">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Actualización de la Tasa del Dolar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                            <label> Precio del Dolar <span style="color: red; font-size: 20px;"> * </span></label>
                            <input type="number" step="any" min="1" max="1000" class="modificar_proveedor form-control" id="priceDolar" name="priceDolar" placeholder="ingresa el precio del dolar">
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-3">
                                <p> Los campos con <span style="color: red; font-size: 20px;"> * </span> son obligatorios </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
    <div class="copyright">
        &copy; <strong><span>sistema de control de pagos y mensualidades de gimnasios 2025</span></strong>
    </div>
    <div class="credits">
        <p>DESAROLLADO POR: DANIEL BARRUETA </p>
    </div>
</footer>
<!-- End Footer -->