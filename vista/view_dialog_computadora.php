<form id="form_computadora">
    <table class="t_monitor">
        <tr>
        <tr type="hidden">
           <td><input type="hidden" name="id_vinculo" id="id_vinculo" value="{id_vinculo}"></td>
        </tr>
        <tr>
          <td>Sector:</td>
 		  <td>{select_Areas}</td>
        </tr>
        <tr>
          <td>Usuario:</td>
          <td>{select_Usuarios}</td>
        </tr>
        <tr>
          <td><input style="background-color:#D3D3D3" type="hidden" name="id_computadora" id="id_computadora" value="{id_computadora}" readonly></td>
        </tr>
        </br>
        <tr>
          <td><input type="submit" id="submit" tabindex="-1"></td>
          <td></td>
        </tr>
   </table>
</form>

<script>

$(document).ready(function(){

	var fueRegalo = false;

    $('#select_areas').removeAttr('disabled');

    $('#select_areas').on('change', function(){
    	console.log('Entro al cambio de area');

		if($('#select_areas').val() == 1){
			console.log('El area es Stock');
			$('#select_usuarios_computadora option:contains("Ninguno")').prop('selected', true);
			$('#select_usuarios_computadora').attr('disabled', 'disabled');
		}
		else{

			if(fueRegalo){

					if($('#select_usuarios_computadora option:selected').val() > 1 && $('#select_areas option:selected').val() != 2){

						$.post('controlador/UsuariosController.php',
						{
							id_usuario : $('#select_usuarios_computadora option:selected').val(),
							action : "buscar_area"

						}, function(id_area) {
								$('#select_areas').removeAttr('disabled');
								$('#select_areas option[value='+id_area+']').attr('selected', 'selected');
								$('#select_areas').attr('disabled', 'disabled');
						});

					}
					fueRegalo = false;
			}
			else{
				if($('#select_areas').val() == 2){
				fueRegalo = true;}
				console.log('seleccionando un area que no es stock');

			}
		}
	})


	$('#select_usuarios_computadora').on('change', function(){
		console.log('Entro al cambio de usuario');

		if($('#select_usuarios_computadora option:selected').val() > 1 && $('#select_areas option:selected').val() != 2){

			console.log('Entro al cambio de area');

			$.post('controlador/UsuariosController.php',
			{
				id_usuario : $('#select_usuarios_computadora option:selected').val(),
				action : "buscar_area"

			}, function(id_area) {
					$('#select_areas').removeAttr('disabled');
					$('#select_areas option[value='+id_area+']').attr('selected', 'selected');
					$('#select_areas').attr('disabled', 'disabled');
			});
		}
		else if($('#select_usuarios_computadora option:selected').val() == 1){
			$('#select_areas').removeAttr('disabled');
		}
		else{
			console.log('No entre en ninguno de los 2');
		}
	});

	$("#form_computadora").on('submit',function(event){

		event.preventDefault();

		if($("#select_usuarios_computadora").val() == ""){
			alert("El usuario no puede ser vacio.");
		}
		else{

		var datosUrl =	$("#form_computadora").serialize();
		if($("#select_areas option:selected").val() > 2 && $("#select_usuarios_computadora option:selected").val() != 1)
		{
			datosUrl += "&area="+ $("#select_areas option:selected").val();
		}
		datosUrl += "&action=modificar";

		console.log(datosUrl);

		$.ajax({
			url: 'controlador/ComputadorasController.php',
			type: 'POST',
			data: datosUrl,
		})
		.done(function(response) {
			console.log("success");
			console.log(response);
			if(response == 1){
				alert("Los datos han sido actualizados correctamente");
				$("#dialogcontent_cpu").dialog("close");
	            $("#contenedorPpal").load("controlador/ComputadorasController.php");
        	}
        	else{console.log('Algo salio mal');}

		})
		.fail(function() {
			console.log("error");
			alert("Algo no se registro correctaente");
		})
		.always(function() {
			console.log("complete");
		})
		}
	});

});




</script>

