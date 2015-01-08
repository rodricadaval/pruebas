<form id="form_detalle_agregar_desc">
    <table class="t_monitor">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_switch" id="id_switch" value="{id_switch}"></td>
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
            url: 'controlador/SwitchsController.php',
            type: 'POST',
            data: datosUrl,
            success: function(response){
                if(response){
                    console.log("success");
                    alert("La descripcion ha sido modificada correctamente.");
                    $("#dialogcontent_switch").dialog("destroy").empty();
                    $("#dialogcontent_switch").remove();
                    $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                    if("{viene}" == "normal"){
                        $("#contenedorPpal").load("controlador/SwitchsController.php");
                    }
                    else if("{viene}" == "stock"){
                        $("#contenedorPpal").load("controlador/StockController.php", {vista: "ver_switchs"});
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