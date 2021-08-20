<?php
$pattern = '/([0-9][0-9$MB]+)/';
//([0-9\/]+)
//'/([0-9][0-9$MB]+)/'
$file = fopen("./data_log/uwsgi.log", "r") or die("Unable to open file!");
$columns = array(
    'address_space_usage', 'address_space', 'rss_usage', 'rss', 'pid', 'app', 'req'
);


// polyfill the str_contains for PHP earlier version
function str_contains($haystack, $needle){
    return $needle !== '' && mb_strpos($haystack, $needle) !== false;
}

// to check string that contain specific $word 
function isContainWord($data, $word){
    if (str_contains($data, $word) === true) {
        return true;
    } else {
        return false;
    }
}

//set Object Length to length of column
function setObjectLength($matches, $columns){
    $columns_length = count($columns);

    if (isset($matches) && count($matches) > $columns_length) {
        $matches_final = array_slice($matches, 0, 7);
        return $matches_final;
    } else {
        echo "matches length lesser than columns \n";
    }
}

// to check regex with preg_match_all()
function pregMatch($pattern, $line){
    if (preg_match_all($pattern, $line, $matches)) {
        unset($matches[0]);
        $matches_final = $matches[1];
        return $matches_final;
    } else {
        echo "no match in this line \n";
    };
};


function setColumnArray($data, $columns){
    $result = array_combine($columns, $data);
    return $result;
}


function logToJson($file, $pattern, $columns){
    $results = array();
    $indexOfResults = 0;

    while (!feof($file)) {
        $line = fgets($file);
        $matches = pregMatch($pattern, $line);
        $matches = setObjectLength($matches, $columns);
        $matches_check = isContainWord($matches[1], 'MB');
       

        if ($matches_check === true) {
            $result = setColumnArray($matches, $columns);
            $result['id'] = $indexOfResults;
            $results[$indexOfResults] = $result;
            $indexOfResults++;
        }
    };

    //json encode
    $json_results = json_encode($results, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    file_put_contents('./json/uwsgi.json', $json_results);

    fclose($file);
}

logToJson($file, $pattern, $columns);
