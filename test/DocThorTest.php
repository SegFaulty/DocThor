<?php
	// go to test Dir
	// run with php DocThorTest.php

	include dirname(__FILE__).'/../DocThor.php';
	include dirname(__FILE__).'/TestClass.php';

	// generate Doc for TestClass
	$docThor = new DocThor();
	$result = $docThor->buildClass('Test_DocThor_TestClass');

	// load TestClass
	$expected = file_get_contents(dirname(__FILE__).'/TestClass.php');
	// extract classDefinition
	preg_match('~^<\?php(\s*)//detected EOL~',$expected,$matches);
	$eol = $matches[1];
	$expected = str_replace($eol, PHP_EOL, $expected);
	$expected = preg_replace('~^<\?php.*?//detected EOL\s*~s','',$expected); // start entfernen

	// check if its the same like generated
#echo 'exp:'.$expected.':::'."\n";
#echo 'res:'.$result.':::'."\n";
#echo 'exp:'.Waps_Helper_String::getStringRepresentationOfBinary($expected).':::'."\n";
#echo 'res:'.Waps_Helper_String::getStringRepresentationOfBinary($result).':::'."\n";
	if( $expected===$result ){
		echo 'Test passed'.PHP_EOL;
	} else {
		echo 'Test failed'.PHP_EOL;
		$len = max(strlen($result), strlen($expected));
		for($i=0; $i<$len; $i++){
			if( $result[$i]!=$expected[$i] ){
				echo 'expected:'.substr($expected,$i,100).'...'.PHP_EOL;
				echo 'but  was:'.substr($result,$i,100).'...'.PHP_EOL;
				break;
			}
		}
	}

