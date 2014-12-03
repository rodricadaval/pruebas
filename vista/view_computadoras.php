<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable"></table>
<div id="dialogcontent_cpu" title="">
</div>
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
						//{ "sTitle" : "ID" , "mData" : "id_computadora"},
						{ "sTitle" : "Nro de Serie" , "mData" : "num_serie"},
						{ "sTitle" : "Marca" , "mData" : "marca"},
						{ "sTitle" : "Modelo" , "mData" : "modelo"},
						{ "sTitle" : "Tipo" , "mData" : "clase"},
						{ "sTitle" : "Sector" , "mData" : "sector"},
						{ "sTitle" : "Usuario" , "mData" : "usuario"},
						{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
										'<a class="ventana_area " href="">Modificar</a>'}
						]
    			})
			}

		});
	});

	$("#contenedorPpal").on('click' , '#modificar_computadora' , function(){

		console.log($(this).attr("id_computadora"));
		var id_computadora = $(this).attr("id_computadora");
		$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Computadoras",
				ID : id_computadora,
				select_Areas : "Areas", //Clase de la cual quiero sacar el select
				select_clases : "Computadoras",
				queSos : "computadora" //a quien le voy a generar la vista
			}, function(data){
				$("#dialogcontent_cpu").html(data);
				$("#dialogcontent_cpu").attr("title","Modificar Computadora");
				$("#dialogcontent_cpu").dialog({
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
											height: 300,
											close : function(){
												$(this).dialog("destroy").empty();
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy").empty();
						                        },
						                        "Enviar" : function(){
						                        	$("#form_computadora").submit();
						                        }
						                    }
				});
			}
		);
	});

	$("#contenedorPpal").on('click' , '#modificar_tipo_computadora' , function(){

			console.log($(this).attr("id_computadora"));
			var id_computadora = $(this).attr("id_computadora");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Computadoras",
					ID : id_computadora,
					select_clases : "Computadoras",
					queSos : "computadora", //a quien le voy a generar la vista
					action : "modif_tipo"
				}, function(data){
					$("#dialogcontent_cpu").html(data);
					$("#dialogcontent_cpu").attr("title","Cambiar Tipo");
					$("#dialogcontent_cpu").dialog({
												show: {
												effect: "blind",
												duration: 1000,
												modal:true
												},
												hide: {
												effect: "explode",
												duration: 200
												},
												width : 300,
												height: 230,
												close : function(){
													$(this).dialog("destroy").empty();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                        },
							                        "Enviar" : function(){
							                        	$("#form_cambiar_tipo_computadora").submit();
							                        }
							                    }
					});
				}
			);
		});

	$("#contenedorPpal").on('click' , '#modificar_sector_computadora' , function(){

				console.log($(this).attr("id_computadora"));
				var id_computadora = $(this).attr("id_computadora");
				$.post( "vista/dialog_content.php",
					{
						TablaPpal : "Computadoras",
						ID : id_computadora,
						select_Areas : "Areas", //Clase de la cual quiero sacar el select
						queSos : "computadora", //a quien le voy a generar la vista
						action : "modif_sector"
					}, function(data){
						$("#dialogcontent_cpu").html(data);
						$("#dialogcontent_cpu").attr("title","Cambiar Sector");
						$("#dialogcontent_cpu").dialog({
													show: {
													effect: "blind",
													duration: 1000,
													modal:true
													},
													hide: {
													effect: "explode",
													duration: 200
													},
													width : 300,
													height: 230,
													close : function(){
														$(this).dialog("destroy").empty();
													},
													buttons :
								                    {
								                        "Cancelar" : function () {
								                            $(this).dialog("destroy").empty();
								                        },
								                        "Aceptar" : function(){
								                        var objeto = $("#form_computadora_mod_sector").serializeArray();
								                        if(objeto != ""){
							                        		if(objeto[0].value != 0 || objeto[0].value != ""){
							                        			$("#form_computadora_mod_sector").submit();
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

	$("#contenedorPpal").on('click' , '#eliminar_computadora' , function(){

		console.log($(this).attr("id_computadora"));
		var id_computadora = $(this).attr("id_computadora");
		datosUrl = "id_computadora="+id_computadora+"&action=eliminar";
		console.log(datosUrl);

		$.ajax({
			url: 'controlador/ComputadorasController.php',
			type: 'POST',
			data: datosUrl,
		})
		.done(function(response) {
			if(response){
				console.log("success");
				alert("El monitor ha sido eliminado correctamente.");
				$("#contenedorPpal").load("controlador/ComputadorasController.php");
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