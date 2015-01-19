<form id="form_borrar_marca_y_modelo" autocomplete="off">
<fieldset>
<legend><text style="font-size:15px;">Escriba la marca y modelo a borrar</text></legend>
    <div class="control-group">
        <label class="control-label" for="select_marcas_a_borrar">Marca</label>
        <div class="controls">
            {select_marcas}
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="select_modelos_a_borrar">Modelo</label>
        <div class="controls">
            <select id='select_modelos_a_borrar' name='modelo'>
                        <option value=''>Seleccionar</option>
            </select>
        </div>
    </div>
</fieldset>
</form>

<script type="text/javascript">

    $("#select_marcas_a_borrar").on('change',function(){

        console.log("Evento de seleccion de modelos");

        $.post('controlador/CreacionController.php',
            {
                action : "borrar_marca",
                tablaPpal : "{Producto}",
                value : $("#select_marcas_a_borrar").val(),
                cuestion : "sel_modelos"
            }, function(data) {
            $('#select_modelos_a_borrar').replaceWith(data);
            }
        );
    });

    $("#form_borrar_marca_y_modelo").validate({
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
            }
        } ,
        messages : {
            marca : {
                required : 'El campo Marca no puede ser vacío'
            },
            modelo : {
                required : 'El campo Modelo no puede ser vacío'
            }
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

                var modelo_a_borrar = $("#select_modelos_a_borrar option:selected").val().replace(/\./g, ' ');
                
                $.ajax({
                        url : 'metodos_ajax_asoc.php',
                        method: 'post',
                        data:{ clase: 'Marcas',
                               metodo: 'borrar_de_producto',
                               tipo: "{Producto}",
                               marca: $("#select_marcas_a_borrar option:selected").val(),
                               modelo: modelo_a_borrar
                             },
                        dataType: 'json',
                        success : function(data){
                            
                            if(data == true){
                                alert('Se ha borrado la marca y el modelo del producto correctamente');
                                $("#dialogcontent_borrar_marca").dialog("destroy");
                                $("#dialogcontent_borrar_marca").remove();
                                if("{Producto}" == "Monitores"){
                                    $("#tabs1").load("controlador/ProductosController.php",{action:"agregar_monitor"});
                                }
                                if("{Producto}" == "Computadoras"){
                                    $("#tabs2").load("controlador/ProductosController.php",{action:"agregar_computadora"});
                                }
                                else if("{Producto}" == "Impresoras"){
                                    $("#tabs5").load("controlador/ProductosController.php",{action:"agregar_impresora"});
                                }
                                else if("{Producto}" == "Routers"){
                                    $("#tabs6").load("controlador/ProductosController.php",{action:"agregar_router"});
                                }
                                 else if("{Producto}" == "Switchs"){
                                    $("#tabs7").load("controlador/ProductosController.php",{action:"agregar_switch"});
                                }
                            }
                            else if(data == false){
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