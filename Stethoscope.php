<?php
/**
 * scans php-extension c source files for Informations, to build better API-classes
 * @package DocThor
 */
class Stethoscope {

	/**
	 * scan dir recursivly for .c files
	 * @param $dirName
	 * @param array $entries
	 * @return array
	 */
	public function scanDir($dirName, &$entries=array()){
		if( !is_dir($dirName) ){
			die('directory: '.$dirName.' not found!'."\n");
		}
		foreach (glob($dirName."*.c") as $sourceFile) {
		    $this->scanFile($sourceFile, $entries);
		}
		// scanDir
		foreach (glob($dirName.'*', GLOB_ONLYDIR|GLOB_MARK) as $sourceDir) {
			if( $dirName!=$sourceDir ){
		        $this->scanDir($sourceDir, $entries);
			}
		}
		return $entries;
	}

	/**
	 * @param $fileName
	 * @param array $entries
	 * @return array
	 */
	public function scanFile($fileName, &$entries=array()){
		$content = file_get_contents($fileName);

		$regexp = '/\* \{\{\{ proto ([^ ]*?) '; // returnType
		$regexp.= '(\w*?)(?:->|::)(\w*?)'; // Class::Method OR Class->Method
		$regexp.= '\(([^\)]*?)\)'; // parameters
		$regexp.= '([^\*]*?)\*/'; // comment
		$regexp.= '\s+PHP_METHOD'; // code start
		$regexp.= '\(([^,]+), *([^\)]+)\)'; // (className, Method)
		if( preg_match_all('~'.$regexp.'~s', $content, $matches) ){
#print_r($matches);
			foreach( $matches[1] as $index=>$value ){
				$returnType = $matches[1][$index];
				$className = $matches[2][$index];
				$methodName = $matches[3][$index];
				$parameterString = $matches[4][$index];
				$comment = trim($matches[5][$index]);
				$cClassName = $matches[6][$index];
				$cMethodName = $matches[7][$index];

				$parameters = array();
				if( $parameterString!='' ){
					$pRegex = '(\w+) '; //  type
					$pRegex.= '&?\$?(\w+)'; //  name
					if( preg_match_all('~'.$pRegex.'~', $parameterString, $pMatches) ){
						foreach( $pMatches[1] as $pIndex=>$value ){
							$pType = $pMatches[1][$pIndex];
							$pName = $pMatches[2][$pIndex];
							$parameters[$pName] = array(
								'type' => $pType,
								'name' => $pName,
							);
						}
					}
				}

				$methodEntry = array(
					'returnType' => $returnType,
					'parameters' => $parameters,
					'parameterString' => ":".$parameterString.":",
					'comment' => $comment,
				);
				$entries['classes'][$className]['methods'][$methodName] = $methodEntry;

			}
		} else {
#			echo substr($content, 100000);
		}
		return $entries;
	}
}


if( isset($argv) AND count($argv)==2 AND preg_replace('~.*/~','',$argv[0])==preg_replace('~.*/~','',__FILE__ ) ){
	$s = new Stethoscope();
	$entries = $s->scanDir($argv[1]);
	print_r($entries);
}