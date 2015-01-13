<form id="liberar_memoria" autocomplete="off">
	<p>¿Esta seguro que desea liberar el memoria? El usuario y la computadora que tenía asignados se perderán.</p>
	<!--<input type="hidden" name="boton" id="boton" value="enviar">-->
	<input type="hidden" name="id_memoria" id="id_memoria" value="{id_memoria}">
</form>

<script type="text/javascript">
	
	$("#liberar_memoria").on('submit',function(event){
		event.preventDefault();

			var id_memoria = $("#id_memoria").val();
		
    		$.post( "controlador/MemoriasController.php",
				{
					action : "liberar",
					id_memoria : id_memoria
				}, function(data){
					if(data == "true"){
						console.log("Entro a cambiar de contenedor");
						alert("Los datos han sido actualizados correctamente.");
	               	}
					else if (data == "false"){
						alert("Hubo un error en el código. Revisar");
						console.log("Hubo un error.");
					}	
						$("#dialogcontent_memoria").dialog("destroy").empty();
	                    $("#dialogcontent_memoria").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
	                    $("#contenedorPpal").load("controlador/MemoriasController.php");
					    
				}
			);

	});

</script>