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
        	<td>
          		<input style="background-color:#D3D3D3" type="hidden" name="id_monitor" id="id_monitor" value="{id_monitor}">
          	</td>
        </tr>
        <tr>
        	<td>Usuario:</td>
        	<td>
       			<div id="multiple-datasets">
     				<input name="nombre_usuario" id="nombre_usuario" class="typeahead" type="text" placeholder="Nombre de usuario" value="{nombre_apellido}">
				</div>
			</td>
		</tr>
		<tr><td></td><td><div class="error text-error"></div></td></tr>

        <tr>
          <td><input type="submit" id="submit" tabindex="-1"></td>
          <td></td>
        </tr>
   </table>
</form>

<div style="display:none">
	<div id="alerta_regalo" title="ATENCION!">Recuerde ingresar el usuario que solicit&oacute; el regalo</div>
	<div id="alerta_stock" title="ATENCION!"></div>
</div>

<!--<script type="text/javascript" src="lib/multiple-usuarios.js"> </script>-->

<script>

$(document).ready(function(){


	var validado = false;
	//var usuarioSelActual 	= 0;
	//var fueRegalo 			= false;

	/*
		console.log('el usuario que viene no es vacio');

		usuarioSelActual = {id_usuario};

		if({id_usuario} == 1){
			$('.typeahead').typeahead('val',"{nombre_apellido}");
		}
		else{
			$('.typeahead').typeahead('val',"{nombre_apellido}"+" {nombre_area}");
		}

	*/
	$('#select_areas').removeAttr('disabled');

    $('#select_areas').on('change', function(){

    	var id_sector = $(this).val();
    	console.log('Entro al cambio de area');

		if (id_sector == 1) {

			// SI EL DEPOSITO ES STOCK, NO PERMITE INGRESAR USUARIO
			// SE PODRIA INFORMAR AL USUARIO DE QUE NO SE PERMITE INGRESAR UN USUARIO, PERO ES MEDIO MOLESTO

			$('#nombre_usuario').val('Sin usuario');
    		$("#nombre_usuario").attr('readonly','readonly');
    		//$('.typeahead ').val('Sin usuario');
			//$(".typeahead").removeAttr('placeholder');
			//$('#select_usuarios_monitor option:contains("Ninguno")').prop('selected', true);

		} else if (id_sector == 2) {

			// SI EL DEPOSITO ES REGALOS, INFORMA DE QUE SE DEBE INGRESAR UN USUARIO (NO IMPORTA SI EL USUARIO TIENE UN DEPOSITO ASOCIADO)
			// LA VALIDACION SE HACE CON JQUERYVALIDATOR AL MOMENTO DE ENVIAR EL FORMULARIO

			$('#nombre_usuario').removeAttr('readonly');
			$('#alerta_regalo').dialog({
				buttons : [
					{
						text : 'Aceptar' ,
						click : function () {
							$(this).dialog("close");
						}
					}
				]
			});
			/*if(fueRegalo){

					if(usuarioSelActual > 1 && $('#select_areas option:selected').val() != 2){

						$.post('controlador/UsuariosController.php',
						{
							id_usuario : usuarioSelActual,
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
					$(".typeahead").removeAttr('placeholder');
				}
				console.log('seleccionando un area que no es stock');
			}
			*/

		} else {

			// EL DEPOSITO NO ES STOCK NI REGALOS, EL TYPEAHEAD DEL USUARIO SOLO DEBE TRAER LOS USUARIOS QUE PERTENEZCAN A ESE DEPOSITO

			$('#nombre_usuario').removeAttr('readonly');

		}
	});

	$("#nombre_usuario").typeahead({
		source : function (query , process) {
			var id_deposito = $("#select_areas").val();
			$.ajax({
				type 		: 'post' ,
				data 		: {
					id_deposito : id_deposito ,
					term 		: query
				} ,
				url  		: 'lib/listado_usuarios.php' ,
				dataType 	: 'json' ,
				success 	: function (data) {
					process (data);
				}
			})
		} ,
		minLength : 3,
		updater: function(obj) { console.log(obj); return obj; }

	});

/*
	$("#nombre_usuario").on('change',function(){
		console.log($("#nombre_usuario").val());
	})

	$('.typeahead').on('typeahead:selected', function(event, usuario) {


  		if(usuario['id'] > 1 && $('#select_areas option:selected').val() != 2){

  			console.log('Entre a cambiar el area');

			$.post('controlador/UsuariosController.php',
			{
				id_usuario : usuarioSelActual,
				action : "buscar_area"

			}, function(id_area) {

					$('#select_areas option[value='+id_area+']').attr('selected', 'selected');
					//$('#select_areas').find('option[value='+id_area+']').attr('selected', 'selected');
					$('#select_areas').attr('disabled', 'disabled');
			});
  		}
  		else if(usuarioSelActual == 1){
			$('#select_areas').removeAttr('disabled');
		}
		else{
			console.log('No entre en ninguno de los 2. Probablemente sea un regalo');
		}
	});
	*/
	/*
	$("#form_monitor").on('submit',function(event){

		event.preventDefault();

		$("#form_monitor").validate({
		errorLabelContainer : ".error" ,
		rules : {
			nombre_usuario : {
				required : true,
				remote 	 : {
					url 	: 'lib/busca_usuario.php' ,
					type 	: 'post' ,
					data 	: {
						nombre_usuario : function() {
							console.log($("#nombre_usuario").val());
							return $("#nombre_usuario").val();
						}
					}
				}
			}
		} ,
		messages : {
			nombre_usuario : {
				remote : 'El usuario no existe'
			}
		} ,
		submitHandler : function (form) {
			console.log (form);
			validado = true;
		}
	});

		console.log($('.typeahead').typeahead('val'));

		console.log($("#form_monitor").serialize());

		if($('.typeahead').typeahead('val') == ""){
			alert("El usuario no puede ser vacio. Si no quieres elegir un usuario selecciona -Sin usuario- ");
		}
		else{

			var datosUrl =	$("#form_monitor").serialize();
			if($("#select_areas option:selected").val() > 2 && usuarioSelActual != 1)
			{
				datosUrl += "&area="+ $("#select_areas option:selected").val();
			}
			datosUrl += "&action=modificar&usuario=" + $('.typeahead').typeahead('val');

			console.log(datosUrl);

			$.ajax({
				url: 'controlador/MonitoresController.php',
				type: 'POST',
				data: datosUrl,
				success : function(response){
					console.log(response);
					console.log("success");
					alert("Los datos han sido actualizados correctamente");
					$("#dialogcontent_monitor").dialog("close");
		            $("#contenedorPpal").load("controlador/MonitoresController.php");
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
	});
	*/
});

</script>

