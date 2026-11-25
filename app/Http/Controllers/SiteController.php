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

        return back()->with('success', 'Thanks! Peakload will follow up within one business day.');
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
            'dirt-fill-hauling' => [
                'title' => 'Dirt & Fill Hauling',
                'summary' => 'Export cut material or import engineered fill with weighed dump tickets.',
                'body' => 'Peakload stages end-dump and tri-axle trucks for cut/fill cycles on Front Range jobsites. We coordinate dump destinations, track tonnage tickets, and keep loaders fed so excavators are never waiting on trucks.',
                'image' => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=1600&q=80',
            ],
            'aggregate-delivery' => [
                'title' => 'Aggregate Delivery',
                'summary' => 'Road base, rock, sand, and recycled concrete delivered to the pad.',
                'body' => 'Order by the ton or by the load. Peakload sources common Front Range aggregates and drops them where your crew needs them — driveways, pads, trenches, and staging yards included.',
                'image' => 'https://images.unsplash.com/photo-1590496793929-36417d3117de?auto=format&fit=crop&w=1600&q=80',
            ],
            'construction-spoil' => [
                'title' => 'Construction Spoil Removal',
                'summary' => 'Keep excavations clear with staged dump-truck cycles off the site.',
                'body' => 'From foundation digs to utility trenches, we haul spoil to approved facilities. Tell us soil type and access constraints and we will size the truck and dump plan before the first load leaves.',
                'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80',
            ],
            'demo-debris-hauling' => [
                'title' => 'Demo Debris Hauling',
                'summary' => 'Move demolition debris and mixed construction waste with the right dump ticket.',
                'body' => 'Peakload hauls demo loads that fit dump-truck specs — concrete chunks, asphalt millings, and mixed C&D when the receiving facility allows. We confirm dump fees and material rules up front so there are no surprises at the gate.',
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1600&q=80',
            ],
            'site-material-transport' => [
                'title' => 'Site Material Transport',
                'summary' => 'Shuttle rock, dirt, and stockpile material between pads and yards.',
                'body' => 'Need material moved from stockpile A to pad B without leaving the project? We run short-haul cycles with trucks sized for site roads and turnarounds.',
                'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=1600&q=80',
            ],
            'same-day-dispatch' => [
                'title' => 'Same-Day Truck Dispatch',
                'summary' => 'When a loader is waiting, Peakload can often put a truck on the road today.',
                'body' => 'Call early with address, material, and dump preference. Subject to fleet availability across Denver, Aurora, Boulder, and Colorado Springs corridors.',
                'image' => 'https://images.unsplash.com/photo-1621905251918-48416bd8575a?auto=format&fit=crop&w=1600&q=80',
            ],
        ];
    }
}