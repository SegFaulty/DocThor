<?php
//detected EOL

class Test_DocThor_TestClass {
	const TEST_CONST_1 = 1;
	const TEST_CONST_2 = 'abc';
	public $attr1;
	public $attr2;
	protected $pattr1;
	protected $pattr2;
	public function getId() {}
	public function get($name) {}
	public function getDefault($name, $default='string') {}
	public function getEmpty($name, $default='') {}
	public function defaultZero($def=0) {}#
	public function defaultNull($def=NULL) {}
	public function defaultEmptyArray($array=array (
)) {}
	public function requiredObject(ReflectionClass $object) {}
}
