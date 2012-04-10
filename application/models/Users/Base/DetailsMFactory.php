<?php

/**
 * @namespace
 */
namespace \Model\Users;

/**
 * Factory base trait
 */
trait DetailsMFactory
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
		$aComponents[] = \Model\Users\DetailsM::info();
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
	 * @return	\Model\Users\DetailsM
	 */
	public function buildElement()
	{
		return new \Model\Users\DetailsM();
	}




}
