<?php
require './init.php';
//session_start();
unset($_SESSION['admin']);
//session_destroy();

header('location:'.URL.'index.php');