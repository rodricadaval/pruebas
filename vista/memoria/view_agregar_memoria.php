<form id="form_agregar_memoria">
<fieldset>
<legend>Complete los Datos</legend>
<ul>
    <li><text>Marca:</text>{select_marcas_memorias}</li>
    <li><text>Tipo:</text>{select_tipos_memorias}</li> 
    <li colspan="1"><text>Velocidad:</text>
        <select id='select_velocidades' name='velocidad'>
            <option value=''>Seleccionar</option>
        </select>
    </li>
    <li><text>Capacidad:</text>{select_capacidades}{select_unidades}</li>
    <li><text>Cantidad:</text><input name="cant_veces" placeholder="Ingrese cantidad"></li>
    <li><input type="button" class="btn btn-success" id="boton_nueva_marca" value="Nueva Marca"><input class="btn btn-primary" id="boton_crear_memoria" type="submit" name="crearMemoria" value="Crear"></li>
</ul> 
</fieldset>   
    <br>
    <div><p class="error_ag_mem text-error"></p></div>
</form>

<script type="text/javascript">

    console.log("{select_tipos_memorias}");

    $('#select_unidades_memorias option[value='+2+']').attr('selected', 'selected');

	$("#select_marcas_memorias,#select_tipos_memorias").on('change',function(){

		console.log("Evento de seleccion de tipos");

		$.post('controlador/ProductosController.php',
			{
				value_marca : $('#select_marcas_memorias option:selected').val(),
                value_tipo : $('#select_tipos_memorias option:selected').val(),
				tipo : "sel_velocidades",
				action : "agregar_memoria",

			}, function(data) {
			$("#select_velocidades").replaceWith(data);
			});
	});

    $("#form_agregar_memoria").validate({
        errorLabelContainer : ".error_ag_mem" ,
        wrapper : "li" ,
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
</script>