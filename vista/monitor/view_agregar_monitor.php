
<h3>{titulo}</h3><p>Seleccione la marca y modelo del monitor</p>

<form id="form_agregar_monitor">
<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_agregar_monit"></table>
	<tr>
            <th>Marca: </th>
    </tr>
    <tr>
            <td>{select_marcas_monitores}</td>
    </tr>
    <tr>
    	<td>Modelo:</td>
    	<td><select id='select_modelos_Monitor' name='modelo'>
    				<option value=''>Seleccionar</option></select></td>
    	</td>
    	<td>Nro de Serie:</td>
    	<td><input id="nro_de_serie_m" type="text" name="num_serie_mon"</td>
    </tr>
    <tr><td><div class="error_ag_monit text-error"> </div></td></tr>
     </div>
    <div id="agregar"><input class="boton_agregar_monitor" type="submit" name="crearMonitor" value="Crear"</div>
</table>
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
        errorLabelContainer : ".error_ag_monit" ,
        wrapper : "li" ,
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
</script>