<?php

namespace Xsolla {
	use \Symfony\Component\HttpFoundation\Request as Request;
	use \Symfony\Component\HttpFoundation\Response as Response;
	use Xsolla\Message\MessageConverter as MessageConverter;
	use Xsolla\Storage\Storage as Storage;
	
	class AppController {
		private $model = null;
		
		//Not optimal way of manipulating data because cache is reloaded
		//each time page reloads. Better do it when server starts.
		public function __construct($storage_type = 'csv') {
			$factory = Storage::instance()->getFactory($storage_type);
			if($factory != null){
				$this->model = $factory->createStorage();
				$this->model->load();
			}
			else {
				die('Not supported storage type!');
			}
		}
		
		public function __destruct() {
			$this->model->save();
		}
		
		public function getRows(Request $request){
			$content_type = $request->headers->get('Content-Type');
			$formatter = MessageConverter::instance()->getFormatter($content_type);
			if($formatter != null)
				return new Response($formatter->encode($this->model->getRows()), 200);
			else
				return new Response('This content type is not supported!', 500);
		}
		
		public function addRow(Request $request) {
			$content_type = $request->headers->get('Content-Type');
			$formatter = MessageConverter::instance()->getFormatter($content_type);
			if($formatter != null)
			{
				$row = $formatter->decode($request->getContent());
				$this->model->addRow($row);
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
						$this->model->getHeaders(),
						$this->model->getRow($offset)
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
				$result = $this->model->updateRow($offset, $row);
				return new Response('', $result ? 201 : 500);
			} else
				return new Response('This content type is not supported!', 500);
		}
		
		public function deleteRow(Request $request, $offset) {
			$this->model->deleteRow($offset);
			return new Response('', 200);
		}
	}
}