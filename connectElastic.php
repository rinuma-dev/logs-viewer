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
            print_r($response);
            $params = array();
            unset($response);
        }
        $index+= $index; 
        
        // print_r($response);
    };
}
//     function getIndex($client)
//     {
//         $searchParams = [];
//         $searchParams = [];
//         $searchParams['index'] = 'log_uwsgi';
//         $searchParams['type'] = 'log_uwsgi';
//         // this is how you specify a query in ES
//         $searchParams['body']['query']['match']['_all'] = 'my_query';
//         $searchParams['body']['sort'] = ['_score'];
//         // the actual query. Results are stored in a PHP array
//         $retDoc = $client->search($searchParams);
//     }
// };
indexingUwsgiElastic($client, $JsonUwsgi);
// getIndex($client);