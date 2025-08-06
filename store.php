<?php
require_once('functions.php');

savePostedData($_POST); //遷移元からのでーたを渡してる
header('Location: ./index.php');