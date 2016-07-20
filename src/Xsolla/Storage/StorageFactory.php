<?php
namespace Xsolla\Storage
{
	interface StorageFactory{
		public function createStorage(); //return TableModel
	}
}