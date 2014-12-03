<h2>{TABLA}</h2>
<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable"></table>
<div id="dialogcontent_monitor" title=""></div>

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
				$("#dataTable").dataTable({
   			 		"destroy" : true,
   			 		"bJQueryUI" : true,
					"aaData" : data,
					"aoColumns" :[
						//{ "sTitle" : "ID" , "mData" : "id_monitor"},
						{ "sTitle" : "Nro de Serie" , "mData" : "num_serie"},
						{ "sTitle" : "Marca" , "mData" : "marca"},
						{ "sTitle" : "Modelo" , "mData" : "modelo"},
						{ "sTitle" : "Pulgadas" , "mData" : "pulgadas"},
						{ "sTitle" : "Sector" , "mData" : "sector"},
						{ "sTitle" : "Usuario" , "mData" : "usuario"},
						{ "sTitle" : "Cpu" , "mData" : "cpu_serie"},
						{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
										'<a class="ventana_area " href="">Modificar</a>'}
						]
    			})
			}

		});
	});

	$("#contenedorPpal").on('click' , '#modificar_monitor' , function(){

		console.log($(this).attr("id_monitor"));
		var id_monitor = $(this).attr("id_monitor");
		$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Monitores",
				ID : id_monitor,
				select_Areas : "Areas", //Clase de la cual quiero sacar el select
				queSos : "monitor" //a quien le voy a generar la vista
			}, function(data){
				$("#dialogcontent_monitor").html(data);
				$("#dialogcontent_monitor").attr("title","Modificar Monitor");
				$("#dialogcontent_monitor").dialog({
											show: {
											effect: "blind",
											duration: 1000,
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
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy").empty();
						                        },
						                        "Enviar" : function(){
						                        	$("#form_monitor").submit();
						                        }
						                    }
				});
			}
		);
	});

$("#contenedorPpal").on('click' , '#modificar_usuario_monitor' , function(){

		console.log($(this).attr("id_monitor"));
		var id_monitor = $(this).attr("id_monitor");
		$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Monitores",
				ID : id_monitor,
				select_Areas : "Areas", //Clase de la cual quiero sacar el select
				queSos : "monitor", //a quien le voy a generar la vista
				select_Computadoras : "Computadoras",
				action : "modif_usuario"
			}, function(data){
				$("#dialogcontent_monitor").html(data);
				$("#dialogcontent_monitor").attr("title","Cambiar de Usuario");
				$("#dialogcontent_monitor").dialog({
											show: {
											effect: "blind",
											duration: 1000,
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
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy").empty();
						                        },
						                        "Enviar" : function(){
						                        	$("#form_monitor_mod_usuario").submit();
						                        }
						                    }
				});
			}
		);
	});

$("#contenedorPpal").on('click' , '#modificar_cpu_monitor' , function(){

		console.log($(this).attr("id_monitor"));
		var id_monitor = $(this).attr("id_monitor");
		$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Monitores",
				ID : id_monitor,
				select_Areas : "Areas", //Clase de la cual quiero sacar el select
				queSos : "monitor", //a quien le voy a generar la vista
				select_Computadoras : "Computadoras",
				action : "modif_cpu"
			}, function(data){
				$("#dialogcontent_monitor").html(data);
				$("#dialogcontent_monitor").attr("title","Cambiar de CPU");
				$("#dialogcontent_monitor").dialog({
											show: {
											effect: "blind",
											duration: 1000,
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
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy").empty();
						                        },
						                        "Enviar" : function(){
						                        	$("#form_monitor_mod_cpu").submit();
						                        }
						                    }
				});
			}
		);
	});

	$("#contenedorPpal").on('click' , '#modificar_sector_monitor' , function(){

			console.log($(this).attr("id_monitor"));
			var id_monitor = $(this).attr("id_monitor");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Monitores",
					ID : id_monitor,
					select_Areas : "Areas", //Clase de la cual quiero sacar el select
					queSos : "monitor", //a quien le voy a generar la vista
					action : "modif_sector"
				}, function(data){
					$("#dialogcontent_monitor").html(data);
					$("#dialogcontent_monitor").attr("title","Cambiar Sector");
					$("#dialogcontent_monitor").dialog({
												show: {
												effect: "blind",
												duration: 1000,
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
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                        },
							                        "Aceptar" : function(){
							                        	var objeto = $("#form_monitor_mod_sector").serializeArray();
							                        	if(objeto != ""){
							                        		if(objeto[0].value != 0 || objeto[0].value != ""){
							                        			$("#form_monitor_mod_sector").submit();
							                        		}
							                        		else{
							                        			$(this).dialog("destroy").empty();
							                        		}
							                          	}
							                          	else{ $(this).dialog("destroy").empty();}
							                        }
							                    }
					});
				}
			);
		});

$("#contenedorPpal").on('click' , '#desasignar_todo_monitor' , function(){

			console.log($(this).attr("id_monitor"));
			var id_monitor = $(this).attr("id_monitor");
	
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Monitores",
					ID : id_monitor,
					queSos : "monitor", //a quien le voy a generar la vista
					action : "liberar"
				}, function(data){
					$("#dialogcontent_monitor").html(data);
					$("#dialogcontent_monitor").attr("title","Liberar");
					$("#dialogcontent_monitor").dialog({
												show: {
												effect: "blind",
												duration: 1000,
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
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                        },
							                        "Aceptar" : function(){
							                        		$("#liberar_monitor").submit();
													}
							                    }
					});
				}
			);
		});


	$("#contenedorPpal").on('click' , '#eliminar_monitor' , function(){

		console.log($(this).attr("id_monitor"));
		var id_monitor = $(this).attr("id_monitor");
		datosUrl = "id_monitor="+id_monitor+"&action=eliminar";
		console.log(datosUrl);

		$.ajax({
			url: 'controlador/MonitoresController.php',
			type: 'POST',
			data: datosUrl,
		})
		.done(function(response) {
			if(response){
				console.log("success");
				alert("El monitor ha sido eliminado correctamente.");
				$("#contenedorPpal").load("controlador/MonitoresController.php");
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

	});

</script>