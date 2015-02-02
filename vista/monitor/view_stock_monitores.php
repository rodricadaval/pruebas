<h2>Monitores en Stock</h2>
<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable"></table>

<script type="text/javascript">

	$(document).ready(function(event){

		$.blockUI({ css: {
						border: 'none',
						padding: '15px',
						backgroundColor: '#000',
						'-webkit-border-radius': '10px',
						'-moz-border-radius': '10px',
						opacity: .5,
						color: '#fff'
					} });

		$.ajax({
			url : 'metodos_ajax.php',
			method: 'post',
			data:{ clase: '{TABLA}',
				   metodo: 'listarEnStock',
				   tipo: 'json'},
			dataType: 'json',
			success : function(data){

				$.unblockUI();

				$("#dataTable").dataTable({
   			 		"destroy" : true,
					"aaData" : data,
					"iDisplayLength": 25,
					"aoColumns" :[
						//{ "sTitle" : "ID" , "mData" : "id_monitor"},
						{ "sTitle" : "Nro de Serie" , "mData" : "num_serie"},
						{ "sTitle" : "Marca" , "mData" : "marca"},
						{ "sTitle" : "Modelo" , "mData" : "modelo"},
						{ "sTitle" : "Pulgadas" , "mData" : "pulgadas"},
						{ "sTitle" : "Sector" , "mData" : "sector"},
						{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
										'<a class="ventana_area " href="">Modificar</a>'}
						],
					"aoColumnDefs": [
			            { "sWidth": "30%", "aTargets": [ -1 ] }
			        ]
    			})
			}
		});
	});


$("#contenedorPpal").on('click' , '#modificar_usuario_monitor' , function(){

		console.log("Entro a modificar usuario del monitor");
		console.log("id_monitor: "+$(this).attr("id_monitor"));
		var id_monitor = $(this).attr("id_monitor");
		$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Monitores",
				ID : id_monitor,
				select_Areas : "Areas", //Clase de la cual quiero sacar el select
				queSos : "monitor", //a quien le voy a generar la vista
				select_Computadoras : "Computadoras",
				action : "modif_usuario",
				viene : "stock"
			}, function(data){
				jQuery('<div/>', {
				    id: 'dialogcontent_monitor',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
				$("#dialogcontent_monitor").html(data);
				$("#dialogcontent_monitor").dialog({
											show: {
											effect: "explode",
											duration: 200,
											modal:true
											},
											hide: {
											effect: "explode",
											duration: 200
											},
											width : 350,
											height : 350,
											close : function(){
												$(this).dialog("destroy");
												$("#dialogcontent_monitor").remove();
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy");
						                            $("#dialogcontent_monitor").remove();
						                        },
						                        "Enviar" : function(){
						                        	$("#form_monitor_mod_usuario").submit();
						                        }
						                    }
				});
			}
		);
	});

$("#contenedorPpal").on('click' , '#modificar_cpu_monitor' , function(){

		console.log("Entro a modificar cpu del monitor");
		console.log("id_monitor: "+$(this).attr("id_monitor"));
		var id_monitor = $(this).attr("id_monitor");
		$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Monitores",
				ID : id_monitor,
				select_Areas : "Areas", //Clase de la cual quiero sacar el select
				queSos : "monitor", //a quien le voy a generar la vista
				select_Computadoras : "Computadoras",
				action : "modif_cpu",
				viene : "stock"
			}, function(data){
				jQuery('<div/>', {
				    id: 'dialogcontent_monitor',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
				$("#dialogcontent_monitor").html(data);
				$("#dialogcontent_monitor").dialog({
											show: {
											effect: "explode",
											duration: 200,
											modal:true
											},
											hide: {
											effect: "explode",
											duration: 200
											},
											width : 350,
											height : 330,
											close : function(){
												$(this).dialog("destroy");
												$("#dialogcontent_monitor").remove();
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy");
						                            $("#dialogcontent_monitor").remove();
						                        },
						                        "Enviar" : function(){
						                        	$("#form_monitor_mod_cpu").submit();
						                        }
						                    }
				});
			}
		);
	});

	$("#contenedorPpal").on('click' , '#modificar_sector_monitor' , function(){

			console.log("Entro a modificar sector del monitor");
			console.log("id_monitor: "+$(this).attr("id_monitor"));
			var id_monitor = $(this).attr("id_monitor");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Monitores",
					ID : id_monitor,
					select_Areas : "Areas", //Clase de la cual quiero sacar el select
					queSos : "monitor", //a quien le voy a generar la vista
					action : "modif_sector",
					viene : "stock"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_monitor',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_monitor").html(data);
					$("#dialogcontent_monitor").dialog({
												show: {
												effect: "explode",
												duration: 200,
												modal:true
												},
												hide: {
												effect: "explode",
												duration: 200
												},
												width : 300,
												height : 250,
												close : function(){
													$(this).dialog("destroy");
													$("#dialogcontent_monitor").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy");
							                            $("#dialogcontent_monitor").remove();
							                        },
							                        "Aceptar" : function(){
							                        	$("#form_monitor_mod_sector").submit();
							                        }
							                    }
					});
				}
			);
	});


	$("#contenedorPpal").on('click' , '#eliminar_monitor' , function(){

		console.log("Entro a eliminar monitor");
		console.log("id_monitor: "+$(this).attr("id_monitor"));
		var id_monitor = $(this).attr("id_monitor");

		$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Monitores",
					ID : id_monitor,
					queSos : "monitor", //a quien le voy a generar la vista
					action : "eliminar",
					viene : "stock"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_monitor',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_monitor").html(data);
					$("#dialogcontent_monitor").dialog({
												title: "Motivo de baja",
												show: {
												effect: "explode",
												duration: 200,
												modal:true
												},
												hide: {
												effect: "explode",
												duration: 200
												},
												width : 300,
												height : 280,
												close : function(){
													$(this).dialog("destroy");
													$("#dialogcontent_monitor").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy");
							                            $("#dialogcontent_monitor").remove();
							                        },
							                        "Aceptar" : function(){
							                        		$("#form_detalle_eliminar_monitor").submit();
													}
							                    }
					});
				}
			);


	});

</script>