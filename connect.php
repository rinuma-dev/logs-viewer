<?php
$servername = "localhost";
$username = "root";
$password = "";
$DBname = "datalog";

$conn = new mysqli($servername, $username, $password);
$connDB = new mysqli($servername, $username, $password, $DBname);


function setDataMysql($connDB, $sql)
{
    if ($connDB->query($sql) === TRUE) {
        echo "set Data ke mysql berhasil dilakukan";
    }
};
