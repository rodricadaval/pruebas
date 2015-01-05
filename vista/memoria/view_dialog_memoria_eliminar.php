<form id="form_detalle_eliminar_memoria">
    <table class="t_memoria">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_memoria" id="id_memoria" value="{id_memoria}"></td>
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

    $("#form_detalle_eliminar_memoria").on('submit',function(event){
        event.preventDefault();

        var datosUrl = $("#form_detalle_eliminar_memoria").serialize();
        
        datosUrl += "&action=eliminar";

        $.ajax({
            url: 'controlador/MemoriasController.php',
            type: 'POST',
            data: datosUrl,
            success: function(response){
                if(response){
                    console.log("success");
                    alert("El memoria ha sido dado de baja correctamente.");
                    $("#dialogcontent_memoria").dialog("destroy").empty();
                    $("#dialogcontent_memoria").remove();
                    if("{viene}" == "normal"){
                            $("#contenedorPpal").remove();
                            jQuery('<div/>', {
                            id: 'contenedorPpal',
                            text: 'Texto por defecto!'
                            }).appendTo('.realBody');
                            $("#contenedorPpal").load("controlador/MemoriasController.php");
                    }
                    else if("{viene}" == "stock"){
                        $("#contenedorPpal").load("controlador/StockController.php", {vista: "ver_memorias"});
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