<form id="form_nueva_marca" autocomplete="off">
<fieldset>
<legend><text style="font-size:15px;">Escriba una marca nueva o elija una existente</text></legend>
    <div class="control-group">
        <label class="control-label" for="marcas">Marca</label>
        <div class="controls">
            <input name="marca" id="marcas" class="typeahead" type="text" placeholder="Ingrese Marca">
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

    $("#form_nueva_marca").validate({
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
                               marca: $("#marcas").val()
                             },
                        dataType: 'json',
                        success : function(data){
                            console.log(data);

                            if(data == true){
                                alert('Se ha agregado el producto correctamente');
                                $("#dialogcontent_nueva_marca").dialog("destroy");
                                $("#dialogcontent_nueva_marca").remove();
                                if("{Producto}" == "Disco"){
                                    $("#tabs4").load("controlador/ProductosController.php",{action:"agregar_disco"});
                                }
                            }
                            else if(data == "estaba"){
                                alert("La marca ya se encuentra");
                                $("#dialogcontent_nueva_marca").dialog("destroy");
                                $("#dialogcontent_nueva_marca").remove();
                                if("{Producto}" == "Disco"){
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