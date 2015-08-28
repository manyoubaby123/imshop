<?php

namespace im\elasticsearch\components;

use im\search\components\index\Document;
use im\search\components\query\QueryInterface;

class QueryResult extends \im\search\components\query\QueryResult
{
    /**
     * @var array
     */
    private $_response;

    /**
     * @var float
     */
    private $_maxScore = 0;

    /**
     * @var int
     */
    private $_took = 0;

    /**
     * @var bool
     */
    private $_timedOut = false;

    function __construct(QueryInterface $query, array $response)
    {
        parent::__construct($query);
        $this->init($response);
    }

    /**
     * @inheritdoc
     */
    public function getObjects()
    {
        if (!$this->objects && $this->documents && $transformer = $this->getQuery()->getTransformer()) {
            $this->objects = $transformer->transform($this->documents);
        }

        return $this->objects;
    }

    protected function init(array $response)
    {
        $this->_response = $response;
        $this->total = isset($response['hits']['total']) ? $response['hits']['total'] : 0;
        $this->_maxScore = isset($response['hits']['max_score']) ? $response['hits']['max_score'] : 0;
        $this->_took = isset($response['took']) ? $response['took'] : 0;
        $this->_timedOut = !empty($response['timed_out']);
        if (isset($response['hits']['hits'])) {
            foreach ($response['hits']['hits'] as $hit) {
                $this->documents[] = new Document($hit['_id'], $hit['_source'], $hit['_type'], $hit['_index'], $hit['_score']);
            }
        }
        if (isset($response['aggregations'])) {
            $this->facets = $response['aggregations'];
        }
    }
}