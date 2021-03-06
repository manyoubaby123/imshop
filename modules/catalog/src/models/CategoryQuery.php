<?php

namespace im\catalog\models;

use im\tree\models\TreeQuery;

/**
 * Class CategoryQuery
 * @package im\catalog\models
 * @method CategoryQuery roots
 * @method CategoryQuery leaves
 */
class CategoryQuery extends TreeQuery
{
    public function status($status)
    {
        $this->andWhere(['status' => $status]);

        return $this;
    }

    public function active()
    {
        $this->andWhere(['status' => Category::STATUS_ACTIVE]);

        return $this;
    }

    public function inactive()
    {
        $this->andWhere(['status' => Category::STATUS_INACTIVE]);

        return $this;
    }
}