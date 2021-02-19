<?php

namespace Concrete\Package\D3RandomImage\Block\D3RandomImage;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Entity\File\Version;
use Concrete\Core\File\FileList;
use Concrete\Core\File\Set\Set;
use Exception;

class Controller extends BlockController 
{
	protected $btTable = 'btD3RandomImage';
	protected $btInterfaceWidth = '400';
	protected $btInterfaceHeight = '300';
	protected $btDefaultSet = 'multimedia';
	
	public function getBlockTypeName() 
	{
        return t('Random Image');
	}

	public function getBlockTypeDescription() 
	{
        return t('Display a random image from a file set');
	}

	public function view()
	{
		try {
			$fv = $this->getRandomImage($this->fsID);
			$imagePath = $this->getImagePath($fv);
			
			$this->set('fv', $fv);
			$this->set('imagePath', $imagePath);
		} catch(Exception $e) {
			$this->set('error', $e->getMessage());
		}
	}

	public function add()
	{
        $this->addEdit();
	}

    public function edit()
	{
	    $this->addEdit();

		$this->set('max_width', (empty($this->max_width) ? '' : $this->max_width));
		$this->set('max_height', (empty($this->max_height) ? '' : $this->max_height));
	}

    private function addEdit()
    {
        $this->set('fileSetOptions', $this->getFileSetsOptions());
    }

    public function save($args)
	{
        $args['do_crop'] = ($args['do_crop']) ? 1 : 0;
		
		// Due to a bug in the core, we can't save null values yet...
		$args['max_width'] = (!empty($args['max_width'])) ? $args['max_width'] : 0;
		$args['max_height'] = (!empty($args['max_height'])) ? $args['max_height'] : 0;
		
		parent::save($args);
	}

    /**
     * @param int $fsID
     *
     * @return Version | Exception
     * @throws Exception
     */
	private function getRandomImage($fsID)
	{
		if (!$fsID) {
			throw new \Exception(t('No file set has been selected'));
		}
		
		$fs = Set::getByID($fsID);
		if (!$fs) {
			throw new \Exception(t("File set doesn't exist (anymore)"));
		}
		
		$list = new FileList();
		$list->filterBySet($fs);
		$list->setItemsPerPage(1);
		$list->sortBy('RAND()');
		$files = $list->getResults();
		
		if (!$files OR !is_array($files) OR !$files[0]) {
			throw new Exception(t('File set is empty'));
		}
		
		$fv = $files[0]->getRecentVersion();
		
		if (!$fv) {
			throw new Exception(t('File version not found'));
		}
		
		return $fv;
	}
	
	/**
	 * @param Version $fv
     *
	 * @return string
	 */
	private function getImagePath($fv)
	{
		if (!empty($this->max_width) OR !empty($this->max_height)) {
			$max_width  = empty($this->max_width) ? 9999 : $this->max_width;
			$max_height = empty($this->max_height) ? 9999 : $this->max_height;
			
			$ih = $this->app->make('helper/image');
			$thumb = $ih->getThumbnail($fv->getFile(), $max_width, $max_height, $this->do_crop);

			return $thumb->src;
		}

        return $fv->getRelativePath();
	}
	
	
	/**
	 * @return array FileSetID, FileSetName
	 */
	private function getFileSetsOptions()
	{
        $options = ['' => t('None')];

		$sets = Set::getMySets();
		
		if ($sets) {
	        foreach($sets as $set){
	            $options[$set->getFileSetID()] = $set->getFileSetName();
	        }
		}
		
		return $options;
	}
}
