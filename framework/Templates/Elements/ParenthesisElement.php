<?php

/* !
 * SmoothPHP
 * This file is part of the SmoothPHP project.
 * * * *
 * Copyright (C) 2016 Rens Rikkerink
 * License: https://github.com/Ikkerens/SmoothPHP/blob/master/License.md
 * * * *
 * ParenthesisElement.php
 * A list of output elements that are executed before their result is used.
 */

namespace SmoothPHP\Framework\Templates\Elements;

use SmoothPHP\Framework\Templates\TemplateState;
use SmoothPHP\Framework\Templates\Elements\PrimitiveElement;

class ParenthesisElement extends Element {
    private $element;
    
    public function __construct(Element $element) {
        $this->element = $element;
    }
    
    public function simplify(TemplateState $tpl) {
        $this->element = $this->element->simplify($tpl);
        if ($this->element instanceof PrimitiveElement) {
            return $this->element;
        } else
            return $this;
    }
}
