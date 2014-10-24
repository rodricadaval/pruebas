<?php
session_start();

if( empty($_SESSION['priority']) ) {
     print_r("Error");
}
else {
     print $_SESSION['priority'];
}

?>