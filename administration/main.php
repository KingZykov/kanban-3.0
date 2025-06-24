<?php 
session_start();
if(isset($_SESSION['user'])) {
	header('Location: projects.php');
	die();
} else {
	require '../login/login.php';
}

?>