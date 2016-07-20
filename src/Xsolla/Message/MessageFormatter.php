<?php

namespace Xsolla\Message {
	interface MessageFormatter {
		public function encode($row);
		public function decode($str);
	}
}