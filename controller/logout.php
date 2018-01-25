<?php

session_destroy();

echo "Logout Efetuado";

header('location: index.php');
