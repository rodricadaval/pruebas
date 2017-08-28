<form id="form_reactivar" autocomplete="off">
    <p>¿Esta seguro que desea devolver a activos la pc que fue dada de baja?</p>
    <table class="t_monitor">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_computadora" id="id_computadora" value="{id_computadora}"></td>
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

        $("#form_reactivar").on('submit',function(event){
            event.preventDefault();

            var datosUrl = $("#form_reactivar").serialize();

            datosUrl += "&action=reactivar";

            var id_computadora = $("#form_reactivar #id_computadora").attr("value");

            $.ajax({
                url: 'controlador/ComputadorasController.php',
                type: 'POST',
                data: datosUrl,
                success: function(response){
                    if(response){
                        console.log("success");
                        alert("La computadora se devolvio a activas.");
                        $("#dialogcontent").dialog("destroy").empty();
                        $("#dialogcontent").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                            id: 'contenedorPpal',
                            text: 'Texto por defecto!'
                        }).appendTo('.realBody');

                        $("#contenedorPpal").load("controlador/ComputadorasController.php");

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