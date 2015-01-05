<h2>{TABLA} en Stock</h2>
<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable"></table>
<script type="text/javascript">

	$(document).ready(function(event){
		$.ajax({
			url : 'metodos_ajax.php',
			method: 'post',
			data:{ clase: '{TABLA}',
				   metodo: 'listarEnStock',
				   tipo: 'json'},
			dataType: 'json',
			success : function(data){
				$("#dataTable").dataTable({
   			 		"destroy" : true,   			 		
					"aaData" : data,
					"aoColumns" :[
						//{ "sTitle" : "ID" , "mData" : "id_computadora"},
						{ "sTitle" : "Nro de Serie" , "mData" : "num_serie"},
						{ "sTitle" : "Marca" , "mData" : "marca"},
						{ "sTitle" : "Modelo" , "mData" : "modelo"},
						{ "sTitle" : "Tipo" , "mData" : "clase"},
						{ "sTitle" : "Slots Libres" , "mData" : "slots_libres"},
						{ "sTitle" : "Sector" , "mData" : "sector"},
						{ "sTitle" : "Descripcion" , "mData" : "descripcion"},
						{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
										'<a class="ventana_area " href="">Modificar</a>'}
						]
    			})
			}
		});
	});

	$("#contenedorPpal").on('click' , '#modificar_tipo_computadora' , function(){

			console.log("Entro a modificar tipo");
			console.log("id_computadora: "+$(this).attr("id_computadora"));
			var id_computadora = $(this).attr("id_computadora");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Computadoras",
					ID : id_computadora,
					select_clases : "Computadoras",
					queSos : "computadora", //a quien le voy a generar la vista
					action : "modif_tipo",
					viene : "stock"
				}, function(data){
					jQuery('<div/>', {
					    id: 'dialogcontent_cpu',
					    text: 'Texto por defecto!'
					}).appendTo('#contenedorPpal');
					$("#dialogcontent_cpu").html(data);
					$("#dialogcontent_cpu").dialog({
												title: "Cambiar Tipo",
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
												height: 240,
												close : function(){
													$(this).dialog("destroy").empty();
													$("#dialogcontent_cpu").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                           $(this).dialog("destroy").empty();
							                           $("#dialogcontent_cpu").remove();
							                        },
							                        "Enviar" : function(){
							                        	$("#form_cambiar_tipo_computadora").submit();
							                        }
							                    }
					});
				}
			);
		});

	$("#contenedorPpal").on('click' , '#modificar_usuario_computadora' , function(){

					console.log("Entro a modificar usuario");
					console.log("id_computadora: "+$(this).attr("id_computadora"));
					var id_computadora = $(this).attr("id_computadora");
					$.post( "vista/dialog_content.php",
						{
							TablaPpal : "Computadoras",
							ID : id_computadora,
							select_Areas : "Areas",
							queSos : "computadora", //a quien le voy a generar la vista
							action : "modif_usuario",
							viene : "stock"
						}, function(data){
							jQuery('<div/>', {
							    id: 'dialogcontent_cpu',
							    text: 'Texto por defecto!'
							}).appendTo('#contenedorPpal');
							$("#dialogcontent_cpu").html(data);
							$("#dialogcontent_cpu").dialog({
														title: "Cambiar Usuario",
														show: {
														effect: "explode",
														duration: 150,
														modal:true
														},
														hide: {
														effect: "explode",
														duration: 150
														},
														width : 400,
														height: 350,
														close : function(){
															$(this).dialog("destroy").empty();
															$("#dialogcontent_cpu").remove();
														},
														buttons :
									                    {
									                        "Cancelar" : function () {
									                           $(this).dialog("destroy").empty();
									                           $("#dialogcontent_cpu").remove();
									                        },
									                        "Enviar" : function(){
									                        	$("#form_cambiar_usuario_computadora").submit();
									                        }
									                    }
							});
						}
					);
		});

	$("#contenedorPpal").on('click' , '#modificar_sector_computadora' , function(){

				console.log("Entro a modificar sector");
				console.log("id_computadora: "+$(this).attr("id_computadora"));
				var id_computadora = $(this).attr("id_computadora");
				$.post( "vista/dialog_content.php",
					{
						TablaPpal : "Computadoras",
						ID : id_computadora,
						select_Areas : "Areas", //Clase de la cual quiero sacar el select
						queSos : "computadora", //a quien le voy a generar la vista
						action : "modif_sector",
						viene : "stock"
					}, function(data){
						jQuery('<div/>', {
					    id: 'dialogcontent_cpu',
					    text: 'Texto por defecto!'
					}).appendTo('#contenedorPpal');
						$("#dialogcontent_cpu").html(data);
						$("#dialogcontent_cpu").dialog({
													title: "Cambiar Sector",
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
														$("#dialogcontent_cpu").remove();
													},
													buttons :
								                    {
								                        "Cancelar" : function () {
								                            $(this).dialog("destroy").empty();
								                       		$("#dialogcontent_cpu").remove();
								                        },
								                        "Aceptar" : function(){
								                        	$("#form_computadora_mod_sector").submit();
								                        }
								                    }
						});
					}
				);
			});

	$("#contenedorPpal").on('click' , '#eliminar_computadora' , function(){

		console.log("Entro a eliminar");
		console.log("id_computadora: "+$(this).attr("id_computadora"));
		var id_computadora = $(this).attr("id_computadora");

			$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Computadoras",
				ID : id_computadora,
				queSos : "computadora", //a quien le voy a generar la vista
				action : "eliminar",
				viene : "stock"
			}, function(data){
				jQuery('<div/>', {
			    id: 'dialogcontent_cpu',
			    text: 'Texto por defecto!'
			}).appendTo('#contenedorPpal');
				$("#dialogcontent_cpu").html(data);
				$("#dialogcontent_cpu").dialog({
											title: "Motivo de baja",
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
												$("#dialogcontent_cpu").remove();
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy").empty();
						                       		$("#dialogcontent_cpu").remove();
						                        },
						                        "Guardar" : function(){
						                        	$("#form_detalle_eliminar_computadora").submit();
						                        }
						                    }
				});
			}
		);

	});


	$("#contenedorPpal").on('click' , '#agregar_descripcion_computadora' , function(){

			console.log("Entro a agregar descripcion");
			console.log("id_computadora: "+$(this).attr("id_computadora"));
			var id_computadora = $(this).attr("id_computadora");

				$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Computadoras",
					ID : id_computadora,
					queSos : "computadora", //a quien le voy a generar la vista
					action : "agregar_desc",
					viene : "stock"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_cpu',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_cpu").html(data);
					$("#dialogcontent_cpu").dialog({
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
													$("#dialogcontent_cpu").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                       		$("#dialogcontent_cpu").remove();
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