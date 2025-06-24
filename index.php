<?php session_start();

if(isset($_SESSION['user'])) {
	header('Location: administration/projects.php');
	die();
} else {
	header('Location: administration/main.php');
}

?>