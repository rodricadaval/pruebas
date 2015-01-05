<form id="form_detalle_eliminar_monitor">
    <table class="t_monitor">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_monitor" id="id_monitor" value="{id_monitor}"></td>
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

    $("#form_detalle_eliminar_monitor").on('submit',function(event){
        event.preventDefault();

        var datosUrl = $("#form_detalle_eliminar_monitor").serialize();
        
        datosUrl += "&action=eliminar";

        $.ajax({
            url: 'controlador/MonitoresController.php',
            type: 'POST',
            data: datosUrl,
            success: function(response){
                if(response){
                    console.log("success");
                    alert("El monitor ha sido dado de baja correctamente.");
                    $("#dialogcontent_monitor").dialog("destroy").empty();
                    $("#dialogcontent_monitor").remove();
                    $("#contenedorPpal").remove();
                    jQuery('<div/>', {
                    id: 'contenedorPpal',
                    text: 'Texto por defecto!'
                    }).appendTo('.realBody');
                    if("{viene}" == "normal"){
                        $("#contenedorPpal").load("controlador/MonitoresController.php");
                    }
                    else if("{viene}" == "stock"){
                       $("#contenedorPpal").load("controlador/StockController.php", {vista: "ver_monitores"});
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