
// funcion para validar el rango de fechas en las que se quiere ver las entradas registradas
function dateValidate(){
    const inputDateStart = document.getElementById('fecha_inicio').value // input de la fecha de inicio (desde)
    const inputDateEnd = document.getElementById('fecha_fin').value; // input de la fecha de fin (Hasta)
    const dateToday = document.getElementById('fecha_actual').value; // input que contiene la fecha actual
    const btn_enviar = document.getElementById('btn_fechas'); // boton encargado de solicitar las entradas en base a un rango de fechas 

    let msjDate = document.getElementById('mensaje_fecha_iguales'); // contenedor del mensaje de error sobre las fechas 

    if (inputDateStart != "" && inputDateEnd != "") {

        if (inputDateStart > inputDateEnd || inputDateStart > dateToday || inputDateEnd > dateToday) {
            msjDate.classList.remove('d-none');
            
            btn_enviar.setAttribute('disabled','disabled');
            btn_enviar.classList.add('btn-outline-secondary');
            btn_enviar.classList.remove('btn-outline-primary');
        }else{
            msjDate.classList.add('d-none');

            btn_enviar.classList.remove('btn-outline-secondary');
            btn_enviar.classList.add('btn-outline-primary');
            btn_enviar.removeAttribute('disabled');
        }
    }
}


// Esta funcionalidad se encarga de mostrar un boton 
// [const btnReportesFechas = document.getElementById('btnReportesFechas');]
// para generar un reporte por fechas de las entradas registradas en el sistema
const reportDates = document.querySelectorAll('.reportDates');

reportDates.forEach(input => {
    input.addEventListener('change', () => {
        const btnReportesFechas = document.getElementById('btnReportesFechas');

        const msjDate = document.querySelector('.showThis');
        const dateToday = document.getElementById('fecha_actual').value;
        const fechaReporteInicio = document.getElementById('fechaReporteInicio').value;
        const fechaReporteFin = document.getElementById('fechaReporteFin').value;

        if (fechaReporteInicio != "" && fechaReporteFin != "") {

            if (fechaReporteInicio > fechaReporteFin || fechaReporteInicio > dateToday || fechaReporteFin > dateToday) {
                msjDate.classList.contains('d-none') ? msjDate.classList.remove('d-none') : '';
                btnReportesFechas.classList.contains('d-none') ? '' : btnReportesFechas.classList.add('d-none');
            }else{
                msjDate.classList.contains('d-none') ? '' : msjDate.classList.add('d-none');
                btnReportesFechas.classList.contains('d-none') ? btnReportesFechas.classList.remove('d-none') : btnReportesFechas.classList.add('d-none');
            }
            limpiar_boton_reportes_por_fecha();
        }
    });
});

// Esta funcionalidad se encarga de resetear el input de las fechas seleccionadas para el reporte de entradas
// y también se encarga de ocultar nuevamente el boton de generar reporte.
const limpiar_boton_reportes_por_fecha = () => {
    const btnReportesFechas = document.getElementById('btnReportesFechas');

    btnReportesFechas.addEventListener('click', ()=>{
        setTimeout(() => {
            document.getElementById('fechaReporteInicio').value = '';
            btnReportesFechas.classList.add('d-none');
        }, 2000);
    })
};