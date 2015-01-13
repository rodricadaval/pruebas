<form id="form_nueva_marca_y_modelo_computadora" autocomplete="off">
<fieldset>
<legend><text style="font-size:15px;">Escriba una marca nueva o elija una existente</text></legend>
    <div class="control-group">
        <label class="control-label" for="marcas">Marca</label>
        <div class="controls">
            <input name="marca" id="marcas" class="typeahead" type="text" placeholder="Ingrese Marca">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="modelo">Modelo</label>
        <div class="controls">
            <input name="modelo" id="modelo" type="text" placeholder="Ingrese Modelo">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="slots">Slots</label>
        <div class="controls">
            <input name="slots" id="slots" type="text" placeholder="Ingrese Slots">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="select_unidades_computadoras">Memoria Max</label>
        <div class="controls">
            {select_capacidades}{select_unidades}
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

    $("#select_unidades_computadoras option[value=2]").attr("selected","selected");
    $("#select_unidades_computadoras").attr("disabled","disabled");


    $("#form_nueva_marca_y_modelo_computadora").validate({
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