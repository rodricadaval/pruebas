<form id="form_num_serie" autocomplete="off">
    <table class="t_monitor">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_tablet" id="id_tablet" value="{id_tablet}"></td>
            </tr>
        <tr>
            <td colspan="2">Num Serie:</td>   
        </tr>
        <tr>
            <td><textarea rows="4" cols="50" name="num_serie">{num_serie}</textarea></td>
        </tr>
  </table>
</form>

<script>

$(document).ready(function(){

    $("#form_num_serie").on('submit',function(event){
        event.preventDefault();

        var datosUrl = $("#form_num_serie").serialize();
        
        datosUrl += "&action=agregar_num_serie";

        console.log(datosUrl);

        $.ajax({
            url: 'controlador/TabletsController.php',
            type: 'POST',
            data: datosUrl,
            success: function(response){
                if(response){
                    console.log("success");
                    alert("El num_serie ha sido modificado correctamente.");
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