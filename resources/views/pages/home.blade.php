@extends('layouts.app')

@section('title', 'Peakload Hauling Co.')
@section('meta_description', 'Dump truck dirt haul-away, aggregate delivery, construction spoil, and demo debris hauling across Denver and the Colorado Front Range by Peakload Hauling Co.')

@section('content')
<section class="hero">
    <div class="wrap hero-inner">
        <h1>Peakload<br>Hauling Co.</h1>
        <p>Tri-axle and end-dump trucks that move dirt, rock, and spoil on time. Material delivery and haul-away for builders, excavators, and homeowners across the Front Range.</p>
        <div class="hero-ctas">
            <a class="btn btn-primary" href="{{ route('contact') }}">Book a haul</a>
            <a class="btn btn-ghost" href="{{ route('services') }}">View hauling services</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2>Hauling services</h2>
        <p class="lede">Dirt haul-away, gravel delivery, demo spoil, site material transport, and same-day truck dispatch — priced by load and tonnage.</p>
        <div class="grid-3">
            @foreach([
                ['dirt-fill-hauling','Dirt & Fill Hauling','Export cut material or import engineered fill with weighed tickets.','https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=1200&q=80'],
                ['aggregate-delivery','Aggregate Delivery','Road base, rock, and sand delivered to the pad when you need it.','https://images.unsplash.com/photo-1590496793929-36417d3117de?auto=format&fit=crop&w=1200&q=80'],
                ['construction-spoil','Construction Spoil Removal','Keep excavations clear with staged dump-truck cycles off the site.','https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$blurb,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad">
                        <h3>{{ $title }}</h3>
                        <p>{{ $blurb }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Why Peakload</h2>
        <p class="lede">Front Range crews that treat truck timing, dump tickets, and site access as the job — not an afterthought.</p>
        <div class="grid-3">
            <div class="panel">
                <h3>Right truck, right load</h3>
                <p>End dumps and tri-axles matched to material type, load size, and restricted site access.</p>
            </div>
            <div class="panel">
                <h3>Ticketed tonnage</h3>
                <p>Weighed loads and clear haul logs so GCs can reconcile dirt and rock against the budget.</p>
            </div>
            <div class="panel">
                <h3>ERP-run dispatch</h3>
                <p>Quotes, truck days, inventory, invoices, and follow-ups live in one operations stack.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Ops dashboard preview</h2>
        <p class="lede">A glimpse of the Peakload ERP used to dispatch trucks, track aggregate inventory, and close invoices.</p>
        <div class="dash-preview">
            <div>
                <div class="grid-3" style="grid-template-columns:repeat(3,1fr)">
                    <div class="metric"><strong>22</strong> Active hauls</div>
                    <div class="metric"><strong>$186k</strong> Pipeline</div>
                    <div class="metric"><strong>16</strong> Dispatches</div>
                </div>
                <div class="bars">
                    <span style="height:40%"></span><span style="height:65%"></span><span style="height:50%"></span>
                    <span style="height:80%"></span><span style="height:55%"></span><span style="height:90%"></span>
                    <span style="height:70%"></span>
                </div>
            </div>
            <div>
                <h3 style="font-family:var(--font-display);text-transform:uppercase;margin-top:0;font-size:1.4rem">Live modules</h3>
                <ul style="line-height:1.9;padding-left:1.1rem;color:rgba(255,255,255,.86)">
                    <li>Jobs & truck dispatch</li>
                    <li>Aggregate / fuel inventory</li>
                    <li>Invoices & payments</li>
                    <li>AI content + troubleshooting</li>
                </ul>
                <a class="btn btn-primary" style="margin-top:1rem" href="/admin">Open admin ERP</a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>From the yard</h2>
        <p class="lede">Guides and field notes for builders, excavators, and property managers.</p>
        <div class="grid-3">
            @forelse($posts as $post)
                <a class="panel" href="{{ route('blog.show', $post->slug) }}">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->excerpt }}</p>
                </a>
            @empty
                <div class="panel"><p>Blog posts will appear after seeding.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
