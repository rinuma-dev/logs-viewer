<?php
$pattern = '/([0-9\/]+)/';
$myfile = fopen("./data_log/uwsgi.log", "r") or die("Unable to open file!");
$keys = array ('address_space_usage','address_space', 'rss_usage',
'rss', 'pid','app','req');


$results=array();
$indexOfResults = 0;

function cleanDataUwsgi($data){
    $data[1] = str_replace("/","", $data[1]);
    $data[3] = str_replace("/","", $data[3]);
    return $data;
    
}

while(! feof($myfile)){
    $line = fgets($myfile);
	if (preg_match_all($pattern, $line, $matches)){

        unset($matches[0]);
        $matches_final = $matches[1];
        $matches_final = array_slice($matches_final,0, 7);
        $matches_final = cleanDataUwsgi($matches_final);

        if(!empty($matches_final) && count($matches_final) === count($keys) ){
            $result = array_combine($keys,$matches_final);
        }
        $result['id']=$indexOfResults; 
    };

    //store result on Results
  
    $results[$indexOfResults] = $result;
    
    $indexOfResults++;

};
//json encode
$json_results = json_encode($results, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
file_put_contents('./json/uwsgi.json', $json_results);


fclose($myfile);
?>