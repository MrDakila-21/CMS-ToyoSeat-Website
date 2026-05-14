{{-- resources/views/guest/about/business-introduction.blade.php --}}
@extends('layouts.app')

@section('title', 'Business Introduction - About Us')

@section('content')
<style>
    .business-header {
        background: linear-gradient(135deg, #0E334C 0%, #1a5a7a 100%);
        color: white;
        padding: 60px 0;
        margin-bottom: 50px;
    }
    
    .section-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #0E334C;
        position: relative;
        padding-bottom: 15px;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: #3988BD;
    }
    
    .text-center .section-title:after {
        left: 50%;
        transform: translateX(-50%);
    }
    
    .automotive-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .automotive-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .organization-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .organization-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .organization-card img {
        transition: transform 0.3s ease;
    }
    
    .organization-card:hover img {
        transform: scale(1.05);
    }
    
    .characteristic-icon {
        font-size: 3rem;
        color: #3988BD;
        margin-bottom: 1rem;
    }
    
    .characteristic-card {
        text-align: center;
        padding: 30px 20px;
        border-radius: 15px;
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid #e0e0e0;
    }
    
    .characteristic-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transform: translateY(-5px);
    }
    
    .partnership-logo {
        padding: 20px;
        background: white;
        border-radius: 10px;
        transition: all 0.3s ease;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e0e0e0;
    }
    
    .partnership-logo:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: #3988BD;
    }
    
    .partnership-logo img {
        max-width: 100%;
        max-height: 100px;
        object-fit: contain;
    }
    
    .badge-custom {
        background: #3988BD;
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        margin-bottom: 20px;
        display: inline-block;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #3988BD;
    }
    
    @media (max-width: 768px) {
        .section-title {
            font-size: 1.5rem;
        }
        
        .business-header {
            padding: 40px 0;
        }
    }
</style>

<div class="business-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">Business Introduction</h1>
                <div class="line-custom" style="width: 80px; height: 3px; background: #3988BD; margin: 20px auto;"></div>
                <p class="lead">Discover our comprehensive business portfolio and commitment to excellence</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Automotive Seat Cover Section -->
    @if($automotiveSeats->count() > 0)
    <div class="mb-5">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="section-title">Automotive Seat Cover</h2>
                <p class="text-muted">Premium quality seat covers designed for comfort and durability</p>
            </div>
        </div>
        <div class="row">
            @foreach($automotiveSeats as $seat)
            <div class="col-md-6 mb-4">
                <div class="card automotive-card h-100">
                    <div class="row g-0 h-100">
                        @if($seat->image)
                        <div class="col-md-5">
                            <img src="{{ $seat->image_url }}" class="img-fluid rounded-start h-100" alt="{{ $seat->title }}" style="object-fit: cover;">
                        </div>
                        @endif
                        <div class="col-md-7">
                            <div class="card-body">
                                <h5 class="card-title">{{ $seat->title }}</h5>
                                <p class="card-text">{{ $seat->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Organization Structure Section -->
    @if($organizationMembers->count() > 0)
    <div class="mb-5">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">Organizational Structure</h2>
                <p class="text-muted">Meet our dedicated leadership team</p>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($organizationMembers as $member)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card organization-card h-100 text-center">
                    @if($member->image)
                    <div class="overflow-hidden">
                        <img src="{{ $member->image_url }}" class="card-img-top" alt="{{ $member->name }}" style="height: 250px; object-fit: cover;">
                    </div>
                    @else
                    <div class="bg-light p-5 text-center">
                        <i class="fas fa-user-circle fa-4x text-muted"></i>
                    </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $member->name }}</h5>
                        <p class="text-primary mb-0">{{ $member->position }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Characteristics Section -->
    @if($characteristics->count() > 0)
    <div class="mb-5 py-4" style="background: #f8f9fa; border-radius: 20px;">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="section-title">Business Characteristics</h2>
                    <p class="text-muted">What makes us unique in the industry</p>
                </div>
            </div>
            <div class="row">
                @foreach($characteristics as $characteristic)
                <div class="col-md-4 mb-4">
                    <div class="characteristic-card">
                        @if($characteristic->image)
                        <img src="{{ $characteristic->image_url }}" alt="{{ $characteristic->title }}" style="width: 80px; height: 80px; object-fit: contain; margin-bottom: 1rem;">
                        @else
                        <div class="characteristic-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        @endif
                        <h4>{{ $characteristic->title }}</h4>
                        <p class="text-muted">{{ $characteristic->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Partnership Section -->
    @if($partnerships->count() > 0)
    <div class="mb-5">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">Our Partners</h2>
                <p class="text-muted">Trusted partnerships that drive excellence</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            @foreach($partnerships as $partner)
            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                <div class="partnership-logo">
                    @if($partner->image)
                    <img src="{{ $partner->image_url }}" alt="{{ $partner->title }}" class="img-fluid">
                    @else
                    <div class="text-center">
                        <i class="fas fa-building fa-3x text-muted"></i>
                        <p class="mt-2 mb-0 small">{{ $partner->title }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection