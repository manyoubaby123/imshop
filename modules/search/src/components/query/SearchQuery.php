<?php

namespace im\search\components\query;

/**
 * Search query.
 *
 * @package im\search\components\query
 */
class SearchQuery implements SearchQueryInterface
{
    /**
     * @inheritdoc
     */
    public function isEmpty()
    {
        return false;
    }
}