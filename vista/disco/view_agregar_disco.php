<form id="form_agregar_disco">
<fieldset>
<legend>Complete los Datos</legend>
    <ul>
        <li><text>Marca:</text>{select_marcas_discos}</li>
        <li><text>Capacidad:</text>{select_capacidades}{select_unidades}</li>
        <li><text>Cantidad:</text><input id="cantidad" name="cant_veces" placeholder="Ingrese cantidad"></li>
        <li><input type="button" class="btn btn-success" id="boton_nueva_marca" value="Nueva Marca"><input class="btn btn-primary" id="boton_crear_disco" type="submit" name="crearDisco" value="Crear"></li>
    </ul>
    </fieldset>
    <br>
    <div><p class="error_ag_disc text-error"></p></div>
</form>


<script type="text/javascript">

    console.log("{select_tipos_discos}");

    $('#select_unidades_discos option[value='+3+']').attr('selected', 'selected');

    $("#form_agregar_disco").validate({
        errorLabelContainer : ".error_ag_disc" ,
        wrapper : "li" ,
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
        submitHandler : function (form) {
            console.log ("Formulario OK");

            console.log('Evento de click en crear');
            console.log($("#form_agregar_disco").serialize());
            var dataUrl = $("#form_agregar_disco").serialize() + "&tipo=Disco";

            
            console.log(dataUrl);

            $.ajax({
                            url: 'controlador/CreacionController.php',
                            type: 'POST',
                            data: dataUrl,
                            success: function(response){
                                console.log(response);
                                console.log("success");
                                alert('Se ha agregado el producto correctamente');
                                $("#tabs4").load("controlador/ProductosController.php",{action:"agregar_disco"});
                            }
            })
            .fail(function() {
                console.log("error");
                alert('Hubo un error');
                $("#tabs4").load("controlador/ProductosController.php",{action:"agregar_disco"});

            })
            .always(function() {
                console.log("complete");
            });
        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });


    $("#form_agregar_disco").on('click',"#boton_nueva_marca",function(){
     
        $.post( "controlador/CreacionController.php",
                {
                    tablaPpal : "Disco",
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
                                                height : 280,
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
                                                        $("#form_nueva_marca").submit();
                                                    }
                                                }
                    });
                }
        );
    });
</script>