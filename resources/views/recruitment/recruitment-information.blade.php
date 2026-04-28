@extends('layouts.app')

@section('title', 'Recruitment Information')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold" style="color: #0E334C;">Recruitment Information</h1>
        <div class="line" style="width: 80px; height: 3px; background: #3988BD; margin: 20px auto;"></div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <i class="fas fa-graduation-cap fa-2x mb-3" style="color: #3988BD;"></i>
                    <h4>New Graduate Recruitment</h4>
                    <p class="text-muted">Opportunities for fresh graduates.</p>
                    <span class="badge bg-info">Coming Soon</span>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <i class="fas fa-briefcase fa-2x mb-3" style="color: #3988BD;"></i>
                    <h4>Career Recruitment</h4>
                    <p class="text-muted">Experienced professional positions.</p>
                    <span class="badge bg-info">Coming Soon</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="alert alert-info mt-3">
        <i class="fas fa-info-circle"></i> Detailed job postings will be added soon.
    </div>
</div>
@endsection