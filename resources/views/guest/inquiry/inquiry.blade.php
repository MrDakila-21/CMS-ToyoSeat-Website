@extends('layouts.app')

@section('title', 'Inquiry')

@section('content')
<link rel="stylesheet" href="{{ asset('css/guest/inquiry.css') }}">
<link rel="stylesheet" href="{{ asset('css/dash.css') }}">

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Hero Section - Modern Gradient (copied from guest location blade) -->
<div class="hero-section-wrapper">
    <div class="hero-particles"></div>
    <div class="container">
        <div class="hero-section text-center fade-in-up">
            <h1 class="hero-title">Contact Us</h1>
            <div class="hero-line">
                <div class="hero-line-main"></div>
                <div class="hero-line-dot"></div>
                <div class="hero-line-main"></div>
            </div>
            <p class="hero-subtitle">We'll get back to you within 24 hours</p>
            <div class="hero-scroll-indicator">
                <span class="scroll-text">Send Inquiry</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        <!-- Contact Information Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm corporate-card h-100">
                <div class="card-body p-4">
                    <h5 class="corporate-subtitle mb-4">
                        <i></i>Get in Touch
                    </h5>
                    
                    <!-- Address with icon and text - both clickable -->
                    <a href="https://maps.app.goo.gl/Kwgv214z2peBGJLy9" target="_blank" class="contact-link" rel="noopener noreferrer">
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
                    <a href="mailto:info@toyoseat.ph" class="contact-link" rel="noopener noreferrer">
                        <div class="contact-info-item mb-4">
                            <div class="contact-icon">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Email Us</h6>
                                <p class="mb-0 small">info@toyoseat.ph</p>
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

                    {{-- FORM --}}
                    <form action="{{ route('guest.inquiry.store') }}" method="POST" id="inquiryForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control corporate-input" placeholder="Enter your full name" value="{{ old('name') }}">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control corporate-input" placeholder="your.email@company.com" value="{{ old('email') }}">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- NEW: Contact Number Field -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Contact Number <span class="text-danger">*</span></label>
                                <input type="tel" name="contact_number" class="form-control corporate-input" placeholder="e.g., 09171234567" value="{{ old('contact_number') }}">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- NEW: Company Name Field -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Company Name</label>
                                <input type="text" name="company_name" class="form-control corporate-input" placeholder="Enter your company name" value="{{ old('company_name') }}">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- UPDATED: Subject Field - Plain text, not dropdown -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                <div class="subject-display">
                                    <input type="text" class="form-control corporate-input subject-input" value="General Inquiry" readonly disabled>
                                    <input type="hidden" name="subject" value="General Inquiry">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                                <textarea name="message" rows="5" class="form-control corporate-input" placeholder="Please provide details about your inquiry...">{{ old('message') }}</textarea>
                                <div class="invalid-feedback"></div>
                            </div>
 <!-- Commented out the attachment field as per the latest requirements, but keeping the code for future reference if needed
                            <div class="col-12">
                                <label class="form-label fw-semibold">Attachment</label>
                                <input type="file" name="attachment" id="attachment" class="form-control corporate-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Supported formats: PDF, DOC, DOCX, JPG, PNG (Max 2MB)</small>
                                <div class="invalid-feedback"></div>
                            </div>

                            -->

                            <!-- UPDATED: Terms & Conditions Checkbox - NOT disabled, opens modal when clicked -->
                            <div class="col-12 mt-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="termsCheckbox">
                                    <label class="form-check-label terms-label" for="termsCheckbox">
                                        I agree with the <span class="terms-link">Terms & Conditions</span>
                                    </label>
                                </div>
                            </div>

                            <!-- UPDATED: Submit Button - Initially Disabled -->
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn corporate-btn w-100" id="submitBtn" disabled>
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

