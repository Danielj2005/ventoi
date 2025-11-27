/* 
@{btn_add_card_product}
- obtiene y almacena el id del boton.
- al darle click agregará una card para registrar otro producto
*/
const btn_add_card_product = document.getElementById('btn_add_card_product');

btn_add_card_product.addEventListener('click', (e) => {
    e.preventDefault();
    $.ajax({
        data: {'module' : "añadir_productos_para_registrar", id : ""},
        url:  URL_API,
        type:  'post',
        success:function(valores){
            $(`#tableProduct`).append(valores);
        },
        error: function(){
            Swal.fire("ocurrio un error!","la solicitud no pudo ser procesada","error");
        }
    });
});