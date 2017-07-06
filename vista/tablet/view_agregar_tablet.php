<form id="form_agregar_tablet" autocomplete="off">
    <fieldset>
        <legend>Complete los Datos <small>(hay validaciones al crear)</small></legend>
        <div class="control-group">
            <label class="control-label" for="select_marcas_tablets">Marca</label>
            <div class="controls">
                {select_marcas_tablets}
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="select_modelos_tablets">Modelo</label>
            <div class="controls">
                {select_modelos_tablets}
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="nro_serie">Nro de Serie</label>
            <div class="controls">
                <input id="nro_serie" type="text" name="nro_serie">
            </div>
        </div>
        <div class="form-actions">
            <input type="button" class="btn btn-success" id="boton_nueva_marca" value="Nueva Marca y Modelo">
            <input class="btn btn-primary" id="boton_crear_switch" type="submit" name="crearSwitch" value="Crear">
        </div>
    </fieldset>
</form>
<script type="text/javascript">

	/*$("#select_marcas_tablets").on('change',function(){

		console.log("Evento de seleccion de modelos");

		$.post('controlador/ProductosController.php',
       {
        value : $('#select_marcas_tablets option:selected').val(),
        tipo : "sel_modelos",
        action : "agregar_tablet",

    }, function(data) {
      $("#select_modelos_tablets").replaceWith(data);
  });
});*/


	$("#form_agregar_tablet").validate({
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
            nro_serie: {
            	required : true,
            	remote      : {
                    url     : 'busquedas/busca_nro_serie_tablet.php' ,
                    type     : 'post' ,
                    data     : {
                        serie : function() {
                            return $("#nro_serie").val();
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
            nro_serie :{
            	required: 'El numero de serie no puede ser nulo',
            	remote: 'Ya existe una tablet con ese numero de serie'
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
        console.log($("#form_agregar_tablet").serialize());
        var todo = "";

        todo = $("#select_modelos_tablets").val().replace(/\./g, ' ');

        var dataUrl = "marca="+$('#select_marcas_tablets option:selected').val()+"&modelo="+todo+"&num_serie="+$("#nro_serie").val()+"&tipo=Tablet";

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
        console.log("Fail");
      console.log(validator);
  }
});

    $("#form_agregar_tablet").on('click',"#boton_nueva_marca",function(){

        $.post( "controlador/CreacionController.php",
        {
            tablaPpal : "Tablets",
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
                height : 410,
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