<!-- Terms & Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms & Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="termsModalBody">
                <div id="termsContent" style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                    <h6>1. Introduction</h6>
                    <p>Welcome to Toyo Seat Philippines Corporation. By submitting this inquiry form, you agree to be bound by these Terms & Conditions.</p>
                    
                    <h6>2. Information Collection</h6>
                    <p>We collect personal information including your name, email address, contact number, company name, and any message content you provide. This information is used solely for responding to your inquiry and improving our services.</p>
                    
                    <h6>3. Data Usage</h6>
                    <p>Your personal information will not be shared with third parties without your explicit consent, except as required by law. We implement appropriate security measures to protect your data.</p>
                    
                    <h6>4. Response Time</h6>
                    <p>We strive to respond to all inquiries within 24-48 business hours. Response times may vary depending on the nature and complexity of your inquiry.</p>
                    
                    <h6>5. Accuracy of Information</h6>
                    <p>You are responsible for ensuring that all information provided in the inquiry form is accurate, current, and complete. We reserve the right to contact you for clarification if needed.</p>
                    
                    <h6>6. Confidentiality</h6>
                    <p>Any information shared through this inquiry form will be treated as confidential and used exclusively for addressing your specific inquiry.</p>
                    
                    <h6>7. Limitations of Liability</h6>
                    <p>Toyo Seat Philippines Corporation shall not be liable for any damages arising from the use or inability to use our inquiry system, including but not limited to technical issues, delays, or loss of data.</p>
                    
                    <h6>8. Modifications</h6>
                    <p>We reserve the right to modify these Terms & Conditions at any time without prior notice. Continued use of our inquiry system constitutes acceptance of any modifications.</p>
                    
                    <h6>9. Governing Law</h6>
                    <p>These Terms & Conditions shall be governed by and construed in accordance with the laws of the Republic of the Philippines.</p>
                    
                    <h6>10. Contact Information</h6>
                    <p>If you have any questions about these Terms & Conditions, please contact us at our official email.</p>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="agreeBtn" disabled>Yes, I Agree</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/inquiry.js') }}"></script>

<!-- Font Awesome 6 (matching location blade) -->
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

<!-- Smooth Scroll for Hero Indicator (matching location blade) -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scrollIndicator = document.querySelector('.hero-scroll-indicator');
        if (scrollIndicator) {
            scrollIndicator.addEventListener('click', function() {
                // Scroll to the form section
                const formCard = document.querySelector('.corporate-card');
                if (formCard) {
                    formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    window.scrollBy({
                        top: window.innerHeight - 100,
                        behavior: 'smooth'
                    });
                }
            });
        }
    });
</script>
@endpush

