<form id="liberar_disco" autocomplete="off">
	<p>¿Esta seguro que desea liberar el disco? El usuario y la computadora que tenía asignados se perderán.</p>
	<input type="hidden" name="id_disco" id="id_disco" value="{id_disco}">
</form>

<script type="text/javascript">
	
	$("#liberar_disco").on('submit',function(event){
		event.preventDefault();

			var id_disco = $("#id_disco").val();
		
    		$.post( "controlador/DiscosController.php",
				{
					action : "liberar",
					id_disco : id_disco
				}, function(data){
					if(data == "true"){
						console.log("Entro a cambiar de contenedor");
						alert("Los datos han sido actualizados correctamente.");
	               	}
					else if (data == "false"){
						alert("Hubo un error en el código. Revisar");
						console.log("Hubo un error.");
					}	
						$("#dialogcontent_disco").dialog("destroy").empty();
	                    $("#dialogcontent_disco").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
	                    $("#contenedorPpal").load("controlador/DiscosController.php");
					    
				}
			);

	});

</script>