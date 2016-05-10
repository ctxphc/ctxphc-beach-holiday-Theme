<?php
/**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 11/23/2015
 * Time: 4:05 PM
 */

namespace CTXPHC\BeachHoliday\Classes;


class cmRegProc {
	public $states = array();
	public $relationships = array();
	public $memb_cost;
	public $memb_info;
	public $memb_type;

	public function __construct( $states_arr, $relationship_arr, $memb_costs, $memb_info  ) {
		$this->memb_costs = $memb_costs;
		$this->memb_info = $memb_info;
		$this->relationships = $relationship_arr;
		$this->states = $states_arr;
	}

	public function getPrices() {
		foreach ( $this->memb_costs as $mc_key => $mc_val ){
			$this->memb_cost[ $mc_key] = $mc_val;
		}

		return $this->memb_cost;
	}

	public function getMembTypes() {
		foreach ( $this->relationships as $mr_key => $mr_val ){
			$this->memb_types[ $mr_key ] = $mr_val;
		}

		return $this->memb_types;
	}
	public function getRelationshipTypes( $table ) {

	}

}