<!-- JavaScript for Form Validation and Toast Messages -->
<script>
// Toast notification system without close button
function showToast(message, type = 'error') {
    const toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) return;
    
    const toastId = 'toast_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    
    // Map type to dash.css classes
    const toastClass = type === 'success' ? 'success-toast' : 'error-toast';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    // Removed the close button (|x|) from the toast HTML
    const toastHtml = `
        <div id="${toastId}" class="login-toast ${toastClass}">
            <div class="login-toast-content">
                <i class="fas ${icon}"></i>
                <span>${message}</span>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.classList.add('hide');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }, 5000);
}

window.closeToast = function(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.add('hide');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
};

// Internet Connectivity Check Function
function isOnline() {
    return navigator.onLine;
}

// Check internet connection before form submission
function checkInternetAndSubmit(e) {
    e.preventDefault(); // Prevent default form submission
    
    // Check if there's internet connection
    if (!isOnline()) {
        showToast('No internet connectivity. Please connect to send an inquiry...', 'error');
        return false;
    }
    
    // Validate form before submitting
    if (!validateForm()) {
        showToast('Please fix the errors in the form before submitting.', 'error');
        return false;
    }
    
    if (!document.getElementById('termsCheckbox').checked) {
        showToast('Please agree to the Terms & Conditions before submitting.', 'error');
        return false;
    }
    
    // If all validations pass and online, submit the form
    document.getElementById('inquiryForm').submit();
}

// Monitor online/offline status and show notifications
function initNetworkMonitoring() {
    // Show notification when going offline
    window.addEventListener('offline', function() {
        showToast('You are offline. Please check your internet connection.', 'error');
        
        // Disable submit button when offline
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.title = 'No internet connection';
            submitBtn.style.opacity = '0.6';
            submitBtn.style.cursor = 'not-allowed';
        }
    });
    
    // Show notification when coming online
    window.addEventListener('online', function() {
        showToast('Internet connection restored. You can now submit your inquiry.', 'success');
        
        // Re-enable submit button if form is valid and terms are agreed
        const submitBtn = document.getElementById('submitBtn');
        const termsCheckbox = document.getElementById('termsCheckbox');
        if (submitBtn && termsCheckbox && termsCheckbox.checked && validateForm()) {
            submitBtn.disabled = false;
            submitBtn.title = '';
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }
    });
    
    // Check initial online status on page load
    if (!isOnline()) {
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.title = 'No internet connection';
            submitBtn.style.opacity = '0.6';
            submitBtn.style.cursor = 'not-allowed';
        }
    }
}

// Form validation functions
function validateName(name) {
    if (!name || name.trim() === '') {
        return 'Full name is required';
    }
    if (name.trim().length < 2) {
        return 'Full name must be at least 2 characters';
    }
    if (name.trim().length > 255) {
        return 'Full name must not exceed 255 characters';
    }
    if (!/^[a-zA-Z\s\-\'\.]+$/.test(name.trim())) {
        return 'Full name can only contain letters, spaces, hyphens, apostrophes, and periods';
    }
    return null;
}

function validateEmail(email) {
    if (!email || email.trim() === '') {
        return 'Email address is required';
    }
    if (email.trim().length > 255) {
        return 'Email address must not exceed 255 characters';
    }
    const emailRegex = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
    if (!emailRegex.test(email.trim())) {
        return 'Please enter a valid email address (e.g., name@example.com)';
    }
    return null;
}

function validateContactNumber(contactNumber) {
    if (!contactNumber || contactNumber.trim() === '') {
        return 'Contact number is required';
    }
    if (contactNumber.trim().length > 20) {
        return 'Contact number must not exceed 20 characters';
    }
    // Philippine mobile number format (11 digits starting with 09, or 10 digits starting with 0)
    const phoneRegex = /^(\+63|0)[0-9]{9,10}$/;
    const numericRegex = /^[0-9+\-\s\(\)]+$/;
    if (!numericRegex.test(contactNumber.trim())) {
        return 'Contact number can only contain numbers, spaces, and symbols + - ( )';
    }
    return null;
}

function validateCompanyName(companyName) {
    if (companyName && companyName.trim().length > 255) {
        return 'Company name must not exceed 255 characters';
    }
    return null;
}

function validateMessage(message) {
    if (!message || message.trim() === '') {
        return 'Message is required';
    }
    if (message.trim().length < 10) {
        return 'Message must be at least 10 characters';
    }
    if (message.trim().length > 5000) {
        return 'Message must not exceed 5000 characters';
    }
    return null;
}

function validateAttachment(file) {
    if (!file || !file.name) return null;
    
    const allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
    const fileExtension = file.name.split('.').pop().toLowerCase();
    
    if (!allowedExtensions.includes(fileExtension)) {
        return 'Invalid file type. Supported formats: PDF, DOC, DOCX, JPG, PNG';
    }
    
    if (file.size > 2 * 1024 * 1024) { // 2MB
        return 'File size must not exceed 2MB';
    }
    
    return null;
}

// Real-time validation for individual fields
function validateField(field) {
    const value = field.value;
    let error = null;
    
    switch(field.name) {
        case 'name':
            error = validateName(value);
            break;
        case 'email':
            error = validateEmail(value);
            break;
        case 'contact_number':
            error = validateContactNumber(value);
            break;
        case 'company_name':
            error = validateCompanyName(value);
            break;
        case 'message':
            error = validateMessage(value);
            break;
        case 'attachment':
            error = validateAttachment(value);
            break;
    }
    
    const invalidFeedback = field.parentElement.querySelector('.invalid-feedback');
    if (error) {
        field.classList.add('is-invalid');
        if (invalidFeedback) {
            invalidFeedback.textContent = error;
        }
        return false;
    } else {
        field.classList.remove('is-invalid');
        if (invalidFeedback) {
            invalidFeedback.textContent = '';
        }
        return true;
    }
}

// Validate entire form
function validateForm() {
    const fields = ['name', 'email', 'contact_number', 'message'];
    let isValid = true;
    
    // Validate required fields
    fields.forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (field && !validateField(field)) {
            isValid = false;
        }
    });
    
    // Validate optional fields if they have values
    const companyName = document.querySelector('[name="company_name"]');
    if (companyName && companyName.value.trim() !== '' && !validateField(companyName)) {
        isValid = false;
    }
    
    const attachment = document.querySelector('[name="attachment"]');
    if (attachment && attachment.files.length > 0 && !validateField(attachment)) {
        isValid = false;
    }
    
    return isValid;
}

// Enable/disable submit button based on terms, form validation, AND internet connection
function updateSubmitButton() {
    const termsCheckbox = document.getElementById('termsCheckbox');
    const submitBtn = document.getElementById('submitBtn');
    
    if (termsCheckbox && submitBtn) {
        if (termsCheckbox.checked && validateForm() && isOnline()) {
            submitBtn.disabled = false;
            submitBtn.title = '';
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        } else if (!isOnline()) {
            submitBtn.disabled = true;
            submitBtn.title = 'No internet connection';
            submitBtn.style.opacity = '0.6';
            submitBtn.style.cursor = 'not-allowed';
        } else {
            submitBtn.disabled = true;
            submitBtn.title = '';
            submitBtn.style.opacity = '0.6';
            submitBtn.style.cursor = 'not-allowed';
        }
    }
}

// Main initialization
document.addEventListener('DOMContentLoaded', function() {
    const formInputs = document.querySelectorAll('#inquiryForm input, #inquiryForm textarea');
    const form = document.getElementById('inquiryForm');
    const termsCheckbox = document.getElementById('termsCheckbox');
    const submitBtn = document.getElementById('submitBtn');
    const agreeBtn = document.getElementById('agreeBtn');
    const termsModalElement = document.getElementById('termsModal');
    const termsModal = new bootstrap.Modal(termsModalElement);
    const termsContent = document.getElementById('termsContent');
    
    let userAgreed = false;
    let isOpeningModal = false;
    
    // Add validation event listeners to form inputs
    formInputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
            updateSubmitButton();
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
            updateSubmitButton();
        });
    });
    
    // Special handling for file input
    const fileInput = document.querySelector('[name="attachment"]');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            validateField(this);
            updateSubmitButton();
        });
    }
    
    // Override form submission with internet check
    if (form) {
        form.addEventListener('submit', checkInternetAndSubmit);
    }
    
    // Initialize network monitoring
    initNetworkMonitoring();
    
    // Check if there are old values and validate them
    const oldName = "{{ old('name') }}";
    const oldEmail = "{{ old('email') }}";
    const oldContact = "{{ old('contact_number') }}";
    const oldMessage = "{{ old('message') }}";
    
    if (oldName) validateField(document.querySelector('[name="name"]'));
    if (oldEmail) validateField(document.querySelector('[name="email"]'));
    if (oldContact) validateField(document.querySelector('[name="contact_number"]'));
    if (oldMessage) validateField(document.querySelector('[name="message"]'));
    
    // Terms & Conditions Modal Logic
    function checkScrollBottom() {
        if (termsContent) {
            const scrollTop = termsContent.scrollTop;
            const scrollHeight = termsContent.scrollHeight;
            const clientHeight = termsContent.clientHeight;
            
            if (scrollTop + clientHeight >= scrollHeight - 10) {
                agreeBtn.disabled = false;
            } else {
                agreeBtn.disabled = true;
            }
        }
    }
    
    function resetModalState() {
        if (termsContent) {
            termsContent.scrollTop = 0;
        }
        if (agreeBtn) {
            agreeBtn.disabled = true;
        }
        userAgreed = false;
    }
    
    if (termsContent) {
        termsContent.addEventListener('scroll', checkScrollBottom);
    }
    
    if (termsModalElement) {
        termsModalElement.addEventListener('shown.bs.modal', function() {
            if (termsContent) {
                termsContent.scrollTop = 0;
            }
            if (agreeBtn) {
                agreeBtn.disabled = true;
            }
        });
    }
    
    if (termsCheckbox) {
        termsCheckbox.addEventListener('change', function(e) {
            if (isOpeningModal) return;
            
            if (termsCheckbox.checked) {
                isOpeningModal = true;
                termsCheckbox.checked = false;
                termsModal.show();
                isOpeningModal = false;
            } else {
                updateSubmitButton();
                userAgreed = false;
            }
        });
    }
    
    if (agreeBtn) {
        agreeBtn.addEventListener('click', function() {
            userAgreed = true;
            if (termsCheckbox) {
                termsCheckbox.checked = true;
            }
            updateSubmitButton();
            termsModal.hide();
        });
    }
    
    if (termsModalElement) {
        termsModalElement.addEventListener('hidden.bs.modal', function() {
            if (!userAgreed && termsCheckbox) {
                termsCheckbox.checked = false;
                updateSubmitButton();
            }
        });
    }
    
    // Add event listeners for form validation to update submit button
    formInputs.forEach(input => {
        input.addEventListener('input', updateSubmitButton);
        input.addEventListener('blur', updateSubmitButton);
    });
    
    if (termsCheckbox) {
        termsCheckbox.addEventListener('change', updateSubmitButton);
    }
    
    // Initial update of submit button
    updateSubmitButton();
    
    // Show any existing session messages as toasts
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    
    @if($errors->any())
        @foreach($errors->all() as $error)
            showToast('{{ $error }}', 'error');
        @endforeach
    @endif
});
</script>

@endsection