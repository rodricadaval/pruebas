<form id="form_borrar_marca" autocomplete="off">
<fieldset>
<legend><text style="font-size:15px;">Escriba la marca y modelo a borrar</text></legend>
    <div class="control-group">
        <label class="control-label" for="select_marcas_a_borrar">Marca</label>
        <div class="controls">
            {select_marcas}
        </div>
    </div>
</fieldset>
</form>

<script type="text/javascript">

    $("#form_borrar_marca").validate({
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
                
                $.ajax({
                        url : 'metodos_ajax_asoc.php',
                        method: 'post',
                        data:{ clase: 'Marcas',
                               metodo: 'borrar_de_producto',
                               tipo: "{Producto}",
                               marca: $("#select_marcas_a_borrar option:selected").val(),
                             },
                        dataType: 'json',
                        success : function(data){
                            
                            if(data == true){
                                alert('Se ha borrado la marca y el modelo del producto correctamente');
                                $("#dialogcontent_borrar_marca").dialog("destroy");
                                $("#dialogcontent_borrar_marca").remove();
                                if("{Producto}" == "Memorias"){
                                    $("#tabs3").load("controlador/ProductosController.php",{action:"agregar_memoria"});
                                }
                                else if("{Producto}" == "Discos"){
                                    $("#tabs4").load("controlador/ProductosController.php",{action:"agregar_disco"});
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