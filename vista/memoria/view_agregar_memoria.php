
<h3>{titulo}</h3><p>Seleccione marca, tipo y velocidad de Memoria RAM</p>

<form id="form_agregar_memoria">
<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_agregar_memo"></table>
	<tr>
            <td>Marca: </th>{select_marcas_memorias}</td>
        	<td><text>Tipo:</text>{select_tipos_memorias}</td>
        	</td> 
            <td colspan="1">Velocidad:
                <select id='select_velocidades' name='velocidad'>
                    <option value=''>Seleccionar</option>
                </select>
            </td>
    </tr>
    <br>
    <br>
    <tr>
            <td>Cantidad:<input name="cant_veces" placeholder="Ingrese cantidad"</input></td>
            <td>Capacidad:{select_capacidades}</td>
            <td>{select_unidades}</td>
            <td><input class="boton_agregar_memoria" type="submit" name="crearMemoria" value="Crear"</td>
    </tr>
    <br>
    <br>
    <tr><td><div class="error_ag_mem text-error"></div></td></tr>  
    
</table>
</form>



<script type="text/javascript">

    console.log("{select_tipos_memorias}");

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
                                $("#tabs2").load("controlador/ProductosController.php",{action:"agregar_memoria"});
                            }
            })
            .fail(function() {
                console.log("error");
                alert('Hubo un error');
                $("#tabs2").load("controlador/ProductosController.php",{action:"agregar_memoria"});

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