<?php  

namespace Concrete\Package\D3RandomImage;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Package\Package;

class Controller extends Package 
{
	protected $pkgHandle = 'd3_random_image';
	protected $appVersionRequired = '8.4.4';
	protected $pkgVersion = '2.0.0';
	
	public function getPackageName() 
	{
		return t('Random Image');
	}	
	
	public function getPackageDescription() 
	{
		return t('Display a random image from a file set');
	}
	
	public function install() 
	{
		$pkg = parent::install();
		
		BlockType::installBlockType('d3_random_image', $pkg);
	}
	
	public function uninstall() 
	{
		parent::uninstall();

        $db = $this->app->make(Connection::class);
		$db->executeQuery('DROP TABLE IF EXISTS btD3RandomImage');
	}
}