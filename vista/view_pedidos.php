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
				$.get("logueo/check.php", function(answer){
     					if(answer == 1 || answer == 3){
							$("#dataTable").dataTable({
			   			 		"destroy" : true,
								"aaData" : data,
								"aoColumns" :[
									{ "sTitle" : "Producto" , "mData" : "producto"},
									{ "sTitle" : "Fecha" , "mData" : "fecha"},
									{ "sTitle" : "Cantidad" , "mData" : "cantidad"},
									{ "sTitle" : "Usuario" , "mData" : "usuario"},
									{ "sTitle" : "Observaciones" , "mData" : "observaciones"},
									{ "sTitle" : "Estado" , "mData" : "estado"},
									{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
										'<a class="ventana_pedido " href="#">Cancelar</a>'}
									]
			    			})
						}
						else if(answer == 2){
							$("#dataTable").dataTable({
			   			 		"destroy" : true,
								"aaData" : data,
								"aoColumns" :[
									{ "sTitle" : "Producto" , "mData" : "producto"},
									{ "sTitle" : "Fecha" , "mData" : "fecha"},
									{ "sTitle" : "Cantidad" , "mData" : "cantidad"},
									{ "sTitle" : "Usuario" , "mData" : "usuario"},
									{ "sTitle" : "Observaciones" , "mData" : "observaciones"},
									{ "sTitle" : "Estado" , "mData" : "estado"}
									]
			    			})
						}
 				});
			}

		});
	});

</script>