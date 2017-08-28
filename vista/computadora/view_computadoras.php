<div class="ui one column grid">
	<div class="btn btn-danger" id="bajas">Dadas de baja</div>
	<div class="column" id="tabla">
		<div class="ui raised segment">
			<a class="ui teal ribbon label">{TABLA}</a>
			<table  cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable"></table>
		</div>
	</div>
	<div class="column" id="tabla_bajas" style="display: none;">
		<div class="ui raised segment">
			<a class="ui teal ribbon label">{TABLA}(dadas de baja)</a>
			<table  cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable-bajas"></table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(event){

		cargando ();

		$.ajax({
			url : 'metodos_ajax.php',
			method: 'post',
			data:{ clase: '{TABLA}',
			metodo: 'listarCorrecto',
			tipo: 'json'},
			dataType: 'json',
			success : function(data){
				$.get('logueo/check_priority.php', function(permisos) {

					quitar_cargando ();

					if( permisos == 1 || permisos == 3) {
						$("#dataTable").dataTable({
							"destroy" : true,
							"aaData" : data,
							"bAutoWidth": false,
							"iDisplayLength": 25,
							"aoColumns" :[
									//{ "sTitle" : "ID" , "mData" : "id_computadora"},
									{ "sTitle" : "Nro de Serie" , "mData" : "num_serie"},
									{ "sTitle" : "Marca" , "mData" : "marca"},
									{ "sTitle" : "Modelo" , "mData" : "modelo"},
									{ "sTitle" : "Tipo","mData" : "clase"},
									{ "sTitle" : "Slots Lib.", "mData" : "slots_libres"},
									{ "sTitle" : "M(GB)" , "mData" : "mem_max"},
									{ "sTitle" : "Sector" , "mData" : "sector"},
									{ "sTitle" : "Usuario" ,"sWidth": "21%", "mDataProp": "nombre_apellido",
									"mRender": function ( data, type, row ) {
										return '<div id="ver_usuario" usuario="'+data+'"><a title="Ver productos de '+data+' "href="edit.php?usuario='+ data+'">'+data+'</a></div>';
									}
								},
								{ "sTitle" : "Descripcion" , "mData" : "descripcion"},
								{ "sTitle": "Action", "mData" : "m","sWidth": "26%","sDefaultContent":
								'<a class="ventana_area " href="">Modificar</a>'}
								]

									/*,
									"aoColumnDefs": [
							            { "sWidth": "24%", "aTargets": [ -1 ] }
							            ]*/
							        })
					}
					else if (permisos == 2) {
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
									{ "sTitle" : "Max(GB)" , "mData" : "mem_max"},
									{ "sTitle" : "Sector" , "mData" : "sector"},
									{ "sTitle" : "Usuario" ,"mDataProp": "nombre_apellido",
									"mRender": function ( data, type, row ) {
										return '<div id="ver_usuario" usuario="'+data+'"><a title="Ver productos de '+data+' "href="edit.php?usuario='+ data+'">'+data+'</a></div>';
									}
								}
								],
								"aoColumnDefs": [
								{ "sWidth": "20%", "aTargets": [ -1 ] }
								]
							})
					}
					else { window.location.href = "logueo/login.php";}
				});
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
			viene : "normal"
		}, function(data){
			jQuery('<div/>', {
				id: 'dialogcontent_cpu',
				text: ''
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
							viene : "normal"
						}, function(data){
							jQuery('<div/>', {
								id: 'dialogcontent_cpu',
								text: ''
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
								height: 440,
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
						viene : "normal"
					}, function(data){
						jQuery('<div/>', {
							id: 'dialogcontent_cpu',
							text: ''
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
							height: 310,
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

	$("#contenedorPpal").on('click' , '#generar_memorandum' , function(event){
		event.preventDefault();

		console.log("Entro a seleccionar los productos para el memorandum de la computadora");
		console.log("id_computadora: "+$(this).attr("id_computadora"));
		var id_computadora = $(this).attr("id_computadora");

		$.post( "vista/dialog_listado_memorandum_cpu.php",
		{
			id_computadora : id_computadora,
			action : "ver_listado_para_memorandum"
		}, function(data){
			jQuery('<div/>', {
				id: 'dialogcontent_memorandum_cpu',
				text: ''
			}).appendTo('#contenedorPpal');
			$("#dialogcontent_memorandum_cpu").html(data);
			$("#dialogcontent_memorandum_cpu").dialog({
				title: "Generar Memorandum",
				show: {
					effect: "explode",
					duration: 200,
					modal:true
				},
				hide: {
					effect: "explode",
					duration: 200
				},
				width : 600,
				height : 450,
				close : function(){
					$(this).dialog("destroy");
					$("#dialogcontent_memorandum_cpu").remove();
				},
				buttons :
				{
					"Cancelar" : function () {
						$(this).dialog("destroy");
						$("#dialogcontent_memorandum_cpu").remove();
					},
					"Generar Memorandum" : function(){
						$("#form_listado_memorandum_cpu").submit();
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
				viene : "normal"
			}, function(data){
				jQuery('<div/>', {
					id: 'dialogcontent_cpu',
					text: ''
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
						"Aceptar" : function(){
							$("#form_detalle_eliminar_computadora").submit();
						}
					}
				});
			}
			);

	});


	$("#contenedorPpal").on('click','#ver_usuario',function(event){
		event.preventDefault();
		console.log("Entro a ver los productos del usuario");
		console.log("usuario: "+$(this).attr("usuario"));
		var usuario = $(this).attr("usuario");

		$.post( "vista/dialog_productos_usuario.php",
		{
			usuario : usuario,
			action : "ver_productos"
		}, function(data){
			jQuery('<div/>', {
				id: 'dialogcontent_prod_usuario',
				text: ''
			}).appendTo('#contenedorPpal');
			$("#dialogcontent_prod_usuario").html(data);
			$("#dialogcontent_prod_usuario").dialog({
				title: "Productos de "+usuario,
				show: {
					effect: "explode",
					duration: 200,
					modal:true
				},
				hide: {
					effect: "explode",
					duration: 200
				},
				width : 600,
				height : 640,
				close : function(){
					$(this).dialog("destroy").empty();
					$("#dialogcontent_prod_usuario").remove();
				},
				buttons :
				{
					"Aceptar" : function () {
						$(this).dialog("destroy").empty();
						$("#dialogcontent_prod_usuario").remove();
					}
				}
			});
		}
		);
	})

	$("#contenedorPpal").on('click','#ver_productos',function(event){
		event.preventDefault();
		console.log("Entro a ver los productos de la computadora");
		console.log("id_computadora: "+$(this).attr("id_computadora"));
		console.log("num_serie: "+$(this).attr("num_serie"));
		var id_computadora = $(this).attr("id_computadora");
		var num_serie = $(this).attr("num_serie");

		$.post( "vista/dialog_productos_cpu.php",
		{
			id_computadora : id_computadora,
			action : "ver_productos"
		}, function(data){
			jQuery('<div/>', {
				id: 'dialogcontent_cpu',
				text: ''
			}).appendTo('#contenedorPpal');
			$("#dialogcontent_cpu").html(data);
			$("#dialogcontent_cpu").dialog({
				title: "Productos de la pc: "+num_serie,
				show: {
					effect: "explode",
					duration: 200,
					modal:true
				},
				hide: {
					effect: "explode",
					duration: 200
				},
				width : 600,
				height : 630,
				close : function(){
					$(this).dialog("destroy");
					$("#dialogcontent_cpu").remove();
				},
				buttons :
				{
					"Aceptar" : function () {
						$(this).dialog("destroy");
						$("#dialogcontent_cpu").remove();
					}
				}
			});
		}
		);
	})

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
					viene : "normal"
				}, function(data){
					console.log(data);
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

	$("#contenedorPpal").on('click' , '#cambiar_num_serie' , function(){

		console.log("Entro a cambiar numero de serie");
		console.log("id_computadora: "+$(this).attr("id_computadora"));
		var id_computadora = $(this).attr("id_computadora");

		$.post( "vista/dialog_content.php",
		{
			TablaPpal : "Computadoras",
			ID : id_computadora,
					queSos : "computadora", //a quien le voy a generar la vista
					action : "modif_num_serie",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
						id: 'dialogcontent_cpu',
						text: 'Texto por defecto!'
					}).appendTo('#contenedorPpal');
					$("#dialogcontent_cpu").html(data);
					$("#dialogcontent_cpu").dialog({
						title: "Numero de serie",
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
								$("#form_modif_num_serie").submit();
							}
						}
					});
				}
				);

	});

	$("#contenedorPpal").on( 'mouseenter', '#dataTable_usuario tbody tr', function () {
		var area = $(this).find(':nth-child(4)');		
		$(area).tooltip();
		

		if($(this).find(':nth-child(4)').text() === 'CESION'){
			var id_usuario = $(area).closest('tr').find('#modificar_usuario').attr('id_usuario');

			$.post({
				url: 'controlador/UsuariosController.php',
				data: {
					id_usuario : id_usuario,
					action : "descripcion_area"
				},
				success: function (data) {
					$(area).attr('title',data);
				}
			});
		}		
	} );

	$("#contenedorPpal").on('click' , '#reactivar' , function(){

		var id_computadora = $(this).attr("id_computadora");

		$.post( "controlador/ComputadorasController.php",
		{
			ID : id_computadora,
			action : "reactivar_dialog"
		}, function(data){
			console.log(data);
			jQuery('<div/>', {
				id: 'dialogcontent',
				text: 'Texto por defecto!'
			}).appendTo('#contenedorPpal');
			$("#dialogcontent").html(data);
			$("#dialogcontent").dialog({
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
					$("#dialogcontent").remove();
				},
				buttons :
				{
					"Cancelar" : function () {
						$(this).dialog("destroy").empty();
						$("#dialogcontent").remove();
					},
					"Aceptar" : function(){
						$("#form_reactivar").submit();
					}
				}
			});
		}
		);
	});

	$('#contenedorPpal').on('click',"#bajas",function () {

		$('#tabla').hide();
		$('#tabla_bajas').show();
		$('#bajas').hide();

		$.ajax({
			url : 'metodos_ajax.php',
			method: 'post',
			data:{ clase: '{TABLA}',
			metodo: 'listarBajas',
			tipo: 'json'},
			dataType: 'json',
			success : function(data){
				$.get('logueo/check_priority.php', function(permisos) {

					quitar_cargando ();

					if( permisos == 1 || permisos == 3) {
						$("#dataTable-bajas").dataTable({
							"destroy" : true,
							"aaData" : data,
							"bAutoWidth": false,
							"iDisplayLength": 25,
							"aoColumns" :[
									//{ "sTitle" : "ID" , "mData" : "id_computadora"},
									{ "sTitle" : "Nro de Serie" , "mData" : "num_serie"},
									{ "sTitle" : "Marca" , "mData" : "marca"},
									{ "sTitle" : "Modelo" , "mData" : "modelo"},
									{ "sTitle" : "Tipo","mData" : "clase"},
									{ "sTitle" : "Slots Lib.", "mData" : "slots_libres"},
									{ "sTitle" : "M(GB)" , "mData" : "mem_max"},
									{ "sTitle" : "Sector" , "mData" : "sector"},
									{ "sTitle" : "Usuario" ,"sWidth": "21%", "mDataProp": "nombre_apellido",
									"mRender": function ( data, type, row ) {
										return '<div id="ver_usuario" usuario="'+data+'"><a title="Ver productos de '+data+' "href="edit.php?usuario='+ data+'">'+data+'</a></div>';
									}
								},
								{ "sTitle" : "Descripcion" , "mData" : "descripcion"},
								{ "sTitle": "Action", "mData" : "m","sWidth": "26%","sDefaultContent":
								'<a class="ventana_area " href="">Modificar</a>'}
								]

									/*,
									"aoColumnDefs": [
							            { "sWidth": "24%", "aTargets": [ -1 ] }
							            ]*/
							        })
					}
					else if (permisos == 2) {
						$("#dataTable-bajas").dataTable({
							"destroy" : true,
							"aaData" : data,
							"aoColumns" :[
									//{ "sTitle" : "ID" , "mData" : "id_computadora"},
									{ "sTitle" : "Nro de Serie" , "mData" : "num_serie"},
									{ "sTitle" : "Marca" , "mData" : "marca"},
									{ "sTitle" : "Modelo" , "mData" : "modelo"},
									{ "sTitle" : "Tipo" , "mData" : "clase"},
									{ "sTitle" : "Slots Libres" , "mData" : "slots_libres"},
									{ "sTitle" : "Max(GB)" , "mData" : "mem_max"},
									{ "sTitle" : "Sector" , "mData" : "sector"},
									{ "sTitle" : "Usuario" ,"mDataProp": "nombre_apellido",
									"mRender": function ( data, type, row ) {
										return '<div id="ver_usuario" usuario="'+data+'"><a title="Ver productos de '+data+' "href="edit.php?usuario='+ data+'">'+data+'</a></div>';
									}
								}
								],
								"aoColumnDefs": [
								{ "sWidth": "20%", "aTargets": [ -1 ] }
								]
							})
					}
					else { window.location.href = "logueo/login.php";}
				});
			}
		});
	});

	$("#contenedorPpal").on('click' , '#reactivar' , function(){

		var id_computadora = $(this).attr("id_computadora");
		console.log(id_computadora);

		$.post( "controlador/ComputadorasController.php",
		{
			ID : id_computadora,
			action : "reactivar_dialog"
		}, function(data){
			console.log(data);
			jQuery('<div/>', {
				id: 'dialogcontent',
				text: 'Texto por defecto!'
			}).appendTo('#contenedorPpal');
			$("#dialogcontent").html(data);
			$("#dialogcontent").dialog({
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
					$("#dialogcontent").remove();
				},
				buttons :
				{
					"Cancelar" : function () {
						$(this).dialog("destroy").empty();
						$("#dialogcontent").remove();
					},
					"Aceptar" : function(){
						$("#form_reactivar").submit();
					}
				}
			});
		}
		);
	});

	$("#contenedorPpal").on('click' , '#desasignar_usuario_computadora' , function(){

		console.log("Entro a desasignar usuario de la computadora");
		console.log("id_computadora: "+$(this).attr("id_computadoras"));
		var id_computadora = $(this).attr("id_computadora");

		$.post( "vista/dialog_content.php",
		{
			TablaPpal : "Computadoras",
			ID : id_computadora,
					queSos : "computadora", //a quien le voy a generar la vista
					action : "liberar"
				}, function(data){
					jQuery('<div/>', {
						id: 'dialogcontent_cpu',
						text: 'Texto por defecto!'
					}).appendTo('#contenedorPpal');
					$("#dialogcontent_cpu").html(data);
					$("#dialogcontent_cpu").dialog({
						title: "Liberar",
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
								$("#form_computadora_liberar").submit();
							}
						}
					});
				}
				);

	});
</script>
