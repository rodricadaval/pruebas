<form id="form_toner_modificar_area" autocomplete="off">
    <table class="t_monitor">
        <tbody>
        <tr type="hidden">
               <td><input type="hidden" name="ID" id="id_toner" value="{id_toner}"></td>
           </tr>
            <tr>
               <td>
                   {select_areas}
               </td>
           </tr>    
           </tbody>       
   </table>
</form>

<script>

    $(document).ready(function(){

        $("#select_areas").removeAttr("disabled");

        $("#form_toner_modificar_area").on('submit',function(event){

            event.preventDefault();
            
            var datosUrl =    $("#form_toner_modificar_area").serialize();
            
            datosUrl += "&action=cambiar_area";

            console.log(datosUrl);

            $.ajax({
                url: 'controlador/TonersController.php',
                type: 'POST',
                data: datosUrl,
                success : function(response){
                    if(response){
                       console.log(response);
                       alert("Los datos han sido actualizados correctamente.");
                       $("#dialogcontent").dialog("destroy").empty();
                       $("#dialogcontent").remove();
                       $("#contenedorPpal").remove();
                       jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                    }).appendTo('.realBody');
                       $("#contenedorPpal").load("controlador/TonersController.php");
                   }
                   else{
                     alert("Error en la query.");
                 }
             }
         })
            .fail(function() {
                console.log("error");
                alert("Algo no se registro correctamente");
            })
            .always(function() {
                console.log("complete");
            })
        });
    });

</script>