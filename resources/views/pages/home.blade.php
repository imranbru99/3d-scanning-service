@extends('layouts.app')

@section('title', 'Pointforge Reality Capture')
@section('meta_description', '3D laser scanning, as-built point clouds, and scan-to-CAD deliverables across Austin and Central Texas by Pointforge Reality Capture.')

@section('content')
<section class="hero">
    <div class="wrap hero-inner">
        <h1>Pointforge Reality Capture</h1>
        <p>LiDAR reality capture for builders, architects, and VDC teams — as-built scans, dense point clouds, and scan-to-CAD deliverables with field discipline and ERP-backed project tracking.</p>
        <div class="hero-ctas">
            <a class="btn btn-primary" href="{{ route('contact') }}">Request a scan quote</a>
            <a class="btn btn-ghost" href="{{ route('services') }}">View scanning services</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2>Reality capture services</h2>
        <p class="lede">As-built laser scanning, interior/MEP capture, scan-to-BIM, topo, clash verification, and phased progress documentation — scoped by scan day and deliverable tier.</p>
        <div class="grid-3">
            @foreach([
                ['building-as-built-laser-scanning','Building As-Built Laser Scanning','Shell and façade documentation with registered point clouds.','https://images.unsplash.com/photo-1486406146926-9300a8dcf259?auto=format&fit=crop&w=1200&q=80'],
                ['interior-mep-reality-capture','Interior / MEP Reality Capture','Dense interior scans for coordination and retrofits.','https://images.unsplash.com/photo-1581094794329-c811d9fab50c?auto=format&fit=crop&w=1200&q=80'],
                ['scan-to-cad-bim-deliverables','Scan-to-CAD / Scan-to-BIM','Modeled deliverables traced from registered scans.','https://images.unsplash.com/photo-1582719478250-c89cae7dc08b?auto=format&fit=crop&w=1200&q=80'],
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
        <h2>Why Pointforge</h2>
        <p class="lede">Central Texas crews that treat control, registration, and deliverable scope as part of the scan — not an afterthought once field capture ends.</p>
        <div class="grid-3">
            <div class="panel">
                <h3>Survey-minded capture</h3>
                <p>Control targets and overlap planning so registered clouds hold up in CAD and coordination software.</p>
            </div>
            <div class="panel">
                <h3>Clear deliverable tiers</h3>
                <p>Point cloud-only, TruView, and scan-to-BIM packages scoped before the first scan position is set.</p>
            </div>
            <div class="panel">
                <h3>ERP-run projects</h3>
                <p>Quotes, scan days, processing SKUs, invoices, and field appointments in one operations stack.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Ops dashboard preview</h2>
        <p class="lede">A glimpse of the Pointforge ERP used to schedule scans, track processing packages, and close invoices.</p>
        <div class="dash-preview">
            <div>
                <div class="grid-3" style="grid-template-columns:repeat(3,1fr)">
                    <div class="metric"><strong>14</strong> Active scan jobs</div>
                    <div class="metric"><strong>$142k</strong> Pipeline</div>
                    <div class="metric"><strong>9</strong> Field days booked</div>
                </div>
                <div class="bars">
                    <span style="height:45%"></span><span style="height:70%"></span><span style="height:55%"></span>
                    <span style="height:82%"></span><span style="height:60%"></span><span style="height:88%"></span>
                    <span style="height:72%"></span>
                </div>
            </div>
            <div>
                <h3 style="font-family:var(--font-display);margin-top:0;font-size:1.4rem">Live modules</h3>
                <ul style="line-height:1.9;padding-left:1.1rem;color:rgba(255,255,255,.86)">
                    <li>Scan jobs &amp; mobilization</li>
                    <li>Scanner / processing catalog</li>
                    <li>Invoices &amp; payments</li>
                    <li>AI content + troubleshooting</li>
                </ul>
                <a class="btn btn-primary" style="margin-top:1rem" href="/admin">Open admin ERP</a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>From the field</h2>
        <p class="lede">Notes on registration, BIM handoff, and reality capture workflows for Texas project teams.</p>
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