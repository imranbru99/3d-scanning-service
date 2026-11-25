@extends('layouts.app')
@section('title', 'Dump Truck Hauling Services')
@section('content')
<section class="section">
    <div class="wrap">
        <h2>Services</h2>
        <p class="lede">Peakload moves dirt, rock, spoil, and demo debris across Denver, Aurora, Boulder, Colorado Springs, and the Front Range.</p>
        <div class="grid-3">
            @foreach([
                ['dirt-fill-hauling','Dirt & Fill Hauling','https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=1200&q=80'],
                ['aggregate-delivery','Aggregate Delivery','https://images.unsplash.com/photo-1590496793929-36417d3117de?auto=format&fit=crop&w=1200&q=80'],
                ['construction-spoil','Construction Spoil Removal','https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80'],
                ['demo-debris-hauling','Demo Debris Hauling','https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=80'],
                ['site-material-transport','Site Material Transport','https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=1200&q=80'],
                ['same-day-dispatch','Same-Day Truck Dispatch','https://images.unsplash.com/photo-1621905251918-48416bd8575a?auto=format&fit=crop&w=1200&q=80'],
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
