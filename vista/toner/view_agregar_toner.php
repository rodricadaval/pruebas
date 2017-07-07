<form id="form_agregar_toner" autocomplete="off">
    <fieldset>
        <legend>Complete los Datos <small>(hay validaciones al crear)</small></legend>
        <div class="control-group">
            <label class="control-label" for="select_impresoras">Impresora</label>
            <div class="controls">
                {select_impresoras}
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="select_areas">Areas</label>
            <div class="controls">
                {select_areas}
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="cantidad">Cantidad</label>
            <div class="controls">
                <input id="cantidad" type="number" name="cantidad" placeholder="Ingrese cantidad">
            </div>
        </div>
        <div class="form-actions">
            <input class="btn btn-primary" id="boton_crear_toner" type="submit" name="crearToner" value="Crear">
        </div>
    </fieldset>
</form>

<script type="text/javascript">

	$("#form_agregar_toner").validate({
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
            cantidad : {
                required : true
            }
        },
        messages : {
            marca : {
                required : 'Debe seleccionar una marca'
            },
            modelo : {
                required : 'Debe seleccionar un modelo'
            },
            cantidad : {
                required : 'Debe indicar una cantidad'
            }
        },
        highlight: function(element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function(element) {
            element.text('').addClass('valid').closest('.control-group').removeClass('error').addClass('success');
        },
        submitHandler : function (form) {
            console.log ("Formulario OK");
            console.log('Evento de click en crear');
            console.log($("#form_agregar_toner").serialize());

            var id_impresora_desc = $('#select_impresoras option:selected').val();
            var id_area = $('#select_areas option:selected').val();

            var dataUrl = $("#form_agregar_toner").serialize();
            dataUrl += "&id_impresora_desc="+id_impresora_desc+"&id_area="+id_area;
            dataUrl += "&action=dar_alta";

            $.ajax({
                url: 'controlador/TonersController.php',
                type: 'POST',
                data: dataUrl,
                success: function(response){
                    console.log(response);
                    console.log("success");
                    alert('Se ha agregado el producto correctamente');
                    $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                            id: 'contenedorPpal',
                            text: 'Texto por defecto!'
                        }).appendTo('.realBody');

                        $("#contenedorPpal").load("controlador/TonersController.php");
                }
            })
            .fail(function() {
                console.log("error");
                alert('Hubo un error');
                $("#tabs1").load("controlador/ProductosController.php",{action:"agregar_toner"});

            })
            .always(function() {
                console.log("complete");
            });
        },
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });

</script>