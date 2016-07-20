<?php

namespace Xsolla\Model
{
	abstract class CachedModelTable implements TableModel
	{
		private $table = array();
		private $has_header = false;
		private $ncols = 2; //Default number of columns
		
		private function isValidOffset($offset) {
			return ($offset >= 0 ) && (count($this->table) > $offset);
		}
		
		//not available through public API
		protected function setNumCol($numcol) {
			if($numcol > 0) {
				$this->ncols = $numcol;
				return true;
			}
			return false;
		}
		
		//not available through public API
		protected function setHeader($header){
			if(!$this->has_header){
				array_unshift($this->table, $header);
				$this->has_header = true;
				
				return true;
			}
			return false;
		}
		
		//not available through public API
		//and it is not actually important if
		//we dont care about speed of application
		//and how much memory it uses.
		protected function getTableRef(){
			return $this->table;
		}
		
		/**
		 * @return array
		 */
		public function getHeaders(){
			if($this->has_header){
				return $this->table[0];
			}
			
			return null; //null is kinda array
		}
		/**
		 * @return void
		 */
		public function addRow(array $row){
			if(count($row) == $this->ncols){
				array_push($this->table, $row);
			}
		}
		/**
		 * @param int $offset
		 * @param array $row
		 * @return boolean
		 */
		public function updateRow($offset, array $row){
			if($this->isValidOffset($offset)) {
				$this->table[$offset] = $row;
				return true;
			}
			
			return false;
		}
		/**
		 * @param int $offset
		 * @return array|null
		 */
		public function getRow($offset){
			if($this->isValidOffset($offset)) {
				return $this->table[$offset];
			}
			
			return null;
		}
		/**
		 * @param $offset integer
		 * @return boolean
		 */
		public function deleteRow($offset) {
			if($this->isValidOffset($offset)) {
				array_splice($this->table, $offset, 1); //amazing implementation of delete function
				return true;
			}
			
			return false;
		}
		/**
		 * Creating copy of a table, not passing it directly
		 * @return array
		 */
		public function getRows() {
			//copying table
			return array_map(function($elem) {
				return array_map(function($obj) {
					return $obj;
				}, $elem);
			}, $this->table);
		}
		/**
		 * @return int
		 */
		public function countRows() {
			return count($this->table);
		}
	}
}