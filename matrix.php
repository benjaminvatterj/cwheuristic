<?php

class matrix{
	
	private $original_matrix;
	private $original_row_keys;
	private $original_column_keys;

	private $working_matrix;
	private $working_matrix_column_keys;
	private $working_matrix_row_keys;

	function matrix($bidemnsional_array){
		$this->original_matrix = $bidemnsional_array;
		$this->original_row_keys = array_keys($bidemnsional_array);
		$this->original_column_keys = array_keys($bidemnsional_array[$this->original_row_keys[0]]);
		$this->working_matrix = $bidemnsional_array;
	}

	function getMatrix(){return $this->working_matrix;}
	function getRowKeys(){
		$this->refreshWorking();
		return $this->working_matrix_row_keys;}
	function getColumnKeys(){
		$this->refreshWorking();
		return $this->working_matrix_column_keys;}
	function getOriginal(){return $this->original_matrix;}

	function refreshWorking(){
		$this->working_matrix_row_keys = array_keys($this->working_matrix);
		if(count($this->working_matrix_row_keys)<1){$this->working_matrix_column_keys=Array();}
		else{ $this->working_matrix_column_keys = array_keys($this->working_matrix[$this->working_matrix_row_keys[0]]); }
	}

	function deleteRow($key){
		$this->refreshWorking();
		if(in_array($key, $this->working_matrix_row_keys)){
			unset($this->working_matrix[$key]);
			$this->refreshWorking();
			return true;
		}
		else return false;
	}

	function deleteColumn($key){
		$this->refreshWorking();
		if(!in_array($key,$this->working_matrix_column_keys)) return false;
		foreach($this->working_matrix as $row=>$val){
			unset($this->working_matrix[$row][$key]);
		}
		$this->refreshWorking();
		return true;
	}

	function getRowCardinal($key){
		$this->refreshWorking();
		$sum=0;
		if(!in_array($key, $this->working_matrix_row_keys)) return false;
		foreach ($this->working_matrix[$key] as $col_key => $value) {
			$sum += ($value==0 || $value=='' ) ? 0 : 1 ;
		}
		return $sum;
	}

	function getColumnCardinal($key){
		$this->refreshWorking();
		if(!in_array($key,$this->working_matrix_column_keys)) return false;
		$sum = 0;
		foreach ($this->working_matrix as $row_key => $row) {
			$sum += ($row[$key]==0 || $row[$key]=='') ? 0 : 1;
		}
		return $sum;
	}

	function getFirstPositiveRow($col_key,$ignore=false){
		$this->refreshWorking();
		if(!in_array($col_key,$this->working_matrix_column_keys)) return false;
		foreach ($this->working_matrix as $row_key => $row) {
			if(is_array($ignore)){ if(in_array($row_key,$ignore)){ continue; }}
			if($row[$col_key]==1) return $row_key;
		}
		return false;
	}

	function getFirstPositiveColumn($row_key, $ignore=false){
		$this->refreshWorking();
		if(!in_array($row_key, $this->working_matrix_row_keys)) return false;
		foreach ($this->working_matrix[$row_key] as $col_key => $value) {
			if(is_array($ignore)){ if(in_array($col_key,$ignore)){ continue; }}
			if($value==1) return $col_key;
		}
		return false;

	}

	//basic necesarry condition for solution but no sufficient
	function possible(){
		$this->refreshWorking();
		if(count($this->working_matrix_column_keys)>=count($this->working_matrix_row_keys)) return true;
		else return false;
	}

	function maximumIterations(){
		$this->refreshWorking();
		$column_size = count($this->working_matrix_column_keys);
		$factor = 1;
		for($i=$column_size; $i>1; $i--){
			$factor *= $i;
		}
		return $factor;
	}



}



?>