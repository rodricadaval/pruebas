<form id="form">
    <div id="errores" class="error_dialog"></div>
    <table class="fixed_user">
        <tr>
          <td>Permisos</td>
          <td>{select_Permisos}</td>
          <td>Interno</td>
          <td><input type="text" name="interno" id="interno" value="{interno}"></td>
        </tr>
        <tr>
          <td class="required">Nombre</td>
          <td><input type="text" name="nombre_apellido" id="nombre_apellido" value="{nombre_apellido}"></td>
          <td class="ID">ID</td>
          <td class="ID"><input style="background-color:#D3D3D3" type="text" name="id_usuario" id="id_usuario" value="{id_usuario}" readonly></td>
        </tr>
        <tr>
          <td class="required">Usuario  </td>
          <td><input type="text" name="usuario" id="usuario" value="{usuario}"></td>
          <td class="f_password">Password Actual </td>
          <td class="f_password"><input type="password" name="password" id="password" value="" disabled></td>
        </tr>
        <tr type="hidden">
           <td><input type="hidden" name="password_orig" id="password_orig" value="{password}"></td>
        </tr>
        <tr>
          <td>Email</td>
          <td><input type="text" name="email" id="email" value="{email}"></td>
          <td class="f_nueva_password">Nueva Password </td>
          <td class="f_nueva_password"><input type="password" name="nueva_password" id="nueva_password" value="" disabled></td>
        </tr>
        <tr>
          <td>Area</td>
          <td>{select_Areas}</td>
          <td class="f_conf_password">Confirmar</td>
          <td class="f_conf_password"><input type="password" name="conf_password" id="conf_password" value="" disabled></td>
        </tr>

            <td colspan="4" id="vista_pass">
              <input style="float:right; margin-bottom:20px;" type="button" id="cambiar_pass" name="boton" value="Cambiar Contraseña">
            </td>

        <tr class="boton_radio">
            <td colspan="4">
              <text id="text_pregunta">¿Desea que todos los productos del usuario sigan perteneciendo a él luego de cambiar de area?
              </text>
            </td>
        </tr>
        <tr>
           <td class="boton_radio" colspan="4">
                <input style="margin-left:220px;display:inline-block;border:0px;width:20px;height:20px;" type="radio" name="con_productos" value="SI" checked>
                <text style="font-size:17px;vertical-align:middle;color:black;display:inline-block;font-style:bold;"> SI</text>
               <input style="margin-left:5px;border:0px;display:inline-block;width:20px;height:20px;" type="radio" name="con_productos" value="NO">
                <text style="font-size:17px;vertical-align:middle;display:inline-block;color:black;font-style:bold;">NO</text>
           </td>
        </tr>
    </table>
</form>

<script>


