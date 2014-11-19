<form id="form_monitor">
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
          <td><input style="background-color:#D3D3D3" type="hidden" name="id_monitor" id="id_monitor" value="{id_monitor}" readonly></td>
        </tr>
        <tr>
        <td>Usuario:</td>
        <td>
        <div id="multiple-datasets">
     	<input class="typeahead" type="text" placeholder="Nombre de usuario">
		</div>
		</td>

		</tr>
		</br>
        <tr>
          <td><input type="submit" id="submit" tabindex="-1"></td>
          <td></td>
        </tr>
   </table>
</form>

<script type="text/javascript" src="lib/multiple-usuarios.js"> </script>

<script>

$(document).ready(function(){

	$('#select_areas').removeAttr('disabled');

    var fueRegalo = false;


    $('#select_areas').on('change', function(){
    	console.log('Entro al cambio de area');

		if($('#select_areas').val() == 1){
			console.log('El area es Stock');



			//$('.typeahead ').val('Sin usuario');
			$(".typeahead").removeAttr('placeholder');
			$(".typeahead").attr('readonly','readonly');

			//$('#select_usuarios_monitor option:contains("Ninguno")').prop('selected', true);

		}
		else{
			if(fueRegalo){

					if(usuario['id'] > 1 && $('#select_areas option:selected').val() != 2){

						$.post('controlador/UsuariosController.php',
						{
							id_usuario : usuario['id'],
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
				fueRegalo = true;
				$('.typeahead').removeAttr('readonly');
				$(".typeahead").removeAttr('placeholder');
				}
				console.log('seleccionando un area que no es stock');
			}
			}
	})

	$('.typeahead').on('typeahead:selected', function(event, usuario) {

		console.log(usuario['id']);
		console.log($('.typeahead').typeahead('val'));

  		if(usuario['id'] > 1 && $('#select_areas option:selected').val() != 2){

  			console.log('Entre a cambiar el area');

			$.post('controlador/UsuariosController.php',
			{
				id_usuario : usuario['id'],
				action : "buscar_area"

			}, function(id_area) {

					$('#select_areas option[value='+id_area+']').attr('selected', 'selected');
					//$('#select_areas').find('option[value='+id_area+']').attr('selected', 'selected');
					$('#select_areas').attr('disabled', 'disabled');
			});
  		}
  		else if(usuario['id'] == 1){
			$('#select_areas').removeAttr('disabled');
		}
		else{
			console.log('No entre en ninguno de los 2. Probablemente sea un regalo');
		}
	});

	$("#form_monitor").on('submit',function(event){

		event.preventDefault();

		console.log($('.typeahead').typeahead('val'));

		console.log($("#form_monitor").serialize());
/*
		if($("#select_usuarios_monitor").val() == ""){
			alert("El usuario no puede ser vacio.");
		}
		else{

		var datosUrl =	$("#form_monitor").serialize();
		if($("#select_areas option:selected").val() > 2 && $("#select_usuarios_monitor option:selected").val() != 1)
		{
			datosUrl += "&area="+ $("#select_areas option:selected").val();
		}
		datosUrl += "&action=modificar";

		console.log(datosUrl);

		$.ajax({
			url: 'controlador/MonitoresController.php',
			type: 'POST',
			data: datosUrl,
		})
		.done(function(response) {
			console.log("success");
			console.log(response);
			if(response == 1){
				alert("Los datos han sido actualizados correctamente");
				$("#dialogcontent_monitor").dialog("close");
	            $("#contenedorPpal").load("controlador/MonitoresController.php");
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
*/
	});

});




</script>

