<form id="form_area">
    <div id="errores_area" class="error_dialog"></div>
    <table class="mytable">
        <tr>
          <td class="required">Nombre</td>
          <td><input type="text" name="nombre" id="nombre" value="{nombre}"></td>
        </tr>
        <tr>
          <td><input style="background-color:#D3D3D3" type="text" name="id_area" id="id_area" value="{id_area}" readonly></td>
        </tr>
   </table>
</form>

<script>

$(document).ready(function(){

    var area = $('#nombre');
    var area_orig = '{nombre}';
    var estado = {nuevo};
    var validado = false;
    $("#id_area").hide();


    $("#form_area").validate({
        errorLabelContainer : "#errores_area" ,
        wrapper : "li" ,
        rules : {
            nombre : {
              required : true,
              minlength : 3,
              maxlength : 30,
              remote: {
                url: "checkDisponibilidad.php",
                type: "post",
                data: {
                  dato: function() {
                    if($( "#nombre" ).val() != area_orig){
                      return $( "#nombre" ).val();
                    }
                  },
                  action: function(){
                    return "chequeo";
                  },
                  tabla: function(){
                    return "Areas";
                  }
                }
              }
            }
        } ,
        messages : {
            nombre : {
              required : 'El area es OBLIGATORIA',
              minlength : 'El area debe tener más de 3 caracteres',
              maxlength : 'El area debe tener menos de 30 caracteres',
              remote : 'El nombre de area ya existe'
            }
        } ,
        submitHandler : function (form_area) {
           console.log ("Formulario OK");
            
            console.log("Aca empieza el envio de datos de area");

            var UrlToPass;
            UrlToPass = $("#form_area").serialize();

            if(estado == 1){
              UrlToPass+="&action=crear";
            }
            else if(estado == 0){
              UrlToPass+="&action=modificar";
            }
            console.log(UrlToPass);

              $.ajax({
                    type : 'POST',
                    data : UrlToPass,
                    url  : 'controlador/AreasController.php',
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
                        $("#dialogcontentarea").dialog("destroy").empty();
                        $("#dialogcontentarea").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        $("#contenedorPpal").load("controlador/AreasController.php");
                    }
              });       
        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });
});

</script>

