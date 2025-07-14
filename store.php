<?php
require_once('functions.php');

createData($_POST);
header('Location: ./index.php');