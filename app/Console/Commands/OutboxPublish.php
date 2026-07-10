<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OutboxPublish extends Command
{
    protected $signature = 'outbox:publish {--limit=50}';
    protected $description = 'Publish unpublished outbox events to Edge (print jobs)';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $edge = rtrim(config('services.edge.base_url'), '/');

        $events = DB::table('outbox_events')
            ->whereNull('published_at')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($events->isEmpty()) {
            $this->info('No unpublished events.');
            return self::SUCCESS;
        }

        foreach ($events as $e) {
            try {
                if ($e->event_type === 'print.receipt') {
                    $payload = json_decode($e->payload, true) ?: [];
                    $job = $payload['job'] ?? null;
                    if (!$job) {
                        throw new \RuntimeException('Missing job in payload');
                    }
                    $resp = Http::timeout(5)->post($edge . '/print/test', $job);
                    if ($resp->failed()) {
                        throw new \RuntimeException('Edge responded ' . $resp->status());
                    }
                    // Mark receipt printed_at when print published successfully
                    if ($e->aggregate_type === 'receipt' && $e->aggregate_id) {
                        DB::table('receipts')
                            ->where('id', $e->aggregate_id)
                            ->update(['printed_at' => now(), 'updated_at' => now()]);
                    }
                }

                DB::table('outbox_events')
                    ->where('id', $e->id)
                    ->update(['published_at' => now(), 'attempts' => DB::raw('attempts + 1'), 'updated_at' => now()]);

                $this->line("Published event #{$e->id} ({$e->event_type})");
            } catch (\Throwable $ex) {
                DB::table('outbox_events')
                    ->where('id', $e->id)
                    ->update([
                        'attempts' => DB::raw('attempts + 1'),
                        'last_error' => substr($ex->getMessage(), 0, 500),
                        'updated_at' => now(),
                    ]);
                $this->warn("Failed event #{$e->id}: {$ex->getMessage()}");
            }
        }

        return self::SUCCESS;
    }
}
