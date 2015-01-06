<div class="dialogo_listado_memorandum_usuario">
<form id="form_listado_memorandum_usuario">
{LISTADO}
</form>
<script>
	$(document).ready(function(){

	var inicio = true;
		$("#form_listado_memorandum_usuario").on("submit",function(e){
			e.preventDefault();

			var checked='';

	    	$('#productos_seleccionados:checked').each(function(){
	        	if(inicio){
	        		checked="id_vinculo="+$(this).val();
	        		inicio = false;
	        	}
	        	else{
	        		checked=checked+"&id_vinculo="+$(this).val();
	        	}
	    	});

	            console.log(checked);

	    /*        $.ajax({
                    type : 'POST',
                    data : checked,
                    url  : 'controlador/APDF.php',
                    success: function(responseText){ // Obtengo el resultado de exito
                     
                        $("#form_listado_memorandum_usuario").dialog("destroy");
                        $("#form_listado_memorandum_usuario").remove();
                    }
              });*/

	          window.open("controlador/APDF.php?"+checked);
			})

	});
	
</script>
</div>