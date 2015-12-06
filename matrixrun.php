
<?php
/*
run execution of cw model
 */
set_time_limit(240);
ini_set('error_log','error_log.txt');
include('data.php');
include('matrixcw.php');




$time_start = microtime(true); 
$cw = new cw();
$cw->loadData($client_nodes,$client_demands,$distance_matrix,$truck_capacity);
$cw->cwSavings();
$cw->sortSavings();
$cw->cwroutes();
$cw->finishingTouch();
$time_end = microtime(true);
echo "<h1>Clarke & Wright savings Hueristics with assymetric costs and time windows</h1><br>";
foreach ($cw->routes as $key => $value) {
	echo "<h2>Route number {$key}</h2><br>";
	echo "<b>Truck: </b>".$value['truck']."<br>";
	echo "<b>Path: </b>".implode('->',$value['road'])."<br>";
	echo "<b>Total Distance of Path: </b>".$value['total_distance']."<br>";
	echo "<b>Total Time of Path: </b>".$value['total_time']."<br>";
	echo "<b>Time back at depot: </b>".$value['time_back_at_depot']."<br>";
	echo "<br><br>";
}
if(count($cw->unsatisfied)>1){
echo "The following clients weren't visited : <br>";
?>
	<table>
		<thead>
			<th>Client</th><th>Reason</th>
		</thead>
		<tbody>
<?php foreach($cw->unsatisfied as $client=>$reason){ ?>
			<tr><td><?=$client?></td><td><?=$reason?></td></tr>
<?php } ?>
		</tbody>
	<table>
		<br>
<?php
}
else echo "all clients were visited! <br>";
echo "The total cost for the routing problem is : ".$cw->total_cost." seconds <br>";
echo "The total excution time of the Hueristics was : ".($time_end-$time_start)." seconds "."<br>";
echo "The total reassign cost of the process was : ".$cw->reassing_cost."<br>"."<br>";
if(count($conversion)>0){
		echo "the conversion for duplicate nodes was as follow : <br>";
		foreach ($conversion as $key => $value) {
			list($from,$to) = $value;
			$num = $key+2;
			echo "$from -> $to <br>";
		}
	}
?>