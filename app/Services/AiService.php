<?php

namespace App\Services;

use App\Models\ChatLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiService
{
    /** @var array<int, array{q: string, a: string}> */
    protected array $faq = [
        [
            'q' => 'cost price estimate ton load haul dirt',
            'a' => 'Front Range dump-truck hauling is usually quoted per load or per ton plus dump fees. Distance to dump, truck size, wait time, and material type all matter. Call (303) 555-0172 with the address and material for a same-day estimate.',
        ],
        [
            'q' => 'truck size tri axle end dump capacity',
            'a' => 'Peakload runs end dumps and tri-axles sized for dirt, rock, and spoil. Typical loads depend on material density and legal weight — we match the truck to site access and the receiving facility.',
        ],
        [
            'q' => 'services dirt gravel aggregate spoil demo delivery',
            'a' => 'Peakload offers dirt & fill hauling, aggregate delivery, construction spoil removal, demo debris hauling, on-site material transport, and same-day dispatch across Denver and the Front Range.',
        ],
        [
            'q' => 'dump ticket landfill facility fee',
            'a' => 'We confirm dump destinations and gate fees before the first load. Clean dirt, rock, concrete, and mixed C&D may go to different facilities — tell us the material so tickets stay clean.',
        ],
        [
            'q' => 'schedule appointment dispatch contact same day',
            'a' => 'Call or text (303) 555-0172, use the contact form, or WhatsApp us. Denver metro and Front Range dispatches are often available within 1–2 business days; same-day when the fleet has open slots.',
        ],
        [
            'q' => 'wait time standby demurrage loader',
            'a' => 'Quoted rates assume reasonable load/unload cycles. Extended standby waiting on a loader or gate can add hourly charges — we flag that on the ticket and the invoice.',
        ],
        [
            'q' => 'service area denver aurora boulder colorado springs',
            'a' => 'Peakload covers Denver, Aurora, Boulder, Colorado Springs, and surrounding Front Range corridors. Ask about staging for multi-day excavation cycles.',
        ],
        [
            'q' => 'aggregate gravel road base sand delivery',
            'a' => 'We deliver common Front Range aggregates — road base, gravel, sand, and recycled concrete — by the ton or load. Share product and drop location for timing.',
        ],
    ];

    public function chat(string $message, ?string $sessionId = null): array
    {
        $reply = null;
        $source = 'demo';

        if ($key = config('services.openai.key')) {
            try {
                $reply = $this->callOpenAi($message, $key);
                $source = 'openai';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply && ($gemini = config('services.gemini.key'))) {
            try {
                $reply = $this->callGemini($message, $gemini);
                $source = 'gemini';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply) {
            $reply = $this->demoReply($message);
            $source = 'demo';
        }

        ChatLog::query()->create([
            'session_id' => $sessionId,
            'message' => $message,
            'reply' => $reply,
            'source' => $source,
        ]);

        return ['reply' => $reply, 'source' => $source];
    }

    public function generate(string $type, string $prompt): string
    {
        $message = match ($type) {
            'blog' => "Write a concise SEO blog post outline and intro for a dump truck hauling company about: {$prompt}",
            'description' => "Write a compelling product/service description for dump truck hauling: {$prompt}",
            'automation' => "Suggest 5 practical AI automations for a hauling SME ERP related to: {$prompt}",
            'troubleshoot' => "Troubleshoot this dump truck hauling operations issue with clear steps: {$prompt}",
            default => $prompt,
        };

        $result = $this->chat($message);

        return $result['reply'];
    }

    protected function demoReply(string $message): string
    {
        $hay = Str::lower($message);
        $best = null;
        $bestScore = 0;

        foreach ($this->faq as $item) {
            $score = 0;
            foreach (explode(' ', $item['q']) as $word) {
                if ($word !== '' && str_contains($hay, $word)) {
                    $score++;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $item['a'];
            }
        }

        if ($best && $bestScore > 0) {
            return $best;
        }

        return 'Thanks for reaching Peakload Hauling Co. We haul dirt, rock, spoil, and demo debris across Denver and the Front Range. Ask about truck sizes, tonnage pricing, dump tickets, aggregate delivery, or scheduling—or call (303) 555-0172 to book a haul.';
    }

    protected function callOpenAi(string $message, string $key): string
    {
        $response = Http::withToken($key)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are the Peakload Hauling Co. assistant for a Denver / Colorado Front Range dump truck hauling company. Be concise, practical, and focused on dirt haul-away, aggregate delivery, construction spoil, demo debris, truck dispatch, and dump tickets. Never invent illegal claims.',
                    ],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.4,
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'choices.0.message.content', 'Unable to generate a response.');
    }

    protected function callGemini(string $message, string $key): string
    {
        $model = config('services.gemini.model', 'gemini-1.5-flash');
        $response = Http::timeout(30)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "You are Peakload Hauling Co. assistant (Denver dump truck hauling). Answer briefly:\n{$message}"],
                        ],
                    ],
                ],
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'candidates.0.content.parts.0.text', 'Unable to generate a response.');
    }
}