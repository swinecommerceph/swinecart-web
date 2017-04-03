<?php

namespace App\Repositories;

use App\Models\Product;
use App\Modesl\Breeder;
use Elasticsearch\Client;

class ElasticsearchProductRepository implements ProductRepository
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
     * Perform search on Elasticsearch search engine
     * then return a QueryBuilder
     *
     * @param   string      $query
     * @return  QueryBuilder
     */
    public function search(string $query="")
    {
        $items = $this->searchOnElastic($query);

        return $this->buildQueryBuilder($items);
    }

    /**
     * Performs search on Elasticsearch search engine
     * based on the query given by Customer
     *
     * @param   string      $query
     * @return  Array
     */
    private function searchOnElastic($query)
    {
        $instance = new Product;

        $items = $this->search->search([
            'index' => $instance->getSearchIndex(),
            'type' => $instance->getSearchType(),
            'body' =>[
                'query' => [
                    'multi_match' => [
                        'fields' => ['breeder_name', 'province', 'type', 'breed'],
                        'query' => $query
                    ]
                ]
            ]
        ]);

        return $items;
    }

    /**
     * Get the product ids from the hits found
     * on Elasticsearch search engine then
     * create a QueryBuilder
     *
     * @param   array       $items
     * @return  QueryBuilder
     */
    private function buildQueryBuilder(array $items)
    {
        $productIds = array_pluck($items['hits']['hits'], '_source.id') ? : [];
        $productsQueryBuilder = Product::whereIn('id', $productIds);

        return $productsQueryBuilder;
    }
}
