<form id="form_nueva_marca_y_modelo_tablet" autocomplete="off">
    <fieldset>
        <legend><text style="font-size:15px;">Escriba una marca nueva o elija una existente</text></legend>
        <div class="control-group">
            <label class="control-label" for="marcas">Marca</label>
            <div class="controls">
                <input name="marca" id="marcas" type="text" placeholder="Ingrese Marca">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="modelo">Modelo</label>
            <div class="controls">
                <input name="modelo" id="modelo" type="text" placeholder="Ingrese Modelo">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="procesador">Procesador</label>
            <div class="controls">
                <input name="procesador" id="procesador" type="text" placeholder="Ingrese nombre">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="memoria">Memoria</label>
            <div class="controls">
                <input name="memoria" id="memoria" type="text" placeholder="Ingrese Cantidad y unidad(GB)">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="disco">Disco</label>
            <div class="controls">
                <input name="disco" id="disco" type="text" placeholder="Ingrese Cantidad y unidad(GB)">
            </div>
        </div>
    </fieldset>
</form>

<script type="text/javascript">

    $("#form_nueva_marca_y_modelo_tablet").validate({
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
            procesador : {
                required : true
            },
            memoria : {
                required : true
            },
            disco : {
                required : true
            },
        } ,
        messages : {
            marca : {
                required : 'El campo Marca no puede ser vacío'
            },
            modelo : {
                required : 'El campo Modelo no puede ser vacío'
            },
            procesador : {
                required : 'El campo Procesador no puede ser vacío'
            },
            memoria : {
                required : 'El campo Memoria no puede ser vacío'
            },
            disco : {
                required : 'El campo Disco no puede ser vacío'
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
            url : '/controlador/TabletsController.php',
            method: 'post',
            data:{ 
                marcas: $('#marcas').val(),
                modelo: $('#modelo').val(),
                procesador: $('#procesador').val(),
                memoria: $('#memoria').val(),
                disco: $('#disco').val(),
            },
            dataType: 'json',
            success : function(data){

                if(data == true){
                    alert('Se ha agregado el producto correctamente');
                    $("#dialogcontent_nueva_marca").dialog("destroy");
                    $("#dialogcontent_nueva_marca").remove();
                    $("#tabs2").load("controlador/ProductosController.php",{action:"agregar_tablet"});
                }
                else if (data == "estaba"){
                    alert('Ya estaba esa marca y modelo agregada');
                    $("#dialogcontent_nueva_marca").dialog("destroy");
                    $("#dialogcontent_nueva_marca").remove();
                    $("#tabs2").load("controlador/ProductosController.php",{action:"agregar_tablet"});
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