<?php

namespace App\Provider;

use App\Interfaces\CustomerProviderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CustomerApiProvider implements CustomerProviderInterface
{
    /**
     * @var HttpClientInterface
     */
    protected $client;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    /**
     * @param HttpClientInterface $client
     * @param LoggerInterface     $logger
     */
    public function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }
    
    /**
     * @param array $filter
     *
     * @return array
     * @throws \Exception
     */
    public function getCustomers(array $filter): array
    {
        try {
            $response = $this->client->request('GET', 'https://randomuser.me/api/?' . http_build_query($filter));
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new \Exception('internal error');
        }
    
        try {
            return $response->toArray()['results'];
        } catch (ClientExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new \Exception('internal error');
        } catch (DecodingExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new \Exception('internal error');
        } catch (RedirectionExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new \Exception('internal error');
        } catch (ServerExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new \Exception('internal error');
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new \Exception('internal error');
        }
    }
}