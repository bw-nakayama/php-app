<?php
require_once('functions.php');

savePostedData($_POST);
header('Location: ./index.php');