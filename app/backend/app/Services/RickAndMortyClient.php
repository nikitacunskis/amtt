<?php

namespace App\Services;

use App\Services\ExternalApiService;
use App\Interfaces\RickAndMortyClientInterface;

class RickAndMortyClient extends ExternalApiService implements RickAndMortyClientInterface
{

    public function __construct(ExternalApiService $api)
    {
        $this->setBaseUrl('https://rickandmortyapi.com/api');
    }

    public function list(int $page = 1): array
    {
        $response = $this->get('/character', ['page' => $page]);
    
        $info = $response['info'] ?? [];
        $results = $response['results'] ?? [];
    
        return [
            'info' => [
                'count' => $info['count'] ?? 0,
                'pages' => $info['pages'] ?? 0,
                'next' => isset($info['next']) ? $this->convertToLocalUrl($info['next']) : null,
                'prev' => isset($info['prev']) ? $this->convertToLocalUrl($info['prev']) : null,
            ],
            'results' => $results,
        ];
    }
    
    protected function convertToLocalUrl(string $originalUrl): string
    {
        $query = parse_url($originalUrl, PHP_URL_QUERY); // gives "page=2"
        parse_str($query, $params);
        $page = $params['page'] ?? 1;
        return url("/api/rick-and-morty/{$page}");
    }
    

    public function showCharacter(string $id): array
    {
        return $this->get("/character/{$id}");
    }

    public function showLocation(string $id): array
    {
        return $this->get("/location/{$id}");
    }

    public function showEpisode(string $id): array
    {
        return $this->get("/episode/{$id}");
    }
    
    public function search(array $filters): array
    {
        try {
            $query = <<<GQL
            query SearchCharacters(\$name: String, \$status: String) {
                characters(filter: { name: \$name, status: \$status }) {
                    results {
                        id
                        name
                        status
                        species
                        type
                        gender
                        origin {
                            id
                        }
                        location {
                            id
                        }
                        image
                        episode {
                            id
                        }
                        created
                    }
                }
            }
            GQL;

            $variables = [
                'name' => $filters['name'] ?? null,
                'status' => $filters['status'] ?? null,
            ];

            $response = $this->post('/graphql', [
                'query' => $query,
                'variables' => $variables,
            ]);

            if (isset($response['errors'])) {
                \Log::error('GraphQL returned errors:', $response['errors']);
            }

            return $response['data']['characters']['results'] ?? [];
        } catch (\Exception $e) {
            \Log::error('RickAndMortyClient::search() error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function resolveOrCreateCustomer(array $order, string $targetConnectionName, string $sourceConnectionName, array $sourceLanguages, array $targetLanguages): array
    {
        return CustomerMapping::getTarget($mapping, $order['customer_id']) ??
            CustomerMapping::insertGhost(
                [
                    'customer_id' => $order['customer_id'],
                    'tenant_id' => $order['tenant_id'] ?? 0,
                    'failed_login_date' => null,
                    'gms_client_id' => $order['gms_client_id'] ?? 0,
                    'language_id' => $targetLanguages[$sourceLanguages[$order['language_id']]->code2]->value ?? 0,
                    'firstname' => $order['firstname'] ?? '',
                    'lastname' => $order['lastname'] ?? '',
                    'email' => $order['email'] ?? '',
                    'telephone' => $order['telephone'] ?? '',
                    'telephone_code' => $order['telephone_code'] ?? '',
                    'ccID' => $order['ccID'] ?? 0,
                ],
                $targetConnectionName,
                $sourceConnectionName
            );
    }

    private function resolveLanguages(Connection $sourceConnection, Connection $targetConnection): array
    {
        $sourceLanguages = $sourceConnection->table('gr_language')
            ->get()
            ->keyBy('language_id')
            ->toArray();
        Log::info('Resolved source languages', ['source_languages_count' => count($sourceLanguages)]);

        $targetLanguages = $targetConnection->table('lookup_values')
            ->where('context', 'language')
            ->get(['key', 'value'])
            ->keyBy('key')
            ->toArray();
        Log::info('Resolved target languages', ['target_languages_count' => count($targetLanguages)]);

        return [$sourceLanguages, $targetLanguages];
    }
}