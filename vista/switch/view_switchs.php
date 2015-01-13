<h2>{TABLA}</h2>
<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable"></table>

<script type="text/javascript">

	$(document).ready(function(event){
		$.ajax({
			url : 'metodos_ajax.php',
			method: 'post',
			data:{ clase: '{TABLA}',
				   metodo: 'listarCorrecto',
				   tipo: 'json'},
			dataType: 'json',
			success : function(data){
				$.get('logueo/check_priority.php', function(permisos) {
					if( permisos == 1 || permisos == 3) {
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

	$("#contenedorPpal").on('click' , '#modificar_sector_switch' , function(){

			console.log("Entro a modificar sector del switch");
			console.log("id_switch: "+$(this).attr("id_switch"));
			var id_switch = $(this).attr("id_switch");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Switchs",
					ID : id_switch,
					select_Areas : "Areas", //Clase de la cual quiero sacar el select
					queSos : "switch", //a quien le voy a generar la vista
					action : "modif_sector",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_switch',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_switch").html(data);
					$("#dialogcontent_switch").dialog({
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
													$("#dialogcontent_switch").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_switch").remove();
							                        },
							                        "Aceptar" : function(){
							                        	$("#form_switch_mod_sector").submit();
							                        }
							                    }
					});
				}
			);
	});

	$("#contenedorPpal").on('click' , '#modificar_ip_switch' , function(){

			console.log("Entro a modificar ip del switch");
			console.log("id_switch: "+$(this).attr("id_switch"));
			var id_switch = $(this).attr("id_switch");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Switchs",
					ID : id_switch,
					queSos : "switch", //a quien le voy a generar la vista
					action : "modif_ip",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_switch',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_switch").html(data);
					$("#dialogcontent_switch").dialog({
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
													$("#dialogcontent_switch").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_switch").remove();
							                        },
							                        "Aceptar" : function(){
							                        	$("#form_switch_mod_ip").submit();
							                        }
							                    }
					});
				}
			);
	});

	$("#contenedorPpal").on('click' , '#eliminar_switch' , function(){

		console.log("Entro a eliminar switch");
		console.log("id_switch: "+$(this).attr("id_switch"));
		var id_switch = $(this).attr("id_switch");

		$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Switchs",
					ID : id_switch,
					queSos : "switch", //a quien le voy a generar la vista
					action : "eliminar",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_switch',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_switch").html(data);
					$("#dialogcontent_switch").dialog({
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
													$("#dialogcontent_switch").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_switch").remove();
							                        },
							                        "Aceptar" : function(){
							                        		$("#form_detalle_eliminar_switch").submit();
													}
							                    }
					});
				}
			);
	});

	$("#contenedorPpal").on('click' , '#agregar_descripcion_switch' , function(){

		console.log("Entro a agregar descripcion");
		console.log("id_switch: "+$(this).attr("id_switch"));
		var id_switch = $(this).attr("id_switch");

			$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Switchs",
				ID : id_switch,
				queSos : "switch", //a quien le voy a generar la vista
				action : "agregar_desc",
				viene : "normal"
			}, function(data){
				jQuery('<div/>', {
			    id: 'dialogcontent_switch',
			    text: 'Texto por defecto!'
			}).appendTo('#contenedorPpal');
				$("#dialogcontent_switch").html(data);
				$("#dialogcontent_switch").dialog({
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
												$("#dialogcontent_switch").remove();
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy").empty();
						                       		$("#dialogcontent_switch").remove();
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