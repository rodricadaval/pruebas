<?php
session_start();

if (isset($_SESSION['priority']))
{
	print $_SESSION['priority'];
}
else
{
	print "0";
}
?>
