<form id="form_detalle_eliminar_impresora">
    <table class="t_impresora">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_impresora" id="id_impresora" value="{id_impresora}"></td>
            </tr>
        <tr>
            <td colspan="2">Detalle:</td>   
        </tr>
        <tr>
            <td><textarea rows="4" cols="50" name="detalle_baja">{descripcion}</textarea></td>
        </tr>
  </table>
</form>

<script>

$(document).ready(function(){

    $("#form_detalle_eliminar_impresora").on('submit',function(event){
        event.preventDefault();

        var datosUrl = $("#form_detalle_eliminar_impresora").serialize();
        
        datosUrl += "&action=eliminar";

        $.ajax({
            url: 'controlador/ImpresorasController.php',
            type: 'POST',
            data: datosUrl,
            success: function(response){
                if(response){
                    console.log("success");
                    alert("La impresora ha sido dada de baja correctamente.");
                    $("#dialogcontent_impresora").dialog("destroy").empty();
                    $("#dialogcontent_impresora").remove();
                    if("{viene}" == "normal"){
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        $("#contenedorPpal").load("controlador/ImpresorasController.php");
                    }
                    else if("{viene}" == "stock"){
                        $("#contenedorPpal").load("controlador/StockController.php", {vista: "ver_impresoras"});
                    }
                }
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    })

     
});

</script>