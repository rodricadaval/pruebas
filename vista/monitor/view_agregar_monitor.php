<form id="form_agregar_monitor" autocomplete="off">
<fieldset>
<legend>Complete los Datos <small>(hay validaciones al crear)</small></legend>
    <div class="control-group">
        <label class="control-label" for="select_marcas_monitores">Marca</label>
        <div class="controls">
            {select_marcas_monitores}
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="select_modelos_Monitor">Modelo</label>
        <div class="controls">
            <select id='select_modelos_Monitor' name='modelo'>
                    <option value=''>Seleccionar</option>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="nro_de_serie_m">Nro de Serie</label>
        <div class="controls">
            <input id="nro_de_serie_m" type="text" name="num_serie_mon">
        </div>
    </div>
    <div class="form-actions">
            <input type="button" class="btn btn-success" id="boton_nueva_marca" name="nueva_marca_monitor" value="Nueva Marca y Modelo">
            <input class="btn btn-primary" id="boton_crear_monitor" type="submit" name="crearMonitor" value="Crear">
        </div>
</fieldset>
</form>

<script type="text/javascript">

	$("#select_marcas_monitores").on('change',function(){

		console.log("Evento de seleccion de modelos");

		$.post('controlador/ProductosController.php',
			{
				value : $('#select_marcas_monitores option:selected').val(),
				tipo : "sel_modelos",
				action : "agregar_monitor",

			}, function(data) {
	       		$("#select_modelos_Monitor").replaceWith(data);
			});
	});


	$("#form_agregar_monitor").validate({
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
            num_serie_mon : {
            	required : true,
            	remote      : {
                    url     : 'busquedas/busca_nro_serie_monitor.php' ,
                    type     : 'post' ,
                    data     : {
                        serie : function() {
                            return $("#nro_de_serie_m").val();
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
            num_serie_mon :{
            	required: 'El numero de serie no puede ser nulo',
            	remote: 'Ya existe un monitor con ese numero de serie'
            }
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
            console.log($("#form_agregar_monitor").serialize());

            var dataUrl = "marca="+$('#select_marcas_monitores option:selected').val()+"&modelo="+$("#select_modelos_Monitor").val()+"&num_serie="+$("#nro_de_serie_m").val()+"&tipo=Monitor";

            $.ajax({
                            url: 'controlador/CreacionController.php',
                            type: 'POST',
                            data: dataUrl,
                            success: function(response){
                                console.log(response);
                                console.log("success");
                                alert('Se ha agregado el producto correctamente');
                                $("#tabs1").load("controlador/ProductosController.php",{action:"agregar_monitor"});
                            }
            })
            .fail(function() {
                console.log("error");
                alert('Hubo un error');
                $("#tabs1").load("controlador/ProductosController.php",{action:"agregar_monitor"});

            })
            .always(function() {
                console.log("complete");
            });


        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });

    $("#form_agregar_monitor").on('click',"#boton_nueva_marca",function(){
     
        $.post( "controlador/CreacionController.php",
                {
                    tablaPpal : "Monitor",
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
                                                height : 420,
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