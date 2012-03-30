#DocThor#


is a php-script which generates php-source code-stubs for given extensions or class names

we use it at [Schottenland.de](http://www.schottenland.de) to generate simple API-Files for all installed extensions
to avoid *"unknown class" or "unknown method"* errors in the IDE (e.g. [PhpStorm](http://www.jetbrains.com/phpstorm/))

##Usage##
* with one parameter it will check if it is a loaded extension, if not it considers it a class name `php DocThor.php APC`
* with more than one parameter, all are considered class names `php DocThor.php Directory DirectoryIterator`

##How it works##
* uses reflections to determine classes, methods, constants, functions, etc. and "rebuilds" these as empty structures

##Example##
will generate php-source code for the [ZMQ-Extension](http://www.zeromq.org) (if installed)

* download DocThor.php
* run `php DocThor.php ZMQ > zmqApi.php`
* put zmqApi.php in your IDE-project-path
 
zmqApi.php looks like:

    <?php
    // ZMQ-API v1.0.3 Docs build by DocThor [2012-03-30]
    class ZMQ {
            const SOCKET_PAIR = 0;
            const SOCKET_PUB = 1;
            const SOCKET_SUB = 2;
            const SOCKET_XSUB = 10;
            const SOCKET_XPUB = 9;
            const SOCKET_REQ = 3;
            const SOCKET_REP = 4;
            const SOCKET_XREQ = 5;
            const SOCKET_XREP = 6;
            const SOCKET_PUSH = 8;
            const SOCKET_PULL = 7;
            const SOCKET_DEALER = 5;
            const SOCKET_ROUTER = 6;
            const SOCKET_UPSTREAM = 7;
            const SOCKET_DOWNSTREAM = 8;
            const POLL_IN = 1;
            const POLL_OUT = 2;
            const MODE_SNDMORE = 2;
            const MODE_NOBLOCK = 1;
            const MODE_DONTWAIT = 1;
            const DEVICE_FORWARDER = 0;
            const DEVICE_QUEUE = 0;
            const DEVICE_STREAMER = 0;
            const ERR_INTERNAL = -99;
            const ERR_EAGAIN = 11;
            const ERR_ENOTSUP = 95;
            const ERR_EFSM = 156384763;
            const ERR_ETERM = 156384765;
            const LIBZMQ_VER = '3.1.0';
            const SOCKOPT_HWM = 201;
            const SOCKOPT_SNDHWM = 23;
            const SOCKOPT_RCVHWM = 24;
            const SOCKOPT_AFFINITY = 4;
            const SOCKOPT_IDENTITY = 5;
            const SOCKOPT_RATE = 8;
            const SOCKOPT_RECOVERY_IVL = 9;
            const SOCKOPT_SNDBUF = 11;
            const SOCKOPT_RCVBUF = 12;
            const SOCKOPT_LINGER = 17;
            const SOCKOPT_RECONNECT_IVL = 18;
            const SOCKOPT_RECONNECT_IVL_MAX = 21;
            const SOCKOPT_BACKLOG = 19;
            const SOCKOPT_MAXMSGSIZE = 22;
            const SOCKOPT_SUBSCRIBE = 6;
            const SOCKOPT_UNSUBSCRIBE = 7;
            const SOCKOPT_TYPE = 16;
            const SOCKOPT_RCVMORE = 13;
            const SOCKOPT_FD = 14;
            const SOCKOPT_EVENTS = 15;
            const SOCKOPT_SNDTIMEO = 28;
            const SOCKOPT_RCVTIMEO = 27;
    }
    class ZMQContext {
            public function __construct($io_threads="", $persistent="") {}
            public function getsocket($type, $dsn, $on_new_socket="") {}
            public function ispersistent() {}
    }
    class ZMQSocket {
            public function __construct(ZMQContext $ZMQContext, $type, $persistent_id="", $on_new_socket="") {}
            public function send($message, $mode="") {}
            public function recv($mode="") {}
            public function sendmulti($message, $mode="") {}
            public function recvmulti($mode="") {}
            public function bind($dsn, $force="") {}
            public function connect($dsn, $force="") {}
            public function setsockopt($key, $value) {}
            public function getendpoints() {}
            public function getsockettype() {}
            public function ispersistent() {}
            public function getpersistentid() {}
            public function getsockopt($key) {}
            public function sendmsg($message, $mode="") {}
            public function recvmsg($mode="") {}
    }
    class ZMQPoll {
            public function add($entry, $type) {}
            public function poll(&$readable, &$writable, $timeout="") {}
            public function getlasterrors() {}
            public function remove($remove) {}
            public function count() {}
            public function clear() {}
    }
    class ZMQDevice {
            public function __construct($frontend, $backend) {}
            public function run() {}
            public function setidlecallback($idle_callback) {}
            public function setidletimeout($timeout) {}
    }
    class ZMQException {
            protected $message;
            protected $code;
            protected $file;
            protected $line;
            public function __construct($message="", $code="", $previous="") {}
            public function getMessage() {}
            public function getCode() {}
            public function getFile() {}
            public function getLine() {}
            public function getTrace() {}
            public function getPrevious() {}
            public function getTraceAsString() {}
            public function __toString() {}
    }
    class ZMQContextException {
            protected $message;
            protected $code;
            protected $file;
            protected $line;
            public function __construct($message="", $code="", $previous="") {}
            public function getMessage() {}
            public function getCode() {}
            public function getFile() {}
            public function getLine() {}
            public function getTrace() {}
            public function getPrevious() {}
            public function getTraceAsString() {}
            public function __toString() {}
    }
    class ZMQSocketException {
            protected $message;
            protected $code;
            protected $file;
            protected $line;
            public function __construct($message="", $code="", $previous="") {}
            public function getMessage() {}
            public function getCode() {}
            public function getFile() {}
            public function getLine() {}
            public function getTrace() {}
            public function getPrevious() {}
            public function getTraceAsString() {}
            public function __toString() {}
    }
    class ZMQPollException {
            protected $message;
            protected $code;
            protected $file;
            protected $line;
            public function __construct($message="", $code="", $previous="") {}
            public function getMessage() {}
            public function getCode() {}
            public function getFile() {}
            public function getLine() {}
            public function getTrace() {}
            public function getPrevious() {}
            public function getTraceAsString() {}
            public function __toString() {}
    }
    class ZMQDeviceException {
            protected $message;
            protected $code;
            protected $file;
            protected $line;
            public function __construct($message="", $code="", $previous="") {}
            public function getMessage() {}
            public function getCode() {}
            public function getFile() {}
            public function getLine() {}
            public function getTrace() {}
            public function getPrevious() {}
            public function getTraceAsString() {}
            public function __toString() {}
    }

