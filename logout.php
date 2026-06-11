<?php
require_once "controller/auth.php";
logoutUser();
header("Location: login.php");
exit;
