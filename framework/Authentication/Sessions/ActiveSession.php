<?php

/**
 * SmoothPHP
 * This file is part of the SmoothPHP project.
 * **********
 * Copyright © 2015-2020
 * License: https://github.com/Ikkerens/SmoothPHP/blob/master/License.md
 * **********
 * ActiveSession.php
 */

namespace SmoothPHP\Framework\Authentication\Sessions;

use SmoothPHP\Framework\Authentication\UserTypes\User;
use SmoothPHP\Framework\Database\Mapper\DBObjectMapper;
use SmoothPHP\Framework\Database\Mapper\MappedDBObject;

class ActiveSession extends MappedDBObject {

	const SESSION_KEY = 'sm_ases';

	private $userId;
	private $ip;
	private $selector;
	private $validator;
	private $csrf;

	public function __construct(User $user) {
		global $request;
		$this->userId = $user->getId();
		$this->ip = $request->getIP();

		$this->selector = bin2hex(random_bytes(16));
		$validator = random_bytes(72);
		// We base64 encode the validator before hashing, as otherwise dashboard server
		// cannot handle all sessions (~40% of the sessions would fail).
		// This seems to be due to different handling of a null byte.
		// By first base64 encoding we get rid of this pesky null byte.
		$this->validator = password_hash(base64_encode($validator), PASSWORD_DEFAULT);
		$this->csrf = base64_encode(random_bytes(128));

		setcookie(self::SESSION_KEY, sprintf('%s:%s', $this->selector, base64_encode($validator)),
			0, // This cookie expires at the end of the session
			'/', // This cookie applies to all sub-paths
			cookie_domain(), // Apply it to this host and all its subdomains
			false, // This cookie does not require HTTPS
			false); // This cookie can be transferred over non-HTTP
	}

	public function getTableName() {
		return 'sessions';
	}

	public function getUserId() {
		return $this->userId;
	}

	public static function readCookie(DBObjectMapper $map) {
		$headers = getallheaders();
		$cookieSet = isset($_COOKIE[self::SESSION_KEY]);
		if ($cookieSet || isset($headers['Authorization'])) {
			$value = null;
			if($cookieSet) {
				$value = explode(':', $_COOKIE[self::SESSION_KEY]);
			}else{
				$authorization = $headers['Authorization'];
				if(strpos($authorization, 'Bearer ') !== 0) {
					return null;
				}

				$value = explode(':',substr($authorization, 7));
			}

			if (count($value) != 2) {
				return null;
			}

			global $request;
			/* @var $session ActiveSession */
			$session = $map->fetch([
				// TODO fix
				//'ip'       => $request->getIP(),
				'selector' => $value[0]
			]);

			if (!$session)
				return null;

			if (!password_verify($value[1], $session->validator))
				return null;

			return $session;
		}

		return null;
	}

	public function getCSRF() {
		return $this->csrf;
	}
}