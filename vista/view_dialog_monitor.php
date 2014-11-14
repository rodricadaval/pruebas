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
          <td>Usuario</td>
          <td>{select_Usuarios}</td>
        </tr>
        <tr>
          <td>ID</td>
          <td><input style="background-color:#D3D3D3" type="text" name="id_monitor" id="id_monitor" value="{id_monitor}" readonly></td>
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

    $('#select_areas').removeAttr('disabled');

    $('#select_areas').on('change', function(){
    	console.log('Entro al cambio de area');

		if($('#select_areas').val() == 1){
			console.log('El area es Stock');
			$('#select_usuarios option:contains("Ninguno")').prop('selected', true);
			$('#select_usuarios').attr('disabled', 'disabled');
			//$('#select_usuarios').replaceWith("<select disabled id='select_usuarios' name='usuarios'><option selected='selected' value='1'>Ninguno</option>");
		}
		else{
			console.log('seleccionando un area que no es stock');
			$('#select_usuarios').removeAttr('disabled');
			}
	})


	$('#select_usuarios').on('change', function(){
		console.log('Entro al cambio de usuario');

		if($('#select_usuarios option:selected').val() > 1 && $('#select_areas option:selected').val() != 2){

			$.post('controlador/UsuariosController.php',
			{
				id_usuario : $('#select_usuarios option:selected').val(),
				action : "buscar_area"

			}, function(id_area) {

					$('#select_areas option[value='+id_area+']').attr('selected', 'selected');
					//$('#select_areas').find('option[value='+id_area+']').attr('selected', 'selected');
					$('#select_areas').attr('disabled', 'disabled');
			});
		}
		else if($('#select_usuarios option:selected').val() == 1){
			$('#select_areas').removeAttr('disabled');
		}
	});

});


</script>

