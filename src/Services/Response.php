<?php

namespace MN\Services;

class Response {
	
	const HTTP_CONTINUE = 100;
	const HTTP_SWITCHING_PROTOCOLS = 101;
	const HTTP_PROCESSING = 102;
	const HTTP_EARLY_HINTS = 103;
	const HTTP_OK = 200;
	const HTTP_CREATED = 201;
	const HTTP_ACCEPTED = 202;
	const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
	const HTTP_NO_CONTENT = 204;
	const HTTP_RESET_CONTENT = 205;
	const HTTP_PARTIAL_CONTENT = 206;
	const HTTP_MULTI_STATUS = 207;
	const HTTP_ALREADY_REPORTED = 208;
	const HTTP_IM_USED = 226;
	const HTTP_MULTIPLE_CHOICES = 300;
	const HTTP_MOVED_PERMANENTLY = 301;
	const HTTP_FOUND = 302;
	const HTTP_SEE_OTHER = 303;
	const HTTP_NOT_MODIFIED = 304;
	const HTTP_USE_PROXY = 305;
	const HTTP_RESERVED = 306;
	const HTTP_TEMPORARY_REDIRECT = 307;
	const HTTP_PERMANENTLY_REDIRECT = 308;
	const HTTP_BAD_REQUEST = 400;
	const HTTP_UNAUTHORIZED = 401;
	const HTTP_PAYMENT_REQUIRED = 402;
	const HTTP_FORBIDDEN = 403;
	const HTTP_NOT_FOUND = 404;
	const HTTP_METHOD_NOT_ALLOWED = 405;
	const HTTP_NOT_ACCEPTABLE = 406;
	const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
	const HTTP_REQUEST_TIMEOUT = 408;
	const HTTP_CONFLICT = 409;
	const HTTP_GONE = 410;
	const HTTP_LENGTH_REQUIRED = 411;
	const HTTP_PRECONDITION_FAILED = 412;
	const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
	const HTTP_REQUEST_URI_TOO_LONG = 414;
	const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
	const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
	const HTTP_EXPECTATION_FAILED = 417;
	const HTTP_I_AM_A_TEAPOT = 418;
	const HTTP_MISDIRECTED_REQUEST = 421;
	const HTTP_UNPROCESSABLE_ENTITY = 422;
	const HTTP_LOCKED = 423;
	const HTTP_FAILED_DEPENDENCY = 424;
	
	/**
	 * @var array
	 */
	public static $status_texts = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		103 => 'Early Hints',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		208 => 'Already Reported',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Payload Too Large',
		414 => 'URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',
		421 => 'Misdirected Request',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		425 => 'Too Early',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		451 => 'Unavailable For Legal Reasons',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		508 => 'Loop Detected',
		510 => 'Not Extended',
		511 => 'Network Authentication Required',
	);
	
	/** @var array  */
	public $headers;
	
	/** @var mixed  */
	public $content;
	
	/** @var int  */
	public $status_code;
	
	/** @var mixed  */
	public $status_text;
	
	/** @var string */
	public $redirect;
	
	/** @var string */
	public $charset;
	
	/**
	 * Response constructor.
	 *
	 * @param string $content
	 * @param int $status
	 * @param array $headers
	 * @param string $redirect
	 */
	public function __construct($content = '', $status = 400, $headers = array(), $redirect = '') {
		$this->content = $content;
		$this->status_code = $status;
		$this->status_text = self::$status_texts[$status];
		$this->headers = $headers;
		$this->redirect = $redirect;
	}
	
	/**
	 * @return mixed
	 */
	public function getContent() {
		return $this->content;
	}
	
	/**
	 * @param mixed $content
	 * @return Response
	 */
	public function setContent($content) {
		$this->content = $content;
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function getHeaders() {
		return $this->headers;
	}
	
	/**
	 * @param array $headers
	 * @return Response
	 */
	public function setHeaders($headers) {
		foreach($headers as $key => $val) {
			$this->headers[$key] = $val;
		}
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getStatusCode() {
		return $this->status_code;
	}
	
	/**
	 * @param int $status_code
	 * @return Response
	 */
	public function setStatusCode($status_code) {
		$this->status_code = $status_code;
		$this->status_text = self::$status_texts[$status_code];
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getStatusText() {
		return $this->status_text;
	}
	
	/**
	 * @param mixed $status_text
	 * @return Response
	 */
	public function setStatusText($status_text) {
		$this->status_text = $status_text;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getRedirect() {
		return $this->redirect;
	}
	
	/**
	 * @param string $redirect
	 * @return Response
	 */
	public function setRedirect($redirect) {
		$this->redirect = $redirect;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getCharset() {
		return $this->charset;
	}
	
	/**
	 * @param string $charset
	 * @return Response
	 */
	public function setCharset($charset) {
		$this->charset = $charset;
		return $this;
	}
	
	public function send() {
		
		$out = [
			'status_code' => $this->status_code,
			'status_text' => $this->status_text,
			'content' => $this->content,
			'redirect' => $this->redirect
		];
		
		if(empty($this->headers['Content-Type'])) {
			$this->headers['Content-Type'] = "application/json";
		}
		
		$charset = !empty($this->charset) ? $this->charset: 'UTF-8';
		$this->headers['Content-Type'] = $this->headers['Content-Type'].'; charset='.$charset;
		
		foreach ($this->headers as $name => $value) {
			header($name.': '.$value, false, $this->status_code);
		}
		
		if(strpos($this->headers['Content-Type'], 'application/json') !== false) {
			exit(json_encode($out));
		} else {
			return $out;
		}
	}
	
}