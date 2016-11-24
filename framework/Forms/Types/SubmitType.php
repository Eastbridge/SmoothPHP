<?php

/* !
 * SmoothPHP
 * This file is part of the SmoothPHP project.
 * * * *
 * Copyright (C) 2016 Rens Rikkerink
 * License: https://github.com/Ikkerens/SmoothPHP/blob/master/License.md
 * * * *
 * SubmitType.php
 * Description
 */

namespace SmoothPHP\Framework\Forms\Types;

use SmoothPHP\Framework\Forms\Containers\Type;

class SubmitType extends Type {

    public function __construct($field, array $attributes) {
        parent::__construct($field, $attributes);
        $this->attributes = array_replace_recursive($this->attributes, array(
            'attr' => array(
                'type' => 'submit',
                'value' => $this->attributes['label']
            )
        ));
    }

}