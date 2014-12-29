<form id="form_agregar_impresora">
<fieldset>
<legend>Complete los Datos</legend>
    <ul>
        <li><text>Marca:</text>{select_marcas_impresoras}</li>
        <li><text>Modelo:</text>
            <select id='select_modelos_Impresora' name='modelo'>
                <option value=''>Seleccionar</option>
            </select>
        </li>
        <li><text>Nro de Serie:</text><input id="nro_de_serie_i" type="text" name="num_serie_imp"></li>
        <li><text>IP:</text><input id="ip" type="text" name="ip"></li>
        <li><input type="button" class="btn btn-success" id="boton_nueva_marca" value="Nueva Marca y Modelo"><input class="btn btn-primary" id="boton_crear_impresora" type="submit" name="crearImpresora" value="Crear"></li>
    </ul>
</fieldset>
    <br>
    <div><p class="error_ag_i text-error"></p></div>
</form>

<script type="text/javascript">

	$("#select_marcas_impresoras").on('change',function(){

		console.log("Evento de seleccion de modelos");

		$.post('controlador/ProductosController.php',
			{
				value : $('#select_marcas_impresoras option:selected').val(),
				tipo : "sel_modelos",
				action : "agregar_impresora",

			}, function(data) {
	       		$("#select_modelos_Impresora").replaceWith(data);
			});
	});


	$("#form_agregar_impresora").validate({
        errorLabelContainer : ".error_ag_i",
        wrapper : "li",
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
            ip: {
                required : true,
                remote      : {
                    url     : 'busquedas/busca_ip_impresora.php' ,
                    type     : 'post' ,
                    data     : {
                        ip : function() {
                            return $("#ip").val();
                        }
                    }
                }
            },
            num_serie_imp : {
            	required : true,
            	remote      : {
                    url     : 'busquedas/busca_nro_serie_impresora.php' ,
                    type     : 'post' ,
                    data     : {
                        serie : function() {
                            return $("#nro_de_serie_i").val();
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
            num_serie_imp :{
            	required: 'El numero de serie no puede ser nulo',
            	remote: 'Ya existe un impresora con ese numero de serie'
            },
            ip : {
                required : 'El ip no puede ser nulo',
                remote: 'Ya existe un impresora con esa ip'
            },
        } ,
        submitHandler : function (form) {
            console.log ("Formulario OK");
            console.log('Evento de click en crear');
            console.log($("#form_agregar_impresora").serialize());

             if($("#select_modelos_Impresora").val().indexOf("-") >= 0){
                var data = $("#select_modelos_Impresora").val().split('-');
                primparte = data[0];
                sdaparte = data[1];
                console.log(primparte);
                console.log(sdaparte);
            }
            else{
                primparte = $("#select_modelos_Impresora").val();
                sdaparte = "";
            }

            var dataUrl = "marca="+$('#select_marcas_impresoras option:selected').val()+"&modelo="+primparte+' '+sdaparte+"&num_serie="+$("#nro_de_serie_i").val()+"&ip="+$("#ip").val()+"&tipo=Impresora";

            $.ajax({
                            url: 'controlador/CreacionController.php',
                            type: 'POST',
                            data: dataUrl,
                            success: function(response){
                                console.log(response);
                                console.log("success");
                                alert('Se ha agregado el producto correctamente');
                                $("#tabs5").load("controlador/ProductosController.php",{action:"agregar_impresora"});
                            }
            })
            .fail(function() {
                console.log("error");
                alert('Hubo un error');
                $("#tabs5").load("controlador/ProductosController.php",{action:"agregar_impresora"});

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