<?php

class ZeroDayApi {
var $paramlist;

	public function __construct($params) {
		$this->paramlist = $params;
		
		$this->sortBy = $this->getParam('sortby');
		$this->sortOrder = $this->getParam('sortorder');
		
		$this->outLimit = $this->getParam('limit');
	}

	public function getParam($name) {
		if (is_array($name)) {
			foreach($name as $n) {
				if (isset($this->paramlist[$n])) {
					return $this->paramlist[$n];
				}
			}
			return null;
		}
		return @$this->paramlist[$name];
	}
	
	public function setDataSetJSON($datafile) {
		$this->data = json_decode(file_get_contents($datafile));
	}
	
	public function getResults() {
	
		$results = $this->getSearchResults();
		$this->sortResults($results);

		if ($this->outLimit) {
			$results = array_splice($results, 0, $this->outLimit);
		}
		
		return $results;
	}
	
	protected function getSearchResults() {
		$results = array();
		foreach($this->data as $s) {
			if ($this->isFound($s)) {
				array_push($results, $this->getRecordData($s));
			}
		}
		
		return $results;	
	}
	
	public function sortResults(&$results) {
		if (isset($this->sortBy) || isset($this->sortOrder)) {
		
			if (!isset($results[0][$this->sortBy])) {
				// TODO: Report error : no such field for sortBy
				return;
			}
			
			$props = array( 
				'mul' => $this->sortOrder === "desc" ? -1 : 1, 
				'field' => $this->sortBy ? $this->sortBy : 'name',
				'stringcompare' => false
			);
			
			if ($this->isFieldNumericallySortable($props['field']))  {
				$props['stringcompare'] = true;
			}
				
			usort($results, function($a, $b) use ($props) {
				@$p1 = $a['results'][$props['field']];

				if (!empty($p1)) {
					$p2 = $b['results'][$props['field']];
				} else {
					$p1 = $a[$props['field']];
					$p2 = $b[$props['field']];
				}

				if ($props['stringcompare']) {
					return $props['mul'] * strcmp($p1, $p2);
				}				

				if ($p1 == $p2) {
					return 0;
				}
				return $props['mul'] * (floatval($p1) < floatval($p2) ? -1 : 1);
			});
		}
	}
	
	protected function isFound(&$searchRow) {
		return true;
	}
	
	public function isFieldNumericallySortable($fileName) {
		return false;
	}
	
	protected function getRecordData($s) {
		return array(
			'name' => $s[0],
			//
			'results' => isset($s['results']) ? $s['results'] : ''
		);
	}
		
}


//
// Utility methods
//
function distance($lat1, $lon1, $lat2, $lon2) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;

  return ($miles * 1.609344 * 1000);
}

?>