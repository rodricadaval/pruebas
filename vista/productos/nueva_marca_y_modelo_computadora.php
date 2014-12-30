<form id="form_nueva_marca_y_modelo_computadora">
<fieldset>
<legend><text style="font-size:15px;">Escriba una marca nueva o elija una existente</text></legend>
<ul>
    <li><text>Marca</text><input name="marca" id="marcas" class="typeahead" type="text" placeholder="Ingrese Marca"></li>
    <li><text>Modelo</text><input name="modelo" id="modelo" type="text" placeholder="Ingrese Modelo"></li>
    <li><text>Slots</text><input name="slots" id="slots" type="text" placeholder="Ingrese Slots"></li>
    <li><text>Memoria Max</text>{select_capacidades}{select_unidades}</li>

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

    $("#select_unidades_computadoras option[value=2]").attr("selected","selected");
    $("#select_unidades_computadoras").attr("disabled","disabled");


    $("#form_nueva_marca_y_modelo_computadora").validate({
        errorLabelContainer : ".error_n_marc",
        wrapper : "li",
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
            slots : {
                required : true,
                number : true
            }
        } ,
        messages : {
            marca : {
                required : 'El campo Marca no puede ser vacío'
            },
            modelo : {
                required : 'El campo Modelo no puede ser vacío'
            },
            slots : {
                required : 'El campo Slots no puede ser vacío',
                number : 'La cantidad de slots debe ser numérica'
            }
        } ,
        submitHandler : function (form) {
            console.log ("Formulario OK");

                $.ajax({
                        url : 'metodos_ajax_asoc.php',
                        method: 'post',
                        data:{ clase: 'Marcas',
                               metodo: 'agregar',
                               tipo: "{Producto}",
                               marca: $("#marcas").val(),
                               modelo: $("#modelo").val(),
                               slots: $("#slots").val(),
                               mem_max: $("#select_capacidades_computadoras").val()
                             },
                        dataType: 'json',
                        success : function(data){
                            
                            if(data == true){
                                alert('Se ha agregado el producto correctamente');
                                $("#dialogcontent_nueva_marca").dialog("destroy");
                                $("#dialogcontent_nueva_marca").remove();
                                $("#tabs2").load("controlador/ProductosController.php",{action:"agregar_computadora"});
                            }
                            else if (data == "estaba"){
                                alert('Ya estaba esa marca y modelo agregada');
                                $("#dialogcontent_nueva_marca").dialog("destroy");
                                $("#dialogcontent_nueva_marca").remove();
                                $("#tabs2").load("controlador/ProductosController.php",{action:"agregar_computadora"});
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