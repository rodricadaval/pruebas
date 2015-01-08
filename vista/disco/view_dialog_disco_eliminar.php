<form id="form_detalle_eliminar_disco">
    <table class="t_disco">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_disco" id="id_disco" value="{id_disco}"></td>
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

    $("#form_detalle_eliminar_disco").on('submit',function(event){
        event.preventDefault();

        var datosUrl = $("#form_detalle_eliminar_disco").serialize();
        
        datosUrl += "&action=eliminar";

        $.ajax({
            url: 'controlador/DiscosController.php',
            type: 'POST',
            data: datosUrl,
            success: function(response){
                if(response){
                    console.log("success");
                    alert("El disco ha sido dado de baja correctamente.");
                    $("#dialogcontent_disco").dialog("destroy").empty();
                    $("#dialogcontent_disco").remove();
                    $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                    if("{viene}" == "normal"){
                            $("#contenedorPpal").load("controlador/DiscosController.php");
                    }
                    else if("{viene}" == "stock"){
                        $("#contenedorPpal").load("controlador/StockController.php", {vista: "ver_discos"});
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