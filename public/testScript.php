<?php


//$oUser = \Model\Users\UserFactory::getInstance()->create('myname1', 'mysurname1');
//$oUser = \Model\Users\ModeratorFactory::getInstance()->create('myname2', 'mysurname2', 'email2');

//
//$aUsers = \Model\Users\UserFactory::getInstance()->getFromIds(array(1, 2), [], ['friends']);
//
//foreach($aUsers as $oUser)
//{
//	echo "user\n";
//	show($oUser);
//	echo "friends\n";
//	showFriends($oUser);
//	echo "\n\n";
//}
//\Model\Users\DiaryFactory::getInstance()->create(2, 'bbbb');
//$aTmp = \Model\Users\DiaryFactory::getInstance()->getFromIds(array(2, 3, 4), [], ['user']);
//
//foreach($aTmp as $oDiary)
//{
//	echo $oDiary->getUser()->getName() . "\n";
//}


//die($oUser->getSettings()->setSettingOne('aaa222ddd')->save());

//for($i = 0; $i < 6; $i++)
//{
//	$oUser = \Model\Users\UserFactory::getInstance()->create('name'. $i, 'surname'. $i);
//	show($oUser);
//}
//
//for($i = 6; $i < 11; $i++)
//{
//	$oUser = \Model\Users\ModeratorFactory::getInstance()->create('name'. $i, 'surname'. $i, 'email'. $i);
//	show($oUser);
//}

//$oUser->delete();
//$oUser->setName('2newname');
//$oUser->setEmail('2mynewemail');
//$oUser->save();
//$aUsers = \Model\Users\ModeratorFactory::getInstance()->getFromIds(array(1,2,3));
//foreach($aUsers as $oUser)
//{
//	$oUser->setName('mod-name' . $oUser->getId());
//	$oUser->setSurname('mod-surname' . $oUser->getId());
//	$oUser->setEmail('mod-email' . $oUser->getId());
//	$oUser->save();
//}
//
//foreach($aUsers as $oUser)
//{
//	show($oUser);
//}

echo 'FINISH';
die("\n");

function show(\Model\Users\User $oUser)
{
	echo $oUser->getId() .'-'. $oUser->getName() . "\n";
}

function showFriends(\Model\Users\User $oUser)
{
	foreach($oUser->getFriends() as $oFriend)
	{
		show($oFriend);
	}
}