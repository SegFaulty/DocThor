<?php

require_once(dirname(__FILE__).'/Stethoscope.php');

class DocThor {

	/**
	 * CLI - OPTIONS
	 * @var array
	 */
	protected $options = array();
	/**
	 * informations extracted from sourceCode
	 * @var array
	 */
	protected $sourceEntries = array();
	/**
	 * detected inconsistencies between reflection and source code
	 * @var array
	 */
	protected $sourceInconsistencies = array();
	/**
	 * parses commandline
	 * @param $argv
	 */
	public function runFromCli($argv){
		if( isset($argv) AND count($argv)>1 ){
			// getOptions
			$this->options = getopt('',array('sourceDir:', 'check'));
			if( !empty($this->options['sourceDir']) ){
				if( !is_dir($this->options['sourceDir']) ){
					die('sourceDir: '.$this->options['sourceDir'].' not found!');
				}
				$stethoscope = new Stethoscope();
				$this->sourceEntries = $stethoscope->scanDir($this->options['sourceDir']);
# print_r($this->sourceEntries);
			}

			array_shift($argv);
			$subjects = array();
			foreach( $argv as $options ){
				if( substr($options,0,2)!='--' ){
					$subjects[] = $options;
				}
			}
			$result = '<?php'.PHP_EOL;
			if( count($subjects)==1 AND extension_loaded($subjects[0]) ){
				$result.= $this->buildExtension($subjects[0]);
			}else{
				foreach( $subjects as $className ){
					if( substr($className,0,2)!='--' ){
						$result.= $this->buildClass($className);
					}
				}
			}
			if( array_key_exists('check', $this->options) ){
				echo count($this->sourceInconsistencies).' inconsistencies found'."\n";
				echo join("\n", $this->sourceInconsistencies)."\n";
			} else {
				echo $result;
			}

		}else{
			$file = preg_replace('~.*/~','',__FILE__ );
			echo 'Usage: php '.$file."\n";
		}
	}

	/**
	 * build class Api
	 * @param $className
	 * @return string
	 */
	public function buildClass($className) {
		$result = '';
		if( class_exists($className) ){
			$refClass = new ReflectionClass($className);
			$className = $refClass->getName();
			$result.= 'class '.$className.' ';
			if( $refClass->getParentClass() ){
				$result.= 'extends '.$refClass->getParentClass()->getName().' ';
			}
			$result.= '{'.PHP_EOL;

			// check for constants
			foreach( $refClass->getConstants() as $name=>$value ){
				$result.= "\t".'const '.$name.' = '.var_export($value, true).';'.PHP_EOL;
			}

			// check for public attributes
			foreach( $refClass->getProperties() as $property ){
				if( $property->getDeclaringClass()->getName()!=$refClass->getName() ){
					continue; // complete inheritted
				}
				if( $property->isPublic() OR $property->isProtected() ){
					$result.= "\t";
					if( $property->isPublic() ){
						$result.= 'public ';
					}else{
						$result.= 'protected ';
					}
					$result.= '$'.$property->getName().';'.PHP_EOL;
				}
			}

			// check for methods
			foreach( $refClass->getMethods() as $method ){
				$method = $refClass->getMethod($method->getName());
				if( $method->getDeclaringClass()->getName()!=$refClass->getName() ){
					continue; // complete inheritted
				}
				if( $method->isPublic() OR $method->isProtected() ){

					$refParameters = $method->getParameters();

					// process sourceInformations
					if( isset($this->sourceEntries['classes'][$className]['methods'][$method->getName()])){
						$entry = $this->sourceEntries['classes'][$className]['methods'][$method->getName()];
						$docLines = array();
						if( !empty($entry['comment']) ){
							$docLines[] = $entry['comment'];
							$docLines[] = '';
						}
						if( !empty($entry['parameters']) ){
							if( count($refParameters)!=count($entry['parameters']))  {
								$this->sourceInconsistencies[] = 'inconsistent parameter count: '.$refClass->getName().'::'.$method->getName().' reflection:'.count($refParameters).' sourceCode:'.count($entry['parameters']);
							}
							$refParamIndex = 0;
							foreach($entry['parameters'] as $parameterName=>$pEntry){
								$refParameter = $refParameters[$refParamIndex++];
								if( $refParameter->getName() != $parameterName)  {
									$this->sourceInconsistencies[] = 'inconsistent parameter name: '.$refClass->getName().'::'.$method->getName().' reflection:'.$refParameter->getName().' sourceCode:'.$parameterName;
								}
								if( $refParameter->getClass()!='' AND $refParameter->getClass()->getName() != $pEntry['type'])  {
									$this->sourceInconsistencies[] = 'inconsistent parameter type: '.$refClass->getName().'::'.$method->getName().' reflection:'.$refParameter->getClass()->getName().' sourceCode:'.$pEntry['type'];
								}
								$docLines[] = '@param '.$pEntry['type'].' $'.$parameterName;
							}
						}
						if( !empty($entry['returnType']) ){
							$docLines[] = '@return '.$entry['returnType'];
						}

						$result.= $this->generateDocBlock("\t", $docLines);
					}


					$result.= "\t";
					if( $method->isPublic() ){
						$result.= 'public ';
					}else{
						$result.= 'protected ';
					}
					$result.= $this->buildFunction($method);
				}
			}

			$result.= '}'."\n";
		}else{
			trigger_error('DocThor: class '.$className.' not found!',E_USER_ERROR);
		}
		return $result;
	}

