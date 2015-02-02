<div class="dialogo_listado_memorandum_usuario">
<form id="form_listado_memorandum_usuario">
{LISTADO}
</form>
<script>
	$(document).ready(function(){

		$("#form_listado_memorandum_usuario").on("submit",function(e){
			e.preventDefault();

			var checked='';

			checked = "id_usuario="+"{id_usuario}";
			var i = 1;

	    	$('#productos_seleccionados:checked').each(function(){
	        		checked=checked+"&"+"prod"+i+"="+$(this).val();
	        		i++;
	    	});

	        console.log(checked);

	         window.open("controlador/APDF.php?"+checked);
		})

	});

</script>
</div>