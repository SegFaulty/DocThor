#DocThor#


is a php-script which generates php-source code-stubs for given extensions or class names

we use it at [Schottenland.de](http://www.schottenland.de) to generate simple API-Files for all installed extensions
to avoid *"undefined class" or "undefined method"* errors in the IDE (e.g. [PhpStorm](http://www.jetbrains.com/phpstorm/))

![undefined](https://raw.github.com/SegFaulty/DocThor/master/undefined.png)

##Usage##
* with one parameter it will check if it is a loaded extension, if not it considers it a class name `php DocThor.php APC`
* with more than one parameter, all are considered class names `php DocThor.php Directory Exception`
* with --sourceDir parameter, it scans .c files for more information `php DocThor.php --sourceDir=/opt/src/zeromq/ zmq`
* with --check parameter, no structure is generated, only the inconsistencies betwenn source and reflection are shown `php DocThor.php --sourceDir=/opt/src/zeromq/ --check zmq`

##With C source files##
some hints if you use the --sourceDir parameter

* if you installed the extension from e.g. PECL (without source) you should download the source code
* the information is extracted from (optional) code-comments in the source files
* some **extensions do not include** the (for the compiling optional but for the DocThor) required information
* if your are lucky and the DocThor extracts more information, then you have a big chance they are not consistent with the "reflected" structure (because the developer has to update the internal doc comments on code change .. and you know ..)
* so **trust the structure** but **don't trust the docBlocks**
* use a tool like PhpStorm that marks the inconsistencies and fix manually (use --check to preview)

##How it works##
* primary: uses reflections to determine classes, methods, constants, functions, etc. and "rebuilds" these as empty structures
* secondary: if you provide a (php-extension-c-files) sourceDirectory, then the DocThor will use his stethoscope to extract more information from the source-files to enhance the structure-files with docBlocks

##Test##
simple test script included in test directory, run with `php DocThorTest.php` 

##Example##
will generate php-source code for the [ZMQ-Extension](http://www.zeromq.org) (if installed)

* `mkdir zmq && cd zmq` make a tmp dir
* `wget https://raw.github.com/SegFaulty/DocThor/master/DocThor.php` download DocThor
* `wget https://raw.github.com/SegFaulty/DocThor/master/Stethoscope.php` and his stethoscope
* `wget https://github.com/mkoppanen/php-zmq/tarball/master -O php-zmq.tar.gz` download ZMQ-Sources
* `tar -xzf php-zmq.tar.gz` and untar
* `php DocThor.php --sourceDir=./ zmq > zmqApi.php` ask the DocThor and get the prescription
* put zmqApi.php in your IDE-project path (and correct the inconsistencies)
* `php DocThor.php --sourceDir=./ --check zmq` outputs only the differences between source code and reflection
 
zmqApi.php looks like:

    <?php
    /**
     * zmq-API v1.0.3 Docs build by DocThor [2012-03-31]
     * @package zmq
     */

    /**
     * @package zmq
     */
    class ZMQ {
            const SOCKET_PAIR = 0;
            const SOCKET_PUB = 1;
            const SOCKET_SUB = 2;
            ...
            const SOCKOPT_EVENTS = 15;
            const SOCKOPT_SNDTIMEO = 28;
            const SOCKOPT_RCVTIMEO = 27;
    }
    /**
     * @package zmq
     */
    class ZMQContext {
            /**
             * Build a new ZMQContext object
             *
             * @param integer $io_threads
             * @param boolean $is_persistent
             * @return ZMQContext
             */
            public function __construct($io_threads="", $persistent="") {}
            public function getsocket($type, $dsn, $on_new_socket="") {}
            public function ispersistent() {}
    }
    /**
     * @package zmq
     */
    class ZMQSocket {
            /**
             * Build a new ZMQSocket object
             *
             * @param ZMQContext $context
             * @param integer $type
             * @param string $persistent_id
             * @param callback $on_new_socket
             * @return ZMQSocket
             */
            public function __construct(ZMQContext $ZMQContext, $type, $persistent_id="", $on_new_socket="") {}
            ...

see all in the Wiki at [zmq-Api PHP-file](https://github.com/SegFaulty/DocThor/wiki/zmq)

check result:

    8 inconsistencies found
    inconsistent parameter name: ZMQContext::__construct reflection:persistent sourceCode:is_persistent
    inconsistent parameter name: ZMQSocket::__construct reflection:ZMQContext sourceCode:context
    inconsistent parameter name: ZMQSocket::send reflection:mode sourceCode:flags
    inconsistent parameter name: ZMQSocket::sendmulti reflection:message sourceCode:messages
    inconsistent parameter name: ZMQSocket::sendmulti reflection:mode sourceCode:flags
    inconsistent parameter name: ZMQPoll::add reflection:entry sourceCode:object
    inconsistent parameter name: ZMQPoll::add reflection:type sourceCode:events
    inconsistent parameter name: ZMQPoll::remove reflection:remove sourceCode:item