$(document).ready(function(){

    var usuario = $('#usuario');
    var usuario_orig = '{usuario}';
    var estado = {nuevo};
    var area_orig = $('#select_areas option:selected').val();

    $(".f_password,.f_nueva_password,.f_conf_password,.boton_radio").hide();

    $('#select_areas').removeAttr('disabled');
    $("#select_areas option[value=1]").remove();

    if(estado == 1)
    {
        $(".f_nueva_password, .f_conf_password").show(); 
        $("#nueva_password, #conf_password").removeAttr("disabled"); 
        $("#vista_pass").hide();
        $(".ID").hide();
    }

    $("#form").validate({
        errorLabelContainer : "#errores" ,
        wrapper : "li" ,
        rules : {
            nombre_apellido : {
              required : true ,
              minlength : 5,
              maxlength : 50
            } ,
            usuario : {
              required : true,
              minlength : 3,
              maxlength : 30,
              remote: {
                url: "checkDisponibilidad.php",
                type: "post",
                data: {
                  dato: function() {
                    if($( "#usuario" ).val() != usuario_orig){
                      return $( "#usuario" ).val();
                    }
                  },
                  action: function(){
                    return "chequeo";
                  },
                  tabla: function(){
                    return "Usuarios";
                  }
                }
              }
            },
            area : {
              required : true
            },
            permisos : {
              required : true
            },
            password :{
              required : true,
              equalTo: "#password_orig"
            },
            conf_password : {
              required : true,
              equalTo: "#nueva_password"
            },
            nueva_password : {
              required : true
            },
            email :{
              required : false,
              email : true
            }
        } ,
        messages : {
            nombre_apellido : {
              required : 'El nombre es OBLIGATORIO',
              minlength : 'El nombre debe tener más de 4 caracteres',
              maxlength : 'El nombre debe tener menos de 50 caracteres'
            },
            usuario : {
              required : 'El usuario es OBLIGATORIO',
              minlength : 'El usuario debe tener más de 2 caracteres',
              maxlength : 'El usuario debe tener menos de 50 caracteres',
              remote : 'El nombre de usuario ya existe'
            },
            area : {
              required : 'El area es OBLIGATORIA'
            },
            permisos : {
              required : 'Los permisos son OBLIGATORIOS'
            },
            password : {
              required : 'La password antigua es OBLIGATORIA. Si no desea cambiar de password clickee el boton -No cambiar-',
              equalTo: 'La password antigua no es la correcta. Si no desea cambiar de password clickee el boton -No cambiar-'
            },
            conf_password : {
              required : 'La confirmarcion de password es OBLIGATORIA',
              equalTo: 'Las passwords ingresadas no son iguales'
            },
            nueva_password : {
              required : 'La nueva password es OBLIGATORIA',
              equalTo: "Las passwords ingresadas no son iguales"
            },
            email : {
              email : 'Por favor, ingresa un email con formato correcto'
            }
        },
        submitHandler : function (form) {
          console.log ("Formulario OK");
  
            console.log("Aca empieza el envio de datos de usuario");
            $("#password_orig").attr("disabled","disabled");

            var UrlToPass; 

            if(estado == 1 && $("#select_permisos option:selected").val() == 2){
              $("#conf_password").val($("#usuario").val()); 
              $("#nueva_password").val($("#usuario").val()); 
            }

            if($("#email").val() == ""){
              $("#email").attr("disabled","disabled");
            }
            if($("#interno").val() == ""){
              $("#interno").attr("disabled","disabled");
            }
            if($("#conf_password").val() != ""){
                $("#conf_password,#nueva_password,#password").attr("disabled","disabled");
                UrlToPass = $("#form").serialize();
                UrlToPass += "&password="+$("#conf_password").val();
              }
            else{
              UrlToPass = $("#form").serialize();
            }
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
                    url  : 'controlador/UsuariosController.php',
                    success: function(responseText){ // Obtengo el resultado de exito
                        if(responseText == 0){
                          alert("No se pudieron plasmar los datos. Error de en la Base de datos.");
                        }
                        else if(responseText == 1){
                          console.log("Los datos han sido actualizados correctamente!");
                          alert("Los datos han sido actualizados correctamente!");
                        }
                        else{
                          console.log(responseText);
                          alert('Problema en la Sql query');
                        }
                        $("#dialogcontent").dialog("destroy").empty();
                        $("#dialogcontent").remove();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        $("#contenedorPpal").load("controlador/UsuariosController.php");
                    }
              });
        },
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });
 
     $("#cambiar_pass").on('click',function(){
           
            if ($("#cambiar_pass").val() == "Cambiar Contraseña") {
              $("#cambiar_pass").val("No cambiar");
              $(".f_password, .f_nueva_password, .f_conf_password").show(); 
              $("#password,#nueva_password,#conf_password").removeAttr("disabled");
            }
            else if ($("#cambiar_pass").val() == "No cambiar") {
              $("#cambiar_pass").val("Cambiar Contraseña");
              $(".f_password, .f_nueva_password, .f_conf_password").hide();              
              $("#password,#nueva_password,#conf_password").attr("disabled","disabled");
            }            
    });
    
    $("#select_permisos").on('change',function(){
      if(estado == 1){
          if($("#select_permisos option:selected").val() == 2){
              $(".f_nueva_password, .f_conf_password").hide();
              $("#nueva_password, #conf_password").attr("disabled","disabled");
              $("#usuario").prop('readonly',true);
              if($("#nombre_apellido").val()!= ""){
                $("#usuario").val($("#nombre_apellido").val().toLowerCase().split(" ").join(""));
              }
          }
          else{
              $(".f_nueva_password, .f_conf_password").show();
              $("#nueva_password, #conf_password").removeAttr("disabled");
              $("#usuario").removeAttr("readonly");    
          } 
      }
    });

    $("#nombre_apellido").on('input',function(){
      if(estado == 1 && $("#select_permisos option:selected").val() == 2){
          $("#usuario").val($("#nombre_apellido").val().toLowerCase().split(" ").join(""));
      }
    });

    $("#select_areas").on('change',function(){
      if(estado == 0 && area_orig != $("#select_areas option:selected").val()){
        $(".boton_radio").show();
      }
      else{
        $(".boton_radio").hide();
        $('input[name="con_productos"][value="SI"]').prop('checked', true);
        $('input[name="con_productos"][value="NO"]').removeAttr("checked");
      }
    })

});
</script>