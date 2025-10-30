<?php
require 'helpers.php';
session_unset();
session_destroy();
header('Location: index.php');
exit;
