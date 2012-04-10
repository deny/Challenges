<?php

/**
 * @namespace
 */
namespace ScaZF\Tool\Validator\Field;

use ScaZF\Tool\Validator\ValidatorAbstract;

/**
 * Field type validator
 *
 * @author	Daniel Kózka
 */
class TypeString extends ValidatorAbstract
{
	// error types
	const OPT_COUNT		= 'opt-count';
	const WRONG_LENGTH	= 'wrong-legth';

	/**
	 * Constructor
	 *
	 * @return	ScaZF\Tool\Validator\Field\TypeModel
	 */
	public function __construct()
	{
		parent::__construct(1, array(
			self::OPT_COUNT		=> 'String allow only one option (string length)',
			self::WRONG_LENGTH 	=> 'Wrong string length definition: {0}'
		));
	}

	/**
	 * Validation function
	 *
	 * @param	array	$aValues	validated values
	 * @return	void
	 */
	protected function validate(array $aValues)
	{
		$oField = $aValues[0];
		if(!$oField instanceof \ScaZF\Tool\Schema\Field)
		{
			throw \Exception('Validate value must be instance of Schema\Field');
		}

		$aAttribs = $oField->getTypeAttribs();

		if(count($aAttribs) > 1)
		{
			$this->error(self::OPT_COUNT);
		}

		if(isset($aAttribs[0]) && !is_numeric($aAttribs[0]))
		{
			$this->error(self::WRONG_LENGTH, $aAttribs[0]);
		}
	}
}