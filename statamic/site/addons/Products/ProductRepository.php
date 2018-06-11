<?php

namespace Statamic\Addons\Products;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ProductRepository
{
    protected $guzzle;

    public function __construct()
    {
        $this->guzzle = new Client([
            'base_uri' => env('LARAVEL_URL')
        ]);
    }

    public function all()
    {
        return $this->fromResponseJson(
            $this->guzzle->get('/products')
        );
    }

    public function find($id)
    {
        return $this->fromResponseJson(
            $this->guzzle->get('/products/' . $id)
        );
    }

    public function findBySlug($slug)
    {
        return $this->fromResponseJson(
            $this->guzzle->get('/products/slug/' . $slug)
        );
    }

    public function store($data)
    {
        try {
            return [
                'success' => true,
                'product' => $this->postJson('/products', $data)
            ];
        } catch (ClientException $e) {
            return [
                'success' => false,
                'errors' => $this->prefixErrors($this->fromResponseJson($e->getResponse())['errors']),
            ];
        }
    }

    public function update($id, $data)
    {
        try {
            return [
                'success' => true,
                'product' => $this->putJson('/products/' . $id, $data)
            ];
        } catch (ClientException $e) {
            return [
                'success' => false,
                'errors' => $this->prefixErrors($this->fromResponseJson($e->getResponse())['errors']),
            ];
        }
    }

    private function postJson($url, $payload)
    {
        return $this->fromResponseJson($this->requestJson('POST', $url, $payload));
    }

    private function putJson($url, $payload)
    {
        return $this->fromResponseJson($this->requestJson('PUT', $url, $payload));
    }

    private function requestJson($method, $url, $payload)
    {
        return $this->guzzle->request($method, $url, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => $payload
        ]);
    }

    private function fromResponseJson($response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    private function prefixErrors($errors)
    {
        return collect($errors)->mapWithKeys(function ($value, $key) {
            return ['fields.'.$key => $value];
        })->all();
    }
}
