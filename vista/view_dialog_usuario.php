<form id="form">
    <div id="errores"></div>
    <table class="mytable">
        <tr>
          <td class="required">Nombre</td>
          <td><input type="text" name="nombre_apellido" id="nombre_apellido" value="{nombre_apellido}"></td>
        </tr>
        <tr>
          <td class="required">Usuario</td>
          <td><input type="text" name="usuario" id="usuario" value="{usuario}"></td>
        </tr>
        <tr>
          <td>Email</td>
          <td><input type="text" name="email" id="email" value="{email}"></td>
        </tr>
        <tr type="hidden">
           <td><input type="hidden" name="password_orig" id="password_orig" value="{password}"></td>
        </tr>
        <tr>
          <td>Area</td>
          <td>{select_Areas}</td>
        </tr>
        <tr>
          <td>Permisos</td>
          <td>{select_Permisos}</td>
        </tr>
        <tr>
          <td>ID</td>
          <td><input style="background-color:#D3D3D3" type="text" name="id_usuario" id="id_usuario" value="{id_usuario}" readonly></td>
        </tr>
        <tr id="vista_pass">
          <td></td>
          <td><input type="button" id="cambiar_pass" name="boton" value="Cambiar Contraseña"></td>
        </tr>
        </br>
        <tr>
          <td><input type="submit" id="submit" tabindex="-1"></td>
          <td></td>
        </tr>
   </table>
</form>

<script>


$(document).ready(function(){

    var usuario = $('#usuario');
    var usuario_orig = '{usuario}';
    var password = $('#password');
    var nueva_password = $('#nueva_password');
    var conf_password = $('#conf_password');
    var estado = {nuevo};

    $('#select_areas').removeAttr('disabled');
    $("#select_areas option[value=1]").remove();

    if(estado == 1)
    {
        $.get("vista/agregar_datos_password_nueva.php",function(data){
            $("#vista_pass").replaceWith(data);
        });
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
              required : true,
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
              required : 'La password no puede ser null'
            },
            conf_password : {
              required : 'La confirmarcion de password no puede ser null',
              equalTo: 'Las passwords ingresadas no son iguales'
            },
            nueva_password : {
              required : 'La nueva password es OBLIGATORIA',
              equalTo: "Las passwords ingresadas no son iguales"
            },
            email : {
              email : 'Por favor, ingresa un email con formato correcto'
            }
        } ,
        submitHandler : function (form) {
          console.log ("Formulario OK");
        } ,
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });

});

    $("#form").on('submit',function(){
        var estado = {nuevo};
        console.log("Aca empieza el envio de datos de usuario");

        var UrlToPass;
        UrlToPass = $("#form").serialize();

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
                    }
                    else{
                      alert('Problema en la Sql query');
                    }

                    $("#dialogcontent").dialog("close");
                    $("#contenedorPpal").load("controlador/UsuariosController.php");
                }
          });
    });

    $("#cambiar_pass").on('click',function(){
        $.post("vista/agregar_datos_password.php",function(data){

            $("#vista_pass").replaceWith(data);
        });
    });

