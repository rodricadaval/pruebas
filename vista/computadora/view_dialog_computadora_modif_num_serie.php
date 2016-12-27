<form id="form_modif_num_serie" autocomplete="off">
    <table class="t_monitor">
        <tr>
            <tr type="hidden">
             <td><input type="hidden" name="id_computadora" id="id_computadora" value="{id_computadora}"></td>
         </tr>
         <tr>
            <td colspan="2">Num serie:</td>   
        </tr>
        <tr>
            <td><textarea rows="4" cols="50" name="num_serie">{num_serie}</textarea></td>
        </tr>
    </table>
</form>

<script>

    $(document).ready(function(){

        $("#form_modif_num_serie").on('submit',function(event){
            event.preventDefault();

            var datosUrl = $("#form_modif_num_serie").serialize();

            datosUrl += "&action=modif_num_serie";

            console.log(datosUrl);

            $.ajax({
                url: 'controlador/ComputadorasController.php',
                type: 'POST',
                data: datosUrl,
                success: function(response){
                    if(response){
                        console.log("success");
                        alert("El numero de serie ha sido modificada correctamente.");
                        $("#dialogcontent_cpu").dialog("destroy").empty();
                        $("#dialogcontent_cpu").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                            id: 'contenedorPpal',
                            text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        if("{viene}" == "normal"){
                            $("#contenedorPpal").load("controlador/ComputadorasController.php");
                        }
                        else if("{viene}" == "stock"){
                            $("#contenedorPpal").load("controlador/StockController.php", {vista: "ver_computadoras"});
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