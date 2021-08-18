<?php

use Elasticsearch\ClientBuilder;

require 'vendor/autoload.php';
require './connectRedis.php';

$JsonUwsgi = file_get_contents("json/uwsgi.json");
$JsonUwsgi = json_decode($JsonUwsgi, true);

$client = ClientBuilder::create()->build();

function indexingUwsgiElastic($client, $json)
{
    $index= 0;
    $params = array('body'=>array());
    foreach ($json as $data) {
        // var_dump($data);
        // var_dump($data['rss']);
        $params['body'][] = array(
            'index' => array(
                '_index'=>'log_uwsgi',
                '_type'=>'log_uwsgi',
                '_id'=>$data['id']
            )
        );

        $params['body'][]=array(
            'address_space_usage'=>$data['address_space_usage'],
            'address_space' =>$data['address_space'],
            'rss_usage'=>$data['rss_usage'],
            'rss'=>$data['rss'],
            'pid'=>$data['pid']
        );
        if($index % 100 === 0){
            $response = $client->bulk($params);
            // print_r($response);
            $params = array();
            unset($response);
        }
        $index+= $index; 
        
        // print_r($response);
    };
}

function getDataByid( $client, $index, $type, $id){
    $params['index'] = $index;
    $params['type'] = $type;
    $params['id'] = $id; 

    $result = $client->get($params);
    // var_dump($result);
    return $result;
};

function getDatabySize($client, $index, $type, $size){
    $params = array();
    $params['index'] = $index;
    $params['type'] = $type;
    $params['size'] = $size;
    $params['body']['query']['match_all']= new \stdClass();
    
    $result = $client->search($params);
    var_dump($result);
    return $result;
};   


// indexingUwsgiElastic($client, $JsonUwsgi);
getDataByid($client,'log_uwsgi','log_uwsgi',405);
getDatabySize($client,'log_uwsgi','log_uwsgi',100);
