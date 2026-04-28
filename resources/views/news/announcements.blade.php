@extends('layouts.app')

@section('title', 'Announcements - News')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold" style="color: #0E334C;">Announcements</h1>
        <div class="line" style="width: 80px; height: 3px; background: #3988BD; margin: 20px auto;"></div>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="list-group">
                <div class="list-group-item border-0 shadow-sm mb-3">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-1">Welcome to Our New Website</h5>
                        <small class="text-muted">Coming Soon</small>
                    </div>
                    <p class="text-muted mt-2">We're excited to launch our new website. More announcements coming soon.</p>
                </div>
            </div>
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle"></i> Latest announcements will appear here.
            </div>
        </div>
    </div>
</div>
@endsection