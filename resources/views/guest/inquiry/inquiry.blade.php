@extends('layouts.app')

@section('title', 'Inquiry')

@section('content')
@vite(['resources/css/inqury.css'])
<link rel="stylesheet" href="{{ asset('css/dash.css') }}">

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold inquiry-title">Contact Us</h1>
        <div class="line"></div>
        <p class="lead text-muted">We'll get back to you within 24 hours</p>
    </div>

    <div class="row g-4">
        <!-- Contact Information Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm corporate-card h-100">
                <div class="card-body p-4">
                    <h5 class="corporate-subtitle mb-4">
                        <i></i>Get in Touch
                    </h5>
                    
                    <!-- Address with icon and text - both clickable -->
                    <a href="https://maps.google.com/?q=Lot+7-A+Greenfield+Automotive+park+Don+Jose+City+of+Santa+Rosa+Laguna" target="_blank" class="contact-link" rel="noopener noreferrer">
                        <div class="contact-info-item mb-4">
                            <div class="contact-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Visit Us</h6>
                                <p class="mb-0 small">Lot 7-A, Greenfield Automotive park,<br>Don Jose, City of Santa Rosa, Laguna</p>
                            </div>
                        </div>
                    </a>

                    <!-- Email with icon and text - both clickable -->
                    <a href="mailto:inquiry@toyoseat.com.ph" class="contact-link" rel="noopener noreferrer">
                        <div class="contact-info-item mb-4">
                            <div class="contact-icon">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Email Us</h6>
                                <p class="mb-0 small">inquiry@toyoseat.com.ph</p>
                            </div>
                        </div>
                    </a>

                    <!-- Facebook with icon and text - both clickable -->
                    <a href="https://www.facebook.com/people/Toyo-Seat-Philippines-Corporation/100057821552844/" target="_blank" class="contact-link" rel="noopener noreferrer">
                        <div class="contact-info-item mb-4">
                            <div class="contact-icon">
                                <i class="fa-brands fa-facebook-f"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Facebook</h6>
                                <p class="mb-0 small">Toyo Seat Philippines Corporation</p>
                            </div>
                        </div>
                    </a>

                    <!-- YouTube with icon and text - both clickable -->
                    <a href="https://www.youtube.com/@ToyoSeatPhilippinesCorporation" target="_blank" class="contact-link" rel="noopener noreferrer">
                        <div class="contact-info-item mb-4">
                            <div class="contact-icon">
                                <i class="fa-brands fa-youtube"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">YouTube</h6>
                                <p class="mb-0 small">Toyo Seat Philippines Corporation</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm corporate-card">
                <div class="card-body p-4 p-lg-5">

                    @if(session('success') || $errors->any())
                        <div
                            id="inquiry-flash"
                            class="d-none"
                            data-toast-type="{{ session('success') ? 'success' : 'error' }}"
                            data-toast-message="{{ session('success') ? session('success') : $errors->first() }}"
                        ></div>
                    @endif

                    {{-- FORM --}}
                    <form action="{{ route('guest.inquiry.store') }}" method="POST" id="inquiryForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control corporate-input" placeholder="Enter your full name" value="{{ old('name') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control corporate-input" placeholder="your.email@company.com" value="{{ old('email') }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                <select name="subject" class="form-select corporate-input" required>
                                    <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Select a subject</option>
                                    <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                    <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>Technical Support</option>
                                    <option value="sales" {{ old('subject') == 'sales' ? 'selected' : '' }}>Sales & Partnerships</option>
                                    <option value="careers" {{ old('subject') == 'careers' ? 'selected' : '' }}>Careers</option>
                                    <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                                <textarea name="message" rows="5" class="form-control corporate-input" placeholder="Please provide details about your inquiry..." required>{{ old('message') }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Attachment</label>
                                <input type="file" name="attachment" id="attachment" class="form-control corporate-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Supported formats: PDF, DOC, DOCX, JPG, PNG (Max 2MB)</small>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn corporate-btn w-100">
                                    <i class="fa-solid fa-paper-plane me-2"></i>Submit Inquiry
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/inquiry.js') }}"></script>
@endsection