<?php

namespace Xsolla\Message {
	
	//This class provides easy access to decode and encode messages with various formats
	class MessageConverter {
		private static $inst = null;
		private $formatters = array();
		
		private function __construct() {
			$this->registerFormatter('application/json', new MessageJSON());
			$this->registerFormatter('application/xml', new MessageXML());
		}
		
		public static function instance() {
			if(is_null(self::$inst)) {
				self::$inst = new MessageConverter();
			}
			
			return self::$inst;
		}
		
		public function registerFormatter($name, MessageFormatter $formatter) {
			$this->formatters[$name] = $formatter;
		}
		
		public function getFormatter($type) {
			return $this->formatters[$type];
		}
	}
}