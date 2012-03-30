<?php

class DocThor {

	/**
	 * build class Api
	 * @param $className
	 * @return string
	 */
	public function buildClass($className) {
		$result = '';
		if( class_exists($className) ){
			$refClass = new ReflectionClass($className);
			$result = 'class '.$className.' {'.PHP_EOL;

			// check for constants
			foreach( $refClass->getConstants() as $name=>$value ){
				$result.= "\t".'const '.$name.' = '.var_export($value, true).';'.PHP_EOL;
			}

			// check for public attributes
			foreach( $refClass->getProperties() as $property ){
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
				if( $method->isPublic() OR $method->isProtected() ){
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
			$result.= '// '.$refExtension->getName().'-API v'.$refExtension->getVersion().' Docs build by DocThor ['.date('Y-m-d').']'."\n";
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
				$result.= $this->buildClass($className);
			}
		}else{
			trigger_error('DocThor: extension '.$extensionName.' not found!',E_USER_ERROR);
		}
		return $result;
	}
}

if( isset($argv) AND count($argv)>1 and $argv[0]==preg_replace('~.*/~','',__FILE__ ) ){
	array_shift($argv);
	echo '<?php'.PHP_EOL;
	$docThor = new DocThor();
	if( count($argv)==1 AND extension_loaded($argv[0]) ){
		echo $docThor->buildExtension($argv[0]);
	}else{
		foreach( $argv as $className ){
			echo $docThor->buildClass($className);
		}
	}
}