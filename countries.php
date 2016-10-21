<?php
header('Access-Control-Allow-Origin: *');

include "zeroday.inc";

class CountryCodeAPI extends ZeroDayApi {

	public function __construct($params) {	
		parent::__construct($params);
		
		$this->searchID = $this->getParam('id');
		$this->searchName = $this->getParam('name');
		$this->searchNameClosest = $this->getParam('closest');
		if (isset($this->searchNameClosest) && empty($this->searchNameClosest)) {
			$this->searchNameClosest = 3;
		}
	}
	
	protected function isFound(&$searchRow) {
		$found = true;
		
		if (isset($this->searchID)) {
			$found = strcasecmp($searchRow->id, $this->searchID) == 0 ? $found : false;
		}
		
		if (isset($this->searchName)) {
			$found = strcasecmp($searchRow->name, $this->searchName) == 0 ? $found : false;
			if (!$found && $this->searchNameClosest) {
				$lev = levenshtein(strtolower($this->searchName), strtolower($searchRow->name));
				if ($lev < $this->searchNameClosest) {
					$found = true;
				}

			}
		}
		
		return $found;
	}
		
	protected function getRecordData($s) {

		return array(
			'id' => $s->id,
			'name' => isset($s->name) ? $s->name : '',
			'accuracy' => levenshtein(strtolower($this->searchName), strtolower($s->name))
		);
	}
};


$api = new CountryCodeAPI($_GET);
$api->setDataSetJSON("countries.js");

$result = $api->getResults();

print_r(json_encode($result));

?>