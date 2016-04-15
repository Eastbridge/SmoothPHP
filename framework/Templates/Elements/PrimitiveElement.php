<?php

/* !
 * SmoothPHP
 * This file is part of the SmoothPHP project.
 * * * *
 * Copyright (C) 2016 Rens Rikkerink
 * License: https://github.com/Ikkerens/SmoothPHP/blob/master/License.md
 * * * *
 * StringElement.php
 * Template element of a 'raw' string.
 */

namespace SmoothPHP\Framework\Templates\Elements;

use SmoothPHP\Framework\Templates\TemplateState;

class PrimitiveElement extends Element {
    private $value;
    
    public function __construct($value, $tryParse = false) {
        if ($tryParse && is_string($value)) {
            if (is_numeric($value))
                $value = $value + 0;
            else {
                switch($value) {
                    case "true":
                        $value = true;
                        break;
                    case "false":
                        $value = false;
                        break;
                }
            }
        }
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
    
    public function simplify(TemplateState $tpl) {
        return $this;
    }
    
}
