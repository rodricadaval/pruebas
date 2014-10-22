<?php
require_once '../ini.php';
include '../logueo/chequeo_login.php'
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>

	<link href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"> 
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<title>Editar Area</title>
</head>
<body>

	<div class="as_wrapper">
	<h1>Bienvenido <b><?php echo ucfirst($_SESSION['username']); ?></b><span class="logout" id="logout">Desconectarse</span></h1>
	</div>

	<div class="contenedor">
	</div>
	</br>
	<?php echo $_POST['id_area'] ?>
	</div>
	</body>
	</head>
	</html>