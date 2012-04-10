<?php

// Define path to application directory
defined('CURRENT_PATH')
    || define('CURRENT_PATH', realpath(dirname(__FILE__)));

require_once 'ScaZF/tools/Base/Singleton.php';
require_once 'ScaZF/tools/Base/Screamer.php';
require_once 'ScaZF/tools/Base/Screamer/ClassTrait.php';

require_once 'ScaZF/tools/Schema/Exception.php';
require_once 'ScaZF/tools/Schema/Field.php';
require_once 'ScaZF/tools/Schema/Model.php';
require_once 'ScaZF/tools/Schema/Package.php';
require_once 'ScaZF/tools/Schema/Manager.php';

require_once 'ScaZF/tools/Xml/Reader.php';

require_once 'ScaZF/tools/Validator/ValidatorAbstract.php';
require_once 'ScaZF/tools/Validator/Field/TypeInt.php';
require_once 'ScaZF/tools/Validator/Field/TypeString.php';
require_once 'ScaZF/tools/Validator/Field/TypeModel.php';
require_once 'ScaZF/tools/Validator/Field/TypeEnum.php';
require_once 'ScaZF/tools/Validator/Model.php';
require_once 'ScaZF/tools/Validator/Schema.php';
require_once 'ScaZF/tools/Validator/Field.php';


require_once 'ScaZF/tools/Wrapper/Field.php';
require_once 'ScaZF/tools/Wrapper/Model.php';

require_once 'ScaZF/tools/Db/Generator.php';

require_once 'ScaZF/tools/Model/Exception.php';
require_once 'ScaZF/tools/Model/Template.php';
require_once 'ScaZF/tools/Model/Generator.php';

use \ScaZF\Tool\Schema\Manager;
use \ScaZF\Tool\Validator\Schema;

\ScaZF\Tool\Model\Template::setTemplatesPath(CURRENT_PATH . '/ScaZF/templates');

$oManager = Manager::getInstance();
$oManager->init(CURRENT_PATH . '/Models', CURRENT_PATH .'/tools/Xml/Package.xsf');

$sPackage = 'Users';
$sModelPath = '/home/WWW/PK_4/Challenges/application/models/'. $sPackage . '/Base';

mkdir($sModelPath, 0777, true);

try
{
	$oManager->setDefaultPackage('Users');
	$oManager->loadPackage('Users');
	$oValidator = new Schema();
	if(!$oValidator->isValid($oManager->getPackage('Users')))
	{
		\ScaZF\Tool\Base\Screamer\Screamer::getInstance()->screamErrors($oValidator);
		die("\n");
	}

	$oPackage = $oManager->getPackage($sPackage);
	$oGen = new \ScaZF\Tool\Model\Generator();

	$sTables = '';
	$sTechnical = '';
	$sForeing = '';

	$oSql = new \ScaZF\Tool\Db\Generator();
	foreach($oPackage->getModels() as $oModel)
	{
	// BASE model
		file_put_contents($sModelPath .'/'. $oModel->getName() . '.php', $oGen->getModelBase($oModel));

	// model
		//file_put_contents($sModelPath .'/../'. $oModel->getName() . '.php', '');

	// BASE factory
		file_put_contents($sModelPath .'/'. $oModel->getName() . 'Factory.php', $oGen->getFactoryBase($oModel));

	// factory
		//file_put_contents($sModelPath .'/../'. $oModel->getName() . 'Factory.php', '');
	}

	file_put_contents('/home/WWW/PK_4/Challenges/application/models/'. $sPackage . '.sql', $oSql->getSql($oPackage));
}
catch(\ScaZF\Tool\Schema\Exception $oExc)
{
	echo "\n\nERROR:\n". $oExc->getMessage() ."\n\n";
	echo $oExc->getTraceAsString() . "\n\n";
}



//$oTmp = \ScaZF\Tool\Model\Template::getTemplate('ModelBase');
//echo $oTmp->getSubTemplate('simple-getter', [
//	'field-name'	=> 'User',
//	'p-field-name'	=> 'oUser',
//	'field-type'	=> '\Model\Users\User',
//	'p-field-key'	=> 'iUserId'
//]);

//use \ScaZF\Tool\Schema\Manager;
//use \ScaZF\Tool\Validator\Schema;
//
//$oManager = Manager::getInstance();
//$oManager->init(CURRENT_PATH . '/Models', CURRENT_PATH .'/tools/Xml/Package.xsf');
//
//try
//{
//	$oManager->setDefaultPackage('Users');
//	$oManager->loadPackage('Users');
//	$oValidator = new Schema();
//	if(!$oValidator->isValid($oManager->getPackage('Users')))
//	{
//		\ScaZF\Tool\Base\Screamer\Screamer::getInstance()->screamErrors($oValidator);
//		die("\n");
//	}
//
//	$oPackage = $oManager->getPackage('Users');
//	$oC = new \ScaZF\Tool\Db\Generator();
//
//	echo $oC->getSql($oPackage);
//
//	$sTables = '';
//	$sTechnical = '';
//	$sForeing = '';
//
//	//foreach($oTest->getModels() as $oModel)
//	//{
//	//	$aResult = $oC->toSql($oModel);
//	//
//	//	$sTables .= $aResult['table']  ."\n";
//	//
//	//	foreach($aResult['join'] as $sTable)
//	//	{
//	//		$sTechnical .= $sTable . "\n";
//	//	}
//	//
//	//	foreach($aResult['foreignKeys'] as $sKey)
//	//	{
//	//		$sForeing .= $sKey . "\n";
//	//	}
//	//}
//	//
//	//echo "\n";
//	//echo $sTables . "\n";
//	//echo $sTechnical . "\n";
//	//echo $sForeing . "\n";
//}
//catch(\ScaZF\Tool\Schema\Exception $oExc)
//{
//	echo "\n\nERROR:\n". $oExc->getMessage() ."\n\n";
//	echo $oExc->getTraceAsString() . "\n\n";
//}


die("ok\n");