/*

    $('#nombre').focus();
    var login_result = $('.login_result'); // Get the login result div
    var usuario = $('#usuario');

    function updateTips( t ) {

       login_result.html(t);

       setTimeout(function() {
          login_result.removeClass( "ui-state-highlight", 1500 );
       }, 500 );
    }

    function multiline(text){
        return text.replace(/\n/g,'<br/>');
    }

    function includeTips( t ) {

        if(login_result.text() == "Chequeo de estado"){
          login_result.text(t);
        }
        else{
          var texto = multiline(login_result.text() + " \n " + t);
          login_result.html(texto);
        }

        setTimeout(function() {
            login_result.removeClass( "ui-state-highlight", 1500 );
          }, 500 );
    }

         function checkLength( o, n, min, max ) {
          if ( o.val().length > max || o.val().length < min ) {
             includeTips( "La longitud de " + n + " de debe estar entre " +
             min + " y " + max + "." );
            return false;
          } else {
            return true;
          }
      }

    var estado = "{nuevo}";
    console.log(estado);

    if(estado == 1){

      $.post("vista/dialog_content.php",
        {
          id_usuario : "{id_usuario}"
        }
      );

      $.get("vista/agregar_datos_password_nueva.php",function(data){
        $("#vista_pass").replaceWith(data);
      });
    }



    $('#usuario').on('input',function(){

        if(usuario.val() == ''){
            updateTips("El usuario no puede ser vacío");
        }
        else{

         var UrlToPass = 'action=chequeo&username='+usuario.val();
            $.ajax({ // Send the credential values to another checker.php using Ajax in POST menthod
              type : 'POST',
              data : UrlToPass,
              url  : 'checkDisponibilidad.php',
              success: function(responseText){ // Get the result and asign to each cases
                  if(responseText == 0){
                    updateTips("Usuario Disponible!");
                  }
                  else if(responseText == 1){
                      if(usuario.val() == "{usuario}"){
                        updateTips("No hay cambios");
                      }
                      else{
                      updateTips("Usuario en uso!");
                      }
                  }
                  else{
                    alert('Problem with Sql query');
                  }
              }
            });
        }
    });

    $('#submit').on('click',function(event){

      event.preventDefault();

      var usuario = $('#usuario');
      var password = $('#password');
      var nueva_password = $('#nueva_password');
      var conf_password = $('#conf_password');



      updateTips('');

        login_result.html(''); // Set the pre-loader can be an animation

        if(usuario.val() == ''){
          updateTips("El usuario no puede ser vacío");
          usuario.val("{usuario}");
          return false;
        }

        else if(!checkLength(usuario,"usuario",3,20)){ // Check the username values is empty or not
          usuario.val("{usuario}");
        }

             if((password.val() == "{password}" && nueva_password.val() == conf_password.val() && checkLength(nueva_password,"Nueva Password",3,20)) || ( !password.val() && !nueva_password.val() && !conf_password.val())){

                    console.log("ACA EMPIEZO A MODIFICAR LA BASE DE DATOS");

                    var UrlToPass;

                    UrlToPass = $("#form").serialize();

                    UrlToPass+="&action=modificar";

                    console.log(UrlToPass);

                        $.ajax({
                          type : 'POST',
                          data : UrlToPass,
                          url  : 'controlador/UsuariosController.php',
                            success: function(responseText){ // Get the result and asign to each cases
                                if(responseText == 0){
                                  updateTips("No se pudieron plasmar los datos. Error de en la Base de datos.");
                                }
                                else if(responseText == 1){
                                  alert("Los datos han sido actualizados correctamente!");
                                  $("#dialogcontent").dialog("close");
                                  $("#contenedorPpal").load("controlador/UsuariosController.php");
                                }
                                else{
                                  alert('Problema en la Sql query');
                                }
                            }
                          });
              }
              else if(estado == 1 && password.val() != "" && password.val() == conf_password.val()){

                    UrlToPass = $("#form").serialize();

                    UrlToPass+="&action=crear";

                    console.log(UrlToPass);

                    $.ajax({
                          type : 'POST',
                          data : UrlToPass,
                          url  : 'controlador/UsuariosController.php',
                          success: function(responseText){ // Get the result and asign to each cases
                              if(responseText == 0){
                                updateTips("No se pudieron plasmar los datos. Error de en la Base de datos.");
                              }
                              else if(responseText == 1){
                                alert("Los datos han sido actualizados correctamente!");
                                $("#dialogcontent").dialog("close");
                                $("#contenedorPpal").load("controlador/UsuariosController.php");
                              }
                              else{
                                alert('Problema en la Sql query');
                              }
                          }
                    });
              }
              else{
                    if((password.val() != "{password}" || nueva_password.val() != conf_password.val()) && estado != 1){
                      includeTips("La password actual no es tal o las nuevas passwords son distintas");
                    }

                    else{
                      includeTips("completa todos los campos obligatorios");
                    }

                   password.val("");
                   nueva_password.val("");
                   conf_password.val("");
              }

       });

   $("#cambiar_pass").on('click',function(){

    $.get("vista/agregar_datos_password.php",function(data){
      $("#vista_pass").replaceWith(data);
    })
   });

 });
*/
</script>

