<div id="agregar_productos_a_compu">
<form id="form_agregar_memoria_a_compu" autocomplete="off">
<fieldset style="border-color:#F00;
    border-style: solid;">
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
        <input class="btn btn-primary" id="boton_crear_memoria" type="submit" name="crearMemoria" value="Crear">
    </div>
</fieldset>   
</form>
</div>

<script type="text/javascript">

    console.log("{select_tipos_memorias}");

    $('#form_agregar_memoria_a_compu #select_unidades_memorias option[value='+2+']').attr('selected', 'selected');

	$("#form_agregar_memoria_a_compu #select_marcas_memorias,#form_agregar_memoria_a_compu #select_tipos_memorias").on('change',function(){

		console.log("Evento de seleccion de tipos");

		$.post('controlador/ProductosController.php',
			{
				value_marca : $('#form_agregar_memoria_a_compu #select_marcas_memorias option:selected').val(),
                value_tipo : $('#form_agregar_memoria_a_compu #select_tipos_memorias option:selected').val(),
				tipo : "sel_velocidades",
				action : "agregar_memoria"

			}, function(data) {
			$("#form_agregar_memoria_a_compu #select_velocidades").replaceWith(data);
			});
	});

    $("#form_agregar_memoria_a_compu").validate({
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
            console.log($("#form_agregar_memoria_a_compu").serialize());
            var dataUrl = $("#form_agregar_memoria_a_compu").serialize() + "&tipo=Memoria&id_cpu="+$("#tab_memorias_y_discos").attr("id_cpu");

            
            console.log(dataUrl);

            $.ajax({
                            url: 'controlador/CreacionController.php',
                            type: 'POST',
                            data: dataUrl,
                            success: function(response){
                                console.log(response);
                                console.log("success");
                                alert('Se ha agregado el producto correctamente');
                                $("#tabs3").empty();
                                $( "#agregar_memorias_computadora" ).trigger( "click" );
                                
                            }
            })
            .fail(function() {
                console.log("error");
                alert('Hubo un error');
                $("#tabs3").empty();
                $( "#agregar_memorias_computadora" ).trigger( "click" );

            })
            .always(function() {
                console.log("complete");
            });
        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });
</script>