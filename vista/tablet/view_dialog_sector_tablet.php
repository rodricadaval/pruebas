<form id="form_sector_tablet" autocomplete="off">
    <table class="t_monitor">
        <tr><p id="text_no_puede_cambiar">La computadora no puede ser cambiada de Sector ya que tiene un usuario asignado. Debe clickear en "liberar computadora" si quiere modificar su sector o, si desea que conserve el mismo usuario, dicho usuario debe cambiar su sector.</p></tr>
        <tr>
             <td>{sectores}</td>
        </tr>
        <tr><td><label id="text_pregunta">¿Desea que la tablet vuelva a stock?</label></td></tr>
    </table>
</form>

<script>

$(document).ready(function(){

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

    $("#form_sector_tablet").on('submit',function(event){

        event.preventDefault();

        if("{libre}" == 1){

        	console.log($("#form_sector_tablet").serialize());
            
        	var datosUrl =    $("#form_sector_tablet").serialize();
            
            datosUrl += "&action=modificar&cuestion=sector";

            console.log(datosUrl);

            $.ajax({
                url: 'controlador/TabletsController.php',
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
        }
        else{$("#dialogcontent").dialog("destroy").empty();}
    });
});

</script>