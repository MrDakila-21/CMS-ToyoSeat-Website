@extends('layouts.app')

@section('title', 'Inquiry')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold" style="color: #0E334C;">Inquiry</h1>
        <div class="line" style="width: 80px; height: 3px; background: #3988BD; margin: 20px auto;"></div>
        <p class="lead">Get in touch with us</p>
    </div>
    
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card shadow-sm border-0 p-4">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Your Name</label>
                        <input type="text" class="form-control" placeholder="Enter your name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" class="form-control" placeholder="Subject">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea rows="5" class="form-control" placeholder="Your message here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" style="background: #0E334C; border: none;">Send Message</button>
                </form>
                <div class="alert alert-warning mt-4 mb-0">
                    <i class="fas fa-exclamation-triangle"></i> This is a draft. Contact form functionality will be added soon.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection