<?php
namespace Xsolla\Storage
{
	class SQLStorageFactory implements StorageFactory{
		public function createStorage() {
			return new \Xsolla\Model\SQLModel();
		}
	}
}