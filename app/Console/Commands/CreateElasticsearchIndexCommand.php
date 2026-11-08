<?php

namespace App\Console\Commands;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;

class CreateElasticsearchIndexCommand extends Command
{
    protected $signature = 'elastic:init-housing';

    protected $description = 'Initialize and map the Elasticsearch index for student housing properties';

    public function handle(): int
    {
        $hosts = explode(',', config('services.elasticsearch.hosts', 'http://elasticsearch:9200'));
        $client = ClientBuilder::create()->setHosts($hosts)->build();
        $indexName = 'properties_index';

        $this->info("Connecting to Elasticsearch at {$hosts[0]}...");

        // Delete if already exists
        if ($client->indices()->exists(['index' => $indexName])->asBool()) {
            $this->warn("Index '{$indexName}' already exists. Deleting it...");
            $client->indices()->delete(['index' => $indexName]);
        }

        // Create index with French analyzer and geo_point mapping
        $params = [
            'index' => $indexName,
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                    'analysis' => [
                        'filter' => [
                            'french_elision' => [
                                'type' => 'elision',
                                'articles_case' => true,
                                'articles' => ['l', 'm', 't', 'qu', 'n', 's', 'j', 'd', 'c', 'jusqu', 'quoiqu', 'lorsqu', 'puisqu'],
                            ],
                            'french_stop' => [
                                'type' => 'stop',
                                'stopwords' => '_french_',
                            ],
                            'french_stemmer' => [
                                'type' => 'stemmer',
                                'language' => 'french',
                            ],
                        ],
                        'analyzer' => [
                            'french_custom' => [
                                'tokenizer' => 'standard',
                                'filter' => [
                                    'french_elision',
                                    'lowercase',
                                    'french_stop',
                                    'french_stemmer',
                                ],
                            ],
                        ],
                    ],
                ],
                'mappings' => [
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'title' => [
                            'type' => 'text',
                            'analyzer' => 'french_custom',
                            'fields' => ['keyword' => ['type' => 'keyword']],
                        ],
                        'description' => [
                            'type' => 'text',
                            'analyzer' => 'french_custom',
                        ],
                        'type' => ['type' => 'keyword'],
                        'price_monthly' => ['type' => 'float'],
                        'surface_sqm' => ['type' => 'integer'],
                        'rooms_count' => ['type' => 'integer'],
                        'city' => [
                            'type' => 'text',
                            'analyzer' => 'standard',
                            'fields' => ['keyword' => ['type' => 'keyword']],
                        ],
                        'postal_code' => ['type' => 'keyword'],
                        'is_furnished' => ['type' => 'boolean'],
                        'is_available' => ['type' => 'boolean'],
                        'status' => ['type' => 'keyword'],
                        'location' => ['type' => 'geo_point'],
                        'amenities' => ['type' => 'keyword'],
                        'created_at' => ['type' => 'date'],
                    ],
                ],
            ],
        ];

        $client->indices()->create($params);
        $this->info("✅ Successfully created Elasticsearch index '{$indexName}' with geo_point and french analyzer mapping!");

        return Command::SUCCESS;
    }
}
