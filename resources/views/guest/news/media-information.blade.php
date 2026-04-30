@extends('layouts.app')

@section('title', 'Media Information - News')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold" style="color: #0E334C;">Events & Activities</h1>
        <div class="line" style="width: 80px; height: 3px; background: #3988BD; margin: 20px auto;"></div>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-newspaper fa-3x mb-3" style="color: #3988BD;"></i>
                    <h5>Press Releases</h5>
                    <p class="text-muted">Latest company announcements.</p>
                    <span class="badge bg-info">Coming Soon</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-calendar-alt fa-3x mb-3" style="color: #3988BD;"></i>
                    <h5>Events</h5>
                    <p class="text-muted">Upcoming company events.</p>
                    <span class="badge bg-info">Coming Soon</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-video fa-3x mb-3" style="color: #3988BD;"></i>
                    <h5>Media Kits</h5>
                    <p class="text-muted">Downloadable resources.</p>
                    <span class="badge bg-info">Coming Soon</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection