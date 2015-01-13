<form id="form_computadora_liberar" autocomplete="off">
    <table class="t_comp">
        <tr type="hidden">
           <td><input type="hidden" name="id_vinculo" id="id_vinculo" value="{id_vinculo}"></td>
        </tr>
        <tr><td><label id="text_pregunta">¿Desea que todos los productos asignados a esta computadora sigan perteneciendo a la misma? Si selecciona SI el usuario de los productos se liberará, si elige NO los productos dejarán de pertenecer a la computadora</label></td></tr>
        <tr>
                <td id="boton_radio">
                <label>                
                    <input type="radio" name="en_conjunto" value="SI" checked>SI
                    <input style="margin-left:10px;" type="radio" name="en_conjunto" value="NO">NO
                </label>
               </td>
        </tr>
    </table>
</form>

<script>

$(document).ready(function(){
    
    console.log("El id del vinculo es:"+$("#id_vinculo").val());

    $("#form_computadora_liberar").on('submit',function(event){

        event.preventDefault();

        	console.log($("#form_computadora_liberar").serialize());
            
        	var datosUrl =    $("#form_computadora_liberar").serialize();
            
            datosUrl += "&action=liberar";

            console.log(datosUrl);

            $.ajax({
                url: 'controlador/ComputadorasController.php',
                type: 'POST',
                data: datosUrl,
                success : function(response){
                    if(response){
	                    console.log(response);
	                    alert("Los datos han sido actualizados correctamente.");
	                    $("#dialogcontent_cpu").dialog("destroy").empty();
                        $("#dialogcontent_cpu").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        $("#contenedorPpal").load("controlador/ComputadorasController.php");
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