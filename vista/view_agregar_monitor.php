
<h3><?php echo "Menu para agregar y asignar (si es necesario) un " . $_GET['tipo'];?></h3><p>Seleccione la marca y modelo del monitor</p>

<div class="combo_boxes"></div>

<script type="text/javascript">
	$(document).ready(function(event){

			$.post('controlador/ProductosController.php',
				{
					action:"sel_marcas",
					tipo:"Monitor"
				}
				,function(data){
					$(".combo_boxes").replaceWith(data);
				}
			);

	$("#select_marcas").on('change',function(){

		console.log("Esta entrando");

		$.post('controlador/ProductosController.php',
			{
				value : $('#select_marcas option:selected').val(),
				tipo : "Monitor",
				action : "sel_modelos"

			}, function(data) {
			$(".combo_boxes").append(data);
		});
	});
});
</script>