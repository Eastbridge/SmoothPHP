<?php

/**
 * SmoothPHP
 * This file is part of the SmoothPHP project.
 * **********
 * Copyright © 2015-2018
 * License: https://github.com/Ikkerens/SmoothPHP/blob/master/License.md
 * **********
 * MySQLStatementWithoutResult.php
 */

namespace SmoothPHP\Framework\Database\Statements;

class SQLStatementWithoutResult extends SQLStatement {

	public function createResult() {
		return $this->getStatement()->getInsertID();
	}

}
