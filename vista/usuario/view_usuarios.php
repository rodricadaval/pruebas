<div class="ui one column grid">
	<div class="column">
		<div class="ui raised segment">
			<a class="ui black ribbon label">{TABLA}</a>
			<input style="display:none;" type="button" class="btn btn-success" id="crear_usuario" value="Crear Usuario">
			<table  cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable_usuario"></table>
		</div>
	</div>
</div>

<script type="text/javascript">

	$.get('logueo/check_priority.php', function(permisos) {
		if ( permisos == 1 || permisos == 3 ) {
			$("#crear_usuario").show();
		}
	});

	$(document).ready(function(event){
		var url;

		$.blockUI({
			message: '<h2>Cargando...</h2>',
			css: {
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
			metodo: 'listarTodos'},
			dataType: 'json',
			success : function(data){
				$.get('logueo/check_priority.php', function(permisos) {

					quitar_cargando ();

					if( permisos == 1) {
						$("#dataTable_usuario").dataTable({
							"destroy" : true,
							"aaData" : data,
							"bAutoWidth": false,
							"iDisplayLength": 25,
							"rowHeight": 'auto',
							"aoColumns" :[
							{ "sTitle" : "Usuario" , "mData" : "usuario"},
							{ "sTitle" : "Permisos" , "mData" : "permisos"},
							{ "sTitle" : "Nombre" , "mData" : "nombre_apellido"},
							{ "sTitle" : "Area" , "mData" : "area"},
							{ "sTitle" : "Email" , "mData" : "email"},
							{ "sTitle" : "Int." , "mData" : "interno"},
							{ "sTitle": "Action", "mData" : "m","sWidth": "25%","sDefaultContent":
							'<a class="ventana_usuario" href="">Modificar</a>'}
							],
						})
					}
					else if (permisos == 2) {
						$("#dataTable_usuario").dataTable({
							"destroy" : true,
							"aaData" : data,
							"aoColumns" :[
							{ "sTitle" : "Usuario" , "mData" : "usuario"},
							{ "sTitle" : "Permisos" , "mData" : "permisos"},
							{ "sTitle" : "Nombre" , "mData" : "nombre_apellido"},
							{ "sTitle" : "Area" , "mData" : "area"},
							{ "sTitle" : "Email" , "mData" : "email"},
							{ "sTitle" : "Interno" , "mData" : "interno"},
							],
							"aoColumnDefs": [
							{ "sWidth": "20%", "aTargets": [ -1 ] }
							]
						})
					}
					else if( permisos == 3) {

						$("#dataTable_usuario").dataTable({
							"destroy" : true,
							"aaData" : data,
							"bAutoWidth": false,
							"iDisplayLength": 25,
							"aoColumns" :[
							{ "sTitle" : "ID" , "mData" : "id_usuario"},
							{ "sTitle" : "Usuario" , "mData" : "usuario"},
							{ "sTitle" : "Permisos" , "mData" : "permisos"},
							{ "sTitle" : "Nombre" , "mData" : "nombre_apellido"},
							{ "sTitle" : "Pass" , "mData" : "password"},
							{ "sTitle" : "Area" , "mData" : "area"},
							{ "sTitle" : "Email" , "mData" : "email"},
							{ "sTitle" : "Int." , "mData" : "interno"},
							{ "sTitle": "Action", "mData" : "m","sWidth": "25%","sDefaultContent":
							'<a class="ventana_usuario" href="">Modificar</a>'}
							]
						})
					}
					else { window.location.href = "logueo/login.php";}
				});
			}
		});
	});



	$("#contenedorPpal").on('click' , '#modificar_usuario' , function(){

		console.log($(this).attr("id_usuario"));
		var id_usuario = $(this).attr("id_usuario");
		$.post( "vista/dialog_content.php",
		{
			TablaPpal : "Usuarios",
			ID : id_usuario,
				select_Areas : "Areas",	//Clase de la cual quiero obtener el select
				select_Permisos : "Permisos", //Clase de la cual quiero sacar el select
				queSos : "usuario" //a quien le voy a generar la vista
			}, function(data){
				jQuery('<div/>', {
					id: 'dialogcontent',
					text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
				$("#dialogcontent").html(data);
				$("#dialogcontent").dialog({
					title: "Modificar Datos",
					show: {
						effect: "explode",
						duration: 200,
						modal:true
					},
					hide: {
						effect: "blind",
						duration: 200
					},
					width : 705,
					height: 500,
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
							$("#form").submit();
						}
					}
				});
			}
			);
	});

	$("#contenedorPpal").on('click' , '#eliminar_usuario' , function(){
		console.log($(this).attr("id_usuario"));
		var id_usuario = $(this).attr('id_usuario');
		var UrlToPass;
		UrlToPass="id_usuario="+id_usuario+"&action=eliminar";
		console.log(UrlToPass);
		$.ajax({
			type : 'POST',
			data : UrlToPass,
			url  : 'controlador/UsuariosController.php',
				success: function(responseText){ // Get the result and asign to each cases
					if(responseText == 0){
						alert("No se pudo eliminar el usuario");
					}
					else if(responseText == 2){
						alert("Se ha eliminado a usted mismo, debe volver a iniciar sesion");
						location.reload();
					}
					else if(responseText == 1) {
						alert("Se ha eliminado al usuario correctamente");
						$("#contenedorPpal").remove();
						jQuery('<div/>', {
							id: 'contenedorPpal',
							text: 'Texto por defecto!'
						}).appendTo('.realBody');
						$("#contenedorPpal").load("controlador/UsuariosController.php");
					}
					else{alert("Hubo algun error");}
				}
			});
	});

	$("#contenedorPpal").on('click' , '#crear_usuario' , function(){
		$.post( "vista/dialog_content.php",
		{
			TablaPpal : "Usuarios",
				select_Areas : "Areas",	//Clase de la cual quiero obtener el select
				select_Permisos : "Permisos", //Clase de la cual quiero sacar el select
				queSos : "nuevo" //a quien le voy a generar la vista
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
						modal:true
					},
					hide: {
						effect: "blind",
						duration: 200
					},
					width : 730,
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
							$("#form").submit();
						}
					}
				});
			});
	});

	$("#contenedorPpal").on('click' , '#generar_memorandum' , function(event){
		event.preventDefault();

		console.log("Entro a seleccionar los productos para el memorandum del usuario");
		console.log("id_usuario: "+$(this).attr("id_usuario"));
		var id_usuario = $(this).attr("id_usuario");
		var usuario = $(this).attr("usuario");

		$.post( "vista/dialog_listado_memorandum_usuario.php",
		{
			id_usuario : id_usuario,
			action : "ver_listado_para_memorandum"
		}, function(data){
			jQuery('<div/>', {
				id: 'dialogcontent_memorandum_usuario',
				text: 'Texto por defecto!'
			}).appendTo('#contenedorPpal');
			$("#dialogcontent_memorandum_usuario").html(data);
			$("#dialogcontent_memorandum_usuario").dialog({
				title: "Generar Memorandum para "+usuario,
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
					$("#dialogcontent_memorandum_usuario").remove();
				},
				buttons :
				{
					"Cancelar" : function () {
						$(this).dialog("destroy");
						$("#dialogcontent_memorandum_usuario").remove();
					},
					"Generar Memorandum" : function(){
						$("#form_listado_memorandum_usuario").submit();
					}
				}
			});
			$('.ui-dialog-buttonpane').append('<div class="pull-left"><p>Seleccionados: <span id="counter" text="0"></span></p></div>')
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

	$("#contenedorPpal").on('click','#ver_productos',function(event){
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
				text: 'Texto por defecto!'
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
				height : 630,
				close : function(){
					$(this).dialog("destroy");
					$("#dialogcontent_prod_usuario").remove();
				},
				buttons :
				{
					"Aceptar" : function () {
						$(this).dialog("destroy");
						$("#dialogcontent_prod_usuario").remove();
					}
				}
			});
		}
		);
	})

</script>