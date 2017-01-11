<form id="form_dar_baja" autocomplete="off">
    <p>¿Esta seguro que desea dar de baja la tablet?</p>
    <table class="t_monitor">
        <tr>
            <tr type="hidden">
               <td><input type="hidden" name="id_tablet" id="id_tablet" value="{id_tablet}"></td>
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

            datosUrl += "&action=baja";

            console.log(datosUrl);

            var id_tablet = $("#form_dar_baja #id_tablet").attr("value");
            window.open("controlador/BAJAPDF.php?"+"id_tablet="+id_tablet);

            $.ajax({
                url: 'controlador/TabletsController.php',
                type: 'POST',
                data: datosUrl,
                success: function(response){
                    if(response){
                        console.log("success");
                        alert("La tablet fue dada de baja.");
                        $("#dialogcontent").dialog("destroy").empty();
                        $("#dialogcontent").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                            id: 'contenedorPpal',
                            text: 'Texto por defecto!'
                        }).appendTo('.realBody');

                        $("#contenedorPpal").load("controlador/TabletsController.php");

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