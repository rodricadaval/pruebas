<?php
session_start();

if ( ! isset($_SESSION['priority']))
{
	echo "<script type='text/javascript'>  window.location.reload(true);  </script>";
}
else
{
	print $_SESSION['priority'];
}

?>