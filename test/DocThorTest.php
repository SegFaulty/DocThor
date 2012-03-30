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
	docthor_equals($expected, $result, 'ClassTest');

	$expected = file_get_contents(dirname(__FILE__).'/TestExtSoap.php');
	$expected = str_replace($eol, PHP_EOL, $expected);
	$result = '<?php'.PHP_EOL.$docThor->buildExtension('soap');
	docthor_equals($expected, $result, 'ExtensionTest');


function docthor_equals($expected, $subject, $name) {
	if( $expected===$subject ){
		echo 'Test '.$name.' passed'.PHP_EOL;
	} else {
		echo 'Test '.$name.' failed'.PHP_EOL;
		$len = max(strlen($subject), strlen($expected));
		for($i=0; $i<$len; $i++){
			if( $subject[$i]!=$expected[$i] ){
				echo 'expected:'.substr($expected,$i,100).'...'.PHP_EOL;
				echo 'but  was:'.substr($subject,$i,100).'...'.PHP_EOL;
				break;
			}
		}
	}
}
