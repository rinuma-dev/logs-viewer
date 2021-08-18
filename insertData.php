<?php
require './vendor/autoload.php';
require './connect.php';
require './connectRedis.php';
require './connectElastic.php';

function setDataRedis($redis, $nameKey, $json)
{
  $redis->set($nameKey, $json);
  return true;
};

function getDataRedis($redis, $nameKey)
{
  // $redis = new Predis\Client();
  $getDataJson = $redis->get($nameKey);
  $getDataJson = json_decode($getDataJson);

  return $getDataJson;
};

function removeDataRedis($redis, $nameKey)
{
  $redis->del($nameKey);
  return true;
};

function setDataMysql($connDB, $sql)
{
  $connDB->query($sql);
  return true;
};

function setDataUwsgi($client,$redis, $connDB, $JsonFile)
{
  $setRedis = setDataRedis($redis, 'uwsgi', $JsonFile);
  if ($setRedis) {
    $dataRedis = getDataRedis($redis, 'uwsgi');
    echo "get data redis berhasil";
  };
  
  foreach ($dataRedis as $data) {
    $address_space_usage = $data->address_space_usage;
    $address_space = $data->address_space;
    $rss_usage = $data->rss_usage;
    $rss = $data->rss;
    $pid = $data->pid;
    $app = $data->app;
    $req = $data->req;

    $sql_insert = "INSERT INTO uwsgi_log(address_space_usage,address_space,
        rss_usage,rss,pid,app,req) VALUES ('$address_space_usage', '$address_space',
       '$rss_usage', '$rss', '$pid', '$app', '$req')";

    setDataMysql($connDB, $sql_insert);
    indexingUwsgiElastic($client,$data);
  }

  if(removeDataRedis($redis, 'uwsgi')){
    echo "data berhasil dihapus dari redis";
  };
};


function setDataNginx($redis, $connDB, $JsonNginx)
{
  $setRedis = setDataRedis($redis, 'nginx', $JsonNginx);
  if ($setRedis) {
    echo "set data redis berhasi <br>";

    $dataRedis = getDataRedis($redis, 'nginx');
    echo "get data redis berhasil<br>";
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
  if (removeDataRedis($redis, 'nginx')){
    echo "data berhasil dihapus dari redis";
  };
};

setDataUwsgi($client,$redis, $connDB, $JsonUwsgi);
setDataNginx($redis, $connDB, $JsonNginx);

$connDB()->close();

