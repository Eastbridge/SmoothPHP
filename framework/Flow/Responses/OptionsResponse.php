<?php

/**
 * SmoothPHP
 * This file is part of the SmoothPHP project.
 * **********
 * Copyright © 2015-2020
 * License: https://github.com/Ikkerens/SmoothPHP/blob/master/License.md
 * **********
 * OptionsResponse.php
 */

namespace SmoothPHP\Framework\Flow\Responses;

use SmoothPHP\Framework\Core\Kernel;
use SmoothPHP\Framework\Flow\Requests\Request;

class OptionsResponse extends NoContentResponse {

	public function __construct() {
		parent::__construct();
	}

	public function build(Kernel $kernel, Request $request) {
	}

	protected function sendHeaders() {
		parent::sendHeaders();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Authorization');
	}

	protected function sendBody() {
	}

}