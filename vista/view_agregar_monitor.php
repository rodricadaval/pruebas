
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
    	<td>Nro de Serie:</td>
    	<td><input class="input_nro_serie" type="text" name="nro_de_serie"</td>
    </tr>
     </div>
    <div id="agregar"><input class="boton_agregar_monitor" type="submit" name="crearMonitor" value="Crear"</div>
</table>
</div>


<script type="text/javascript">

	$("#select_marcas_n_monitor").on('change',function(){

		console.log("Evento de seleccion de modelos");

		$.post('controlador/ProductosController.php',
			{
				value : $('#select_marcas_n_monitor option:selected').val(),
				tipo : "sel_modelos",
				action : "view_agregar_monitor",
				queSos: "n_monitor"

			}, function(data) {
			$("#select_modelos_n_monitor").replaceWith(data);
			});
	});

	$.post('controlador/ProductosController.php',
			{
				tipo : "sel_depositos",
				action : "view_agregar_monitor",
				queSos: "n_monitor"

			}, function(data) {
			$("#select_areas_n_monitor").replaceWith(data);
	});


	$("#agregar").on('click',function(){

		console.log('Evento de click en crear');

		var id_marca = $('#select_marcas_n_monitor option:selected').val();
		var id_deposito = $('#select_areas_n_monitor option:selected').val();
		var modelo = $('#select_modelos_n_monitor option:selected').val();
		var nro_de_serie = $('.input_nro_serie').val();

		if(id_deposito == "" || id_marca == ""){

			alert('Deposito y Marca deben estar completados');
			$("#tabs1").load("controlador/ProductosController.php",{action:"view_agregar_monitor",tipo:"sel_marcas",queSos:"n_monitor"});
		}
		else if(nro_de_serie != ""){

			$.post('controlador/chequeo_existencia_nro_serie.php',
				{
					nro_serie : nro_de_serie
				}
				,function(no_existe){
					console.log("Habilitado?: "+no_existe);
					if(no_existe == 1){

							$.ajax({
								url: 'controlador/CreacionController.php',
								type: 'POST',
								data: {id_marca: id_marca,
									   id_deposito: id_deposito,
									   modelo: modelo,
									   num_serie: nro_de_serie,
									   tipo: "Monitor"},
							})
							.done(function(resultado) {
								console.log(resultado);
								console.log("success");
								alert('Se ha agregado el producto correctamente');
								$("#tabs1").load("controlador/ProductosController.php",{action:"view_agregar_monitor",tipo:"sel_marcas",queSos:"n_monitor"});
							})
							.fail(function() {
								console.log("error");
								alert('Hubo un error');
								$("#tabs1").load("controlador/ProductosController.php",{action:"view_agregar_monitor",tipo:"sel_marcas",queSos:"n_monitor"});

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
	});
</script>