	/**
	 * @param string $indent
	 * @param array $lines
	 * @return string
	 */
	protected function generateDocBlock($indent, $lines){
		$result = $indent.'/**'."\n";
		foreach( $lines as $line ){
			$result.= $indent.' * '.$line."\n";
		}
		$result.= $indent.' */'."\n";
		return $result;
	}

	/**
	 * @param ReflectionFunction $function
	 * @return string
	 */
	public function buildFunction($function){
		$result = 'function '.$function->getName().'(';

		$result.= $this->buildParameters($function->getParameters());
		$result.= ') {}'.PHP_EOL;
		return $result;
	}

	/**
	 * @param $parameters
	 * @return string
	 */
	protected function buildParameters($parameters){
		$result = '';
		// check parameters
		$trenn = '';
		foreach( $parameters as $param) {
			$result.= $trenn;
		    $class = $param->getClass();
		    if( !empty($class) ){
			    $result.= $class->getName().' ';
		    }
	        if($param->isPassedByReference()) {
	            $result.= '&';
	        }
		    $result.= '$'.$param->getName();
	        if($param->isOptional()) {
	            if($param->isDefaultValueAvailable()) {
		            $result.= '='.var_export($param->getDefaultValue(), true);
	            }else{
	                $result.= '=""';
                }
	        }
		    $trenn = ', ';
	    }
		return $result;
	}
	/**
	 * build an entire extension
	 * @param $extensionName
	 * @return string
	 */
	public function buildExtension($extensionName) {
		$result = '';
		if( extension_loaded($extensionName) ){
			$refExtension = new ReflectionExtension($extensionName);
			$result.= '/**'."\n";
			$result.= ' * '.$refExtension->getName().'-API v'.$refExtension->getVersion().' Docs build by DocThor ['.date('Y-m-d').']'."\n";
			$result.= ' * @package '.$refExtension->getName()."\n".' */'."\n";
			$result.= "\n";
			// constants
			foreach( $refExtension->getConstants() as $name=>$value ){
				$result.= 'const '.$name.' = '.var_export($value, true).';'.PHP_EOL;
			}
			// functions
			foreach( $refExtension->getFunctions() as $function ){
				$result.= $this->buildFunction($function);
			}
			// classes
			foreach( $refExtension->getClassNames() as $className ){
				$result.= '/**'."\n".' * @package '.$refExtension->getName()."\n".' */'."\n";
				$result.= $this->buildClass($className);
			}
		}else{
			trigger_error('DocThor: extension '.$extensionName.' not found!',E_USER_ERROR);
		}
		return $result;
	}
}

if( isset($argv) AND preg_replace('~.*/~','',$argv[0])==preg_replace('~.*/~','',__FILE__ ) ){
	$docThor = new DocThor();
	$docThor->runFromCli($argv);
}
