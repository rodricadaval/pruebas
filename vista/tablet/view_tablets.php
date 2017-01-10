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
							"iDisplayLength": 300,
							"aoColumns" :[
							{ "sTitle" : "Nro de Serie" , "mData" : "num_serie"},
							{ "sTitle" : "Marca" , "mData" : "marca"},
							{ "sTitle" : "Modelo" , "mData" : "modelo"},
							{ "sTitle" : "Sector" , "mData" : "sector"},
							{ "sTitle" : "Usuario" ,"sWidth": "21%", "mData": "nombre_apellido"},
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
							{ "sTitle" : "Nro de Serie" , "mData" : "num_serie"},
							{ "sTitle" : "Marca" , "mData" : "marca"},
							{ "sTitle" : "Modelo" , "mData" : "modelo"},
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

	$("#contenedorPpal").on('click' , '#cambiar_num_serie' , function(){

		console.log("Entro a cambiar numero de serie");
		console.log("id_tablet: "+$(this).attr("id_tablet"));
		var id_tablet = $(this).attr("id_tablet");

		$.post( "controlador/TabletsController.php",
		{
			ID : id_tablet,
			action : "num_serie"
		}, function(data){
			jQuery('<div/>', {
				id: 'dialogcontent',
				text: 'Texto por defecto!'
			}).appendTo('#contenedorPpal');
			$("#dialogcontent").html(data);
			$("#dialogcontent").dialog({
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
					$("#dialogcontent").remove();
				},
				buttons :
				{
					"Cancelar" : function () {
						$(this).dialog("destroy").empty();
						$("#dialogcontent").remove();
					},
					"Guardar" : function(){
						$("#form_num_serie_usuario").submit();
					}
				}
			});
		}
		);
	});

	$("#contenedorPpal").on('click' , '#agregar_descripcion_tablet' , function(){

		console.log("Entro a agregar descripcion");
		console.log("id_tablet: "+$(this).attr("id_tablet"));
		var id_tablet = $(this).attr("id_tablet");

		$.post( "controlador/TabletsController.php",
		{
			ID : id_tablet,
			action : "descripcion",
		}, function(data){
			jQuery('<div/>', {
				id: 'dialogcontent',
				text: 'Texto por defecto!'
			}).appendTo('#contenedorPpal');
			$("#dialogcontent").html(data);
			$("#dialogcontent").dialog({
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
					$("#dialogcontent").remove();
				},
				buttons :
				{
					"Cancelar" : function () {
						$(this).dialog("destroy").empty();
						$("#dialogcontent").remove();
					},
					"Guardar" : function(){
						$("#form_detalle_agregar_desc").submit();
						location.reload();
					}
				}
			});
		}
		);
	});

	$("#contenedorPpal").on('click' , '#generar_memorandum' , function(event){
		event.preventDefault();

		console.log("Entro a seleccionar los productos para el memorandum de la tablet");
		console.log("id_tablet: "+$(this).attr("id_tablet"));
		var id_tablet = $(this).attr("id_tablet");

		window.open("controlador/TABLETAPDF.php?"+"id_tablet="+id_tablet);
	});

	$("#contenedorPpal").on('click' , '#desasignar_usuario_tablet' , function(){

		console.log("Entro a desasignar tablet");
		console.log("id_tablet: "+$(this).attr("id_tablet"));
		var id_tablet = $(this).attr("id_tablet");

		$.post( "controlador/TabletsController.php",
		{
			ID : id_tablet,
			action : "desasignar"
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
						$("#form_desasignar").submit();
					}
				}
			});
		}
		);
	});

	$("#contenedorPpal").on('click','#ver_detalle',function(event){
		event.preventDefault();
		console.log("Entro a ver detalles de la tablet");
		console.log("id_tablet: "+$(this).attr("id_tablet"));
		var id_tablet = $(this).attr("id_tablet");

		$.post( "controlador/TabletsController.php",
		{
			ID : id_tablet,
			action : "detalle"
		}, function(data){
			console.log(data);
			jQuery('<div/>', {
				id: 'dialogcontent',
				text: ''
			}).appendTo('#contenedorPpal');
			$("#dialogcontent").html(data);
			$("#dialogcontent").dialog({
				title: "Componentes de la tablet: ",
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
					$(this).dialog("destroy");
					$("#dialogcontent").remove();
				},
				buttons :
				{
					"Aceptar" : function () {
						$(this).dialog("destroy");
						$("#dialogcontent").remove();
					}
				}
			});
		}
		);
	});

	$("#contenedorPpal").on('click' , '#modificar_usuario_tablet' , function(){

		console.log("Entro a modificar usuario");
		console.log("id_tablet: "+$(this).attr("id_tablet"));
		var id_tablet = $(this).attr("id_tablet");
		$.post( "controlador/TabletsController.php",
		{
			ID : id_tablet,
			action : "usuario"
		}, function(data){
			console.log(data);
			jQuery('<div/>', {
				id: 'dialogcontent',
				text: ''
			}).appendTo('#contenedorPpal');
			$("#dialogcontent").html(data);
			$("#dialogcontent").dialog({
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
					$("#dialogcontent").remove();
				},
				buttons :
				{
					"Cancelar" : function () {
						$(this).dialog("destroy").empty();
						$("#dialogcontent").remove();
					},
					"Enviar" : function(){
						$("#form_cambiar_usuario_tablet").submit();
					}
				}
			});
		}
		);
	});

	$("#contenedorPpal").on('click' , '#eliminar_tablet' , function(){

		console.log("Entro a dar de baja tablet");
		console.log("id_tablet: "+$(this).attr("id_tablet"));
		var id_tablet = $(this).attr("id_tablet");

		$.post( "controlador/TabletsController.php",
		{
			ID : id_tablet,
			action : "dar_baja"
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
						$("#form_dar_baja").submit();
					}
				}
			});
		}
		);
	});

</script>