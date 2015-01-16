<form id="form_agregar_impresora" autocomplete="off">
<fieldset>
<legend>Complete los Datos <small>(hay validaciones al crear)</small></legend>
        <div class="control-group">
            <label class="control-label" for="num_serie_i">Nro de Serie</label>
            <div class="controls">
                {select_marcas_impresoras}
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="modelo">Modelo</label>
            <div class="controls">
                <select id='select_modelos_Impresora' name='modelo'>
                    <option value=''>Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="num_serie_i">Nro de Serie</label>
          <div class="controls">
            <input type="text" class="input-xlarge" name="num_serie_i" id="nro_de_serie_i">
          </div>
         </div>
        <div class="control-group">
          <label class="control-label" for="ip">IP</label>
          <div class="controls">
            <input type="text" class="input-xlarge" name="ip" id="ip">
          </div>
        </div>
        <div class="form-actions">
            <input type="button" class="btn btn-success" id="boton_nueva_marca" value="Nueva Marca y Modelo">
            <input class="btn btn-primary" id="boton_crear_impresora" type="submit" name="crearImpresora" value="Crear">
        </div>
</fieldset>
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
                },
                IP4Checker : true
            },
            num_serie_i : {
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
            num_serie_i :{
            	required: 'El numero de serie no puede ser nulo',
            	remote: 'Ya existe un impresora con ese numero de serie'
            },
            ip : {
                required : 'El ip no puede ser nulo',
                remote: 'Ya existe un impresora con esa ip'
            },
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
            console.log($("#form_agregar_impresora").serialize());
            var todo = "";

             if($("#select_modelos_Impresora").val().indexOf("-") >= 0 && $("#select_modelos_Impresora").text() != $("#select_modelos_Impresora").val()){
                var data = $("#select_modelos_Impresora").val().split('-');
                primparte = data[0];
                sdaparte = data[1];
                todo = primparte +' '+ sdaparte;
                console.log(primparte);
                console.log(sdaparte);
                console.log(todo);
            }
            else{
                todo = $("#select_modelos_Impresora").val();
            }

            var dataUrl = "marca="+$('#select_marcas_impresoras option:selected').val()+"&modelo="+todo+"&num_serie="+$("#nro_de_serie_i").val()+"&ip="+$("#ip").val()+"&tipo=Impresora";

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

    $("#form_agregar_impresora").on('click',"#boton_nueva_marca",function(){
     
        $.post( "controlador/CreacionController.php",
                {
                    tablaPpal : "Impresora",
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
                                                height : 400,
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