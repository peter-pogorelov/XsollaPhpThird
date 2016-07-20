<?php
namespace Xsolla\Storage
{
	class Storage {
		private static $inst = null;
		private $formatters = array();
		
		private function __construct() {
			$this->registerFactory('csv', new CSVStorageFactory());
			$this->registerFactory('sql', new SQLStorageFactory());
		}
		
		public static function instance() {
			if(is_null(self::$inst)) {
				self::$inst = new Storage();
			}
			
			return self::$inst;
		}
		
		public function registerFactory($name, StorageFactory $formatter) {
			$this->formatters[$name] = $formatter;
		}
		
		public function getFactory($type) {
			return $this->formatters[$type];
		}
	}
}