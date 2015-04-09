<div class="ui one column grid">
	<div class="column">
		<div class="ui raised segment">
			<a class="ui teal ribbon label">{TABLA}</a>
			<table  cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable"></table>
		</div>
	</div>
</div>

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
     					if(answer == 1){
							$("#dataTable").dataTable({
			   			 		"destroy" : true,
								"aaData" : data,
								"aoColumns" :[
									{ "sTitle" : "Marca" , "mData" : "nombre"},
									{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
										'<a class="ventana_area " href="">Modificar</a>'}
			  					]
			    			})
						}
						else if( answer == 3){

							$("#dataTable").dataTable({
			   			 		"destroy" : true,
								"aaData" : data,
								"aoColumns" :[
									{ "sTitle" : "ID" , "mData" : "id_marca"},
									{ "sTitle" : "Marca" , "mData" : "nombre"},
									{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
										'<a class="ventana_area " href="">Modificar</a>'}
			  					]
			    			})
						}
						else if(answer == 2){
							$("#dataTable").dataTable({
			   			 		"destroy" : true,
								"aaData" : data,
								"aoColumns" :[
									{ "sTitle" : "Marca" , "mData" : "nombre"}
			  					]
			    			})
						}
						else { window.location.href = "logueo/login.php";}
 				});
			}

		});
	});

	$("#contenedorPpal").on('click' , '#modificar_marca' , function(){

			console.log($(this).attr("id_marca"));
			var id_marca = $(this).attr("id_marca");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Marcas",
					ID : id_marca,
					queSos : "marca" //a quien le voy a generar la vista
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontentmarca',
				    text: 'Texto por defecto!'
					}).appendTo('#contenedorPpal');
					$("#dialogcontentmarca").html(data);
					$("#dialogcontentmarca").dialog({
											title: "Modificar Marca",
											show: {
											effect: "explode",
											duration: 200,
											modal:true
											},
											hide: {
											effect: "blind",
											duration: 200
											},
											width : 400,
											close : function(){
												$(this).dialog("destroy").empty();
												$(this).remove();
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy").empty();
						                            $(this).remove();
						                        },
						                        "Enviar" : function(){
						                        	$("#form_marca").submit();
						                        }
						                    }
					});
				  }
			);
		});

</script>