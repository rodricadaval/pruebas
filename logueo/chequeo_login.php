<?php
if(!isset($_SESSION['userid']) || $_SESSION['userid'] == ''){
	echo '<script type="text/javascript">window.location = "logueo/login.php"; </script>';
}
?>
