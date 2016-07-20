<?php

namespace Xsolla
{
	class CSVTable extends BasicTable
	{
		private $db_name = "./xsolla.csv";
		private $file_has_header = false; //If file should have header when loading from CSV
		
		public function __construct($file_has_header = true){
			$this->file_has_header = $file_has_header;
		}

		//Assuming that table is not so large because caching large table is not cool.
		//It's a good point to create class CSVtableNotCached where operations with
		//table is produced directly in .csv file without caching it, so we dont
		//need BasicTable class.
		public function load(){
			$file_handle = fopen($this->db_name, "r");
			
			if($file_handle != null){
				$firstRun = true;
				while(!feof($file_handle)){
					$row = fgetcsv($file_handle);
					if($row != false){
						if($firstRun){
							$this->setNumCol(count($row));
							if($this->file_has_header){
								$this->setHeader($row);
							}
							$firstRun = false;
						}
						else
							$this->addRow($row);
					}
				}
				
				fclose($file_handle);
				return true;
			}
			//file not loaded, assuming that we are using empty table
			return false;
		}
		
		public function save() {
			$tbl = $this->getTableRef();
			
			$file_handle = fopen($this->db_name, "w");
			foreach($tbl as $row) {
				fputcsv($file_handle, $row);
			}
			
			fflush($file_handle);
			fclose($file_handle);
		}
	}
}