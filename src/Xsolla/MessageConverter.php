<?php

namespace Xsolla {
	class MessageConverter {
		private static $inst = null;
		private $formatters = array();
		
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