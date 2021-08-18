<?php
require './vendor/autoload.php';
require './connect.php';


$sql_uwsgi = "SELECT * FROM uwsgi_log LIMIT 100";
$logs_uwsgi = $connDB->query($sql_uwsgi);


$sql_nginx = "SELECT * FROM nginx_log LIMIT 100";
$logs_nginx = $connDB->query($sql_nginx);


function exportCSV(String $name, $data){
    $csvFilename = $name."_".date('Ymd'). ".csv";
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$csvFilename\"");	
    $newFileCsv = fopen( 'php://output', 'w' );
    
    $isColumn = true;
    if(!empty($data)){
        foreach($data as $line ){
            if($isColumn){
                fputcsv($newFileCsv, array_values($line));
                $isColumn = false;
            }
            fputcsv($newFileCsv, array_values($line));
        }
        fclose($newFileCsv);
    
}
exit;
}



if(isset($_POST["csvExport_uwsgi"])){
    exportCSV("uwsgi",$logs_uwsgi);
};

if(isset($_POST["csvExport_nginx"])){
    exportCSV("nginx",$logs_nginx);
};

?>