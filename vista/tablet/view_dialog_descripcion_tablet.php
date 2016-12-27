<form id="form_detalle_agregar_desc" autocomplete="off">
    <table class="t_monitor">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_tablet" id="id_tablet" value="{id_tablet}"></td>
            </tr>
        <tr>
            <td colspan="2">Descripcion:</td>   
        </tr>
        <tr>
            <td><textarea rows="4" cols="50" name="descripcion">{descripcion}</textarea></td>
        </tr>
  </table>
</form>

<script>

$(document).ready(function(){

    $("#form_detalle_agregar_desc").on('submit',function(event){
        event.preventDefault();

        var datosUrl = $("#form_detalle_agregar_desc").serialize();
        
        datosUrl += "&action=agregar_desc";

        $.ajax({
            url: 'controlador/TabletsController.php',
            type: 'POST',
            data: datosUrl,
            success: function(response){
            console.log(response);

                if(response){
                    console.log("success");
                    alert("La descripcion ha sido modificada correctamente.");
                    $("#dialogcontent").dialog("destroy").empty();
                    $("#dialogcontent").remove();
                    $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                }
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    });     
});

</script>