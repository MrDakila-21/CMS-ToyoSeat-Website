/**
 * EventActivity Module - Guest View
 * Handles real-time search, filtering, modals, and image preview with zoom
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
    
    // Zoom variables
    let currentZoom = 1;
    let isPanning = false;
    let startX = 0;
    let startY = 0;
    let translateX = 0;
    let translateY = 0;
    
    // Create image preview modal with zoom controls
    function createImagePreviewModal() {
        // Check if modal already exists
        if (document.getElementById('imagePreviewModal')) {
            return;
        }
        
        const modalHTML = `
            <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content bg-dark" style="border-radius: 0 !important;">
                        <div class="modal-header border-0" style="position: absolute; top: 0; left: 0; right: 0; z-index: 1050; background: rgba(0,0,0,0.5); backdrop-filter: blur(10px);">
                            <div class="zoom-controls">
                                <button type="button" class="btn btn-light btn-sm rounded-circle me-2" id="zoomOutBtn" title="Zoom Out">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                                <span class="text-white mx-2" id="zoomLevel">100%</span>
                                <button type="button" class="btn btn-light btn-sm rounded-circle ms-2" id="zoomInBtn" title="Zoom In">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button type="button" class="btn btn-light btn-sm rounded-circle ms-2" id="resetZoomBtn" title="Reset Zoom">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex align-items-center justify-content-center p-0 overflow-hidden" id="previewModalBody">
                            <div id="imageContainer" style="cursor: grab; display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                                <img id="previewImage" src="" alt="Preview" style="max-width: 100%; max-height: 100vh; object-fit: contain; transition: transform 0.2s ease; user-select: none;">
                            </div>
                        </div>
                        <div class="modal-footer border-0 justify-content-center" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(10px);">
                            <button type="button" class="btn btn-light rounded-pill" id="downloadImageBtn">
                                <i class="fas fa-download me-2"></i>Download
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Add zoom functionality
        const zoomInBtn = document.getElementById('zoomInBtn');
        const zoomOutBtn = document.getElementById('zoomOutBtn');
        const resetZoomBtn = document.getElementById('resetZoomBtn');
        const zoomLevel = document.getElementById('zoomLevel');
        const previewImage = document.getElementById('previewImage');
        const imageContainer = document.getElementById('imageContainer');
        
        // Zoom in function
        function zoomIn() {
            if (currentZoom < 3) {
                currentZoom += 0.25;
                updateZoom();
            }
        }
        
        // Zoom out function
        function zoomOut() {
            if (currentZoom > 0.5) {
                currentZoom -= 0.25;
                updateZoom();
            }
        }
        
        // Reset zoom function
        function resetZoom() {
            currentZoom = 1;
            translateX = 0;
            translateY = 0;
            updateZoom();
            updateTransform();
        }
        
        // Update zoom display and image transform
        function updateZoom() {
            if (zoomLevel) {
                zoomLevel.textContent = Math.round(currentZoom * 100) + '%';
            }
            updateTransform();
        }
        
        // Update image transform with pan
        function updateTransform() {
            if (previewImage) {
                previewImage.style.transform = `translate(${translateX}px, ${translateY}px) scale(${currentZoom})`;
            }
        }
        
        // Pan functionality
        function startPan(e) {
            if (currentZoom > 1) {
                isPanning = true;
                startX = e.clientX - translateX;
                startY = e.clientY - translateY;
                imageContainer.style.cursor = 'grabbing';
                e.preventDefault();
            }
        }
        
        function pan(e) {
            if (isPanning && currentZoom > 1) {
                translateX = e.clientX - startX;
                translateY = e.clientY - startY;
                
                // Limit panning based on zoom level
                const maxTranslateX = (previewImage.clientWidth * currentZoom - previewImage.clientWidth) / 2;
                const maxTranslateY = (previewImage.clientHeight * currentZoom - previewImage.clientHeight) / 2;
                
                translateX = Math.min(Math.max(translateX, -maxTranslateX), maxTranslateX);
                translateY = Math.min(Math.max(translateY, -maxTranslateY), maxTranslateY);
                
                updateTransform();
                e.preventDefault();
            }
        }
        
        function stopPan() {
            isPanning = false;
            imageContainer.style.cursor = 'grab';
        }
        
        // Mouse wheel zoom
        function handleWheelZoom(e) {
            e.preventDefault();
            const delta = e.deltaY > 0 ? -0.1 : 0.1;
            const newZoom = currentZoom + delta;
            
            if (newZoom >= 0.5 && newZoom <= 3) {
                currentZoom = newZoom;
                updateZoom();
            }
        }
        
        // Add event listeners for zoom controls
        if (zoomInBtn) zoomInBtn.addEventListener('click', zoomIn);
        if (zoomOutBtn) zoomOutBtn.addEventListener('click', zoomOut);
        if (resetZoomBtn) resetZoomBtn.addEventListener('click', resetZoom);
        
        // Add pan event listeners
        if (imageContainer) {
            imageContainer.addEventListener('mousedown', startPan);
            window.addEventListener('mousemove', pan);
            window.addEventListener('mouseup', stopPan);
            imageContainer.addEventListener('wheel', handleWheelZoom);
            
            // Touch events for mobile
            imageContainer.addEventListener('touchstart', function(e) {
                if (currentZoom > 1) {
                    isPanning = true;
                    startX = e.touches[0].clientX - translateX;
                    startY = e.touches[0].clientY - translateY;
                    e.preventDefault();
                }
            });
            
            window.addEventListener('touchmove', function(e) {
                if (isPanning && currentZoom > 1) {
                    translateX = e.touches[0].clientX - startX;
                    translateY = e.touches[0].clientY - startY;
                    
                    const maxTranslateX = (previewImage.clientWidth * currentZoom - previewImage.clientWidth) / 2;
                    const maxTranslateY = (previewImage.clientHeight * currentZoom - previewImage.clientHeight) / 2;
                    
                    translateX = Math.min(Math.max(translateX, -maxTranslateX), maxTranslateX);
                    translateY = Math.min(Math.max(translateY, -maxTranslateY), maxTranslateY);
                    
                    updateTransform();
                    e.preventDefault();
                }
            });
            
            window.addEventListener('touchend', function() {
                isPanning = false;
            });
        }
        
        // Reset zoom when modal is closed
        const modal = document.getElementById('imagePreviewModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                resetZoom();
            });
            
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) bsModal.hide();
                }
            });
        }
        
        // Add download functionality
        const downloadBtn = document.getElementById('downloadImageBtn');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                const img = document.getElementById('previewImage');
                if (img && img.src) {
                    const link = document.createElement('a');
                    link.href = img.src;
                    link.download = 'event-image.jpg';
                    link.click();
                }
            });
        }
    }
    
    // Function to show image preview
    window.showImagePreview = function(imageUrl, title = 'Image Preview') {
        createImagePreviewModal();
        
        const previewImg = document.getElementById('previewImage');
        if (previewImg) {
            previewImg.src = imageUrl;
            previewImg.alt = title;
            
            // Reset zoom when loading new image
            currentZoom = 1;
            translateX = 0;
            translateY = 0;
            if (document.getElementById('previewImage')) {
                document.getElementById('previewImage').style.transform = `translate(0px, 0px) scale(1)`;
            }
        }
        
        const modalElement = document.getElementById('imagePreviewModal');
        if (modalElement && typeof bootstrap !== 'undefined') {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }
    
    // Helper function to update transform (needs to be accessible globally within this scope)
    function updateTransform() {
        const previewImage = document.getElementById('previewImage');
        if (previewImage) {
            previewImage.style.transform = `translate(${translateX}px, ${translateY}px) scale(${currentZoom})`;
        }
    }
    
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
        
        events.forEach((item, index) => {
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
            
            const createdDate = item.created_at ? new Date(item.created_at).toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            }) : '';
            
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
                                        <div class="modal-image-container position-relative" style="overflow: hidden; border-radius: 12px;">
                                            <img src="${imageUrl}" 
                                                 class="modal-image clickable-image w-100 rounded-3" 
                                                 alt="${escapeHtml(item.title)}" 
                                                 style="cursor: pointer; transition: transform 0.2s; max-height: 400px; width: 100%; object-fit: cover; display: block;"
                                                 data-full-image="${imageUrl}"
                                                 data-image-title="${escapeHtml(item.title)}"
                                                 onerror="this.src='${defaultImage}'">
                                            <div class="image-expand-icon" style="position: absolute; bottom: 12px; right: 12px; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); padding: 8px 12px; border-radius: 25px; color: white; font-size: 14px; cursor: pointer; transition: all 0.2s ease; z-index: 10;">
                                                <i class="fas fa-expand-alt me-1"></i> Expand
                                            </div>
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
                                            ${createdDate ? `
                                            <div class="info-badge-item">
                                                <i class="fas fa-clock"></i>
                                                <span><strong>Published:</strong> ${createdDate}</span>
                                            </div>
                                            ` : ''}
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="description-wrapper-custom">
                                            <div class="description-title">
                                                <i class="fas fa-align-left me-2" style="color: #3988BD;"></i>
                                                Description
                                            </div>
                                            <div class="description-content" style="max-height: 400px; overflow-y: auto;">
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
        
        // CRITICAL FIX: Attach event handlers for image preview
        attachImagePreviewHandlers();
    }
    
    // NEW FUNCTION: Attach handlers for image preview
    function attachImagePreviewHandlers() {
        // Handle expand icon clicks
        document.querySelectorAll('.image-expand-icon').forEach(icon => {
            // Remove existing listener to avoid duplicates
            icon.removeEventListener('click', icon._listener);
            // Create new listener
            const listener = function(e) {
                e.stopPropagation();
                const container = this.closest('.modal-image-container');
                const img = container.querySelector('.clickable-image');
                if (img) {
                    const imageUrl = img.getAttribute('data-full-image') || img.src;
                    const imageTitle = img.getAttribute('data-image-title') || 'Image Preview';
                    window.showImagePreview(imageUrl, imageTitle);
                }
            };
            icon.addEventListener('click', listener);
            icon._listener = listener;
        });
        
        // Handle image clicks
        document.querySelectorAll('.clickable-image').forEach(img => {
            // Remove existing listener to avoid duplicates
            img.removeEventListener('click', img._listener);
            // Create new listener
            const listener = function(e) {
                e.stopPropagation();
                const imageUrl = this.getAttribute('data-full-image') || this.src;
                const imageTitle = this.getAttribute('data-image-title') || 'Image Preview';
                window.showImagePreview(imageUrl, imageTitle);
            };
            img.addEventListener('click', listener);
            img._listener = listener;
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
    
    // Initialize image preview for initial page load
    attachImagePreviewHandlers();
});