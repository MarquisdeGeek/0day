<?php
header('Access-Control-Allow-Origin: *');

include "zeroday.inc";

class LondonTubeAPI extends ZeroDayApi {

	public function __construct($params) {	
		parent::__construct($params);
		
		$this->searchName = $this->getParam('name');
		$this->searchZone = $this->getParam('zone');
		$this->searchLong = $this->getParam(array('long', 'longitude'));
		$this->searchLat = $this->getParam(array('lat', 'latitude'));
		$this->searchDistance = $this->getParam('distance');
	}
	
	public function isFieldNumericallySortable($fileName) {
		if ($fileName === 'name' || $fileName === 'zone') {
			return true;
		}
		
		return false;
	}
	
	protected function isFound(&$searchRow) {
			$found = true;
			
			if (isset($this->searchName)) {
				$found = strstr($searchRow[0], $this->searchName) ? $found : false;
			}

			if (isset($this->searchZone)) {
				$found = strstr($searchRow[5], $this->searchZone) ? $found : false;
			}
			
			if ((isset($this->searchLong) || isset($this->searchLat)) && isset($this->searchDistance)) {
				// Consider the case where long (or lat) is ignored
				$latCheck = isset($this->searchLat) ? $this->searchLat : $searchRow[3];
				$longCheck = isset($this->searchLong) ? $this->searchLong : $searchRow[4];
				
				$distance = distance($searchRow[3], $searchRow[4], $latCheck, $longCheck);
				$found = ($distance < $this->searchDistance) ? $found : false;
				$searchRow['results'] = array('distance' => $distance);
			}

		return $found;
	}
	
	protected function getRecordData($s) {
		return array(
			'name' => $s[0],
			'zone' => $s[5],
			'latitude' => $s[3],
			'longitude' => $s[4],
			//
			'results' => isset($s['results']) ? $s['results'] : ''
		);
	}
};


$api = new LondonTubeAPI($_GET);
$api->setDataSetJSON("stations.js");
$result = $api->getResults();

print_r(json_encode($result));

?>