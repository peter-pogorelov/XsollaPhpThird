<?php

namespace Xsolla {
	interface MessageFormatter {
		public function encode($row);
		public function decode($str);
	}
}