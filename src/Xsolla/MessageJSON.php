<?php

namespace Xsolla {
	class MessageJSON implements MessageFormatter {
		public function encode($row) {
			return json_encode($row);
		}
		
		public function decode($str) {
			return json_decode($str);
		}
	}
}