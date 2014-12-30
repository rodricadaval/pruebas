<form id="form_nueva_marca_y_velocidad_memoria">
<fieldset>
<legend><text style="font-size:15px;">Escriba una marca nueva o elija una existente</text></legend>
<ul>
    <li><text>Marca</text><input name="marca" id="marcas" class="typeahead" type="text" placeholder="Ingrese Marca"></li>
    <li><text>Tipo</text>{select_tipos}</li>
    <li><text>Velocidad</text>{select_velocidades}</li>

</ul>
</fieldset>
    <div class="error_n_marc text-error"></div>
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
                            
                            if(data){
                                alert('Se ha agregado el producto correctamente');
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