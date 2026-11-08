<?php

namespace App\Services\Elasticsearch;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PropertySearchService
{
    protected Client $client;

    protected string $indexName = 'properties_index';

    public function __construct()
    {
        $hosts = explode(',', config('services.elasticsearch.hosts', 'http://elasticsearch:9200'));
        $this->client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
    }

    /**
     * Search properties with multi-criteria filters and geo-location
     *
     * @param  array<string, mixed>  $criteria
     * @return array{results: Collection, total: int, facets: array<string, mixed>}
     */
    public function search(array $criteria = [], int $page = 1, int $perPage = 12): array
    {
        $from = ($page - 1) * $perPage;
        $must = [];
        $filter = [
            ['term' => ['status.keyword' => 'published']],
            ['term' => ['is_available' => true]],
        ];

        // 1. Full-text query with fuzziness
        if (!empty($criteria['query'])) {
            $must[] = [
                'multi_match' => [
                    'query' => $criteria['query'],
                    'fields' => ['title^3', 'city^2', 'description'],
                    'fuzziness' => 'AUTO',
                    'operator' => 'or',
                ],
            ];
        }

        // 2. City filter
        if (!empty($criteria['city'])) {
            $filter[] = ['term' => ['city.keyword' => $criteria['city']]];
        }

        // 3. Property Type filter
        if (!empty($criteria['type'])) {
            $filter[] = ['term' => ['type.keyword' => $criteria['type']]];
        }

        // 4. Price range filter
        if (!empty($criteria['min_price']) || !empty($criteria['max_price'])) {
            $range = [];
            if (!empty($criteria['min_price'])) {
                $range['gte'] = (float) $criteria['min_price'];
            }
            if (!empty($criteria['max_price'])) {
                $range['lte'] = (float) $criteria['max_price'];
            }
            $filter[] = ['range' => ['price_monthly' => $range]];
        }

        // 5. Rooms filter
        if (!empty($criteria['rooms'])) {
            $filter[] = ['term' => ['rooms_count' => (int) $criteria['rooms']]];
        }

        // 6. Furnished filter
        if (isset($criteria['furnished']) && $criteria['furnished'] !== '') {
            $filter[] = ['term' => ['is_furnished' => (bool) $criteria['furnished']]];
        }

        // 7. Geo-distance filter (e.g. proximity to student university)
        if (!empty($criteria['lat']) && !empty($criteria['lon']) && !empty($criteria['radius_km'])) {
            $filter[] = [
                'geo_distance' => [
                    'distance' => "{$criteria['radius_km']}km",
                    'location' => [
                        'lat' => (float) $criteria['lat'],
                        'lon' => (float) $criteria['lon'],
                    ],
                ],
            ];
        }

        // Build Elasticsearch payload
        $body = [
            'from' => $from,
            'size' => $perPage,
            'query' => [
                'bool' => [
                    'must' => !empty($must) ? $must : [['match_all' => new \stdClass]],
                    'filter' => $filter,
                ],
            ],
            // Faceted Aggregations
            'aggs' => [
                'avg_price' => ['avg' => ['field' => 'price_monthly']],
                'min_price' => ['min' => ['field' => 'price_monthly']],
                'max_price' => ['max' => ['field' => 'price_monthly']],
                'types' => ['terms' => ['field' => 'type.keyword']],
                'cities' => ['terms' => ['field' => 'city.keyword']],
            ],
        ];

        // Execute Elasticsearch Query
        try {
            $response = $this->client->search([
                'index' => $this->indexName,
                'body' => $body,
            ]);

            $hits = $response['hits']['hits'] ?? [];
            $total = $response['hits']['total']['value'] ?? 0;
            $aggs = $response['aggregations'] ?? [];

            $results = collect($hits)->map(fn ($hit) => array_merge(
                ['id' => (int) $hit['_id']],
                $hit['_source']
            ));

            return [
                'results' => $results,
                'total' => $total,
                'facets' => $aggs,
            ];
        } catch (\Throwable $e) {
            // Graceful fallback when Elasticsearch is starting or empty
            return [
                'results' => collect([]),
                'total' => 0,
                'facets' => [],
                'error' => $e->getMessage(),
            ];
        }
    }
}
