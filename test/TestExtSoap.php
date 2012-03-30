<?php
// soap-API v Docs build by DocThor [2012-03-31]
const SOAP_1_1 = 1;
const SOAP_1_2 = 2;
const SOAP_PERSISTENCE_SESSION = 1;
const SOAP_PERSISTENCE_REQUEST = 2;
const SOAP_FUNCTIONS_ALL = 999;
const SOAP_ENCODED = 1;
const SOAP_LITERAL = 2;
const SOAP_RPC = 1;
const SOAP_DOCUMENT = 2;
const SOAP_ACTOR_NEXT = 1;
const SOAP_ACTOR_NONE = 2;
const SOAP_ACTOR_UNLIMATERECEIVER = 3;
const SOAP_COMPRESSION_ACCEPT = 32;
const SOAP_COMPRESSION_GZIP = 0;
const SOAP_COMPRESSION_DEFLATE = 16;
const SOAP_AUTHENTICATION_BASIC = 0;
const SOAP_AUTHENTICATION_DIGEST = 1;
const UNKNOWN_TYPE = 999998;
const XSD_STRING = 101;
const XSD_BOOLEAN = 102;
const XSD_DECIMAL = 103;
const XSD_FLOAT = 104;
const XSD_DOUBLE = 105;
const XSD_DURATION = 106;
const XSD_DATETIME = 107;
const XSD_TIME = 108;
const XSD_DATE = 109;
const XSD_GYEARMONTH = 110;
const XSD_GYEAR = 111;
const XSD_GMONTHDAY = 112;
const XSD_GDAY = 113;
const XSD_GMONTH = 114;
const XSD_HEXBINARY = 115;
const XSD_BASE64BINARY = 116;
const XSD_ANYURI = 117;
const XSD_QNAME = 118;
const XSD_NOTATION = 119;
const XSD_NORMALIZEDSTRING = 120;
const XSD_TOKEN = 121;
const XSD_LANGUAGE = 122;
const XSD_NMTOKEN = 123;
const XSD_NAME = 124;
const XSD_NCNAME = 125;
const XSD_ID = 126;
const XSD_IDREF = 127;
const XSD_IDREFS = 128;
const XSD_ENTITY = 129;
const XSD_ENTITIES = 130;
const XSD_INTEGER = 131;
const XSD_NONPOSITIVEINTEGER = 132;
const XSD_NEGATIVEINTEGER = 133;
const XSD_LONG = 134;
const XSD_INT = 135;
const XSD_SHORT = 136;
const XSD_BYTE = 137;
const XSD_NONNEGATIVEINTEGER = 138;
const XSD_UNSIGNEDLONG = 139;
const XSD_UNSIGNEDINT = 140;
const XSD_UNSIGNEDSHORT = 141;
const XSD_UNSIGNEDBYTE = 142;
const XSD_POSITIVEINTEGER = 143;
const XSD_NMTOKENS = 144;
const XSD_ANYTYPE = 145;
const XSD_ANYXML = 147;
const APACHE_MAP = 200;
const SOAP_ENC_OBJECT = 301;
const SOAP_ENC_ARRAY = 300;
const XSD_1999_TIMEINSTANT = 401;
const XSD_NAMESPACE = 'http://www.w3.org/2001/XMLSchema';
const XSD_1999_NAMESPACE = 'http://www.w3.org/1999/XMLSchema';
const SOAP_SINGLE_ELEMENT_ARRAYS = 1;
const SOAP_WAIT_ONE_WAY_CALLS = 2;
const SOAP_USE_XSI_ARRAY_TYPE = 4;
const WSDL_CACHE_NONE = 0;
const WSDL_CACHE_DISK = 1;
const WSDL_CACHE_MEMORY = 2;
const WSDL_CACHE_BOTH = 3;
function use_soap_error_handler($handler="") {}
function is_soap_fault($object) {}
class SoapClient {
	public function SoapClient($wsdl, $options="") {}
	public function __call($function_name, $arguments) {}
	public function __soapCall($function_name, $arguments, $options="", $input_headers="", &$output_headers="") {}
	public function __getLastRequest() {}
	public function __getLastResponse() {}
	public function __getLastRequestHeaders() {}
	public function __getLastResponseHeaders() {}
	public function __getFunctions() {}
	public function __getTypes() {}
	public function __doRequest($request, $location, $action, $version, $one_way="") {}
	public function __setCookie($name, $value="") {}
	public function __setLocation($new_location="") {}
	public function __setSoapHeaders($soapheaders) {}
}
class SoapVar {
	public function SoapVar($data, $encoding, $type_name="", $type_namespace="", $node_name="", $node_namespace="") {}
}
class SoapServer {
	public function SoapServer($wsdl, $options="") {}
	public function setPersistence($mode) {}
	public function setClass($class_name, $args="") {}
	public function setObject($object) {}
	public function addFunction($functions) {}
	public function getFunctions() {}
	public function handle($soap_request="") {}
	public function fault($code, $string, $actor="", $details="", $name="") {}
	public function addSoapHeader($object) {}
}
class SoapFault {
	protected $message;
	protected $code;
	protected $file;
	protected $line;
	public function SoapFault($faultcode, $faultstring, $faultactor="", $detail="", $faultname="", $headerfault="") {}
	public function __toString() {}
	public function __construct($message="", $code="", $previous="") {}
	public function getMessage() {}
	public function getCode() {}
	public function getFile() {}
	public function getLine() {}
	public function getTrace() {}
	public function getPrevious() {}
	public function getTraceAsString() {}
}
class SoapParam {
	public function SoapParam($data, $name) {}
}
class SoapHeader {
	public function SoapHeader($namespace, $name, $data="", $mustunderstand="", $actor="") {}
}
