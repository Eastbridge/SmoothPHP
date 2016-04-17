<?php

/* !
 * SmoothPHP
 * This file is part of the SmoothPHP project.
 * * * *
 * Copyright (C) 2016 Rens Rikkerink
 * License: https://github.com/Ikkerens/SmoothPHP/blob/master/License.md
 * * * *
 * TemplateState.php
 * Description
 */

namespace SmoothPHP\Framework\Templates\Compiler;

class CompilerState {
    public $vars;
    public $blocks;
    public $finishing;
    public $performCalls;

    public function __construct() {
        $this->vars = array();
        $this->blocks = array();
        $this->finishing = false;
        $this->performCalls = false;
    }
}
