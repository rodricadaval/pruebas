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
						{ "sTitle" : "Marca" , "mData" : "marca"},
						{ "sTitle" : "Capacidad" , "mData" : "capacidad"},
						{ "sTitle" : "Sector" , "mData" : "sector"},
						{ "sTitle" : "Usuario" ,"mDataProp": "nombre_apellido",
              				"mRender": function ( data, type, row ) {
  								return '<div id="ver_usuario" usuario="'+data+'"><a title="Ver productos de '+data+' "href="edit.php?usuario='+ data+'">'+data+'</a></div>';
							}
						},
						{ "sTitle" : "Cpu" , "mData" : "cpu_serie"},
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


$("#contenedorPpal").on('click' , '#modificar_usuario_disco' , function(){

		console.log("Entro a modificar usuario del disco");
		console.log("id_disco: "+$(this).attr("id_disco"));
		var id_disco = $(this).attr("id_disco");
		$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Discos",
				ID : id_disco,
				select_Areas : "Areas", //Clase de la cual quiero sacar el select
				queSos : "disco", //a quien le voy a generar la vista
				select_Computadoras : "Computadoras",
				action : "modif_usuario",
				viene : "normal"
			}, function(data){
				jQuery('<div/>', {
				    id: 'dialogcontent_disco',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
				$("#dialogcontent_disco").html(data);
				$("#dialogcontent_disco").dialog({
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
											height : 260,
											close : function(){
												$(this).dialog("destroy").empty();
												$("#dialogcontent_disco").remove();
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy").empty();
						                            $("#dialogcontent_disco").remove();
						                        },
						                        "Enviar" : function(){
						                        	$("#form_disco_mod_usuario").submit();
						                        }
						                    }
				});
			}
		);
	});

$("#contenedorPpal").on('click' , '#modificar_cpu_disco' , function(){

		console.log("Entro a modificar cpu del disco");
		console.log("id_disco: "+$(this).attr("id_disco"));
		var id_disco = $(this).attr("id_disco");
		$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Discos",
				ID : id_disco,
				select_Areas : "Areas", //Clase de la cual quiero sacar el select
				queSos : "disco", //a quien le voy a generar la vista
				select_Computadoras : "Computadoras",
				action : "modif_cpu",
				viene : "normal"
			}, function(data){
				jQuery('<div/>', {
				    id: 'dialogcontent_disco',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
				$("#dialogcontent_disco").html(data);
				$("#dialogcontent_disco").dialog({
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
												$(this).dialog("destroy").empty();
												$("#dialogcontent_disco").remove();
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy").empty();
						                            $("#dialogcontent_disco").remove();
						                        },
						                        "Enviar" : function(){
						                        	$("#form_disco_mod_cpu").submit();
						                        }
						                    }
				});
			}
		);
	});

	$("#contenedorPpal").on('click' , '#modificar_sector_disco' , function(){

			console.log("Entro a modificar sector del disco");
			console.log("id_disco: "+$(this).attr("id_disco"));
			var id_disco = $(this).attr("id_disco");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Discos",
					ID : id_disco,
					select_Areas : "Areas", //Clase de la cual quiero sacar el select
					queSos : "disco", //a quien le voy a generar la vista
					action : "modif_sector",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_disco',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_disco").html(data);
					$("#dialogcontent_disco").dialog({
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
												height : 360,
												close : function(){
													$(this).dialog("destroy").empty();
													$("#dialogcontent_disco").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_disco").remove();
							                        },
							                        "Aceptar" : function(){
							                        	$("#form_disco_mod_sector").submit();
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
												height : 600,
												close : function(){
													$(this).dialog("destroy").empty();
													$("#dialogcontent_prod_usuario").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_prod_usuario").remove();
							                        },
							                        "Enviar" : function(){
							                        	$("#dialogcontent_prod_usuario").submit();
							                        }
							                    }
					});
				}
		);
	})

$("#contenedorPpal").on('click' , '#desasignar_todo_disco' , function(){

			console.log("Entro a desasignar todo del disco");
			console.log("id_disco: "+$(this).attr("id_disco"));
			var id_disco = $(this).attr("id_disco");
	
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Discos",
					ID : id_disco,
					queSos : "disco", //a quien le voy a generar la vista
					action : "liberar"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_disco',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_disco").html(data);
					$("#dialogcontent_disco").dialog({
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
												height : 200,
												close : function(){
													$(this).dialog("destroy").empty();
													$("#dialogcontent_disco").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_disco").remove();
							                        },
							                        "Aceptar" : function(){
							                        		$("#liberar_disco").submit();
													}
							                    }
					});
				}
			);
		});


	$("#contenedorPpal").on('click' , '#eliminar_disco' , function(){

		console.log("Entro a eliminar disco");
		console.log("id_disco: "+$(this).attr("id_disco"));
		var id_disco = $(this).attr("id_disco");

		$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Discos",
					ID : id_disco,
					queSos : "disco", //a quien le voy a generar la vista
					action : "eliminar",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_disco',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_disco").html(data);
					$("#dialogcontent_disco").dialog({
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
													$("#dialogcontent_disco").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_disco").remove();
							                        },
							                        "Aceptar" : function(){
							                        		$("#form_detalle_eliminar_disco").submit();
													}
							                    }
					});
				}
			);
		

	});

</script>