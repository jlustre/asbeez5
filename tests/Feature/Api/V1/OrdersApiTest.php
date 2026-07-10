<?php

use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Str;

it('creates an order stub', function () {
    $response = \Pest\Laravel\postJson('/api/v1/orders', [
        'order' => []
    ], ['Idempotency-Key' => (string) Str::ulid()]);

    $response->assertCreated()
        ->assertJson(fn(AssertableJson $json) => $json
            ->has('public_id')
            ->where('status', 'open')
        );
});

it('requires idempotency key for writes', function () {
    $response = \Pest\Laravel\postJson('/api/v1/orders', ['order' => []]);
    $response->assertStatus(400);
});
