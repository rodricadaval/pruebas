<form id="form_agregar_router" autocomplete="off">
<fieldset>
<legend>Complete los Datos <small>(hay validaciones al crear)</small></legend>
        <div class="control-group">
            <label class="control-label" for="select_marcas_routers">Marca</label>
            <div class="controls">
                {select_marcas_routers}
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="select_modelos_Router">Modelo</label>
            <div class="controls">
                <select id='select_modelos_Router' name='modelo'>
                    <option value=''>Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="nro_de_serie_r">Nro de Serie</label>
            <div class="controls">
                <input id="nro_de_serie_r" type="text" name="num_serie_rout">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="ip">IP <small>(opcional)</small></label>
            <div class="controls">
                <input id="ip" type="text" name="ip">
            </div>
        </div>
        <div class="form-actions">
            <input type="button" class="btn btn-success" id="boton_nueva_marca" value="Nueva Marca y Modelo">
            <input class="btn btn-primary" id="boton_crear_router" type="submit" name="crearRouter" value="Crear">
        </div>
</fieldset>
</form>

<script type="text/javascript">

	$("#select_marcas_routers").on('change',function(){

		console.log("Evento de seleccion de modelos");

		$.post('controlador/ProductosController.php',
			{
				value : $('#select_marcas_routers option:selected').val(),
				tipo : "sel_modelos",
				action : "agregar_router",

			}, function(data) {
	       		$("#select_modelos_Router").replaceWith(data);
			});
	});


	$("#form_agregar_router").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        onsubmit: true,
        rules : {
            marca : {
                required : true
            },
            modelo: {
            	required : true
            },
            ip: {
                required : false,
                remote      : {
                    url     : 'busquedas/busca_ip_router.php' ,
                    type     : 'post' ,
                    data     : {
                        ip : function() {
                            return $("#ip").val();
                        }
                    }
                },
                IP4Checker : true
            },
            num_serie_rout : {
            	required : true,
            	remote      : {
                    url     : 'busquedas/busca_nro_serie_router.php' ,
                    type     : 'post' ,
                    data     : {
                        serie : function() {
                            return $("#nro_de_serie_r").val();
                        }
                    }
                }
            }
        } ,
        messages : {
            marca : {
                required : 'Debe seleccionar una marca'
            },
            modelo : {
                required : 'Debe seleccionar un modelo'
            },
            num_serie_rout :{
            	required: 'El numero de serie no puede ser nulo',
            	remote: 'Ya existe un router con ese numero de serie'
            },
            ip : {
                remote: 'Ya existe un router con esa ip'
            },
        } ,
        highlight: function(element) {
             $(element).closest('.control-group').removeClass('success').addClass('error');
         },
        success: function(element) {
            element.text('OK!').addClass('valid')
            .closest('.control-group').removeClass('error').addClass('success');
        },
        submitHandler : function (form) {
            console.log ("Formulario OK");
            console.log('Evento de click en crear');
            console.log($("#form_agregar_router").serialize());
            var todo = "";

             if($("#select_modelos_Router").val().indexOf("-") >= 0 && $("#select_modelos_Router").text() != $("#select_modelos_Router").val()){
                var data = $("#select_modelos_Router").val().split('-');
                primparte = data[0];
                sdaparte = data[1];
                todo = primparte +' '+ sdaparte;
                console.log(primparte);
                console.log(sdaparte);
                console.log(todo);
            }
            else{
                todo = $("#select_modelos_Router").val();
            }

            var dataUrl = "marca="+$('#select_marcas_routers option:selected').val()+"&modelo="+todo+"&num_serie="+$("#nro_de_serie_r").val()+"&ip="+$("#ip").val()+"&tipo=Router";

            $.ajax({
                            url: 'controlador/CreacionController.php',
                            type: 'POST',
                            data: dataUrl,
                            success: function(response){
                                console.log(response);
                                console.log("success");
                                alert('Se ha agregado el producto correctamente');
                                $("#tabs6").load("controlador/ProductosController.php",{action:"agregar_router"});
                            }
            })
            .fail(function() {
                console.log("error");
                alert('Hubo un error');
                $("#tabs6").load("controlador/ProductosController.php",{action:"agregar_router"});

            })
            .always(function() {
                console.log("complete");
            });


        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });

    $("#form_agregar_router").on('click',"#boton_nueva_marca",function(){
     
        $.post( "controlador/CreacionController.php",
                {
                    tablaPpal : "Router",
                    action : "nueva_marca"
                }, function(data){
                    jQuery('<div/>', {
                        id: 'dialogcontent_nueva_marca',
                        text: 'Texto por defecto!'
                    }).appendTo('#contenedorPpal');
                    $("#dialogcontent_nueva_marca").html(data);
                    $("#dialogcontent_nueva_marca").dialog({
                                                show: {
                                                effect: "explode",
                                                duration: 200,
                                                modal:true
                                                },
                                                hide: {
                                                effect: "explode",
                                                duration: 200
                                                },
                                                width : 440,
                                                height : 400,
                                                close : function(){
                                                    $(this).dialog("destroy");
                                                    $("#dialogcontent_nueva_marca").remove();
                                                },
                                                buttons :
                                                {
                                                    "Cancelar" : function () {
                                                        $(this).dialog("destroy");
                                                        $("#dialogcontent_nueva_marca").remove();
                                                    },
                                                    "Aceptar" : function(){
                                                        $("#form_nueva_marca_y_modelo").submit();
                                                    }
                                                }
                    });
                }
        );
    });
</script>