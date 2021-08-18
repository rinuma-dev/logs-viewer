<?php 
require './vendor/autoload.php';
$JsonUwsgi = file_get_contents("json/uwsgi.json");
$JsonNginx = file_get_contents("json/nginx.json");
$redis = new Predis\Client();
?>