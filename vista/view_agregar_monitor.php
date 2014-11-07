
<h3>{titulo}</h3><p>Seleccione la marca y modelo del monitor</p>
<div id="dialogo_asignar" title="Asignar"></div>

<div class="combo_boxes">
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
    	<td><select id='select_areas' name='depositos'>
    				<option value=''>Seleccionar</option></select>
    	</td>
    	<td>Nro de Serie:</td>
    	<td><input class="input_nro_serie" type="text" name="nro_de_serie"</td>
    </tr>
    </div>
    <div id="agregar"><input class="boton_agregar_monitor" type="button" name="crearMonitor" value="Crear"</div>
</table>
</div>

<script type="text/javascript">
	$(document).ready(function(event){

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
				value : $('#select_marcas option:selected').val(),
				tipo : "sel_depositos",
				action : "view_agregar_monitor"

			}, function(data) {
			$("#select_areas").replaceWith(data);
			});
	});

	$("#agregar").on('click',function(){
		console.log('Evento de click en crear');

		var id_marca = $('#select_marcas option:selected').val();
		var id_deposito = $('#select_areas option:selected').val();
		var modelo = $('#select_modelos option:selected').val();
		var nro_de_serie = $('.input_nro_serie').val();

		if(id_deposito == "" || id_marca == ""){

			alert('Debes ingresar algún deposito o marca!');
			$("#tabs1").load("controlador/ProductosController.php",{action:"view_agregar_monitor",tipo:"sel_marcas"});
		}
		else if(nro_de_serie != ""){

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
									   tipo: "Monitor"},
							})
							.done(function(resultado) {
								console.log(resultado);
								console.log("success");

								$.post('controlador/Dialog_asignar.php',
								{
									tipo : "Monitores"
								}, function(data) {
									$("#dialogo_asignar").html(data);
									$("#dialogo_asignar").dialog("open");
								});

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
						}
				}
			);
		}
		else{
			alert('Debes ingresar el Numero de serie!');
			$("#tabs1").load("controlador/ProductosController.php",{action:"view_agregar_monitor",tipo:"sel_marcas"});
		}

	});

	$( "#dialogo_asignar" ).dialog({
		autoOpen: false,
		show: {
		effect: "blind",
		duration: 1000,
		modal:true
		},
		hide: {
		effect: "explode",
		duration: 200
		},
		width : 400
	});
});
</script>