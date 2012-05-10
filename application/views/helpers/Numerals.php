<?php

/**
 * Helper do słów występujących po liczebnikach
 */
class View_Helper_Numerals extends Zend_View_Helper_Abstract
{
	/**
	 * Funkcja helpera
	 *
	 * @param 	int		$iNum		liczba
	 * @param	string	$sSingle	liczba pojedyńcza
	 * @param	string	$sMulti1	forma dla 2/3/4 ..
	 * @param	string	$sMulti2	forma dla 5,6,...
	 * @return	string
	 */
	public function numerals($iNum, $sSingle, $sMulti1, $sMulti2)
	{
		if($iNum == 1)
		{
			return $sSingle;
		}

		if($iNum > 10 && $iNum < 20)
		{
			return $sMulti2;
		}

		$iNum %= 10;
		if($iNum > 1 && $iNum < 5)
		{
			return $sMulti1;
		}

		return $sMulti2;
	}
}