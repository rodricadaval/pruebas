<form id="liberar_monitor" autocomplete="off">
	<p>¿Esta seguro que desea liberar el monitor? El usuario y la computadora que tenía asignados se perderán.</p>
	<!--<input type="hidden" name="boton" id="boton" value="enviar">-->
	<input type="hidden" name="id_monitor" id="id_monitor" value="{id_monitor}">
</form>

<script type="text/javascript">
	
	$("#liberar_monitor").on('submit',function(event){
		event.preventDefault();

			var id_monitor = $("#id_monitor").val();
		
    		$.post( "controlador/MonitoresController.php",
				{
					action : "liberar",
					id_monitor : id_monitor
				}, function(data){
					if(data == "true"){
						console.log("Entro a cambiar de contenedor");
						alert("Los datos han sido actualizados correctamente.");
	               	}
					else if (data == "false"){
						alert("Hubo un error en el código. Revisar");
						console.log("Hubo un error.");
					}	
						$("#dialogcontent_monitor").dialog("destroy").empty();
	                    $("#dialogcontent_monitor").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        $("#contenedorPpal").load("controlador/MonitoresController.php");					    
				}
			);

	});

</script>