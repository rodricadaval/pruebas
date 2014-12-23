<h2>{TABLA}</h2>
<div id="nueva_area">
	<input type="button" class="btn btn-success" id="crear_area" value="Nueva Area">
</div>
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
				$.get("logueo/check_priority.php", function(answer){
     					if(answer == 1 || answer == 3){
							$("#dataTable").dataTable({
			   			 		"destroy" : true,
			   			 		"bJQueryUI" : true,
								"aaData" : data,
								"aoColumns" :[
									{ "sTitle" : "ID" , "mData" : "id_area"},
									{ "sTitle" : "Area" , "mData" : "nombre"},
									{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
										'Modificar'}]
				   			})
						}
						else if(answer == 2){
							$("#dataTable").dataTable({
			   			 		"destroy" : true,
			   			 		"bJQueryUI" : true,
								"aaData" : data,
								"aoColumns" :[
									{ "sTitle" : "Area" , "mData" : "nombre"}
									]
				   			})
						}
 				});
			}

		});
	});

	$("#contenedorPpal").on('click' , '#modificar_area' , function(){

			console.log($(this).attr("id_area"));
			var id_area = $(this).attr("id_area");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Areas",
					ID : id_area,
					queSos : "area", //a quien le voy a generar la vista
					action : "modificar"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontentarea',
				    text: 'Texto por defecto!'
					}).appendTo('#contenedorPpal');
					$("#dialogcontentarea").html(data);
					$("#dialogcontentarea").dialog({
											title: "Modificar Area",
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
						                        	$("#form_area").submit();
						                        }
						                    }
					});
				  }
			);
		});

	$("#contenedorPpal").on('click' , '#crear_area' , function(){

			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Areas",
					queSos : "area", //a quien le voy a generar la vista
					action : "nueva"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontentarea',
				    text: 'Texto por defecto!'
					}).appendTo('#contenedorPpal');
					$("#dialogcontentarea").html(data);
					$("#dialogcontentarea").dialog({
											title: "Crear Area",
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
						                        	$("#form_nueva_area").submit();
						                        }
						                    }
					});
				  }
			);
		});

</script>
