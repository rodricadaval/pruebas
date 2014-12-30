<form id="form_marca">
    <div id="errores_marca" class="error_dialog"></div>
    <table class="mytable">
        <tr>
          <td class="required">Nombre</td>
          <td><input type="text" name="nombre" id="nombre" value="{nombre}"></td>
        </tr>
        <tr>
          <td><input style="background-color:#D3D3D3" type="text" name="id_marca" id="id_marca" value="{id_marca}" readonly></td>
        </tr>
   </table>
</form>

<script>

$(document).ready(function(){

    var marca = $('#nombre');
    var marca_orig = '{nombre}';
    var validado = false;
    $("#id_marca").hide();


    $("#form_marca").validate({
        errorLabelContainer : "#errores_marca" ,
        wrapper : "li" ,
        rules : {
            nombre : {
              required : true,
              minlength : 2,
              maxlength : 30,
              remote: {
                url: "checkDisponibilidad.php",
                type: "post",
                data: {
                  dato: function() {
                    if($( "#nombre" ).val() != marca_orig){
                      return $( "#nombre" ).val();
                    }
                  },
                  action: function(){
                    return "chequeo";
                  },
                  tabla: function(){
                    return "Marcas";
                  }
                }
              }
            }
        } ,
        messages : {
            nombre : {
              required : 'El marca es OBLIGATORIA',
              minlength : 'El marca debe tener más de 1 caracter',
              maxlength : 'El marca debe tener menos de 30 caracteres',
              remote : 'El nombre de marca ya existe'
            }
        } ,
        submitHandler : function (form_marca) {
           console.log ("Formulario OK");
            
            console.log("Aca empieza el envio de datos de marca");

            var UrlToPass;
            UrlToPass = $("#form_marca").serialize();

            UrlToPass+="&action=modificar";
            
            console.log(UrlToPass);

              $.ajax({
                    type : 'POST',
                    data : UrlToPass,
                    url  : 'controlador/MarcasController.php',
                    success: function(responseText){ // Obtengo el resultado de exito
                        if(responseText == 0){
                          alert("No se pudieron plasmar los datos. Error de en la Base de datos.");
                        }
                        else if(responseText == 1){
                          alert("Los datos han sido actualizados correctamente!");
                        }
                        else{
                          alert('Problema en la Sql query');
                        }
                        $("#dialogcontentmarca").dialog("destroy");
                        $("#dialogcontentmarca").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        $("#contenedorPpal").load("controlador/MarcasController.php");
                    }
              });       
        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });
});

</script>

