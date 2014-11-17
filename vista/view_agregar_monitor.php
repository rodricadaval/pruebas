
<h3>{titulo}</h3><p>Seleccione la marca y modelo del monitor</p>
<div id="dialogo_asignar" title="Asignar Usuario"></div>


<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_agregar"></table>
	<div id="selects">
	<tr>
            <th>Marca: </th>
    </tr>
    <tr>
            <td>{select_marcas}</td>
    </tr>
    <tr>
    	<td>Modelo:</td>
    	<td><select id='select_modelos' name='modelos'>
    				<option value='0'>Seleccionar</option></select></td>
    	</td>
    	<td>Deposito:</td>
    	<td><select class='select_areas' name='depositos'>
    				<option value=''>Seleccionar</option></select>
    	</td>
    	<td>Nro de Serie:</td>
    	<td><input class="input_nro_serie" type="text" name="nro_de_serie"</td>
    </tr>
    </br></br>
    <tr>
    	<td>Usuario:</td>
    	<td><select class='select_usuarios' name='usuarios'>
    				<option value=''>Sin usuario</option></select>
    	</td>
    </tr>
    </div>
    <div id="agregar"><input class="boton_agregar_monitor" type="submit" name="crearMonitor" value="Crear"</div>
</table>
</div>


<script type="text/javascript">

	$("#select_marcas").on('change',function(){

		console.log("Evento de seleccion de modelos");

		$.post('controlador/ProductosController.php',
			{
				value : $('#select_marcas option:selected').val(),
				tipo : "sel_modelos",
				action : "view_agregar_monitor"

			}, function(data) {
			$("#select_modelos").replaceWith(data);
			});

			$.post('controlador/ProductosController.php',
			{
				tipo : "sel_depositos",
				action : "view_agregar_monitor"

			}, function(data) {
			$(".select_areas").replaceWith(data);
			});
	});

	$.post('controlador/ProductosController.php',
			{
				value : $('.select_usuarios option:selected').val(),
				tipo : "sel_usuarios",
				action : "view_agregar_monitor"

			}, function(data) {
				$(".select_usuarios").replaceWith(data);
			}
	);

	$('#contenedorPpal').on('change', '.select_areas', function(){
    	console.log('Entro al cambio de area');

		if($('.select_areas').val() == 1){
			console.log('El area es Stock');
			$('.select_usuarios option:contains("Ninguno")').prop('selected', true);
			$('.select_usuarios').attr('disabled', 'disabled');
		}
		else{
			$('.select_usuarios').removeAttr('disabled');
		}
	});

	$('#contenedorPpal').on('change', '.select_usuarios', function(){
		if($('.select_usuarios option:selected').val() > 1 && $('.select_areas option:selected').val() != 2){

			$.post('controlador/UsuariosController.php',
			{
				id_usuario : $('.select_usuarios option:selected').val(),
				action : "buscar_area"

			}, function(id_area) {

					$('.select_areas option[value='+id_area+']').attr('selected', 'selected');
					$('.select_areas').attr('disabled', 'disabled');
			   }
			);
		}
		else if($('.select_usuarios option:selected').val() == 1){
			$('.select_areas').removeAttr('disabled');
		}
	});

	$("#agregar").on('click',function(){

		console.log('Evento de click en crear');

		var id_marca = $('#select_marcas option:selected').val();
		var id_deposito = $('.select_areas option:selected').val();
		var modelo = $('#select_modelos option:selected').val();
		var nro_de_serie = $('.input_nro_serie').val();
		var id_usuario = $('.select_usuarios option:selected').val();

		if(id_deposito == "" || id_marca == ""){

			alert('Deposito, Marca y Usuario deben estar completados');
			$("#tabs1").load("controlador/ProductosController.php",{action:"view_agregar_monitor",tipo:"sel_marcas"});
		}
		else if(nro_de_serie != "" && id_usuario != ""){

			$.post('controlador/chequeo_existencia_nro_serie.php',
				{
					nro_serie : nro_de_serie
				}
				,function(no_existe){
					console.log(no_existe);
					if(no_existe == 1){

							$.ajax({
								url: 'controlador/CreacionController.php',
								type: 'POST',
								data: {id_marca: id_marca,
									   id_deposito: id_deposito,
									   modelo: modelo,
									   num_serie: nro_de_serie,
									   id_usuario : id_usuario,
									   tipo: "Monitor"},
							})
							.done(function(resultado) {
								console.log(resultado);
								console.log("success");
								alert('Se ha agregado el producto correctamente');
								$("#tabs1").load("controlador/ProductosController.php",{action:"view_agregar_monitor",tipo:"sel_marcas"});
							})
							.fail(function() {
								console.log("error");
								alert('Hubo un error');
								$("#tabs1").load("controlador/ProductosController.php",{action:"view_agregar_monitor",tipo:"sel_marcas"});

							})
							.always(function() {
								console.log("complete");
							});
						}
						else{
							console.log("El numero de serie ya existe");
							alert('El numero de serie ya existe');
							$('.input_nro_serie').val("");
						}
				}
			);
		}
		else if(nro_de_serie == ""){
			alert('Debes ingresar el Numero de serie!');
			$('.input_nro_serie').val("");
		}
		else if(id_usuario == ""){
			alert('Debes elegir un usuario o seleccionar Ninguno');
		}

	});
</script>