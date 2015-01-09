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
		});
	});

	$("#contenedorPpal").on('click' , '#modificar_sector_impresora' , function(){

			console.log("Entro a modificar sector de la impresora");
			console.log("id_impresora: "+$(this).attr("id_impresora"));
			var id_impresora = $(this).attr("id_impresora");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Impresoras",
					ID : id_impresora,
					select_Areas : "Areas", //Clase de la cual quiero sacar el select
					queSos : "impresora", //a quien le voy a generar la vista
					action : "modif_sector",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_impresora',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_impresora").html(data);
					$("#dialogcontent_impresora").dialog({
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
													$("#dialogcontent_impresora").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_impresora").remove();
							                        },
							                        "Aceptar" : function(){
							                        	$("#form_impresora_mod_sector").submit();
							                        }
							                    }
					});
				}
			);
	});

	$("#contenedorPpal").on('click' , '#eliminar_impresora' , function(){

		console.log("Entro a eliminar impresora");
		console.log("id_impresora: "+$(this).attr("id_impresora"));
		var id_impresora = $(this).attr("id_impresora");

		$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Impresoras",
					ID : id_impresora,
					queSos : "impresora", //a quien le voy a generar la vista
					action : "eliminar",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_impresora',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_impresora").html(data);
					$("#dialogcontent_impresora").dialog({
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
													$("#dialogcontent_impresora").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_impresora").remove();
							                        },
							                        "Aceptar" : function(){
							                        		$("#form_detalle_eliminar_impresora").submit();
													}
							                    }
					});
				}
			);
	});

	$("#contenedorPpal").on('click' , '#modificar_ip_impresora' , function(){

			console.log("Entro a modificar ip de la impresora");
			console.log("id_impresora: "+$(this).attr("id_impresora"));
			var id_impresora = $(this).attr("id_impresora");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Impresoras",
					ID : id_impresora,
					queSos : "impresora", //a quien le voy a generar la vista
					action : "modif_ip",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_impresora',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_impresora").html(data);
					$("#dialogcontent_impresora").dialog({
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
													$("#dialogcontent_impresora").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_impresora").remove();
							                        },
							                        "Aceptar" : function(){
							                        	$("#form_impresora_mod_ip").submit();
							                        }
							                    }
					});
				}
			);
	});

	$("#contenedorPpal").on('click' , '#agregar_descripcion_impresora' , function(){

		console.log("Entro a agregar descripcion");
		console.log("id_impresora: "+$(this).attr("id_impresora"));
		var id_impresora = $(this).attr("id_impresora");

			$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Impresoras",
				ID : id_impresora,
				queSos : "impresora", //a quien le voy a generar la vista
				action : "agregar_desc",
				viene : "normal"
			}, function(data){
				jQuery('<div/>', {
			    id: 'dialogcontent_impresora',
			    text: 'Texto por defecto!'
			}).appendTo('#contenedorPpal');
				$("#dialogcontent_impresora").html(data);
				$("#dialogcontent_impresora").dialog({
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
												$("#dialogcontent_impresora").remove();
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy").empty();
						                       		$("#dialogcontent_impresora").remove();
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