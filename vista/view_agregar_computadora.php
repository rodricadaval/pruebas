
<h3>{titulo}</h3><p>Seleccione la marca y modelo de la computadora</p>

<form id="form_agregar_computadora">
<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_agregar"></table>
	<tr>
            <th>Marca: </th>
    </tr>
    <tr>
            <td>{select_marcas_Computadora}</td>
    </tr>
    <tr>
    	<td>Modelo:</td>
    	<td><select id='select_modelos_Computadora' name='modelo'>
    				<option value=''>Seleccionar</option></select></td>
    	</td>
    	<td>Nro de Serie:</td>
    	<td><input id="nro_de_serie_c" type="text" name="num_serie_c"</td>
    </tr>
    <tr><td><div class="error_ag_comp text-error"> </div></td></tr>
     </div>
    <div id="agregar"><input class="boton_agregar_monitor" type="submit" name="crearMonitor" value="Crear"</div>
</table>
</form>



<script type="text/javascript">

	var validado = false;

	$("#select_marcas_Computadora").on('change',function(){

		console.log("Evento de seleccion de modelos");

		$.post('controlador/ProductosController.php',
			{
				value : $('#select_marcas_Computadora option:selected').val(),
				tipo : "sel_modelos",
				action : "view_agregar_computadora",

			}, function(data) {
			$("#select_modelos_Computadora").replaceWith(data);
			});
	});


	$("#form_agregar_computadora").validate({
        errorLabelContainer : ".error_ag_comp" ,
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
        submitHandler : function (form) {
          validado = true;
          console.log (validado);
          console.log ("Formulario OK");
        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
          validado = false;
        }
    });


	$("#form_agregar_computadora").on('submit',function(){

		if(validado){
			console.log('Evento de click en crear');
			console.log($("#form_agregar_computadora").serialize());
            var data = $("#select_modelos_Computadora").val().split(' ');
            var primparte = data[0];
            var sdaparte = data[1];

            var dataUrl = "marca="+$('#select_marcas_Computadora option:selected').val()+"&modelo="+primparte+' '+sdaparte+"&num_serie="+$("#nro_de_serie_c").val()+"&tipo=Computadora";
            console.log(dataUrl);

			$.ajax({
							url: 'controlador/CreacionController.php',
							type: 'POST',
							data: dataUrl,
							success: function(response){
								console.log(response);
								console.log("success");
								alert('Se ha agregado el producto correctamente');
								$("#tabs1").load("controlador/ProductosController.php",{action:"view_agregar_computadora"});
							}
			})
			.fail(function() {
				console.log("error");
				alert('Hubo un error');
				$("#tabs1").load("controlador/ProductosController.php",{action:"view_agregar_computadora"});

			})
			.always(function() {
				console.log("complete");
			});

		}
	});
</script>