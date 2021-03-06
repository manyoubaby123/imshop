<?php

namespace im\search\components\searchable;

use yii\base\Object;

class AttributeDescriptor extends Object
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $label;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var string
     */
    public $type;

    /**
     * @var array
     */
    public $params = [];
}