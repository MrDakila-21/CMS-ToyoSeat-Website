/**
 * EventActivity Module - Guest View
 * Handles real-time search, filtering, and modals
 */

document.addEventListener('DOMContentLoaded', function() {
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const eventsContainer = document.getElementById('eventsContainer');
    const paginationContainer = document.getElementById('paginationContainer');
    const noResultsMessage = document.getElementById('noResultsMessage');
    
    let currentType = currentTypeFromUrl();
    let currentSearch = currentSearchFromUrl();
    let currentPage = 1;
    
    // Get current type from URL
    function currentTypeFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        const type = urlParams.get('type');
        return type && type !== 'all' ? type : 'all';
    }
    
    // Get current search from URL
    function currentSearchFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('search') || '';
    }
    
    // Set search input value
    if (searchInput && currentSearch) {
        searchInput.value = currentSearch;
    }
    
    // Function to fetch filtered events via AJAX
    function fetchFilteredEvents(page = 1) {
        // Get the base URL from the current page
        let url = window.location.pathname + "?ajax=1&page=" + page;
        
        if (currentSearch) {
            url += "&search=" + encodeURIComponent(currentSearch);
        }
        
        if (currentType && currentType !== 'all') {
            url += "&type=" + currentType;
        }
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.total === 0) {
                if (eventsContainer) eventsContainer.innerHTML = '';
                if (paginationContainer) paginationContainer.style.display = 'none';
                if (noResultsMessage) noResultsMessage.style.display = 'block';
            } else {
                if (paginationContainer) paginationContainer.style.display = 'flex';
                if (noResultsMessage) noResultsMessage.style.display = 'none';
                renderEvents(data.data);
                updatePagination(data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // Function to render events with correct image URLs
    function renderEvents(events) {
        if (!eventsContainer) return;
        
        let html = '';
        const defaultImage = "/images/default-image.png";
        
        events.forEach(item => {
            let imageUrl = item.image_url;
            if (!imageUrl || imageUrl === '') {
                imageUrl = defaultImage;
            }
            
            const eventDate = new Date(item.event_date).toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            const typeBadge = item.type === 'event' 
                ? '<span class="badge bg-primary px-3 py-2 rounded-pill"><i class="fas fa-calendar-alt me-1"></i> Event</span>'
                : '<span class="badge bg-success px-3 py-2 rounded-pill"><i class="fas fa-users me-1"></i> Activity</span>';
            
            html += `
                <div class="col-md-6 col-lg-4 event-item" data-type="${item.type}">
                    <div class="card h-100 shadow-sm border-0 event-card" data-bs-toggle="modal" data-bs-target="#eventModal${item.id}" style="cursor: pointer; transition: all 0.3s ease;">
                        <div class="position-relative overflow-hidden">
                            <img src="${imageUrl}" class="card-img-top" alt="${escapeHtml(item.title)}" style="height: 240px; width: 100%; object-fit: cover;" onerror="this.src='${defaultImage}'">
                            <div class="position-absolute top-0 end-0 m-3">
                                ${typeBadge}
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-2" style="color: #0E334C; line-height: 1.4;">
                                ${escapeHtml(item.title).substring(0, 60)}${item.title.length > 60 ? '...' : ''}
                            </h5>
                            <p class="card-text text-muted mb-0">
                                <i class="fas fa-calendar-alt me-2" style="color: #3988BD;"></i>
                                ${eventDate}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="modal fade" id="eventModal${item.id}" tabindex="-1" aria-labelledby="eventModalLabel${item.id}" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 overflow-hidden">
                            <div class="modal-header" style="background: linear-gradient(135deg, #0E334C 0%, #1a4d6e 100%);">
                                <h5 class="modal-title text-white fw-bold">
                                    <i class="fas ${item.type === 'event' ? 'fa-calendar-alt' : 'fa-users'} me-2"></i>
                                    ${escapeHtml(item.title)}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row g-4">
                                    <div class="col-lg-5">
                                        <div class="modal-image-container">
                                            <img src="${imageUrl}" class="modal-image" alt="${escapeHtml(item.title)}" onerror="this.src='${defaultImage}'">
                                        </div>
                                        <div class="info-badge mt-3">
                                            <div class="info-badge-item">
                                                <i class="fas ${item.type === 'event' ? 'fa-calendar-alt' : 'fa-users'}"></i>
                                                <span><strong>Type:</strong> ${item.type.charAt(0).toUpperCase() + item.type.slice(1)}</span>
                                            </div>
                                            <div class="info-badge-item">
                                                <i class="fas fa-calendar-day"></i>
                                                <span><strong>Date:</strong> ${eventDate}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="description-wrapper-custom">
                                            <div class="description-title">
                                                <i class="fas fa-align-left me-2" style="color: #3988BD;"></i>
                                                Description
                                            </div>
                                            <div class="description-content">
                                                ${escapeHtml(item.description).replace(/\n/g, '<br>')}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        eventsContainer.innerHTML = html;
        
        // Reinitialize modal triggers
        document.querySelectorAll('.event-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.closest('.badge')) {
                    e.stopPropagation();
                }
            });
        });
    }
    
    // Update pagination links
    function updatePagination(data) {
        if (!paginationContainer) return;
        
        let paginationHtml = '<ul class="pagination">';
        
        // Previous button
        if (data.current_page > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page - 1}">Previous</a></li>`;
        } else {
            paginationHtml += `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
        }
        
        // Page numbers
        for (let i = 1; i <= data.last_page; i++) {
            if (i === data.current_page) {
                paginationHtml += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }
        
        // Next button
        if (data.current_page < data.last_page) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page + 1}">Next</a></li>`;
        } else {
            paginationHtml += `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
        }
        
        paginationHtml += '</ul>';
        paginationContainer.innerHTML = paginationHtml;
        
        // Attach pagination click events
        document.querySelectorAll('#paginationContainer .page-link[data-page]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.getAttribute('data-page'));
                if (!isNaN(page)) {
                    currentPage = page;
                    fetchFilteredEvents(page);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    }
    
    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Search input with debounce
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            currentSearch = this.value;
            currentPage = 1;
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetchFilteredEvents(1);
            }, 500);
        });
    }
    
    // Filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            currentType = type;
            currentPage = 1;
            
            // Update button styles
            filterButtons.forEach(btn => {
                const btnType = btn.getAttribute('data-type');
                if (btnType === 'all') {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                } else if (btnType === 'event') {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                } else if (btnType === 'activity') {
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-success');
                }
            });
            
            if (type === 'all') {
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');
            } else if (type === 'event') {
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');
            } else if (type === 'activity') {
                this.classList.remove('btn-outline-success');
                this.classList.add('btn-success');
            }
            
            fetchFilteredEvents(1);
        });
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