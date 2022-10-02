<?php

namespace App\Services\Isbn;

use App\Model\Dto\Isbn\GetBookByIsbnResponse;
use App\Services\Utils\HttpClientInterface;
use Error;
use Exception;

class GetBookByIsbn
{
    public function __construct(
        private HttpClientInterface $httpClient
    ) {
    }

    public function __invoke(string $isbn): GetBookByIsbnResponse
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('https://openlibrary.org/isbn/%s.json', $isbn)
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new Exception('Error recuperando el libro');
        }
        $content = $response->getContent();
        // return of json to string json_decode, true for assosiative array
        $json = json_decode($content, true);
        return new GetBookByIsbnResponse(
            $json['title'],
            $json['publish_date'],
            $json['number_of_pages']
        );
    }
}
