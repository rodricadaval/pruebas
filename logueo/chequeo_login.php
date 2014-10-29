<?php
if ((!isset($_SESSION['userid']) || $_SESSION['userid'] == '') && !isset($_POST['id_usuario'])) {
	echo '<script type="text/javascript">window.location = "logueo/login.php"; </script>';
}
if (isset($_SESSION['userid']) && isset($_POST['id_usuario'])) {
	if ($_SESSION['userid'] == $_POST['id_usuario'] && $_SESSION['priority'] != 3) {
		session_destroy();
		echo 1;
	} else {
		echo 0;
	}
}
?>
