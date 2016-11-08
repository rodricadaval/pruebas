<div class="dialogo_productos_usuario">
{computadoras}
{memorias}
{monitores}
{discos}
</div>

<script type="text/javascript">
$("#contenedorPpal").on('click' , '#agregar_memoria' , function(){

			console.log("Entro a agregar memoria");
			console.log("id_memoria: "+$(this).attr("id_memoria"));
			var id_memoria = $(this).attr("id_memoria");

			$.post("controlador/ProductosController.php",
			{
			    action: "agregar_productos_a_computadora"
            }, function(data){
					jQuery('<div/>',
                    {
                        id: 'agregar_productos_a_computadora',
                        text: ''
                    }).appendTo('#contenedorPpal');
                 	$("#agregar_productos_a_computadora").html(data);
                 	$("#agregar_productos_a_computadora").dialog(
                    {
                        title: "Agregar Memorias o Discos",
                        show:
                        {
                                effect: "explode",
                                duration: 200,
                                modal: true
                        },
                        hide:
                        {
                                effect: "explode",
                                duration: 200
                        },
                        width: 510,
                        height: 680,
                        close: function()
                        {
                                $(this).dialog("destroy");
                                $("#agregar_productos_a_computadora").remove();
                                $("#tabs2").load("controlador/ProductosController.php",
                                {
                                        action: "agregar_computadora"
                                });
                        },
                        buttons:
                        {
                                "Terminar": function()
                                {
                                        $(this).dialog("destroy");
                                        $("#agregar_productos_a_computadora").remove();
                                        $("#tabs2").load("controlador/ProductosController.php",
                                        {
                                                action: "agregar_computadora"
                                        });
                                }
                        }
                    });
				}
			);
		});

$("#contenedorPpal").on('click' , '#desasignar_todo_memoria' , function(){

			console.log("Entro a desasignar todo de la memoria");
			console.log("id_memoria: "+$(this).attr("id_memoria"));
			var id_memoria = $(this).attr("id_memoria");

			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Memorias",
					ID : id_memoria,
					queSos : "memoria", //a quien le voy a generar la vista
					action : "liberar"
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
												height : 220,
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
							                        		$("#liberar_memoria").submit();
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

/*$("#contenedorPpal").on('click' , '#agregar_monitor' , function(){

			console.log("Entro a agregar monitor");
			console.log("id_monitor: "+$(this).attr("id_monitor"));
			var id_monitor = $(this).attr("id_monitor");

			$.post("controlador/ProductosController.php",
			{
			    action: "agregar_productos_a_computadora"
            }, function(data){
					jQuery('<div/>',
                    {
                        id: 'agregar_productos_a_computadora',
                        text: ''
                    }).appendTo('#contenedorPpal');
                 	$("#agregar_productos_a_computadora").html(data);
                 	$("#agregar_productos_a_computadora").dialog(
                    {
                        title: "Agregar Memorias o Discos",
                        show:
                        {
                                effect: "explode",
                                duration: 200,
                                modal: true
                        },
                        hide:
                        {
                                effect: "explode",
                                duration: 200
                        },
                        width: 510,
                        height: 680,
                        close: function()
                        {
                                $(this).dialog("destroy");
                                $("#agregar_productos_a_computadora").remove();
                                $("#tabs2").load("controlador/ProductosController.php",
                                {
                                        action: "agregar_computadora"
                                });
                        },
                        buttons:
                        {
                                "Terminar": function()
                                {
                                        $(this).dialog("destroy");
                                        $("#agregar_productos_a_computadora").remove();
                                        $("#tabs2").load("controlador/ProductosController.php",
                                        {
                                                action: "agregar_computadora"
                                        });
                                }
                        }
                    });
				}
			);
		});*/

$("#contenedorPpal").on('click' , '#desasignar_todo_monitor' , function(){

			console.log("Entro a desasignar todo del monitor");
			console.log("id_monitor: "+$(this).attr("id_monitor"));
			var id_monitor = $(this).attr("id_monitor");

			$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Monitores",
					ID : id_monitor,
					queSos : "monitor", //a quien le voy a generar la vista
					action : "liberar",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_monitor',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_monitor").html(data);
					$("#dialogcontent_monitor").dialog({
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
												height : 220,
												close : function(){
													$(this).dialog("destroy");
													$("#dialogcontent_monitor").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy");
							                            $("#dialogcontent_monitor").remove();
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

		console.log("Entro a eliminar monitor");
		console.log("id_monitor: "+$(this).attr("id_monitor"));
		var id_monitor = $(this).attr("id_monitor");

		$.post( "vista/dialog_content.php",
				{
					TablaPpal : "Monitores",
					ID : id_monitor,
					queSos : "monitor", //a quien le voy a generar la vista
					action : "eliminar",
					viene : "normal"
				}, function(data){
					jQuery('<div/>', {
				    id: 'dialogcontent_monitor',
				    text: 'Texto por defecto!'
				}).appendTo('#contenedorPpal');
					$("#dialogcontent_monitor").html(data);
					$("#dialogcontent_monitor").dialog({
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
													$(this).dialog("destroy");
													$("#dialogcontent_monitor").remove();
												},
												buttons :
							                    {
							                        "Cancelar" : function () {
							                            $(this).dialog("destroy");
							                            $("#dialogcontent_monitor").remove();
							                        },
							                        "Aceptar" : function(){
							                        		$("#form_detalle_eliminar_monitor").submit();
													}
							                    }
					});
				}
			);


	});


/*$("#contenedorPpal").on('click' , '#agregar_disco' , function(){

			console.log("Entro a agregar disco");
			console.log("id_disco: "+$(this).attr("id_disco"));
			var id_monitor = $(this).attr("id_disco");

			$.post("controlador/ProductosController.php",
			{
			    action: "agregar_productos_a_computadora"
            }, function(data){
					jQuery('<div/>',
                    {
                        id: 'agregar_productos_a_computadora',
                        text: ''
                    }).appendTo('#contenedorPpal');
                 	$("#agregar_productos_a_computadora").html(data);
                 	$("#agregar_productos_a_computadora").dialog(
                    {
                        title: "Agregar Memorias o Discos",
                        show:
                        {
                                effect: "explode",
                                duration: 200,
                                modal: true
                        },
                        hide:
                        {
                                effect: "explode",
                                duration: 200
                        },
                        width: 510,
                        height: 680,
                        close: function()
                        {
                                $(this).dialog("destroy");
                                $("#agregar_productos_a_computadora").remove();
                                $("#tabs2").load("controlador/ProductosController.php",
                                {
                                        action: "agregar_computadora"
                                });
                        },
                        buttons:
                        {
                                "Terminar": function()
                                {
                                        $(this).dialog("destroy");
                                        $("#agregar_productos_a_computadora").remove();
                                        $("#tabs2").load("controlador/ProductosController.php",
                                        {
                                                action: "agregar_computadora"
                                        });
                                }
                        }
                    });
				}
			);
		});*/

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
												height : 220,
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