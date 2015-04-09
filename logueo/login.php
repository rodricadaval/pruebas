<?php
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Author" content="Rodrigo Cadaval" />
<title>Login Sistema Stock</title>
<script type="text/javascript" src="jquery-1.9.1.js"></script>
<script src="../lib/semantic.js" type="text/javascript"></script>
<link rel="shortcut icon" href="http://programasumar.com.ar/favicon.ico">
<link href="../css/semantic.css" rel="stylesheet" type="text/css">
<link href="../css/login.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
$(document).ready(function(){
	$('#username').focus(); // Focus to the username field on body loads

	$('body').keypress(function (e) {
		 var key = e.which;
		 if(key == 13)  // the enter key code
		  {
		    $('#login').trigger('click')();
		    return false;
		  }
	});

	$('#login').click(function(){ // Create `click` event function for login
		var username = $('#username'); // Get the username field
		var password = $('#password'); // Get the password field
		var login_result = $('.login_result'); // Get the login result div
		login_result.html('loading..'); // Set the pre-loader can be an animation
		if(username.val() == ''){ // Check the username values is empty or not
			username.focus(); // focus to the filed
			login_result.html('<span class="error">Ingresa el nombre de usuario</span>');
			return false;
		}
		if(password.val() == ''){ // Check the password values is empty or not
			password.focus();
			login_result.html('<span class="error">Ingresa la contraseña</span>');
			return false;
		}
		if(username.val() != '' && password.val() != ''){ // Check the username and password values is not empty and make the ajax request
			var UrlToPass = 'action=login&username='+username.val()+'&password='+password.val();
			$.ajax({ // Send the credential values to another checker.php using Ajax in POST menthod
			type : 'POST',
			data : UrlToPass,
			url  : 'checker.php',
			success: function(responseText){ // Get the result and asign to each cases
				if(responseText == 0){
					login_result.html('<span class="error">Usuario o Contraseña Incorrecto!</span>');
				}
				else if(responseText == 1){
					window.location = '../index.php';
				}
				else{
					alert('Problem with Sql query');
				}
			}
			});
		}
		return false;
	});
});
</script>
</head>

<body>

<table width="100" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td height="528" align="center">
    <table width="1000" height="521" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="8">&nbsp;</td>
        <td width="800" valign="bottom"><img src="../images/somb.png" width="100%" height="11" /></td>
        <td width="8">&nbsp;</td>
      </tr>
      <tr>
        <td rowspan="3" align="right" valign="top"><img src="../images/somb2.png" width="8" height="450" /></td>
        <td background="../images/gris.jpg" bgcolor="#FFFFFF"><table width="99%" border="0" cellspacing="20" cellpadding="0">
          <tr>
            <td width="71" align="center"><a href="http://www.msal.gov.ar/sumar/"><img src="../images/logo_sumar.png" alt="Programa SUMAR" width="71" height="71" border="0" /></a></td>
            <td width="555" align="center"><div align="left"><span class="Estilo1">BIENVENIDO AL SISTEMA DE STOCK DEL PROGRAMA SUMAR!</span></div></td>
            <td width="87" align="center"><a href="http://www.plannacer.msal.gov.ar/"><img src="../images/logo_plan-nacer.png" alt="Plan Nacer" width="87" height="55" border="0" /></a></td>
            <td width="160" align="right"><a href="http://www.msal.gov.ar/"><img src="../images/logo_msal.png" alt="Ministerio de Salud de la Nación" width="160" height="55" border="0" /></a></td>
          </tr>
        </table></td>
        <td rowspan="3" align="left" valign="top"><img src="../images/somb3.png" width="8" height="450" /></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="1%" background="../images/barra.gif" bgcolor="#4396DE"><img src="../images/barra.gif" width="2" height="33" /></td>
            <td width="99%" align="right" background="../images/barra.gif" bgcolor="#4396DE">&nbsp;</td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td height="500" valign="top" bgcolor="#FFFFFF">
        <table width="983" border="0" cellspacing="0" cellpadding="0">
          <tr>          
            	<div class="as_wrapper">

<p>Ingresa usuario y contraseña.</p>

<br/>
<form autocomplete="off">
<div style="width:450px; margin-left:27%;" class="ui one column middle aligned relaxed fitted stackable grid">
  <div class="column">
    <div class="ui form segment">
      <div class="login_result" id="login_result"></div>
      <div class="field">
        <label>Usuario</label>
        <div class="ui left icon input">
          <input type="text" name="username" id="username" placeholder="Usuario">
          <i class="user icon"></i>
        </div>
      </div>
      <div class="field">
        <label>Password</label>
        <div class="ui left icon input">
          <input type="password" name="password" id="password">
          <i class="lock icon"></i>
        </div>
      </div>
      <div class="ui blue submit button" name="login" id="login">Login</div>
    </div>
  </div>
</div>
</form>
</div>
        </tr>
      <tr>
        <td colspan="3" align="right" valign="top">
        <img style="margin-top:7%;" src="../images/somb4.png" width="100%" height="11" /></td>
        </tr>
    </table>
    </td>
  </tr>
</table>
</td>
</tr>
</table>
</body>
</html>