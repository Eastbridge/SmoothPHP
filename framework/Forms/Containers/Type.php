<?php

/* !
 * SmoothPHP
 * This file is part of the SmoothPHP project.
 * * * *
 * Copyright (C) 2016 Rens Rikkerink
 * License: https://github.com/Ikkerens/SmoothPHP/blob/master/License.md
 * * * *
 * Type.php
 * Input type, refers to the type attribute of the <input> element
 */

namespace SmoothPHP\Framework\Forms\Containers;

use SmoothPHP\Framework\Flow\Requests\Request;
use SmoothPHP\Framework\Forms\Constraint;
use SmoothPHP\Framework\Forms\Constraints\RequiredConstraint;

abstract class Type extends Constraint {
    protected $field;
    protected $attributes;
    private $constraints;

    public function __construct($field) {
        $this->field = $field;
        $this->attributes = array(
            'label' => self::getLabel($field),
            'required' => true,
            'attr' => array(
                'class' => ''
            ),
            'constraints' => array()
        );
    }

    public function initialize(array $attributes) {
        $this->attributes = array_merge_recursive($this->attributes, $attributes);

        $this->constraints = array();
        foreach($this->attributes['constraints'] as $constraint) {
            if ($constraint instanceof Constraint)
                $this->constraints[] = $constraint;
            else
                $this->constraints[] = new $constraint();
        }

        if (last($this->attributes['required']))
            $this->constraints[] = new RequiredConstraint();

        foreach($this->constraints as $constraint) {
            $copy = $this->attributes;
            $constraint->setAttributes($copy);
            $this->attributes = array_replace_recursive($copy, $this->attributes);
        }
    }

    public function getContainer() {
        return array(
            'rowstart' => '<tr><td>',
            'label' => $this->generateLabel(),
            'rowseparator' => '</td><td>',
            'input' => $this,
            'rowend' => '</td></tr>'
        );
    }

    public function checkConstraint(Request $request, $name, $label, $value, array &$failReasons) {
        foreach($this->constraints as $constraint)
            /* @var $constraint Constraint */
            $constraint->checkConstraint($request, $name, $this->attributes['label'], $value, $failReasons);
        $this->attributes['attr']['value'] = $value;
    }

    public function getFieldName() {
        return $this->field;
    }

    public function setValue($value) {
        $this->attributes['attr']['value'] = $value;
    }

    public function generateLabel() {
        return sprintf('<label for="%s">%s</label>',
            $this->field,
            last($this->attributes['label']));
    }

    public function __toString() {
        $attributes = $this->attributes['attr'];

        $attributes['id'] = $this->field;
        $attributes['name'] = $this->field;

        return sprintf('<input %s />', $this->transformAttributes($attributes));
    }

    protected function transformAttributes(array $attributes) {
        $htmlAttributes = array();

        foreach ($attributes as $key => $attribute) {
            if ($key == 'class')
                $attribute = implode(' ', array_filter((array) $attribute));
            else
                $attribute = last($attribute);
            if (isset($attribute) && strlen($attribute) > 0)
                $htmlAttributes[] = sprintf('%s="%s"', $key, addcslashes($attribute, '"'));
        }

        return implode(' ', $htmlAttributes);
    }

    protected static function getLabel($field) {
        $pieces = preg_split('/(?=[A-Z])/', $field);
        array_map('strtolower', $pieces);
        $pieces[0] = ucfirst($pieces[0]);

        return implode(' ', $pieces);
    }
}