<?php

/**
 * @namespace
 */
namespace Model\Users\Base;

/**
 * Base trait
 */
trait Moderator
{


// FIELDS

	/**
	 * @var	\Model\Users\DetailsM
	 */
	private $oDetails = null;


// INITIALIZATION

	/**
	 * Model initialziation
	 *
	 * @param	array	$aRow			row from DB
	 * @param	array	$aComponents	components desc
	 */
	public function init(array &$aRow, array &$aComponents = [])
	{
		$aComponents[] = self::info();
		parent::init($aRow, $aComponents);



		if(isset($aRow['_details']))
		{
			$this->oDetails = $aRow['_details'] ?
									$aRow['_details'] :
									(new \Model\Users\DetailsM())->initDefault($this);
		}




		return $this;
	}


// GETTERS

	/**
	 * @return	\Model\Users\DetailsM
	 */
	public function getDetails()
	{
		if(!isset($this->oDetails))
		{
			try
			{
				$this->oDetails = \Model\Users\DetailsMFactory::getInstance()->getOne($this->getId());
			}
			catch(\Sca\DataObject\Exception $oExc) // no data - create default object
			{
				$this->oDetails = (new \Model\Users\DetailsM())->initDefault($this);
			}
		}

		return $this->oDetails;
	}


// SETTERS


// STATIC

	/**
	 * Return model DB information
	 *
	 * @return	array
	 */
	public static function info()
	{
		return [
			'table' => 'user_e_moderator',
			'alias'	=> 'uem',
			'key'	=> 'u_id'
		];
	}
}
