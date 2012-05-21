<?php

/**
 * @namespace
 */
namespace Model\Tasks;

class Solution extends \Sca\DataObject\Element
{
	 CONST LANGUAGE_PHP = 'php';
	 CONST LANGUAGE_CPP = 'cpp';

	 CONST STATUS_NEW = 'new';
	 CONST STATUS_TESTING = 'testing';
	 CONST STATUS_SUCCESS = 'success';
	 CONST STATUS_ERROR = 'error';

	use Base\Solution;
}