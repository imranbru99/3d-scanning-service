<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\KnowledgeArticle;
use App\Models\Post;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->take(3)->get();

        return view('pages.home', compact('posts'));
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function serviceShow(string $slug): View
    {
        $services = $this->serviceCatalog();
        abort_unless(isset($services[$slug]), 404);

        return view('services.show', [
            'slug' => $slug,
            'service' => $services[$slug],
            'services' => $services,
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create($data);

        return back()->with('success', 'Thanks! Pointforge will follow up within one business day with scan scope and mobilization details.');
    }

    public function blog(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function blogShow(string $slug): View
    {
        $post = Post::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('blog.show', compact('post'));
    }

    public function knowledge(): View
    {
        $articles = KnowledgeArticle::query()->where('is_published', true)->orderBy('category')->orderBy('title')->get()->groupBy('category');

        return view('kb.index', compact('articles'));
    }

    public function knowledgeShow(string $slug): View
    {
        $article = KnowledgeArticle::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('kb.show', compact('article'));
    }

    public function chat(Request $request, AiService $ai)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'session_id' => ['nullable', 'string', 'max:80'],
        ]);

        $result = $ai->chat($data['message'], $data['session_id'] ?? null);

        return response()->json($result);
    }

    /** @return array<string, array<string, string>> */
    protected function serviceCatalog(): array
    {
        return [
            'building-as-built-laser-scanning' => [
                'title' => 'Building As-Built Laser Scanning',
                'summary' => 'Millimeter-grade exterior and shell documentation with registered point clouds and control.',
                'body' => 'Pointforge captures façades, structural frames, and envelope geometry with terrestrial laser scanners. We establish survey control, run overlapping scan positions, and register clouds for architects and GCs who need trustworthy as-built dimensions across Austin and Central Texas.',
                'image' => 'https://images.unsplash.com/photo-1486406146926-9300a8dcf259?auto=format&fit=crop&w=1600&q=80',
            ],
            'interior-mep-reality-capture' => [
                'title' => 'Interior / MEP Reality Capture',
                'summary' => 'Dense interior scans for clash-ready MEP, ceilings, and occupied spaces.',
                'body' => 'We scan mechanical rooms, corridors, and tenant interiors with scan positions planned around access windows and safety requirements. Deliverables include colorized point clouds and extraction-ready datasets for coordination teams.',
                'image' => 'https://images.unsplash.com/photo-1581094794329-c811d9fab50c?auto=format&fit=crop&w=1600&q=80',
            ],
            'scan-to-cad-bim-deliverables' => [
                'title' => 'Scan-to-CAD / Scan-to-BIM Deliverables',
                'summary' => 'Revit, AutoCAD, and Navisworks-ready models traced from registered scans.',
                'body' => 'Pointforge converts point clouds into CAD linework or BIM elements with LOD scopes agreed up front. We document what is modeled versus left as cloud reference so your design team knows exactly what they are receiving.',
                'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae7dc08b?auto=format&fit=crop&w=1600&q=80',
            ],
            'site-civil-topographic-scanning' => [
                'title' => 'Site & Civil Topographic Scanning',
                'summary' => 'Ground and site topo captured for grading, utilities, and earthwork verification.',
                'body' => 'Terrestrial and mobile mapping workflows capture pads, utilities, and existing grades without waiting on aerial weather windows. Outputs support civil engineers, landscape architects, and contractors validating cut/fill quantities.',
                'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80',
            ],
            'clash-verification-field-scans' => [
                'title' => 'Clash Verification Field Scans',
                'summary' => 'Targeted field captures to prove install clearance before panels ship.',
                'body' => 'When the model and field disagree, we mobilize for focused scan zones — chases, racks, and penetrations — and deliver comparison-ready clouds for VDC teams. Ideal for late-stage MEP and prefab verification.',
                'image' => 'https://images.unsplash.com/photo-1541888946425-0e4a29a4a36a?auto=format&fit=crop&w=1600&q=80',
            ],
            'progress-sequence-reality-capture' => [
                'title' => 'Progress / Sequence Reality Capture',
                'summary' => 'Repeatable scan milestones for schedule accountability and owner reporting.',
                'body' => 'We return on agreed cadence — monthly, per floor, or per phase — with consistent control so change detection and progression visuals stay comparable. Great for healthcare, education, and mission-critical builds.',
                'image' => 'https://images.unsplash.com/photo-1581094271901-8022df4466f9?auto=format&fit=crop&w=1600&q=80',
            ],
        ];
    }
}