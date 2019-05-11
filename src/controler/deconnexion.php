<?php
session_start();
error_log("Deconnexion.php : entree dans la fonction") ;
unset($_SESSION);
#session_unset() ;
session_destroy();

header('location: index.php');