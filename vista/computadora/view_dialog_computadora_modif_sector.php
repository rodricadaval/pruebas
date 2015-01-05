<form id="form_computadora_mod_sector">
    <table class="t_monitor">
        <tr><p id="text_no_puede_cambiar">La computadora no puede ser cambiada de Sector ya que tiene un usuario asignado. Debe clickear en "liberar computadora" si quiere modificar su sector o, si desea que conserve el mismo usuario, dicho usuario debe cambiar su sector.</p></tr>
        <tr type="hidden">
           <td><input type="hidden" name="id_vinculo" id="id_vinculo" value="{id_vinculo}"></td>
        </tr>
        <tr>
             <td>{select_Areas}</td>
        </tr>
        <tr><td><label id="text_pregunta">¿Desea que todos los productos asignados a esta computadora continuén asignados luego de la modificación? Si no tiene productos asignados elija cualquiera.</label></td></tr>
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

    console.log("Esta libre? : {libre}");
    console.log("El id del vinculo es:"+$("#id_vinculo").val());

    if("{libre}" == 1){

        $("#text_no_puede_cambiar").hide();
        $("#select_areas").removeAttr("disabled");
    }
    else{
        $("#select_areas").hide();
        $("#boton_radio").hide();
        $("#text_pregunta").hide();
        $("#id_vinculo").attr('disabled','disabled');
    }

    $("#form_computadora_mod_sector").on('submit',function(event){

        event.preventDefault();

        if("{libre}" == 1){

        	console.log($("#form_computadora_mod_sector").serialize());
            
        	var datosUrl =    $("#form_computadora_mod_sector").serialize();
            
            datosUrl += "&action=modificar&cuestion=sector";

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
                        if("{viene}" == "normal"){
                            $("#contenedorPpal").remove();
                            jQuery('<div/>', {
                            id: 'contenedorPpal',
                            text: 'Texto por defecto!'
                            }).appendTo('.realBody');
                            $("#contenedorPpal").load("controlador/ComputadorasController.php");
                        }
                        else if("{viene}" == "stock"){
                            $("#contenedorPpal").load("controlador/StockController.php", {vista: "ver_computadoras"});
                        }
                	}
                	else{
                	alert("Error en la query.");
                	}
                }
            })
            .fail(function() {
                console.log("error");
                alert("Algo no se registro correctaente");
            })
            .always(function() {
                console.log("complete");
            })
        }
        else{$("#dialogcontent_cpu").dialog("destroy").empty();}
    });
});

</script>