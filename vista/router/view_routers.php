<h2>{TABLA}</h2>
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
				   metodo: 'listarCorrecto',
				   tipo: 'json'},
			dataType: 'json',
			success : function(data){
				$.get('logueo/check_priority.php', function(permisos) {

					$.unblockUI();

					if( permisos == 1 || permisos == 3) {
							var dataTable = $("#dataTable").dataTable({
			   			 		"destroy" : true,
								"aaData" : data,
								"iDisplayLength": 25,
								"aoColumns" :[
									{ "sTitle" : "Nro de Serie" , "mData" : "num_serie"},
									{ "sTitle" : "IP" , "mData" : "ip"},
									{ "sTitle" : "Marca" , "mData" : "marca"},
									{ "sTitle" : "Modelo" , "mData" : "modelo"},
									{ "sTitle" : "Sector" , "mData" : "sector"},
									{ "sTitle" : "Descripcion" , "mData" : "descripcion"},
									{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
													'<a class="ventana_area " href="">Modificar</a>'}
									],
								"aoColumnDefs": [
						            { "sWidth": "20%", "aTargets": [ -1 ] }
						        ]
			    			})
						}
						else if (permisos == 2) {
							var dataTable = $("#dataTable").dataTable({
			   			 		"destroy" : true,
								"aaData" : data,
								"aoColumns" :[
									{ "sTitle" : "Nro de Serie" , "mData" : "num_serie"},
									{ "sTitle" : "IP" , "mData" : "ip"},
									{ "sTitle" : "Marca" , "mData" : "marca"},
									{ "sTitle" : "Modelo" , "mData" : "modelo"},
									{ "sTitle" : "Sector" , "mData" : "sector"},
									{ "sTitle" : "Descripcion" , "mData" : "descripcion"},
									],
								"aoColumnDefs": [
						            { "sWidth": "20%", "aTargets": [ -1 ] }
						        ]
			    			})
						}
					});
			}
		});
	});

	$("#contenedorPpal").on('click' , '#modificar_sector_router' , function(){

			console.log("Entro a modificar sector del router");
			console.log("id_router: "+$(this).attr("id_router"));
			var id_router = $(this).attr("id_router");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Routers",
					ID : id_router,
					select_Areas : "Areas", //Clase de la cual quiero sacar el select
					queSos : "router", //a quien le voy a generar la vista
					action : "modif_sector",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_router',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_router").html(data);
					$("#dialogcontent_router").dialog({
												title: "Cambiar Sector",
												show: {
												effect: "explode",
												duration: 200,
												modal:true
												},
												hide: {
												effect: "explode",
												duration: 200
												},
												width : 260,
												height : 230,
												close : function(){
													$(this).dialog("destroy").empty();
													$("#dialogcontent_router").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_router").remove();
							                        },
							                        "Aceptar" : function(){
							                        	$("#form_router_mod_sector").submit();
							                        }
							                    }
					});
				}
			);
	});

	$("#contenedorPpal").on('click' , '#modificar_ip_router' , function(){

			console.log("Entro a modificar ip del router");
			console.log("id_router: "+$(this).attr("id_router"));
			var id_router = $(this).attr("id_router");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Routers",
					ID : id_router,
					queSos : "router", //a quien le voy a generar la vista
					action : "modif_ip",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_router',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_router").html(data);
					$("#dialogcontent_router").dialog({
												title: "Cambiar IP",
												show: {
												effect: "explode",
												duration: 200,
												modal:true
												},
												hide: {
												effect: "explode",
												duration: 200
												},
												width : 290,
												height : 230,
												close : function(){
													$(this).dialog("destroy").empty();
													$("#dialogcontent_router").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_router").remove();
							                        },
							                        "Aceptar" : function(){
							                        	$("#form_router_mod_ip").submit();
							                        }
							                    }
					});
				}
			);
	});

	$("#contenedorPpal").on('click' , '#eliminar_router' , function(){

		console.log("Entro a eliminar router");
		console.log("id_router: "+$(this).attr("id_router"));
		var id_router = $(this).attr("id_router");

		$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Routers",
					ID : id_router,
					queSos : "router", //a quien le voy a generar la vista
					action : "eliminar",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_router',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_router").html(data);
					$("#dialogcontent_router").dialog({
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
													$(this).dialog("destroy").empty();
													$("#dialogcontent_router").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_router").remove();
							                        },
							                        "Aceptar" : function(){
							                        		$("#form_detalle_eliminar_router").submit();
													}
							                    }
					});
				}
			);
	});

	$("#contenedorPpal").on('click' , '#agregar_descripcion_router' , function(){

		console.log("Entro a agregar descripcion");
		console.log("id_router: "+$(this).attr("id_router"));
		var id_router = $(this).attr("id_router");

			$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Routers",
				ID : id_router,
				queSos : "router", //a quien le voy a generar la vista
				action : "agregar_desc",
				viene : "normal"
			}, function(data){
				jQuery('<div/>', {
			    id: 'dialogcontent_router',
			    text: 'Texto por defecto!'
			}).appendTo('#contenedorPpal');
				$("#dialogcontent_router").html(data);
				$("#dialogcontent_router").dialog({
											title: "Descripcion",
											show: {
											effect: "explode",
											duration: 200,
											modal:true,
											},
											hide: {
											effect: "explode",
											duration: 200
											},
											width : 360,
											height: 290,
											close : function(){
												$(this).dialog("destroy").empty();
												$("#dialogcontent_router").remove();
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy").empty();
						                       		$("#dialogcontent_router").remove();
						                        },
						                        "Guardar" : function(){
						                        	$("#form_detalle_agregar_desc").submit();
						                        }
						                    }
				});
			}
		);

	});

</script>