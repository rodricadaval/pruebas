<h2>Memorias en Stock</h2>
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
						{ "sTitle" : "Marca" , "mData" : "marca"},
						{ "sTitle" : "Tipo" , "mData" : "tipo"},
						{ "sTitle" : "Capacidad" , "mData" : "capacidad"},
						{ "sTitle" : "Velocidad (Mhz)" , "mData" : "velocidad"},
						{ "sTitle" : "Sector" , "mData" : "sector"},
						{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
										'<a class="ventana_area " href="">Modificar</a>'}
						]
    			})
			}
		});
	});

	$("#contenedorPpal").on('click' , '#modificar_sector_memoria' , function(){

			console.log("Entro a modificar sector de la memoria");
			console.log("id_memoria: "+$(this).attr("id_memoria"));
			var id_memoria = $(this).attr("id_memoria");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Memorias",
					ID : id_memoria,
					select_Areas : "Areas", //Clase de la cual quiero sacar el select
					queSos : "memoria", //a quien le voy a generar la vista
					action : "modif_sector",
					viene : "stock"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_memoria',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_memoria").html(data);
					$("#dialogcontent_memoria").dialog({
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
													$("#dialogcontent_memoria").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_memoria").remove();
							                        },
							                        "Aceptar" : function(){
							                        	$("#form_memoria_mod_sector").submit();
							                        }
							                    }
					});
				}
			);
	});

	$("#contenedorPpal").on('click' , '#modificar_usuario_memoria' , function(){

		console.log("Entro a modificar usuario de la memoria");
		console.log("id_memoria: "+$(this).attr("id_memoria"));
		var id_memoria = $(this).attr("id_memoria");
		$.post( "vista/dialog_content.php",
			{
				TablaPpal : "Memorias",
				ID : id_memoria,
				select_Areas : "Areas", //Clase de la cual quiero sacar el select
				queSos : "memoria", //a quien le voy a generar la vista
				select_Computadoras : "Computadoras",
				action : "modif_usuario",
				viene : "stock"
			}, function(data){
				jQuery('<div/>', {
				    id: 'dialogcontent_memoria',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
				$("#dialogcontent_memoria").html(data);
				$("#dialogcontent_memoria").dialog({
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
												$("#dialogcontent_memoria").remove();
											},
											buttons :
						                    {
						                        "Cancelar" : function () {
						                            $(this).dialog("destroy").empty();
						                            $("#dialogcontent_memoria").remove();
						                        },
						                        "Enviar" : function(){
						                        	$("#form_memoria_mod_usuario").submit();
						                        }
						                    }
				});
			}
		);
	});

	$("#contenedorPpal").on('click' , '#modificar_cpu_memoria' , function(){

			console.log("Entro a modificar cpu de la memoria");
			console.log("id_memoria: "+$(this).attr("id_memoria"));
			var id_memoria = $(this).attr("id_memoria");
			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Memorias",
					ID : id_memoria,
					select_Areas : "Areas", //Clase de la cual quiero sacar el select
					queSos : "memoria", //a quien le voy a generar la vista
					select_Computadoras : "Computadoras",
					action : "modif_cpu",
					viene : "stock"
				}, function(data){
					jQuery('<div/>', {
					    id: 'dialogcontent_memoria',
					    text: 'Texto por defecto!'
					}).appendTo('#contenedorPpal');
					$("#dialogcontent_memoria").html(data);
					$("#dialogcontent_memoria").dialog({
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
													$("#dialogcontent_memoria").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy").empty();
							                            $("#dialogcontent_memoria").remove();
							                        },
							                        "Enviar" : function(){
							                        	$("#form_memoria_mod_cpu").submit();
							                        }
							                    }
					});
				}
			);
		});

	$("#contenedorPpal").on('click' , '#eliminar_memoria' , function(){

		console.log("Entro a eliminar memoria");
		console.log("id_memoria: "+$(this).attr("id_memoria"));
		var id_memoria = $(this).attr("id_memoria");
		var datosUrl = "id_memoria="+id_memoria+"&action=eliminar";
        
        datosUrl += "&action=eliminar";

        $.ajax({
            url: 'controlador/MemoriasController.php',
            type: 'POST',
            data: datosUrl,
            success: function(response){
                if(response){
                    console.log("success");
                    alert("La memoria ha sido dado de baja correctamente.");
                    $("#contenedorPpal").remove();
                    jQuery('<div/>', {
                    id: 'contenedorPpal',
                    text: 'Texto por defecto!'
                    }).appendTo('.realBody');
                    $("#contenedorPpal").load("controlador/MemoriasController.php");
                }
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