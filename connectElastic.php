<?php

use Elasticsearch\ClientBuilder;

require 'vendor/autoload.php';
require './connectRedis.php';

$JsonUwsgi = file_get_contents("json/uwsgi.json");
$JsonUwsgi = json_decode($JsonUwsgi, true);

$clientElastic = ClientBuilder::create()->build();

function indexingJsonElastic($client, $json)
{
    $index = 0;
    $params = array('body' => array());
    foreach ($json as $data) {
        echo "start function";
        
        // var_dump($data['rss']);
        $params['body'][] = array(
            'index' => array(
                '_index' => 'log_uwsgi_3',
                '_type' => 'log_uwsgi_3',
                '_id' => $data['id']
            )
        );

        $params['body'][] = array(
            'address_space_usage' => $data['address_space_usage'],
            'address_space' => $data['address_space'],
            'rss_usage' => $data['rss_usage'],
            'rss' => $data['rss'],
            'pid' => $data['pid']
        );
        if ($index % 100 === 0) {
            echo "params on bulk";
            // var_dump($params);
            $response = $client->bulk($params);
            // print_r($response);
            $params = array();
            unset($response);
        }
        $index += $index;

        // print_r($response);
    };
    // var_dump($params);
}

function indexingToElastic($clientElastic,$data,$nameIndex)
{
    
    // $params = array('body' => array());
    $params['body'][] = array(
        'index' => array(
            '_index' => $nameIndex,
            '_type' => $nameIndex,
            '_id' => $data->id
        )
    );

    $params['body'][] = array(
        'address_space_usage' => $data->address_space_usage,
        'address_space_used' => $data->address_space_used,
        'address_space_size' => $data->address_space_size,
        'rss_usage' => $data->rss_usage,
        'rss_used' => $data->rss_used,
        'rss_size' => $data->rss_size,
        'pid' => $data->pid
    );
    $response = $clientElastic->bulk($params);
    return $response;
};

function getDataByid($client, $index, $type, $id)
{
    $params['index'] = $index;
    $params['type'] = $type;
    $params['id'] = $id;

    $result = $client->get($params);
    // var_dump($result);
    return $result;
};

function getDatabySize($client, $index, $type, $size)
{
    $params = array();
    $params['index'] = $index;
    $params['type'] = $type;
    $params['size'] = $size;
    $params['body']['query']['match_all'] = new \stdClass();

    $result = $client->search($params);
    var_dump($result);
    return $result;
};   


// indexingUwsgiElastic($clientElastic, $JsonUwsgi);
// getDataByid($client,'log_uwsgi','log_uwsgi',405);
// getDatabySize($client,'log_uwsgi','log_uwsgi',100);
