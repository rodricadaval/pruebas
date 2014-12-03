<form id="form_cambiar_tipo_computadora">
	<table>
		<tr>
			<td><input type="hidden" name="id_computadora" id="id_computadora" value="{id_computadora}"></td>
		</tr>
		<tr>
			<td>Tipo:</td>
			<td>{select_clases}</td>
		</tr>
	</table>
</form>

<script>

$(document).ready(function(){
	
	$("#form_cambiar_tipo_computadora").on('submit',function(event){
		event.preventDefault();

   			console.log($("#form_cambiar_tipo_computadora").serialize());
    
        	var datosUrl =    $("#form_cambiar_tipo_computadora").serialize();
           
            datosUrl += "&action=modificar&cuestion=tipo";

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
	                    $("#contenedorPpal").load("controlador/ComputadorasController.php");
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
    });
});

</script>