<form id="form_agregar_memoria" autocomplete="off">
<fieldset>
<legend>Complete los Datos <small>(hay validaciones al crear)</small></legend>
    <div class="control-group">
        <label class="control-label" for="select_marcas_memorias">Marca</label>
        <div class="controls">
            {select_marcas_memorias}
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="select_tipos_memorias">Tipo</label>
        <div class="controls">
            {select_tipos_memorias}
        </div>
    </div>
     <div class="control-group">
        <label class="control-label" for="select_velocidades">Velocidad</label>
        <div class="controls">
            <select id='select_velocidades' name='velocidad'>
            <option value=''>Seleccionar</option>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="">Capacidad</label>
        <div class="controls">
            {select_capacidades}{select_unidades}
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="cantidad">Cantidad</label>
        <div class="controls">
            <input type="text" id="cantidad" name="cant_veces" placeholder="Ingrese cantidad">
        </div>
    </div>
     <div class="form-actions">
        <input type="button" class="btn btn-success" id="boton_nueva_marca" value="Nueva Marca">
        <input class="btn btn-primary" id="boton_crear_memoria" type="submit" name="crearMemoria" value="Crear">
        <br><br>
        <input type="button" class="btn btn-danger" id="boton_borrar_marca_sola" value="Borrar una Marca">
    </div>
</fieldset>   
</form>

<script type="text/javascript">

    console.log("{select_tipos_memorias}");

    $('#form_agregar_memoria #select_unidades_memorias option[value='+2+']').attr('selected', 'selected');

	$("#form_agregar_memoria #select_marcas_memorias,#form_agregar_memoria #select_tipos_memorias").on('change',function(){

		console.log("Evento de seleccion de tipos");

		$.post('controlador/ProductosController.php',
			{
				value_marca : $('#form_agregar_memoria #select_marcas_memorias option:selected').val(),
                value_tipo : $('#form_agregar_memoria #select_tipos_memorias option:selected').val(),
				tipo : "sel_velocidades",
				action : "agregar_memoria"

			}, function(data) {
			$("#form_agregar_memoria #select_velocidades").replaceWith(data);
			});
	});

    $("#form_agregar_memoria").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        onsubmit: true,
        rules : {
            marca : {
                required : true
            },
            tipo : {
                required : true
            },
            cant_veces : {
            	required : true
            },
            velocidad : {
                required : true
            }
        } ,
        messages : {
            marca : {
                required : 'Debe seleccionar una marca'
            },
            tipo : {
                required : 'Debe seleccionar un tipo'
            },
            cant_veces : {
            	required: 'Debe ingresar una cantidad'
            },
            velocidad : {
                required: 'Debe seleccionar una velocidad' 
            }
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
            console.log($("#form_agregar_memoria").serialize());
            var dataUrl = $("#form_agregar_memoria").serialize() + "&tipo=Memoria";

            
            console.log(dataUrl);

            $.ajax({
                            url: 'controlador/CreacionController.php',
                            type: 'POST',
                            data: dataUrl,
                            success: function(response){
                                console.log(response);
                                console.log("success");
                                alert('Se ha agregado el producto correctamente');
                                $("#tabs3").load("controlador/ProductosController.php",{action:"agregar_memoria"});
                            }
            })
            .fail(function() {
                console.log("error");
                alert('Hubo un error');
                $("#tabs3").load("controlador/ProductosController.php",{action:"agregar_memoria"});

            })
            .always(function() {
                console.log("complete");
            });
        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });

    $("#form_agregar_memoria").on('click',"#boton_nueva_marca",function(){
     
        $.post( "controlador/CreacionController.php",
                {
                    tablaPpal : "Memoria",
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
                                                width : 430,
                                                height : 450,
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
                                                        $("#form_nueva_marca_y_velocidad_memoria").submit();  
                                                    }
                                                }
                    });
                }
        );
    });

    $("#form_agregar_memoria").on('click',"#boton_borrar_marca_sola",function(){
     
        $.post( "controlador/CreacionController.php",
                {
                    tablaPpal : "Memorias",
                    action : "borrar_marca"
                }, function(data){
                    jQuery('<div/>', {
                        id: 'dialogcontent_borrar_marca',
                        text: ''
                    }).appendTo('#contenedorPpal');
                    $("#dialogcontent_borrar_marca").html(data);
                    $("#dialogcontent_borrar_marca").dialog({
                                                title : 'Marca a borrar',
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
                                                height : 360,
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
                                                        $("#form_borrar_marca").submit();  
                                                    }
                                                }
                    });
                }
        );
    });
</script>