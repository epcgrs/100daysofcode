<?php
session_start();
$_SESSION['current_scene'] = 'start';
header('Location: story.php');
exit();
?>