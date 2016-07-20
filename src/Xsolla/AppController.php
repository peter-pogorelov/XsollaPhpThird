<?php

namespace Xsolla {
	use \Symfony\Component\HttpFoundation\Request as Request;
	use \Symfony\Component\HttpFoundation\Response as Response;
	
	class AppController {
		private $table = null;
		
		//Not optimal way of manipulating data because cache is reloaded
		//each time page reloads. Better do it when server starts.
		public function __construct($storage_type = 'csv') {
			$this->table = new CSVTable();
			$this->table->load();
		
			MessageConverter::instance()->registerFormatter('application/json', new MessageJSON());
			MessageConverter::instance()->registerFormatter('application/xml', new MessageXML());
		}
		
		public function __destruct() {
			$this->table->save();
		}
		
		public function getRows(Request $request){
			$content_type = $request->headers->get('Content-Type');
			$formatter = MessageConverter::instance()->getFormatter($content_type);
			if($formatter != null)
				return new Response($formatter->encode($this->table->getRows()), 200);
			else
				return new Response('This content type is not supported!', 500);
		}
		
		public function addRow(Request $request) {
			$content_type = $request->headers->get('Content-Type');
			$formatter = MessageConverter::instance()->getFormatter($content_type);
			if($formatter != null)
			{
				$row = $formatter->decode($request->getContent());
				$this->table->addRow($row);
				return new Response('Successfully added', 201);
			} else
				return new Response('This content type is not supported!', 500);
		}
		
		public function getRow(Request $request, $offset) {
			$content_type = $request->headers->get('Content-Type');
			$formatter = MessageConverter::instance()->getFormatter($content_type);
			if($formatter != null)
			{
				return new Response($formatter->encode(array(
						$this->table->getHeaders(),
						$this->table->getRow($offset)
					)), 200);
			} else
				return new Response('This content type is not supported!', 500);
		}
		
		public function updateRow(Request $request, $offset) {
			$content_type = $request->headers->get('Content-Type');
			$formatter = MessageConverter::instance()->getFormatter($content_type);
			if($formatter != null)
			{
				$row = $formatter->decode($request->getContent());
				$result = $this->table->updateRow($offset, $row);
				return new Response('', $result ? 201 : 500);
			} else
				return new Response('This content type is not supported!', 500);
		}
		
		public function deleteRow(Request $request, $offset) {
			$this->table->deleteRow($offset);
			return new Response('', 200);
		}
	}
}