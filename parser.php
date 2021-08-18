<?php
$pattern = '/^([^ ]+) ([^ ]+) ([^ ]+) (\[[^\]]+\]) (.*) (.*) (.*) (.*) (.*) (.*) (.*) (.*) (.*)/';
$myfile = fopen("./data_log/nginx.log", "r") or die("Unable to open file!");
$keys = array ('remote_address', 'remote_user', 'remote_', 'time_local',
                'request','status','body_bytes_sent','http_referer', 'rt',
                'uct', 'uht', 'urt', 'gz');


$results=array();
$indexOfResults = 0;

function getResultMatch($pattern,$line){
    if (preg_match($pattern,$line,$matches)){
        unset($matches[0]);
        return $matches;
    };
};

function map_trim_quote($data){
    $data = trim(html_entity_decode($data),'"');
    return $data;
}

function cleanstring($data){
    $data[9]=substr($data[9], 3);
    $data[10]=substr($data[10], 4);
    $data[11]=substr($data[11], 4);
    $data[12]=substr($data[12], 4);
    $data[13]=substr($data[13], 3);
    return $data;

}


while(! feof($myfile)){
    $line = fgets($myfile);
    
	if (preg_match($pattern, $line, $matches)){
        
        unset($matches[0]);
        $matches = array_map('trim', $matches);
        $matches = cleanstring($matches);
        $matches = array_map('map_trim_quote', $matches);
        $result = array_combine($keys,$matches);
    };

    //store result on Results
    // $result['id'] = $indexOfResults;
    $results[$indexOfResults] = $result;
    
    // var_dump( $results);
    $indexOfResults++;

};
//json encode
$json_results = json_encode($results, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
file_put_contents('./json/nginx.json', $json_results);

fclose($myfile);
?>