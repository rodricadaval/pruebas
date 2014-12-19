<form id="form_agregar_disco">
<fieldset>
<legend>Complete los Datos</legend>
<ul>
<li><text>Marca:</text>{select_marcas_discos}</li>
<li><text>Capacidad:</text>{select_capacidades}{select_unidades}</li>
<li><text>Cantidad:</text><input id="cantidad" name="cant_veces" placeholder="Ingrese cantidad"></li>
<li><input class="boton_crear_disco" type="submit" name="crearDisco" value="Crear"></li>
<br>
<br>
<li class="error_ag_disc text-error"></li>
</ul>
</fieldset>
</form>

<style type="text/css">

h3
{
    margin-bottom: 40px; 
}

#form_agregar_disco
{
    /**/
}

#form_agregar_disco ul 
{
    list-style-type: none;
    padding-left: 0px;
}

#form_agregar_disco ul li
{
    vertical-align: middle;
    height: 40px;
    line-height: 40px;
}

.boton_crear_disco
{
    float:right;
    margin-right: 43px;
    margin-top: 10px;
    font-size: 15px;
    background-color: blue;
    color:white;
}

#select_marcas_discos 
{
    width: 160px;
}

#select_capacidades_discos
{
    text-align: right;
    width: 80px;
}

#select_unidades_discos
{
    width: 80px;
}

#form_agregar_disco ul li input[id="cantidad"]
{
    width: 156px;
    text-align: center;
}

#form_agregar_disco ul li text { 
    float: left;
    width: 110px;
    font-size: 17px;
    font-weight: bold;
    vertical-align: middle;
}

</style>

<script type="text/javascript">

    console.log("{select_tipos_discos}");

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
                                $("#tabs2").load("controlador/ProductosController.php",{action:"agregar_disco"});
                            }
            })
            .fail(function() {
                console.log("error");
                alert('Hubo un error');
                $("#tabs2").load("controlador/ProductosController.php",{action:"agregar_disco"});

            })
            .always(function() {
                console.log("complete");
            });
        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });
</script>