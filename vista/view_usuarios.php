<h2>{TABLA}</h2>
<div id="nuevo_usuario">
	<input type="button" id="crear_usuario" value="Crear Usuario">
</div>
<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable"></table>
<div id="dialogcontent" title="Crear/Modificar Usuario">
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
										$("#dataTable").dataTable({
											"destroy" : true,
											"bJQueryUI" : true,
											"aaData" : data,
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
												]
										})
									}
									else if (permisos == 2) {
										$("#dataTable").dataTable({
											"destroy" : true,
											"bJQueryUI" : true,
											"aaData" : data,
											"aoColumns" :[
												{ "sTitle" : "Usuario" , "mData" : "usuario"},
												{ "sTitle" : "Permisos" , "mData" : "permisos"},
												{ "sTitle" : "Nombre" , "mData" : "nombre_apellido"},
												{ "sTitle" : "Area" , "mData" : "area"},
												{ "sTitle" : "Email" , "mData" : "email"},
												{ "sTitle" : "Interno" , "mData" : "interno"}
											]
									})
									}
									else if( permisos == 3) {
										$("#dataTable").dataTable({
											"destroy" : true,
											"bJQueryUI" : true,
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
												]
									})
									}
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
				$("#dialogcontent").html(data);
				$("#dialogcontent").dialog({
											show: {
											effect: "blind",
											duration: 1000,
											modal:true
											},
											hide: {
											effect: "explode",
											duration: 200
											},
											width : 400,
											close : function(){
												$(this).dialog("destroy").empty();
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
					$("#contenedorPpal").load("controlador/UsuariosController.php");
				}
				else{alert("Hubo algun error");}
				}
		});
	});
	$("#nuevo_usuario").on('click' , '#crear_usuario' , function(){
		$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Usuarios",
				select_Areas : "Areas",	//Clase de la cual quiero obtener el select
				select_Permisos : "Permisos", //Clase de la cual quiero sacar el select
				queSos : "nuevo" //a quien le voy a generar la vista
			}, function(data){
				$("#dialogcontent").html(data);
				$("#dialogcontent").dialog({
											show: {
											effect: "blind",
											duration: 1000,
											modal:true
											},
											hide: {
											effect: "explode",
											duration: 200
											},
											width : 400,
											close : function(){
												$(this).dialog("destroy").empty();
											}
				});
			});
	});

</script>