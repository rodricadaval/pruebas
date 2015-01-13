<h2>{TABLA}</h2>
<input type="button" class="btn btn-success" id="crear_usuario" value="Crear Usuario">
<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable_usuario"></table>
</div>
<script type="text/javascript">
	$(document).ready(function(event){
		var url;
					$.ajax({
							url : 'metodos_ajax.php',
							method: 'post',
							data:{ clase: '{TABLA}',
									metodo: 'listarTodos'},
							dataType: 'json',
							success : function(data){
								$.get('logueo/check_priority.php', function(permisos) {
								if( permisos == 1) {
										$("#dataTable_usuario").dataTable({
											"destroy" : true,
											"aaData" : data,
											"rowHeight": 'auto',
											"aoColumns" :[
												{ "sTitle" : "ID" , "mData" : "id_usuario"},
												{ "sTitle" : "Usuario" , "mData" : "usuario"},
												{ "sTitle" : "Permisos" , "mData" : "permisos"},
												{ "sTitle" : "Nombre" , "mData" : "nombre_apellido"},
												{ "sTitle" : "Area" , "mData" : "area"},
												{ "sTitle" : "Email" , "mData" : "email"},
												{ "sTitle" : "Interno" , "mData" : "interno"},
												{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
												'<a class="ventana_usuario " href="">Accion</a>'}
												],
										  "aoColumnDefs": [
									            { "sWidth": "20%", "aTargets": [ -1 ] }
									        ]

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
											"aoColumns" :[
												{ "sTitle" : "ID" , "mData" : "id_usuario"},
												{ "sTitle" : "Usuario" , "mData" : "usuario"},
												{ "sTitle" : "Permisos" , "mData" : "permisos"},
												{ "sTitle" : "Nombre" , "mData" : "nombre_apellido"},
												{ "sTitle" : "Pass" , "mData" : "password"},
												{ "sTitle" : "Area" , "mData" : "area"},
												{ "sTitle" : "Email" , "mData" : "email"},
												{ "sTitle" : "Interno" , "mData" : "interno"},
												{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
												'Accion'}
												],
										    "aoColumnDefs": [
									            { "sWidth": "20%", "aTargets": [ -1 ] }
									        ]
									})
									}
								});
							}
						});

		$.get('logueo/check_priority.php', function(permisos) {
				if (permisos == 2) {
					$("#crear_usuario").hide();
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

	$("#contenedorPpal").on('click' , '#generar_memorandum' , function(){
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
				}
			);
	});

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