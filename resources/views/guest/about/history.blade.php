@extends('layouts.app')

@section('title', 'History - About Us')

@section('content')
<style>
    /* CSS Variables - Enhanced Color Scheme (Same as Location) */
    :root {
        --primary-dark: #0A2B3E;
        --primary: #1A6D8F;
        --primary-light: #3A8EB5;
        --primary-lighter: #E6F3F9;
        --primary-glow: rgba(26, 109, 143, 0.2);
        --secondary: #2C3E50;
        --accent: #E76F51;
        --accent-light: #F4EAE6;
        --gray-light: #F8F9FA;
        --gray-border: #E9ECEF;
        --text-dark: #1A2B3C;
        --text-muted: #6C757D;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.04);
        --shadow-md: 0 8px 24px rgba(0,0,0,0.08);
        --shadow-lg: 0 16px 40px rgba(0,0,0,0.12);
        --shadow-xl: 0 24px 56px rgba(0,0,0,0.16);
        --transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* Hero Section - Same as Location */
    .hero-section-wrapper {
        background: linear-gradient(135deg, #0A2640 0%, #0E334C 50%, #1A4D6F 100%);
        position: relative;
        overflow: hidden;
        margin-top: -1.5rem;
        padding: 2rem 0 0.5rem 0;
        isolation: isolate;
    }

    .hero-particles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            radial-gradient(circle at 20% 30%, rgba(255,255,255,0.08) 2px, transparent 2px),
            radial-gradient(circle at 80% 70%, rgba(255,255,255,0.06) 1px, transparent 1px);
        background-size: 50px 50px, 30px 30px;
        pointer-events: none;
    }

    .hero-section-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -20%;
        width: 140%;
        height: 140%;
        background: radial-gradient(circle, rgba(57, 136, 189, 0.15) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
        pointer-events: none;
    }

    .hero-section-wrapper::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, var(--primary-light), var(--primary), var(--primary-light), transparent);
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.3; }
    }

    .hero-section {
        position: relative;
        z-index: 2;
        padding: 0.5rem 0;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        margin-top: 1rem;
        margin-bottom: 0.75rem;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        letter-spacing: -0.02em;
        background: linear-gradient(135deg, #FFFFFF, #E0F0F8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-line {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin: 1rem auto;
    }

    .hero-line-main {
        width: 50px;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--primary-light), var(--primary-light), transparent);
    }

    .hero-line-dot {
        width: 8px;
        height: 8px;
        background: var(--primary-light);
        border-radius: 50%;
        box-shadow: 0 0 12px rgba(91, 163, 212, 0.6);
    }

    .hero-subtitle {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 600px;
        margin: 0 auto;
        font-weight: 400;
        line-height: 1.5;
    }

    .hero-scroll-indicator {
        margin-top: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .hero-scroll-indicator:hover {
        transform: translateY(3px);
    }

    .scroll-text {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 500;
    }

    .hero-scroll-indicator i {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(8px); }
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
    }

    /* Main Container - Full Width */
    .history-container {
        max-width: 100%;
        margin: 0;
        padding: 40px 40px;
        background: #f5f7fa;
    }
    
    /* Filter Section - Full Width */
    .filter-section {
        background: white;
        padding: 25px 30px;
        border-radius: 12px;
        margin-bottom: 40px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-border);
    }
    
    .filter-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    
    .filter-group {
        flex: 1;
        min-width: 200px;
    }
    
    .filter-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 14px;
    }
    
    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--gray-border);
        border-radius: 8px;
        font-size: 14px;
        transition: var(--transition);
    }
    
    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-glow);
    }
    
    .filter-group button {
        width: 100%;
        padding: 12px 20px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: var(--transition);
    }
    
    .filter-group button:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }
    
    .reset-btn {
        background: #6c757d !important;
    }
    
    .reset-btn:hover {
        background: #5a6268 !important;
    }
    
    /* Results Count */
    .results-count {
        margin-bottom: 20px;
        padding: 10px 0;
        color: var(--text-muted);
        font-size: 14px;
    }
    
    /* Year Section - Full Width Cards */
    .year-section {
        margin-bottom: 60px;
    }
    
    .year-title {
        font-size: 32px;
        font-weight: bold;
        color: var(--primary-dark);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 3px solid var(--primary);
        display: inline-block;
    }
    
    .year-title span {
        font-size: 14px;
        margin-left: 10px;
        color: var(--text-muted);
        font-weight: normal;
    }
    
    /* Cards Grid - Maximize Space */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
    }
    
    /* History Card */
    .history-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        height: 100%;
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.6s ease forwards;
        border: 1px solid var(--gray-border);
    }
    
    .history-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-light);
    }
    
    /* Card Image - Fixed Height */
    .card-image {
        position: relative;
        height: 220px;
        overflow: hidden;
        cursor: pointer;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .history-card:hover .card-image img {
        transform: scale(1.05);
    }
    
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .card-image:hover .image-overlay {
        opacity: 1;
    }
    
    .image-overlay i {
        color: white;
        font-size: 30px;
    }
    
    /* Card Content */
    .card-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .card-date {
        display: inline-block;
        background: var(--primary-lighter);
        color: var(--primary);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 12px;
        align-self: flex-start;
    }
    
    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 12px;
        line-height: 1.4;
    }
    
    .card-description {
        color: var(--text-muted);
        line-height: 1.6;
        font-size: 14px;
        margin-bottom: 0;
        flex: 1;
    }
    
    .description-wrapper {
        display: inline;
    }
    
    .full-description, .short-description {
        display: inline;
    }
    
    .full-description {
        display: none;
    }
    
    .read-more-btn {
        background: none;
        border: none;
        color: var(--primary);
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        padding: 5px 0;
        margin-top: 10px;
        transition: var(--transition);
        display: inline-block;
    }
    
    .read-more-btn:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }
    
    /* Pagination Styles */
    .pagination-container {
        margin-top: 50px;
        margin-bottom: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .pagination-btn {
        padding: 10px 20px;
        background: white;
        border: 1px solid var(--primary);
        color: var(--primary);
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: var(--transition);
    }
    
    .pagination-btn:hover:not(:disabled) {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }
    
    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .page-info {
        padding: 8px 16px;
        background: white;
        border-radius: 8px;
        color: var(--primary-dark);
        font-weight: 600;
    }
    
    .page-numbers {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .page-number {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid var(--gray-border);
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition);
        font-weight: 500;
    }
    
    .page-number:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    .page-number.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    /* Loading State */
    .loading-container {
        text-align: center;
        padding: 80px 20px;
    }
    
    .spinner {
        width: 50px;
        height: 50px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Empty State */
    .empty-state, .no-results {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-md);
    }
    
    .empty-state i, .no-results i {
        font-size: 60px;
        color: var(--primary);
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    /* Image Modal */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.95);
        cursor: pointer;
    }
    
    .image-modal-content {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation: zoom 0.3s ease;
    }
    
    @keyframes zoom {
        from { transform: translate(-50%, -50%) scale(0.1); opacity: 0; }
        to { transform: translate(-50%, -50%) scale(1); opacity: 1; }
    }
    
    .close-modal {
        position: absolute;
        top: 20px;
        right: 40px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }
    
    .close-modal:hover {
        color: #bbb;
    }
    
    /* Scroll to top button */
    .scroll-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: var(--primary);
        color: white;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
        z-index: 1000;
        box-shadow: var(--shadow-md);
    }
    
    .scroll-top.show {
        opacity: 1;
        visibility: visible;
    }
    
    .scroll-top:hover {
        background: var(--primary-dark);
        transform: translateY(-3px);
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .cards-grid {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-subtitle {
            font-size: 0.9rem;
        }
        
        .hero-line-main {
            width: 35px;
        }
        
        .hero-line-dot {
            width: 6px;
            height: 6px;
        }
        
        .hero-scroll-indicator {
            margin-top: 1rem;
        }
        
        .history-container {
            padding: 20px 15px;
        }
        
        .cards-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .filter-row {
            flex-direction: column;
        }
        
        .filter-group {
            width: 100%;
        }
        
        .year-title {
            font-size: 24px;
        }
        
        .card-image {
            height: 200px;
        }
        
        .page-numbers {
            order: 3;
            justify-content: center;
        }
        
        .pagination-container {
            flex-direction: column;
        }
    }
    
    @media (max-width: 576px) {
        .hero-section-wrapper {
            padding: 1rem 0 0.75rem 0;
        }
        
        .hero-title {
            font-size: 1.5rem;
        }
        
        .hero-subtitle {
            font-size: 0.85rem;
        }
        
        .hero-line {
            margin: 0.75rem auto;
        }
        
        .hero-line-main {
            width: 25px;
        }
        
        .scroll-text {
            font-size: 0.6rem;
        }
        
        .hero-scroll-indicator i {
            font-size: 0.75rem;
        }
        
        .card-title {
            font-size: 16px;
        }
        
        .card-description {
            font-size: 13px;
        }
        
        .card-image {
            height: 180px;
        }
        
        .year-title {
            font-size: 20px;
        }
    }
    
    /* Print Styles */
    @media print {
        .hero-section-wrapper,
        .hero-scroll-indicator,
        .filter-section,
        .pagination-container,
        .scroll-top,
        .image-modal {
            display: none;
        }
        
        .history-card {
            box-shadow: none;
            border: 1px solid #ddd;
            page-break-inside: avoid;
        }
        
        .cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    /* Smooth Loading */
    .history-container {
        opacity: 0;
        animation: fadeInUp 0.8s ease-out 0.1s forwards;
    }
</style>

<!-- Hero Section - Same as Location Page -->
<div class="hero-section-wrapper">
    <div class="hero-particles"></div>
    <div class="container">
        <div class="hero-section text-center fade-in-up">
            <h1 class="hero-title">Our History</h1>
            <div class="hero-line">
                <div class="hero-line-main"></div>
                <div class="hero-line-dot"></div>
                <div class="hero-line-main"></div>
            </div>
            <p class="hero-subtitle">Discover the journey of Toyo Seat through the years</p>
            <div class="hero-scroll-indicator">
                <span class="scroll-text">Discover More</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</div>

<div class="history-container">
    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label><i class="fas fa-search me-2"></i>Search</label>
                <input type="text" id="searchInput" placeholder="Search by title or description...">
            </div>
            <div class="filter-group">
                <label><i class="fas fa-calendar me-2"></i>Filter by Year</label>
                <select id="yearFilter">
                    <option value="">All Years</option>
                </select>
            </div>
            <div class="filter-group">
                <label>&nbsp;</label>
                <button id="resetFilters" class="reset-btn">Reset Filters</button>
            </div>
        </div>
    </div>
    
    <!-- Results Count -->
    <div id="resultsCount" class="results-count" style="display: none;"></div>
    
    <!-- Loading State -->
    <div id="loadingState" class="loading-container">
        <div class="spinner"></div>
        <p class="text-muted">Loading history...</p>
    </div>
    
    <!-- Timeline Container -->
    <div id="timelineContainer" style="display: none;"></div>
    
    <!-- Pagination Container -->
    <div id="paginationContainer" class="pagination-container" style="display: none;"></div>
    
    <!-- Empty State -->
    <div id="emptyState" class="empty-state" style="display: none;">
        <i class="fas fa-history"></i>
        <h3>No History Records Found</h3>
        <p class="text-muted">Check back later for our company history timeline.</p>
    </div>
    
    <!-- No Results State -->
    <div id="noResultsState" class="no-results" style="display: none;">
        <i class="fas fa-filter"></i>
        <h4>No matching records found</h4>
        <p class="text-muted">Try adjusting your search or filter criteria.</p>
        <button class="btn btn-primary mt-3" onclick="resetAllFilters()">
            <i class="fas fa-redo me-2"></i>Reset Filters
        </button>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close-modal">&times;</span>
    <img class="image-modal-content" id="modalImage" alt="Full size image">
</div>

<!-- Scroll to Top Button -->
<div class="scroll-top" id="scrollTop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
    <i class="fas fa-arrow-up"></i>
</div>

<!-- Font Awesome 6 -->
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

<script>
    let allHistoryData = [];
    let allYearsList = [];
    let currentPage = 1;
    let yearsPerPage = 5;
    let currentFilters = {
        search: '',
        year: ''
    };
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, fetching history data...');
        loadHistoryData();
        
        // Scroll to top button visibility
        window.addEventListener('scroll', function() {
            const scrollTop = document.getElementById('scrollTop');
            if (window.pageYOffset > 300) {
                scrollTop.classList.add('show');
            } else {
                scrollTop.classList.remove('show');
            }
        });
        
        // Smooth scroll for hero indicator
        const scrollIndicator = document.querySelector('.hero-scroll-indicator');
        if (scrollIndicator) {
            scrollIndicator.addEventListener('click', function() {
                window.scrollBy({
                    top: window.innerHeight - 100,
                    behavior: 'smooth'
                });
            });
        }
    });
    
    async function loadHistoryData() {
        const loadingState = document.getElementById('loadingState');
        const timelineContainer = document.getElementById('timelineContainer');
        const emptyState = document.getElementById('emptyState');
        
        loadingState.style.display = 'block';
        timelineContainer.style.display = 'none';
        emptyState.style.display = 'none';
        
        try {
            const response = await fetch('/guest/about/history-data', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Data received:', data);
            
            loadingState.style.display = 'none';
            
            if (!data || !Array.isArray(data) || data.length === 0) {
                emptyState.style.display = 'block';
                return;
            }
            
            // Filter published records and sort by date (newest first)
            allHistoryData = data.filter(item => item.status === 'published');
            allHistoryData.sort((a, b) => new Date(b.date) - new Date(a.date));
            
            if (allHistoryData.length === 0) {
                emptyState.style.display = 'block';
                return;
            }
            
            // Extract unique years from data
            allYearsList = [...new Set(allHistoryData.map(item => {
                return new Date(item.date).getFullYear();
            }))].sort((a, b) => b - a);
            
            console.log('Years list:', allYearsList);
            
            // Populate year filter
            populateYearFilter();
            
            // Setup event listeners
            setupEventListeners();
            
            // Render initial data with pagination
            currentPage = 1;
            renderHistoryWithPagination();
            timelineContainer.style.display = 'block';
            
        } catch (error) {
            console.error('Error loading history:', error);
            loadingState.style.display = 'none';
            emptyState.style.display = 'block';
            emptyState.innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Error Loading Data</h3>
                <p class="text-muted">Unable to load history records. Please try again later.</p>
                <p class="text-muted small mt-2">Error: ${error.message}</p>
                <button class="btn btn-primary mt-3" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-2"></i>Retry
                </button>
            `;
        }
    }
    
    function populateYearFilter() {
        const yearFilter = document.getElementById('yearFilter');
        yearFilter.innerHTML = '<option value="">All Years</option>';
        
        allYearsList.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearFilter.appendChild(option);
        });
        
        console.log('Years populated in filter:', allYearsList);
    }
    
    function setupEventListeners() {
        const searchInput = document.getElementById('searchInput');
        const yearFilter = document.getElementById('yearFilter');
        const resetBtn = document.getElementById('resetFilters');
        
        searchInput.addEventListener('input', function(e) {
            currentFilters.search = e.target.value.toLowerCase();
            applyFilters();
        });
        
        yearFilter.addEventListener('change', function(e) {
            currentFilters.year = e.target.value;
            applyFilters();
        });
        
        resetBtn.addEventListener('click', function() {
            resetAllFilters();
        });
    }
    
    function resetAllFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('yearFilter').value = '';
        currentFilters = { search: '', year: '' };
        currentPage = 1;
        applyFilters();
    }
    
    function applyFilters() {
        let filteredData = [...allHistoryData];
        
        // Apply search filter
        if (currentFilters.search) {
            filteredData = filteredData.filter(item => 
                item.title.toLowerCase().includes(currentFilters.search) || 
                item.description.toLowerCase().includes(currentFilters.search)
            );
        }
        
        // Apply year filter
        if (currentFilters.year) {
            filteredData = filteredData.filter(item => {
                const itemYear = new Date(item.date).getFullYear();
                return itemYear.toString() === currentFilters.year;
            });
        }
        
        // Update years list based on filtered data
        allYearsList = [...new Set(filteredData.map(item => {
            return new Date(item.date).getFullYear();
        }))].sort((a, b) => b - a);
        
        currentPage = 1;
        renderHistoryWithPagination(filteredData);
    }
    
    function renderHistoryWithPagination(data = allHistoryData) {
        // Apply current filters to get the data
        let filteredData = [...data];
        
        if (currentFilters.search) {
            filteredData = filteredData.filter(item => 
                item.title.toLowerCase().includes(currentFilters.search) || 
                item.description.toLowerCase().includes(currentFilters.search)
            );
        }
        
        if (currentFilters.year) {
            filteredData = filteredData.filter(item => {
                const itemYear = new Date(item.date).getFullYear();
                return itemYear.toString() === currentFilters.year;
            });
        }
        
        // Get unique years from filtered data
        const availableYears = [...new Set(filteredData.map(item => {
            return new Date(item.date).getFullYear();
        }))].sort((a, b) => b - a);
        
        const resultsCount = document.getElementById('resultsCount');
        const noResultsState = document.getElementById('noResultsState');
        const paginationContainer = document.getElementById('paginationContainer');
        
        if (availableYears.length === 0) {
            document.getElementById('timelineContainer').style.display = 'none';
            resultsCount.style.display = 'none';
            paginationContainer.style.display = 'none';
            noResultsState.style.display = 'block';
            return;
        }
        
        noResultsState.style.display = 'none';
        resultsCount.style.display = 'block';
        resultsCount.innerHTML = `<i class="fas fa-list me-2"></i>Showing ${filteredData.length} of ${allHistoryData.length} history records | ${availableYears.length} years total`;
        
        // Pagination logic - show 5 years per page
        const totalPages = Math.ceil(availableYears.length / yearsPerPage);
        const startIndex = (currentPage - 1) * yearsPerPage;
        const endIndex = startIndex + yearsPerPage;
        const currentPageYears = availableYears.slice(startIndex, endIndex);
        
        // Filter data to only include current page years
        const pageData = filteredData.filter(item => {
            const itemYear = new Date(item.date).getFullYear();
            return currentPageYears.includes(itemYear);
        });
        
        // Group data by year for current page
        const groupedByYear = {};
        pageData.forEach(item => {
            const year = new Date(item.date).getFullYear();
            if (!groupedByYear[year]) {
                groupedByYear[year] = [];
            }
            groupedByYear[year].push(item);
        });
        
        // Render the timeline
        renderTimeline(groupedByYear, currentPageYears);
        
        // Render pagination
        renderPagination(currentPage, totalPages, availableYears.length);
        
        paginationContainer.style.display = totalPages > 1 ? 'flex' : 'none';
    }
    
    function renderTimeline(groupedByYear, years) {
        const container = document.getElementById('timelineContainer');
        container.innerHTML = '';
        
        years.forEach(year => {
            const yearData = groupedByYear[year] || [];
            
            // Sort items within year by date (newest first)
            yearData.sort((a, b) => new Date(b.date) - new Date(a.date));
            
            const yearSection = document.createElement('div');
            yearSection.className = 'year-section';
            
            yearSection.innerHTML = `
                <h2 class="year-title">
                    <i class="fas fa-calendar-alt me-2"></i>${year}
                    <span>(${yearData.length} ${yearData.length === 1 ? 'event' : 'events'})</span>
                </h2>
                <div class="cards-grid" id="year-${year}"></div>
            `;
            
            container.appendChild(yearSection);
            
            const cardsGrid = yearSection.querySelector(`#year-${year}`);
            
            yearData.forEach((record, index) => {
                const card = createHistoryCard(record, index);
                cardsGrid.appendChild(card);
            });
        });
    }
    
    function createHistoryCard(record, index) {
        const formattedDate = formatDate(record.date);
        const defaultImageUrl = '/images/default-image.png';
        const imageSrc = record.image_url ? record.image_url : defaultImageUrl;
        
        // Split description for read more functionality (40 words)
        const words = record.description.split(' ');
        const isLong = words.length > 40;
        const shortDescription = isLong ? words.slice(0, 40).join(' ') : record.description;
        const fullDescription = record.description;
        
        const card = document.createElement('div');
        card.className = 'history-card';
        card.style.animationDelay = `${index * 0.05}s`;
        
        card.innerHTML = `
            <div class="card-image" onclick="openImageModal('${imageSrc}')" style="cursor: pointer;">
                <img src="${imageSrc}" alt="${escapeHtml(record.title)}" loading="lazy">
                <div class="image-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            <div class="card-content">
                <span class="card-date">
                    <i class="far fa-calendar-alt me-2"></i>${formattedDate}
                </span>
                <h3 class="card-title">${escapeHtml(record.title)}</h3>
                <div class="card-description">
                    <div class="description-wrapper">
                        <span class="short-description" id="short-${record.id}">${escapeHtml(shortDescription)}</span>
                        <span class="full-description" id="full-${record.id}" style="display: none;">${escapeHtml(fullDescription)}</span>
                    </div>
                    ${isLong ? `<button class="read-more-btn" onclick="toggleReadMore(${record.id})">
                        <i class="fas fa-chevron-down me-1"></i>Read More
                    </button>` : ''}
                </div>
            </div>
        `;
        
        return card;
    }
    
    function renderPagination(currentPage, totalPages, totalYears) {
        const paginationContainer = document.getElementById('paginationContainer');
        
        if (totalPages <= 1) {
            paginationContainer.style.display = 'none';
            return;
        }
        
        paginationContainer.style.display = 'flex';
        
        let paginationHtml = `
            <button class="pagination-btn" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
                <i class="fas fa-chevron-left me-1"></i> Previous
            </button>
            <div class="page-numbers">
        `;
        
        // Show page numbers
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
        
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        if (startPage > 1) {
            paginationHtml += `<div class="page-number" onclick="changePage(1)">1</div>`;
            if (startPage > 2) paginationHtml += `<div class="page-number disabled">...</div>`;
        }
        
        for (let i = startPage; i <= endPage; i++) {
            paginationHtml += `
                <div class="page-number ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">
                    ${i}
                </div>
            `;
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) paginationHtml += `<div class="page-number disabled">...</div>`;
            paginationHtml += `<div class="page-number" onclick="changePage(${totalPages})">${totalPages}</div>`;
        }
        
        paginationHtml += `
            </div>
            <button class="pagination-btn" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
                Next <i class="fas fa-chevron-right ms-1"></i>
            </button>
        `;
        
        paginationContainer.innerHTML = paginationHtml;
    }
    
    function changePage(page) {
        const availableYears = [...new Set(allHistoryData.filter(item => {
            if (currentFilters.search && !item.title.toLowerCase().includes(currentFilters.search) && !item.description.toLowerCase().includes(currentFilters.search)) {
                return false;
            }
            if (currentFilters.year && new Date(item.date).getFullYear().toString() !== currentFilters.year) {
                return false;
            }
            return true;
        }).map(item => new Date(item.date).getFullYear()))].sort((a, b) => b - a);
        
        const totalPages = Math.ceil(availableYears.length / yearsPerPage);
        
        if (page < 1 || page > totalPages) return;
        
        currentPage = page;
        
        // Scroll to top smoothly
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        renderHistoryWithPagination();
    }
    
    function toggleReadMore(id) {
        const shortDesc = document.getElementById(`short-${id}`);
        const fullDesc = document.getElementById(`full-${id}`);
        const button = shortDesc.parentElement.parentElement.querySelector('.read-more-btn');
        
        if (shortDesc.style.display !== 'none') {
            shortDesc.style.display = 'none';
            fullDesc.style.display = 'inline';
            button.innerHTML = '<i class="fas fa-chevron-up me-1"></i>See Less';
        } else {
            shortDesc.style.display = 'inline';
            fullDesc.style.display = 'none';
            button.innerHTML = '<i class="fas fa-chevron-down me-1"></i>Read More';
        }
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
    
    // Prevent modal close when clicking on image
    document.getElementById('modalImage')?.addEventListener('click', function(e) {
        e.stopPropagation();
    });
</script>
@endsection