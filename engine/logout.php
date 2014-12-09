<?php
session_start();
unset($_SESSION['username'] , $_SESSION['key']  );
header('location: ../login');

?>