<?php
// FILE: logout.php
include('includes/dbConfig.php');

session_destroy();
redirect('index.php');
?>