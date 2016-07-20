<?php

namespace Xsolla\Message {
	class MessageXML implements MessageFormatter {
		private function getNextToken($str, &$id) {
			$type = '';
			if(!strcmp(substr($str, $id, 1), '<')){
				while(strcmp(substr($str, ++$id, 1), '>')) {
					$type .= substr($str, $id, 1);
				}
				$id++;
				
				if(!strcmp($type, 'array'))
					return '<array>';
				if(!strcmp($type, 'element'))
					return '<element>';
				if(!strcmp($type, '/array'))
					return '</array>';
				if(!strcmp($type, '/element'))
					return '</element>';
				
				
				return '<error>';
			} else {
				while(strcmp(substr($str, $id, 1), '<')) {
					$type .= substr($str, $id, 1);
					$id++;
				}
				
				return $type;
			}
		}
		
		private function decodingLayer($str, &$id) {
			$res = null;
			$token = $this->getNextToken($str, $id);
			$len = strlen($str);
			if(!strcmp($token, "<array>")){
				$res = array();
				while($id < $len){
					$token = $this->getNextToken($str, $id);
					if(!strcmp($token, "</array>"))
						break;
					
					if(!strcmp($token, "<element>")) {
						$temp = $this->getNextToken($str, $id);
						if(!strcmp($temp, "</element>")){
							$res[] = '';
						} else if(strncmp($temp, '<', 1)) {
							$res[] = $temp;
						} else {
							return null;
						}
					}
					
					if(!strcmp($token, "<array>")) {
						$id -= strlen("<array>");
						$tmp = $this->decodingLayer($str, $id);
						if($tmp == null)
							return null;
						$res[] = $tmp;
					}
				}
			} else if(!strcmp($token, "<element>")) {
				$temp = $this->getNextToken($str, $id);
				if(!strcmp($temp, "</element>")){
					$res[] = '';
				} else if(strncmp($temp, '<', 1)) {
					$res[] = $temp;
				} else {
					return null;
				}
			} else {
				return null;
			}
			
			return $res;
		}
		
		public function encode($row) {
			$result = '';
			if(gettype($row) == 'array'){
				$result .= '<array>';
				foreach($row as $elem) {
					$res = $this->encode($elem);
					$result .= $res;
				}
				$result .= '</array>';
			} else {
				$result = '<element>' . strval($row) . '</element>';
			}
			
			return $result;
		}
		
		public function decode($str) {
			$id = 0;
			
			return $this->decodingLayer($str, $id);
		}
	}
}