<form id="form_nueva_area">
    <table class="mytable">
        <tr>
          <td class="required">Nombre</td>
          <td><input type="text" name="nombre" id="nombre" value=""></td>
        </tr>
   </table>
</form>

<script>

$(document).ready(function(){

  $("#form_nueva_area").on('submit',function(){
        console.log("Aca empieza el envio de datos de area");

        var UrlToPass;
        UrlToPass = $("#form_nueva_area").serialize();
        UrlToPass+="&action=crear";
      
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
  });
});
</script>