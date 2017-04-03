<?php

namespace App\Observers;

use Elasticsearch\Client;

class ElasticsearchObserver
{
    private $elasticsearch;

    /**
     * Setup Elasticsearch client
     *
     * @param   Client      $client
     */
    public function __construct(Client $client)
    {
        $this->elasticsearch = $client;
    }

    /**
     * Index certain attributes of the Product model
     * to the Elasticsearch search engine
     *
     * @param   Product     $model
     * @return  void
     */
    public function saved($model)
    {
        $this->elasticsearch->index([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'id' => $model->id,
            'body' => $model->toSearchArray()
        ]);
    }

    /**
     * Delete document in Elasticsearch search engine
     *
     * @param   Product     $model
     * @return  void
     */
    public function deleted($model)
    {
        $this->elasticsearch->delete([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'id' => $model->id
        ]);
    }
}
