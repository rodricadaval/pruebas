<form id="form_agregar_switch">
<fieldset>
<legend>Complete los Datos</legend>
    <ul>
        <li><text>Marca:</text>{select_marcas_switchs}</li>
        <li><text>Modelo:</text>
            <select id='select_modelos_Switch' name='modelo'>
                <option value=''>Seleccionar</option>
            </select>
        </li>
        <li><text>Nro de Serie:</text><input id="nro_de_serie_s" type="text" name="num_serie_swi"></li>
        <li><text>IP:</text><input id="ip" type="text" name="ip"></li>
        <li><input type="button" class="btn btn-success" id="boton_nueva_marca" value="Nueva Marca y Modelo"><input class="btn btn-primary" id="boton_crear_switch" type="submit" name="crearSwitch" value="Crear"></li>
    </ul>
</fieldset>
    <br>
    <div><p class="error_ag_s text-error"></p></div>
</form>

<script type="text/javascript">

	$("#select_marcas_switchs").on('change',function(){

		console.log("Evento de seleccion de modelos");

		$.post('controlador/ProductosController.php',
			{
				value : $('#select_marcas_switchs option:selected').val(),
				tipo : "sel_modelos",
				action : "agregar_switch",

			}, function(data) {
	       		$("#select_modelos_Switch").replaceWith(data);
			});
	});


	$("#form_agregar_switch").validate({
        errorLabelContainer : ".error_ag_s",
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
                remote      : {
                    url     : 'busquedas/busca_ip_switch.php' ,
                    type     : 'post' ,
                    data     : {
                        ip : function() {
                            return $("#ip").val();
                        }
                    }
                }
            },
            num_serie_swi : {
            	required : true,
            	remote      : {
                    url     : 'busquedas/busca_nro_serie_switch.php' ,
                    type     : 'post' ,
                    data     : {
                        serie : function() {
                            return $("#nro_de_serie_s").val();
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
            num_serie_swi :{
            	required: 'El numero de serie no puede ser nulo',
            	remote: 'Ya existe un switch con ese numero de serie'
            },
            ip : {
                remote: 'Ya existe un switch con esa ip'
            },
        } ,
        submitHandler : function (form) {
            console.log ("Formulario OK");
            console.log('Evento de click en crear');
            console.log($("#form_agregar_switch").serialize());
            var todo = "";

            if($("#select_modelos_Switch").val().indexOf("-") >= 0 && $("#select_modelos_Switch").text() != $("#select_modelos_Switch").val()){
                var data = $("#select_modelos_Switch").val().split('-');
                primparte = data[0];
                sdaparte = data[1];
                todo = primparte +' '+ sdaparte;
                console.log(primparte);
                console.log(sdaparte);
                console.log(todo);
            }
            else{
                todo = $("#select_modelos_Switch").val();
            }

            var dataUrl = "marca="+$('#select_marcas_switchs option:selected').val()+"&modelo="+todo+"&num_serie="+$("#nro_de_serie_s").val()+"&ip="+$("#ip").val()+"&tipo=Switch";

            $.ajax({
                            url: 'controlador/CreacionController.php',
                            type: 'POST',
                            data: dataUrl,
                            success: function(response){
                                console.log(response);
                                console.log("success");
                                alert('Se ha agregado el producto correctamente');
                                $("#tabs7").load("controlador/ProductosController.php",{action:"agregar_switch"});
                            }
            })
            .fail(function() {
                console.log("error");
                alert('Hubo un error');
                $("#tabs7").load("controlador/ProductosController.php",{action:"agregar_switch"});

            })
            .always(function() {
                console.log("complete");
            });


        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });

    $("#form_agregar_switch").on('click',"#boton_nueva_marca",function(){
     
        $.post( "controlador/CreacionController.php",
                {
                    tablaPpal : "Switch",
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
                                                height : 350,
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
                                                        $("#form_nueva_marca_y_modelo").submit();
                                                    }
                                                }
                    });
                }
        );
    });
</script>