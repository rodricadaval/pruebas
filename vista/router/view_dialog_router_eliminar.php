<form id="form_detalle_eliminar_router">
    <table class="t_router">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_router" id="id_router" value="{id_router}"></td>
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

    $("#form_detalle_eliminar_router").on('submit',function(event){
        event.preventDefault();

        var datosUrl = $("#form_detalle_eliminar_router").serialize();
        
        datosUrl += "&action=eliminar";

        $.ajax({
            url: 'controlador/RoutersController.php',
            type: 'POST',
            data: datosUrl,
            success: function(response){
                if(response){
                    console.log("success");
                    alert("La router ha sido dada de baja correctamente.");
                    $("#dialogcontent_router").dialog("destroy").empty();
                    $("#dialogcontent_router").remove();
                   $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                    if("{viene}" == "normal"){
                         $("#contenedorPpal").load("controlador/RoutersController.php");
                    }
                    else if("{viene}" == "stock"){
                        $("#contenedorPpal").load("controlador/StockController.php", {vista: "ver_routers"});
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