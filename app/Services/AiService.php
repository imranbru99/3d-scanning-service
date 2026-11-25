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
            'q' => 'cost price estimate scan day rate point cloud',
            'a' => 'Central Texas laser scanning is usually quoted per scan day, processing tier, and deliverable format. Building size, interior complexity, and BIM LOD drive the number. Call (512) 555-0148 or email hello@pointforgereality.com with address and scope for a same-day estimate.',
        ],
        [
            'q' => 'accuracy tolerance millimeter lidar terrestrial',
            'a' => 'Pointforge registers scans to project control. Typical terrestrial workflows achieve survey-grade registration when control is solid; we document expected tolerance bands in the statement of work so VDC teams can plan accordingly.',
        ],
        [
            'q' => 'services as-built bim cad topo interior mep',
            'a' => 'Pointforge offers building as-built laser scanning, interior/MEP reality capture, scan-to-CAD/BIM, site & civil topo scanning, clash verification field scans, and progress/sequence capture across Austin and Central Texas.',
        ],
        [
            'q' => 'deliverable format e57 rcp las revit autocad',
            'a' => 'Common deliverables include registered point clouds (.e57, .rcp, .las), TruView or web viewers, and modeled CAD/BIM per scope. Tell us your downstream toolchain so we export the right formats and coordinates.',
        ],
        [
            'q' => 'schedule appointment mobilization contact same day',
            'a' => 'Call, WhatsApp +1 512-555-0148, or use the contact form. Austin metro mobilization is often available within a few business days; rush interior scans depend on access and scanner availability.',
        ],
        [
            'q' => 'bim lod level detail modeling scope',
            'a' => 'Scan-to-BIM LOD is defined before modeling starts — what gets traced as walls, ducts, or pipe versus left as cloud reference. Pointforge scopes LOD so invoices match what coordination teams actually need.',
        ],
        [
            'q' => 'service area austin round rock central texas',
            'a' => 'Pointforge covers Austin, Round Rock, Cedar Park, San Marcos, Georgetown, and wider Central Texas corridors. Ask about multi-day hotel corridors and phased campus work.',
        ],
        [
            'q' => 'occupancy access safety interior scan',
            'a' => 'Occupied buildings need escorts, lockout windows, and sometimes night shifts. Share HVAC shutdown needs and PPE rules when you book so scan positions stay efficient and safe.',
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
            'blog' => "Write a concise SEO blog post outline and intro for a 3D laser scanning / reality capture company about: {$prompt}",
            'description' => "Write a compelling product/service description for 3D laser scanning: {$prompt}",
            'automation' => "Suggest 5 practical AI automations for a reality capture SME ERP related to: {$prompt}",
            'troubleshoot' => "Troubleshoot this 3D scanning operations issue with clear steps: {$prompt}",
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

        return 'Thanks for reaching Pointforge Reality Capture. We deliver LiDAR as-built scans, point clouds, and scan-to-CAD/BIM across Austin and Central Texas. Ask about scan-day pricing, deliverable formats, accuracy, interior access, or scheduling—or call (512) 555-0148.';
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
                        'content' => 'You are the Pointforge Reality Capture assistant for a Central Texas 3D laser scanning and reality capture company. Be concise, practical, and focused on as-built scans, point clouds, scan-to-CAD/BIM, topo, clash verification, and mobilization. Never invent legal or survey licensure claims.',
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
                            ['text' => "You are Pointforge Reality Capture assistant (Austin 3D laser scanning). Answer briefly:\n{$message}"],
                        ],
                    ],
                ],
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'candidates.0.content.parts.0.text', 'Unable to generate a response.');
    }
}