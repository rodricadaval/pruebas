<div id="agregar_productos_a_compu">
<form id="form_agregar_disco_a_compu" autocomplete="off">
<fieldset>
<legend>Complete los Datos <small>(hay validaciones al crear)</small></legend>
        <div class="control-group">
            <label class="control-label" for="select_marcas_discos">Marca</label>
            <div class="controls">
                {select_marcas_discos}
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="capacidad">Capacidad</label>
            <div class="controls">
                {select_capacidades}{select_unidades}
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="cant_veces">Cantidad</label>
            <div class="controls">
                <input type="text" id="cantidad" class="input-xlarge" name="cant_veces" placeholder="Ingrese cantidad">
            </div>
        </div>
        <div class="form-actions">
            <input class="btn btn-primary" id="boton_crear_disco" type="submit" name="crearDisco" value="Crear">
            <br><br>
        </div>
    </fieldset>
</form>
</div>


<script type="text/javascript">

    console.log("{select_tipos_discos}");

    $('#form_agregar_disco_a_compu #select_unidades_discos option[value='+3+']').attr('selected', 'selected');

    $("#form_agregar_disco_a_compu").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        onsubmit: true,
        rules : {
            marca : {
                required : true
            },
            cant_veces : {
            	required : true
            }
        },
        messages : {
            marca : {
                required : 'Debe seleccionar una marca'
            },
            cant_veces : {
            	required: 'Debe ingresar una cantidad'
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
            console.log($("#form_agregar_disco_a_compu").serialize());
            var dataUrl = $("#form_agregar_disco_a_compu").serialize() + "&tipo=Disco&id_cpu="+$("#tab_memorias_y_discos").attr("id_cpu");

            
            console.log(dataUrl);

            $.ajax({
                            url: 'controlador/CreacionController.php',
                            type: 'POST',
                            data: dataUrl,
                            success: function(response){
                                console.log(response);
                                console.log("success");
                                alert('Se ha agregado el producto correctamente');
                                $("#tabs4").empty();
                                $( "#agregar_discos_computadora" ).trigger( "click" );
                            }
            })
            .fail(function() {
                console.log("error");
                alert('Hubo un error');
                $("#tabs4").empty();
                $( "#agregar_discos_computadora" ).trigger( "click" );

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