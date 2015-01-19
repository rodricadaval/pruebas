<form id="form_agregar_switch" autocomplete="off">
<fieldset>
<legend>Complete los Datos <small>(hay validaciones al crear)</small></legend>
    <div class="control-group">
        <label class="control-label" for="select_marcas_switchs">Marca</label>
        <div class="controls">
            {select_marcas_switchs}
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="select_modelos_Switch">Modelo</label>
        <div class="controls">
            <select id='select_modelos_Switch' name='modelo'>
                <option value=''>Seleccionar</option>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="nro_de_serie_s">Nro de Serie</label>
        <div class="controls">
            <input id="nro_de_serie_s" type="text" name="num_serie_swi">
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
        <input class="btn btn-primary" id="boton_crear_switch" type="submit" name="crearSwitch" value="Crear">
        <input type="button" class="btn btn-danger" id="boton_borrar_marca" value="Borrar una Marca y Modelo">
    </div>
</fieldset>
</form>

<script type="text/javascript">

	$("#select_marcas_switchs").on('change',function(){

		console.log("Evento de seleccion de modelos");

		$.post('controlador/ProductosController.php',
			{
				value : $('#select_marcas_switchs option:selected').val(),
				tipo : "sel_modelos",
				action : "agregar_switch",

			}, function(data) {
	       		$("#select_modelos_Switch").replaceWith(data);
			});
	});


	$("#form_agregar_switch").validate({
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
                remote      : {
                    url     : 'busquedas/busca_ip_switch.php' ,
                    type     : 'post' ,
                    data     : {
                        ip : function() {
                            return $("#ip").val();
                        }
                    }
                },
                IP4Checker : true
            },
            num_serie_swi : {
            	required : true,
            	remote      : {
                    url     : 'busquedas/busca_nro_serie_switch.php' ,
                    type     : 'post' ,
                    data     : {
                        serie : function() {
                            return $("#nro_de_serie_s").val();
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
            num_serie_swi :{
            	required: 'El numero de serie no puede ser nulo',
            	remote: 'Ya existe un switch con ese numero de serie'
            },
            ip : {
                remote: 'Ya existe un switch con esa ip'
            },
        } ,
        highlight: function(element) {
             $(element).closest('.control-group').removeClass('success').addClass('error');
         },
        success: function(element) {
            element.text('').addClass('valid')
            .closest('.control-group').removeClass('error').addClass('success');
        },
        submitHandler : function (form) {
            console.log ("Formulario OK");
            console.log('Evento de click en crear');
            console.log($("#form_agregar_switch").serialize());
            var todo = "";

            todo = $("#select_modelos_Switch").val().replace(/\./g, ' ');

            var dataUrl = "marca="+$('#select_marcas_switchs option:selected').val()+"&modelo="+todo+"&num_serie="+$("#nro_de_serie_s").val()+"&ip="+$("#ip").val()+"&tipo=Switch";

            $.ajax({
                            url: 'controlador/CreacionController.php',
                            type: 'POST',
                            data: dataUrl,
                            success: function(response){
                                console.log(response);
                                console.log("success");
                                alert('Se ha agregado el producto correctamente');
                                $("#tabs7").load("controlador/ProductosController.php",{action:"agregar_switch"});
                            }
            })
            .fail(function() {
                console.log("error");
                alert('Hubo un error');
                $("#tabs7").load("controlador/ProductosController.php",{action:"agregar_switch"});

            })
            .always(function() {
                console.log("complete");
            });


        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });

    $("#form_agregar_switch").on('click',"#boton_nueva_marca",function(){
     
        $.post( "controlador/CreacionController.php",
                {
                    tablaPpal : "Switch",
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
                                                height : 410,
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

    $("#form_agregar_switch").on('click',"#boton_borrar_marca",function(){
     
        $.post( "controlador/CreacionController.php",
                {
                    tablaPpal : "Switchs",
                    action : "borrar_marca"
                }, function(data){
                    jQuery('<div/>', {
                        id: 'dialogcontent_borrar_marca',
                        text: 'Texto por defecto!'
                    }).appendTo('#contenedorPpal');
                    $("#dialogcontent_borrar_marca").html(data);
                    $("#dialogcontent_borrar_marca").dialog({
                                                title : 'Marca y Modelo a borrar',
                                                show: {
                                                effect: "explode",
                                                duration: 200,
                                                modal:true
                                                },
                                                hide: {
                                                effect: "explode",
                                                duration: 200
                                                },
                                                width : 460,
                                                height : 420,
                                                close : function(){
                                                    $(this).dialog("destroy");
                                                    $("#dialogcontent_borrar_marca").remove();
                                                },
                                                buttons :
                                                {
                                                    "Cancelar" : function () {
                                                        $(this).dialog("destroy");
                                                        $("#dialogcontent_borrar_marca").remove();
                                                    },
                                                    "Aceptar" : function(){
                                                        $("#form_borrar_marca_y_modelo").submit();  
                                                    }
                                                }
                    });
                }
        );
    });
</script>