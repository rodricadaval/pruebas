<div id="asignar_productos_a_compu">
<div id="asignar_productos">
{Monitores}
</div>
</div>

<script type="text/javascript">

    console.log("{select_tipos_memorias}");

    $('#form_asignar_monitor_a_compu #select_unidades_memorias option[value='+2+']').attr('selected', 'selected');

	$("#form_asignar_monitor_a_compu #select_marcas_memorias,#form_asignar_monitor_a_compu #select_tipos_memorias").on('change',function(){

		console.log("Evento de seleccion de tipos");

		$.post('controlador/ProductosController.php',
			{
				value_marca : $('#form_asignar_monitor_a_compu #select_marcas_memorias option:selected').val(),
                value_tipo : $('#form_asignar_monitor_a_compu #select_tipos_memorias option:selected').val(),
				tipo : "sel_velocidades",
				action : "agregar_monitor"

			}, function(data) {
			$("#form_asignar_monitor_a_compu #select_velocidades").replaceWith(data);
			});
	});
</script>