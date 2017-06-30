<form id="form_dar_baja" autocomplete="off">
    <p>¿Esta seguro que desea dar de baja los toners?</p>
    <table class="t_monitor">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="ID" id="id_toner" value="{id_toner}"></td>
           </tr>
           <tr>
            <td colspan="2">Descripcion:</td>   
        </tr>
        <tr>
            <td><textarea rows="4" cols="50" name="descripcion" id="descripcion">{descripcion}</textarea></td>
        </tr>
    </table>
</form>
<script>

    $(document).ready(function(){

        $("#form_dar_baja").on('submit',function(event){
            event.preventDefault();

            var datosUrl = $("#form_dar_baja").serialize();

            datosUrl += "&action=dar_baja";

            console.log(datosUrl);

            var id_toner = $("#form_dar_baja #id_toner").attr("value");

            $.ajax({
                url: 'controlador/TonersController.php',
                type: 'POST',
                data: datosUrl,
                success: function(response){
                    if(response){
                        console.log("success");
                        alert("Los toners fueron dados de baja.");
                        $("#dialogcontent").dialog("destroy").empty();
                        $("#dialogcontent").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                            id: 'contenedorPpal',
                            text: 'Texto por defecto!'
                        }).appendTo('.realBody');

                        $("#contenedorPpal").load("controlador/TonersController.php");

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