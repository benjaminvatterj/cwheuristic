
<?php
/*
parametric run execution of cw model
 */

set_time_limit(2400);
ini_set('error_log','error_log.txt');
include('matrixcw.php');
include('data.php');




$time_start = microtime(true);
$min_unstisfied = count($client_nodes);
$saved_routes;
$saved_unstisfied;
$saved_total_cost;
$saved_reassign_cost;
$min_total_cost=0;
$max = 100;
for($n=0 ; $n<$max ; $n++){
	$cw = new cw();
	$cw->loadData($client_nodes,$client_demands,$distance_matrix,$truck_capacity);
	if($n==0){
		$cw->cwParametricSavings(1,0); //no parametric run case for comparison
		}
	else{
		$cw->cwParametricSavings();
		}
	$cw->sortSavings();
	$cw->cwroutes();
	$cw->finishingTouch();

	if(count($cw->unsatisfied)<$min_unstisfied || (count($cw->unsatisfied)==$min_unstisfied && $min_total_cost>$cw->total_cost)){
		$min_unstisfied = count($cw->unsatisfied);
		$min_total_cost = $cw->total_cost;
		$saved_routes = $cw->routes;
		$saved_total_cost = $cw->total_cost;
		$saved_unstisfied = $cw->unsatisfied;
		$saved_reassign_cost = $cw->reassing_cost;
	}

}
	$time_end = microtime(true);
	echo "<h1>Clarke & Wright savings Hueristics with assymetric costs and time windows : Parametric Run $max iterations</h1><br>";
	foreach ($saved_routes as $key => $value) {
		echo "<h2>Route number {$key}</h2><br>";
		echo "<b>Truck: </b>".$value['truck']."<br>";
		echo "<b>Path: </b>".implode('->',$value['road'])."<br>";
		echo "<b>Total Distance of Path: </b>".$value['total_distance']."<br>";
		echo "<b>Total Time of Path: </b>".$value['total_time']."<br>";
		echo "<b>Time back at depot: </b>".$value['time_back_at_depot']."<br>";
		echo "<b>Weight capacity of truck : </b>".$value['truck_max_weight']." Kg<br>";
		echo "<b>Total weight of order : </b>".$value['total_weight']." Kg<br>";
		echo "<b>Volume capacity of truck : </b>".$value['truck_max_volume']." M3<br>";
		echo "<b>Total volume of order : </b>".$value['total_volume']." M3<br>";
		echo "<br><br>";
	}
	if(count($saved_unstisfied)>1){
	echo "The following clients weren't visited : <br>";
	?>
		<table>
			<thead>
				<th>Client</th><th>Reason</th>
			</thead>
			<tbody>
	<?php foreach($saved_unstisfied as $client=>$reason){ ?>
				<tr><td><?=$client?></td><td><?=$reason?></td></tr>
	<?php } ?>
			</tbody>
		<table>
			<br>
	<?php
	}
	else echo "all clients were visite! <br>";
	echo "The total cost for the routing problem is : ".$saved_total_cost." seconds <br>";
	echo "The total excution time of the Hueristics was : ".($time_end-$time_start)." seconds "."<br>";
	echo "The total reassign cost of the process was : ".$saved_reassign_cost."<br>"."<br>";

	if(count($conversion)>0){
		echo "the conversion for duplicate nodes was as follow : <br>";
		foreach ($conversion as $key => $value) {
			list($from,$to) = $value;
			$num = $key+2;
			echo "$from -> $to <br>";
		}
	}