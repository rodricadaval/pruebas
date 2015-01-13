<form id="form_nueva_marca_y_velocidad_memoria" autocomplete="off">
<fieldset>
<legend><text style="font-size:15px;">Escriba una marca nueva o elija una existente</text></legend>
    <div class="control-group">
        <label class="control-label" for="marcas">Marca</label>
        <div class="controls">
            <input name="marca" id="marcas" class="typeahead" type="text" placeholder="Ingrese Marca">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="select_tipos">Tipo</label>
        <div class="controls">
            {select_tipos}
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="select_velocidades_nueva_memoria">Velocidad</label>
        <div class="controls">
            {select_velocidades}
        </div>
    </div>
</fieldset>
</form>

<script type="text/javascript">

	$("#marcas").typeahead({
        source : function (query , process) {
            $.ajax({
                type         : 'post' ,
                data         : {
                    term         : query
                } ,
                url          : 'lib/listado_marcas.php' ,
                dataType     : 'json' ,
                success     : function (data) {
                    process (data);
                }
            })
        } ,
        minLength : 2
    });

    $("#select_tipos").on('change',function(){

        console.log("Evento de seleccion de tipos");

        $.post('controlador/ProductosController.php',
            {
                value_tipo : $('#select_tipos option:selected').val(),
                tipo : "sel_velocidades_nueva_marca",
                action : "agregar_memoria"

            }, function(data) {
            $("#select_velocidades_nueva_memoria").replaceWith(data);
            });
    });


    $("#form_nueva_marca_y_velocidad_memoria").validate({
        errorLabelContainer : ".error_n_marc",
        wrapper : "li",
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        onsubmit: true,
        rules : {
            marca : {
                required : true
            }
        } ,
        messages : {
            marca : {
                required : 'El campo Marca no puede ser vacío'
            }
        },
        highlight: function(element) {
             $(element).closest('.control-group').removeClass('success').addClass('error');
         },
        success: function(element) {
            element.text('OK!').addClass('valid')
            .closest('.control-group').removeClass('error').addClass('success');
        },
        submitHandler : function (form) {
            console.log ("Formulario OK");

                $.ajax({
                        url : 'metodos_ajax_asoc.php',
                        method: 'post',
                        data:{ clase: 'Marcas',
                               metodo: 'agregar',
                               tipo: "{Producto}",
                               marca: $("#marcas").val(),
                               tecnologia: $("#select_tipos").val(),
                               velocidad: $("#select_velocidades_nueva_memoria option:selected").val()
                             },
                        dataType: 'json',
                        success : function(data){
                            
                            if(data == true){
                                alert('Se ha agregado el producto correctamente');
                                $("#dialogcontent_nueva_marca").dialog("destroy");
                                $("#dialogcontent_nueva_marca").remove();
                                $("#tabs3").load("controlador/ProductosController.php",{action:"agregar_memoria"});
                            }
                            else if(data == "estaba"){
                                alert('Ya esta esa marca y modelo agregada');
                                $("#dialogcontent_nueva_marca").dialog("destroy");
                                $("#dialogcontent_nueva_marca").remove();
                                $("#tabs3").load("controlador/ProductosController.php",{action:"agregar_memoria"});
                            }
                            else{
                                alert("Hubo un error");
                            }
                        }
                    });
        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });

</script>