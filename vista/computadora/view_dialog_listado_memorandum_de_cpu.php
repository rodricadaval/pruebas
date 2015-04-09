<div class="dialogo_listado_memorandum_cpu">
<form id="form_listado_memorandum_cpu">
{LISTADO}
</form>
<script>
	$(document).ready(function(){

		$("#form_listado_memorandum_cpu").on("submit",function(e){
			e.preventDefault();

			var checked='';

			checked = "id_computadora="+"{id_computadora}";
			var i = 1;

	    	$('#productos_seleccionados:checked').each(function(){
	        		checked=checked+"&"+"prod"+i+"="+$(this).val();
	        		i++;
	    	});

	        console.log(checked);

	         window.open("controlador/CPUAPDF.php?"+checked);
		})

	});

</script>
</div>