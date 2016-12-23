<div id="tab_memorias_y_discos" id_cpu="{id_cpu}">
	<table border="0" style="border-top-color:#FFFFFF; margin-left:-20px; width:500px; text-align:center; background-color:#F0F8FF;">
		<tr>
			<td>Memorias</td>

			<td style="width:40px"><a id="agregar_memorias_computadora" class="pointer_mon"><i class="green large plus outline icon" title="Agregar memoria"></i></a></td>

			<td style="width:40px"><a id="asignar_memorias_computadora" class="pointer_mon"><i class="green large edit outline icon" title="Asignar memoria"></i></a></td>

    		<td>Discos</td>

    		<td style="width:40px"><a id="agregar_discos_computadora" class="pointer_mon"><i class="green large plus outline icon" title="Agregar disco"></i></a></td>

			<td style="width:40px"><a id="asignar_discos_computadora" class="pointer_mon"><i class="green large edit outline icon" title="Asignar disco"></i></a></td>

    		<td>Monitores</td>

    		<td style="width:40px"><a id="asignar_monitores_computadora" class="pointer_mon"><i class="green large edit outline icon" title="Asignar monitor"></i></a></td>
    	</tr>
    </table>
    <input type="hidden" id="id_cpu" value="{id_cpu}">
</div>

<script type="text/javascript">
	$("#tab_memorias_y_discos").on('click',"#agregar_memorias_computadora",function(){
		console.log("asdddas");
		$.post( "controlador/ProductosController.php",
		{
			action : "agregar_memoria_a_computadora"
		}, function(data){
			$("#agregar_productos_a_compu").remove();

			jQuery('<div/>', {
				id: 'agregar_productos_a_compu',
				text: ''
			}).appendTo('#agregar_productos_a_computadora');

			$("#agregar_productos_a_compu").html(data);
		}
		);
	});

	$("#tab_memorias_y_discos").on('click',"#agregar_discos_computadora",function(){

		$.post( "controlador/ProductosController.php",
		{
			action : "agregar_disco_a_computadora"
		}, function(data){

			$("#agregar_productos_a_compu").remove();

			jQuery('<div/>', {
				id: 'agregar_productos_a_compu',
				text: ''
			}).appendTo('#agregar_productos_a_computadora');

			$("#agregar_productos_a_compu").html(data);
		}
		);
	});

	$("#tab_memorias_y_discos").on('click',"#asignar_memorias_computadora",function(){

		console.log("asignar_memorias_computadora");

		$.post( "controlador/ProductosController.php",
		{
			action : "asignar_memoria_a_computadora",
			id_cpu : $("#tab_memorias_y_discos").attr("id_cpu")
		}, function(data){
			$("#agregar_productos_a_compu").remove();

			jQuery('<div/>', {
				id: 'agregar_productos_a_compu',
				text: ''
			}).appendTo('#agregar_productos_a_computadora');

			$("#agregar_productos_a_compu").html(data);

		}
		);
	});

	$("#tab_memorias_y_discos").on('click',"#asignar_discos_computadora",function(){

		console.log("asignar_discos_computadora");
		console.log("id_cpu = "+$("#tab_memorias_y_discos").attr("id_cpu"));

		$.post( "controlador/ProductosController.php",
		{
			action : "asignar_disco_a_computadora",
			id_cpu : $("#tab_memorias_y_discos").attr("id_cpu")
		}, function(data){

			$("#agregar_productos_a_compu").remove();

			jQuery('<div/>', {
				id: 'agregar_productos_a_compu',
				text: ''
			}).appendTo('#agregar_productos_a_computadora');

			$("#agregar_productos_a_compu").html(data);
		}
		);
	});

	$("#tab_memorias_y_discos").on('click',"#asignar_monitores_computadora",function(){

		console.log("asignar_monitores_computadora");

		$.post( "controlador/ProductosController.php",
		{
			action : "asignar_monitor_a_computadora",
			id_cpu : $("#tab_memorias_y_discos").attr("id_cpu")
		}, function(data){

			$("#agregar_productos_a_compu").remove();

			jQuery('<div/>', {
				id: 'agregar_productos_a_compu',
				text: ''
			}).appendTo('#agregar_productos_a_computadora');

			$("#agregar_productos_a_compu").html(data);
		}
		);
	});
</script>	
