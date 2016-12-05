<?php

/* !
 * SmoothPHP
 * This file is part of the SmoothPHP project.
 * * * *
 * Copyright (C) 2016 Rens Rikkerink
 * License: https://github.com/Ikkerens/SmoothPHP/blob/master/License.md
 * * * *
 * AuthenticationManager.php
 * Main class for handling authentication.
 */

namespace SmoothPHP\Framework\Authentication;

use SmoothPHP\Framework\Database\Mapper\MappedMySQLObject;
use SmoothPHP\Framework\Flow\Requests\Request;

class LoginSession extends MappedMySQLObject {

    const STATE_NEW = 0;

    private $ip;
    private $token;
    private $state;
    private $lastUpdate;
    private $failedAttempts;

    public function __construct(Request $request) {
        $this->ip = $request->server->REMOTE_ADDR;
        $this->token = base64_encode(openssl_random_pseudo_bytes(128));
        $this->state = self::STATE_NEW;
        $this->lastUpdate = time();
        $this->failedAttempts = 0;
    }

    public function getToken() {
        return $this->token;
    }

    public function getLastUpdate() {
        return $this->lastUpdate;
    }

    public function getFailedAttempts() {
        return $this->failedAttempts;
    }

    public function increaseFailure() {
        $this->lastUpdate = time();
        $this->failedAttempts++;
    }

}