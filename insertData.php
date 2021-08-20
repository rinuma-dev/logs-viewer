<?php
require './vendor/autoload.php';
require './connect.php';
require './connectRedis.php';
require './connectElastic.php';

$JsonUwsgi = file_get_contents("json/uwsgi.json");
$JsonNginx = file_get_contents("json/nginx.json");

function setDataRedis($redis, $nameKey, $json)
{
  $redis->set($nameKey, $json);
  return true;
};

function getDataRedis($redis, $nameKey)
{
  $getDataJson = $redis->get($nameKey);
  $getDataJson = json_decode($getDataJson);

  return $getDataJson;
};

function removeDataRedis($redis, $nameKey)
{
  $redis->del($nameKey);
  return true;
};

function setDataUwsgi($clientElastic, $redis, $connDB, $JsonFile)
{
  $setRedis = setDataRedis($redis, 'uwsgi', $JsonFile);
  if ($setRedis) {
    $dataRedis = getDataRedis($redis, 'uwsgi');
    echo "get data from redis succesfully \n";
  };

  echo "start looping to insert data.. \n";
  foreach ($dataRedis as $data) {
    $address_space_usage = $data->address_space_usage;
    $address_space_used = $data->address_space_used;
    $address_space_size = $data->address_space_size;
    $rss_usage = $data->rss_usage;
    $rss_used = $data->rss_used;
    $rss_size = $data->rss_size;
    $pid = $data->pid;
    $app = $data->app;
    $req = $data->req;

    var_dump($rss_used);

    $sql_insert = "INSERT INTO uwsgi_log(address_space_usage, address_space_used, address_space_size, 
        rss_usage, rss_used, rss_size, pid, app, req) 
        VALUES ('$address_space_usage', '$address_space_used', '$address_space_size', 
        '$rss_usage', '$rss_used', '$rss_size', '$pid', '$app', '$req')";

    echo "insert to Mysql.. \n";
    setDataMysql($connDB, $sql_insert);
    // var_dump($data);

    echo "insert to Elastic... \n";
    $elasticResponse = indexingToElastic($clientElastic, $data, 'log_uwsgi_final');
    // print_r($elasticResponse);
    unset($elasticResponse);
  }


  if (removeDataRedis($redis, 'uwsgi')) {
    echo "Data from redis deleted successfully.. \n";
  };
}





function setDataNginx($redis, $connDB, $JsonNginx)
{
  $setRedis = setDataRedis($redis, 'nginx', $JsonNginx);
  if ($setRedis) {
    echo "set data redis berhasi \n";

    $dataRedis = getDataRedis($redis, 'nginx');
    echo "get data redis berhasil \n";
  };

  foreach ($dataRedis as $data) {
    $remote_address = $data->remote_address;
    $remote_user = $data->remote_user;
    $remote = $data->remote_;
    $time_local = $data->time_local;
    $request = $data->request;
    $status = $data->status;
    $body_bytes_sent = $data->body_bytes_sent;
    $http_referer = $data->http_referer;
    $rt = $data->rt;
    $uct = $data->uct;
    $uht = $data->uht;
    $urt = $data->urt;
    $gz = $data->gz;

    $sql_insert = "INSERT INTO nginx_log(remote_address,remote_user, remote, 
        time_local,request,status,body_bytes_sent, 
        http_referer, rt, uct, uht, urt, gz) 
        VALUES ('$remote_address', '$remote_user', '$remote', '$time_local',
        '$request','$status','$body_bytes_sent', 
        '$http_referer', '$rt', '$uct', '$uht', '$urt', '$gz')";

    setDataMysql($connDB, $sql_insert);
  }
  if (removeDataRedis($redis, 'nginx')) {
    echo "data berhasil dihapus dari redis";
  };
};

setDataUwsgi($clientElastic, $redis, $connDB, $JsonUwsgi);
// setDataNginx($redis, $connDB, $JsonNginx);

$connDB()->close();

// setDataRedis($redis,'uwsgi',$JsonUwsgi);
