<form id="form_liberar_toner" autocomplete="off">
	<p>¿Esta seguro que desea volver los toners al stock?.</p>
	<input type="hidden" name="id_toner" id="id_toner" value="{id_toner}">
</form>

<script type="text/javascript">
	
	$("#form_liberar_toner").on('submit',function(event){
		event.preventDefault();

			var id_toner = $("#id_toner").val();
		
    		$.post( "controlador/TonersController.php",
				{
					ID : id_toner,
					action : "liberar",
				}, function(data){
						alert("Se devuelve los toners al stock.");
						$("#dialogcontent").dialog("destroy").empty();
	                    $("#dialogcontent").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        $("#contenedorPpal").load("controlador/TonersController.php");					    
				}
			);

	});

</script>