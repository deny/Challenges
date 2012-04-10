<?php

/**
 * @namespace
 */
namespace Model\Users\Base;

/**
 * Factory base trait
 */
trait DetailsUFactory
{
	use \Sca\DataObject\Singleton;

	/**
	 * Factory initialization
	 *
	* @param	array	$aComponents	components descritpion
	 * @return	void
	 */
	protected function init(array &$aComponents = [])
	{
		$aComponents[] = \Model\Users\DetailsU::info();
		parent::init($aComponents);
	}

// CREATE METHODS


	/**
	 * Prepare data to create (empty in component)
	 *
	 * @param	array	$aData	model data
	 * @return	array
	 */
	protected function prepareToCreate(array $aData)
	{
	}



// FACTORY METHODS



// OTHER

	/**
	 * Build new model object
	 *
	 * @return	\Model\Users\DetailsU
	 */
	public function buildElement()
	{
		return new \Model\Users\DetailsU();
	}




}
