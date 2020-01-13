<?php

$pdo = new PDO('sqlsrv:server=tcp:40179436dbserver.database.windows.net,1433;Database=searchEngine','dbmanager40179436','ySLH33CI8');

$search = $_GET['q'];

$searche = explode(" ", $search);

$x =0;
$construct = "";
$params = array();

foreach ($searche as $term) {
	$x++;
	if ($x == 1) {
		$construct .= "title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%')";
	} else {
		$construct .= "AND title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%')";
	}
	$params["search$x"] = $term;
}

$results = $pdo->prepare("SELECT * FROM 'advert' WHERE $construct");
$results->execute($params);


if ($results->rowCount() == 0) {
	echo "0 results found! <hr />";
} else {
	echo $results->rowCount()." results found! <hr />";
}

//echo "<pre>";
foreach ($results->fetchAll() as $result) {
	echo $result["title"]."<br />";
	if ($result["description"] == "") {
		echo "No description available."."<br />";
	} else {
	echo $result["description"]."<br />";
	}
	echo $result["url"]."<br />";
	echo "<hr />";
}
//print_r($results->fetchAll());
