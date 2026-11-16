<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

function guzzle(Request $request, ClientInterface $client, Client $concrete): void
{
    $client->request('GET', $request->input('url')); // unsafe
    $client->requestAsync('GET', $request->input('url')); // unsafe
    $concrete->get($request->input('url')); // unsafe
    $concrete->post($request->input('url')); // unsafe
    $concrete->getAsync($request->input('url')); // unsafe
    $client->request('GET', "{$request->input('issuer')}/.well-known/openid-configuration"); // unsafe
    $client->request($request->input('method'), 'https://example.com'); // safe: only the method
}

function laravel(Request $request, PendingRequest $http): void
{
    Http::get($request->input('url')); // unsafe
    Http::post($request->input('url'), ['a' => 1]); // unsafe
    Http::send('GET', $request->input('url')); // unsafe
    Http::baseUrl($request->input('url'))->get('/users'); // unsafe
    Http::withHeaders(['Accept' => 'application/json'])->get($request->input('url')); // unsafe
    $http->delete($request->input('url')); // unsafe
    Http::get('https://example.com', ['q' => $request->input('q')]); // safe: a query parameter
    Http::post('https://example.com', ['url' => $request->input('url')]); // safe: request data
}
