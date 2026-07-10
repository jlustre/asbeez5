<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class Outbox
{
    public static function enqueue(string $aggregateType, int $aggregateId, string $eventType, array $payload = [], array $headers = []): void
    {
        DB::table('outbox_events')->insert([
            'aggregate_type' => $aggregateType,
            'aggregate_id' => $aggregateId,
            'event_type' => $eventType,
            'payload' => json_encode($payload),
            'headers' => $headers ? json_encode($headers) : null,
            'queued_at' => now(),
            'published_at' => null,
            'attempts' => 0,
            'last_error' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
