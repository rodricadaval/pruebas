<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable"></table>

<script type="text/javascript">

	$(document).ready(function(event){
		$.ajax({
			url : 'metodos_ajax.php',
			method: 'post',
			data:{ clase: '{TABLA}',
					metodo: 'listarTodos'},
			dataType: 'json',
			success : function(data){
				$("#dataTable").dataTable({
   			 		"destroy" : true,
					"aaData" : data,
					"aoColumns" :[
						{ "sTitle" : "Insumo" , "mData" : "id_monitor"},
						{ "sTitle" : "Serie" , "mData" : "num_serie"},
						{ "sTitle" : "Descripcion" , "mData" : "id_vinculo"},
						{ "sTitle" : "Descripcion" , "mData" : "id_monitor_desc"}
						]
    			})
			}

		});
	});

</script>