<div class="dialogo_listado_memorandum_usuario">
<form id="form_listado_memorandum_usuario">
{LISTADO}
</form>
<script>
	$(document).ready(function(){
		//Hago un contador para lo seleccionado
		$('#form_listado_memorandum_usuario input').change(function(event) {
			var count = $('#form_listado_memorandum_usuario input').filter(':checked').length;
			$('.ui-dialog-buttonpane #counter').text(count);
		});

		$("#form_listado_memorandum_usuario").on("submit",function(e){
			e.preventDefault();

			var checked='';

			checked = "id_usuario="+"{id_usuario}";
			var i = 1;

	    	$('#productos_seleccionados:checked').each(function(){
	        		checked=checked+"&"+"prod"+i+"="+$(this).val();
	        		i++;
	    	});

	         window.open("controlador/APDF.php?"+checked);
		})

	});

</script>
</div>