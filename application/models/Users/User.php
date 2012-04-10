<?php

/**
 * @namespace
 */
namespace Model\Users;

class User extends \Sca\DataObject\Element
{
	use Base\User;

	CONST ROLE_USER = 'user';
	CONST ROLE_MOD = 'mod';
	CONST ROLE_ADMIN = 'admin';

	CONST STATUS_INACTIVE = 'inactive';
	CONST STATUS_ACTIVE = 'active';
	CONST STATUS_BANNED = 'banned';
}