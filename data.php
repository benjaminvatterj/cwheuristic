<?php
/*
this file contains all the data load for the problem, please read matrixcw.php for full detail



 The following are the data required for the problem to run and the structure of each element

	//list of nodes of the clients to be visited during the day
	$client_nodes = array();
	//number of clients
	$clients = count($client_nodes,0);
	//list of product demand by client
	$client_demands = array(
		'client_number'=>array(
			'total_weight_of_order'=>'weight',
			'total_volume_of_order'=>'volume',
			'client_type'=> 'N/M/S/C')
		);

	//bidimensional array of distances for node a->b and b->a for all nodes. 0 has to be the base
	$distance_matrix = array(
		'node_a' => array(
			'node_a' => 0,
			'every_other_node' => 'value'),
		'node_b' => array(
			'node_b'=>0,
			'every_other_node'=>'value')
			);
	// array of the capacities of each truck
	$truck_capacity = array(
		0=>array('weight'=>'weight_cap', 'volume'=>'volume_cap'),
		1=>array('weight'=>'weight_cap', 'volume'=>'volume_cap')
		);

*/

/*--data load--*/

//client data
$clients_csv = fopen('test_data/clientes.csv','r');
$headers = fgetcsv($clients_csv);
$duplicate_nodes = Array();
$client_demands = Array();
while(!feof($clients_csv)){
	$values = fgetcsv($clients_csv);
	$client_node = preg_replace('/[^0-9]/','',$values[0]);
	if(isset($client_demands[$client_node])){ $duplicate_nodes[] = $values; continue; }
	$type = $values[1];
	$weight = preg_replace('/[^0-9.]/','',$values[2]);
	$volume = preg_replace('/[^0-9.]/','',$values[3]);
	$client_demands[$client_node] = array(
		'total_weight_of_order'=>$weight,
		'total_volume_of_order'=>$volume,
		'client_type'=>$type);
}
fclose($clients_csv);


//distance matrix -- split in two beacuse the site dosen't allow upload greater then 500kb
$distance_matrix = Array();
$distance_csv = fopen('test_data/distance1.csv','r');
$headers = explode(';',fgets($distance_csv));
foreach($headers as $key=>$val){
	if($key==0)continue; //skip 0,0 point of the table
	$distance_matrix['val'] = array();
}
while(!feof($distance_csv)){
	$values = explode(';',fgets($distance_csv));
	$i = $values[0];
	for($j=1 ; $j<count($values) ; $j++){
		$distance_matrix[$i][$headers[$j]] = $values[$j];
	}
}
fclose($distance_csv);
$distance_csv = fopen('test_data/distance2.csv','r');
while(!feof($distance_csv)){
	$values = explode(';',fgets($distance_csv));
	$i = $values[0];
	for($j=1 ; $j<count($values) ; $j++){
		$distance_matrix[$i][$headers[$j]] = $values[$j];
	}
}
fclose($distance_csv);

//truck data
$truck_csv = fopen('test_data/trucksn.csv','r');
$headers = fgetcsv($truck_csv);
while(!feof($truck_csv)){
	$values = fgetcsv($truck_csv);
	$truck = preg_replace('/[^0-9]/','',$values[0]);
	$weight = preg_replace('/[^0-9.]/','',$values[1]);
	$volume = preg_replace('/[^0-9.]/','',$values[2]);
	$truck_capacity[$truck] = array('weight'=>$weight,'volume'=>$volume);
}
fclose($truck_csv);

//fix duplicate clientes
$keys = array_keys($client_demands);
$last_client_id = end($keys);
reset($client_demands);
$n=1;
$conversion = Array();
foreach($duplicate_nodes as $values){
	$client_node = preg_replace('/[^0-9]/','',$values[0]);
	$conversion[] = array($client_node,$last_client_id+$n);
	$type = $values[1];
	$weight = preg_replace('/[^0-9.]/','',$values[2]);
	$volume = preg_replace('/[^0-9.]/','',$values[3]);
	$client_demands[$last_client_id+$n] = array(
		'total_weight_of_order'=>$weight,
		'total_volume_of_order'=>$volume,
		'client_type'=>$type);
	foreach($distance_matrix as $row_key=>$col){
		if(!isset($distance_matrix[$row_key][(int)$client_node]))continue;
		$distance_matrix[$row_key][$last_client_id+$n] = $distance_matrix[$row_key][$client_node];
	}
	$distance_matrix[$last_client_id+$n] = $distance_matrix[$client_node];
	$n++;
}

$client_nodes = array_keys($client_demands);

//uncomment to see result
/*
print_r($client_nodes);
echo"<br><br>";
$to = array_keys($distance_matrix[$row_key]);
?>
<table>
	<tr>
		<td>/</td>
		<? foreach($to as $header):?>
			<td><?=$header?></td>
		<? endforeach;?>
	</tr>
	<? foreach($distance_matrix as $key=>$row):?>
	<tr>
		<td><?=$key?></td>
		<? foreach($row as $dist):?>
		<td><?=$dist?></td>
		<? endforeach;?>
	<tr>
	<? endforeach;?>
<table>
<?
die();
*/

?>
