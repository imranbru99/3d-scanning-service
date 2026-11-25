@extends('layouts.app')
@section('title', '3D Laser Scanning Services')
@section('content')
<section class="section">
    <div class="wrap">
        <h2>Services</h2>
        <p class="lede">Pointforge delivers as-built scans, interior reality capture, scan-to-BIM, topo, clash verification, and progress documentation across Austin and Central Texas.</p>
        <div class="grid-3">
            @foreach([
                ['building-as-built-laser-scanning','Building As-Built Laser Scanning','https://images.unsplash.com/photo-1486406146926-9300a8dcf259?auto=format&fit=crop&w=1200&q=80'],
                ['interior-mep-reality-capture','Interior / MEP Reality Capture','https://images.unsplash.com/photo-1581094794329-c811d9fab50c?auto=format&fit=crop&w=1200&q=80'],
                ['scan-to-cad-bim-deliverables','Scan-to-CAD / Scan-to-BIM','https://images.unsplash.com/photo-1582719478250-c89cae7dc08b?auto=format&fit=crop&w=1200&q=80'],
                ['site-civil-topographic-scanning','Site & Civil Topographic Scanning','https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80'],
                ['clash-verification-field-scans','Clash Verification Field Scans','https://images.unsplash.com/photo-1541888946425-0e4a29a4a36a?auto=format&fit=crop&w=1200&q=80'],
                ['progress-sequence-reality-capture','Progress / Sequence Reality Capture','https://images.unsplash.com/photo-1581094271901-8022df4466f9?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad"><h3>{{ $title }}</h3></div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection