
<div class="combo_boxes"><h2>{TITULO}</h2><p>Seleccione el tipo de articulo que desea asignar</p></div>

<select id='select_articulo' name='articulo'>
<option value=""></option>
<option value="Monitor">Monitor</option>
<option value="Impresora">Impresora</option>
<option value="Computadora">Computadora</option>
<option value="Memoria">Memoria RAM</option>
<option value="Disco">Disco</option>

<script type="text/javascript">
	$("#select_articulo").on("change",function(){

		$.post('controlador/CreadorPedidos.php', {value : $('#select_articulo option:selected').val()}, function(data, textStatus, xhr) {
			/*optional stuff to do after success */
			$(".combo_boxes").replaceWith(data);
		});
	});
</script>