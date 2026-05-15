@extends('layouts.app')

@section('title', 'History - About Us')

@section('content')
<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 3px;
        height: 100%;
        background: linear-gradient(to bottom, #3988BD, #0E334C);
        top: 0;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 60px;
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }
    
    .timeline-item.visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    .timeline-badge {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 80px;
        background: white;
        border: 3px solid #3988BD;
        border-radius: 50%;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        box-shadow: 0 0 0 5px rgba(57, 136, 189, 0.1);
    }
    
    .timeline-badge .year {
        font-weight: bold;
        color: #0E334C;
        font-size: 14px;
        text-align: center;
        line-height: 1.2;
    }
    
    .timeline-content {
        position: relative;
        width: calc(50% - 60px);
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .timeline-content:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .timeline-item.left .timeline-content {
        margin-left: auto;
        margin-right: 60px;
    }
    
    .timeline-item.right .timeline-content {
        margin-right: auto;
        margin-left: 60px;
    }
    
    .timeline-content::before {
        content: '';
        position: absolute;
        top: 30px;
        width: 0;
        height: 0;
        border-style: solid;
    }
    
    .timeline-item.left .timeline-content::before {
        right: -12px;
        border-width: 8px 0 8px 12px;
        border-color: transparent transparent transparent white;
    }
    
    .timeline-item.right .timeline-content::before {
        left: -12px;
        border-width: 8px 12px 8px 0;
        border-color: transparent white transparent transparent;
    }
    
    .timeline-date {
        display: inline-block;
        background: #3988BD;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .timeline-title {
        font-size: 20px;
        font-weight: 700;
        color: #0E334C;
        margin-bottom: 12px;
    }
    
    .timeline-description {
        color: #6c757d;
        line-height: 1.6;
        margin-bottom: 15px;
    }
    
    .timeline-image {
        margin-top: 15px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
    }
    
    .timeline-image img {
        width: 100%;
        height: auto;
        max-height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .timeline-image:hover img {
        transform: scale(1.05);
    }
    
    /* Loading Animation */
    .loading-spinner {
        text-align: center;
        padding: 50px;
    }
    
    .spinner {
        width: 50px;
        height: 50px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #3988BD;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f8f9fa;
        border-radius: 12px;
    }
    
    .empty-state i {
        font-size: 60px;
        color: #3988BD;
        margin-bottom: 20px;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .timeline::before {
            left: 30px;
        }
        
        .timeline-badge {
            left: 30px;
            transform: translateX(0);
            width: 60px;
            height: 60px;
        }
        
        .timeline-badge .year {
            font-size: 11px;
        }
        
        .timeline-content {
            width: calc(100% - 100px);
            margin-left: 100px !important;
        }
        
        .timeline-item.left .timeline-content::before,
        .timeline-item.right .timeline-content::before {
            left: -12px;
            border-width: 8px 12px 8px 0;
            border-color: transparent white transparent transparent;
        }
        
        .timeline-item.left .timeline-content,
        .timeline-item.right .timeline-content {
            margin-right: 0;
            margin-left: 100px;
        }
    }
    
    /* Image Modal */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        padding-top: 50px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
        cursor: pointer;
    }
    
    .image-modal-content {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 90%;
        animation: zoom 0.3s ease;
    }
    
    @keyframes zoom {
        from {transform: scale(0.1); opacity: 0;}
        to {transform: scale(1); opacity: 1;}
    }
    
    .close-modal {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }
    
    .close-modal:hover {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold" style="color: #0E334C;">Our History</h1>
        <div class="line" style="width: 80px; height: 3px; background: #3988BD; margin: 20px auto;"></div>
        <p class="lead text-muted">Discover the journey of Toyo Seat through the years</p>
    </div>
    
    <!-- Loading State -->
    <div id="loadingState" class="loading-spinner">
        <div class="spinner"></div>
        <p class="text-muted">Loading history...</p>
    </div>
    
    <!-- Timeline Container -->
    <div id="timelineContainer" class="timeline" style="display: none;"></div>
    
    <!-- Empty State -->
    <div id="emptyState" class="empty-state" style="display: none;">
        <i class="fas fa-history"></i>
        <h3>No History Records Found</h3>
        <p class="text-muted">Check back later for our company history timeline.</p>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close-modal">&times;</span>
    <img class="image-modal-content" id="modalImage">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadHistoryData();
    });
    
    function loadHistoryData() {
        const loadingState = document.getElementById('loadingState');
        const timelineContainer = document.getElementById('timelineContainer');
        const emptyState = document.getElementById('emptyState');
        
        // Show loading
        loadingState.style.display = 'block';
        timelineContainer.style.display = 'none';
        emptyState.style.display = 'none';
        
        fetch('/admin/histories/all', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingState.style.display = 'none';
            
            // Filter only published records and sort by date
            const publishedRecords = data.filter(item => item.status === 'published');
            
            if (publishedRecords.length === 0) {
                emptyState.style.display = 'block';
                return;
            }
            
            // Sort by date (oldest to newest for timeline)
            publishedRecords.sort((a, b) => new Date(a.date) - new Date(b.date));
            
            renderTimeline(publishedRecords);
            timelineContainer.style.display = 'block';
            
            // Trigger scroll animations
            setTimeout(() => {
                observeTimelineItems();
            }, 100);
        })
        .catch(error => {
            console.error('Error loading history:', error);
            loadingState.style.display = 'none';
            emptyState.style.display = 'block';
            emptyState.innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Error Loading Data</h3>
                <p class="text-muted">Unable to load history records. Please try again later.</p>
            `;
        });
    }
    
    function renderTimeline(records) {
        const container = document.getElementById('timelineContainer');
        container.innerHTML = '';
        
        records.forEach((record, index) => {
            const isLeft = index % 2 === 0; // Alternate sides
            const year = new Date(record.date).getFullYear();
            const formattedDate = formatDate(record.date);
            const imageSrc = record.image_url ? record.image_url : '/images/default-image.png';
            
            const timelineItem = document.createElement('div');
            timelineItem.className = `timeline-item ${isLeft ? 'left' : 'right'}`;
            
            timelineItem.innerHTML = `
                <div class="timeline-badge">
                    <div class="year">${year}</div>
                </div>
                <div class="timeline-content">
                    <span class="timeline-date">${formattedDate}</span>
                    <h3 class="timeline-title">${escapeHtml(record.title)}</h3>
                    <div class="timeline-description">
                        ${escapeHtml(record.description)}
                    </div>
                    ${record.image_url ? `
                        <div class="timeline-image" onclick="openImageModal('${imageSrc}')">
                            <img src="${imageSrc}" alt="${escapeHtml(record.title)}" loading="lazy">
                        </div>
                    ` : ''}
                </div>
            `;
            
            container.appendChild(timelineItem);
        });
    }
    
    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString;
        
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
    
    function observeTimelineItems() {
        const items = document.querySelectorAll('.timeline-item');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2,
            rootMargin: '0px 0px -50px 0px'
        });
        
        items.forEach(item => {
            observer.observe(item);
        });
    }
    
    function openImageModal(imageUrl) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.style.display = 'block';
        modalImg.src = imageUrl;
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
@endsection