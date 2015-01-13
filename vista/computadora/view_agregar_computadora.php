<form id="form_agregar_computadora" autocomplete="off">
<fieldset>
<legend>Complete los Datos <small>(hay validaciones al crear)</small></legend>
    <div class="control-group">
        <label class="control-label" for="select_marcas_computadoras">Marca</label>
        <div class="controls">
            {select_marcas_computadoras}
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="select_modelos_Computadora">Modelo</label>
        <div class="controls">
            <select id='select_modelos_Computadora' name='modelo'>
                        <option value=''>Seleccionar</option>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="nro_de_serie_c">Nro de Serie</label>
        <div class="controls">
            <input id="nro_de_serie_c" type="text" name="num_serie_c">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="select_clase">Tipo</label>
        <div class="controls">
            {select_clases_Computadora}
        </div>
    </div>
    <div class="form-actions">
            <input type="button" class="btn btn-success" id="boton_nueva_marca" value="Nueva Marca y Modelo">
            <input class="btn btn-primary" id="boton_crear_computadora" type="submit" name="crearComputadora" value="Crear">
    </div>
</fieldset>
</form>

<script type="text/javascript">

	$("#select_marcas_computadoras").on('change',function(){

		console.log("Evento de seleccion de modelos");

		$.post('controlador/ProductosController.php',
			{
				value : $('#select_marcas_computadoras option:selected').val(),
				tipo : "sel_modelos",
				action : "agregar_computadora",

			}, function(data) {
			$("#select_modelos_Computadora").replaceWith(data);
			});
	});

	$("#form_agregar_computadora").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        onsubmit: true,
        rules : {
            marca : {
                required : true
            },
            modelo : {
                required : true
            },
            num_serie_c : {
            	required : true,
            	remote      : {
                    url     : 'busquedas/busca_nro_serie_computadora.php' ,
                    type     : 'post' ,
                    data     : {
                        serie : function() {
                            return $("#nro_de_serie_c").val();
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
            num_serie_c : {
            	required: 'El numero de serie no puede ser nulo',
            	remote: 'Ya existe una CPU con ese numero de serie'
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

            var primparte = "";
            var sdaparte = "";
            var dataUrl
            console.log('Evento de click en crear');
            console.log($("#form_agregar_computadora").serialize());

            dataUrl = "marca="+$('#select_marcas_computadoras option:selected').val()+"&num_serie="+$("#nro_de_serie_c").val()+"&clase="+$("#select_clase").val()+"&tipo=Computadora";

            if($("#select_modelos_Computadora").val().indexOf("-") >= 0){
                var data = $("#select_modelos_Computadora").val().split('-');
                primparte = data[0];
                sdaparte = data[1];
                console.log(primparte);
                console.log(sdaparte);
                dataUrl += "&modelo="+primparte+' '+sdaparte;
            }
            else{
                primparte = $("#select_modelos_Computadora").val();
                sdaparte = "";
                dataUrl += "&modelo="+primparte;
            }

            console.log(dataUrl);

            $.ajax({
                            url: 'controlador/CreacionController.php',
                            type: 'POST',
                            data: dataUrl,
                            success: function(response){
                                console.log(response);
                                console.log("success");
                                alert('Se ha agregado el producto correctamente');
                                $("#tabs2").load("controlador/ProductosController.php",{action:"agregar_computadora"});
                            }
            })
            .fail(function() {
                console.log("error");
                alert('Hubo un error');
                $("#tabs2").load("controlador/ProductosController.php",{action:"agregar_computadora"});
            })
            .always(function() {
                console.log("complete");
            });
        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });

    $("#form_agregar_computadora").on('click',"#boton_nueva_marca",function(){
     
        $.post( "controlador/CreacionController.php",
                {
                    tablaPpal : "Computadora",
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
                                                height : 520,
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
                                                        $("#form_nueva_marca_y_modelo_computadora").submit();  
                                                    }
                                                }
                    });
                }
        );
    });
</script>