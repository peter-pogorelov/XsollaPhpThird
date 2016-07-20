<?php
namespace Xsolla\Storage
{
	class CSVStorageFactory implements StorageFactory{
		public function createStorage() {
			return new \Xsolla\Model\CSVModel();
		}
	}
}