/**
 * EventActivity Module - Guest View
 * Using the SAME approach as announcements module (which works on deployed)
 */

// Make sure jQuery is available (like announcements module)
if (typeof jQuery === 'undefined') {
    console.error('jQuery not loaded!');
}

$(document).ready(function() {
    console.log('EventActivity.js loaded with jQuery');
    
    let searchTimeout;
    let currentPage = 1;
    let isLoading = false;
    
    // Zoom variables
    let currentZoom = 1;
    let isPanning = false;
    let startX = 0;
    let startY = 0;
    let translateX = 0;
    let translateY = 0;
    
    // Create fullscreen image preview modal (EXACT same as announcements)
    function createImagePreviewModal() {
        if ($('#imagePreviewModal').length) {
            return;
        }
        
        const modalHTML = `
        <div id="imagePreviewModal" style="
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
            z-index: 10000;
            overflow: hidden;
        ">
            <div style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 75px;
                background: linear-gradient(to bottom, rgba(0,0,0,0.9), rgba(0,0,0,0.45), transparent);
                backdrop-filter: blur(10px);
                z-index: 10000;
                pointer-events: none;
            "></div>

            <div style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 75px;
                background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.45), transparent);
                backdrop-filter: blur(10px);
                z-index: 10000;
                pointer-events: none;
            "></div>

            <div style="
                position: absolute;
                top: 20px;
                left: 20px;
                right: 20px;
                z-index: 10001;
                display: flex;
                justify-content: space-between;
                align-items: center;
            ">
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
                <button id="closePreviewBtn" style="background: rgba(255,255,255,0.2); border: none; width: 45px; height: 45px; border-radius: 50%; cursor: pointer; color: white; font-size: 24px; backdrop-filter: blur(8px);">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="fullscreenImageContainer" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; cursor: grab; overflow: hidden;">
                <img id="fullscreenPreviewImage" src="" alt="Preview" style="max-width: 90%; max-height: 90vh; object-fit: contain; transition: transform 0.2s ease; user-select: none;">
            </div>

            <div style="position: absolute; bottom: 20px; left: 0; right: 0; text-align: center; z-index: 10001;">
                <button type="button" class="btn btn-light rounded-pill" id="downloadImageBtn" style="backdrop-filter: blur(8px); background: rgba(255,255,255,0.9);">
                    <i class="fas fa-download me-2"></i>Download
                </button>
            </div>
        </div>
        `;
        
        $('body').append(modalHTML);
        
        // Get elements
        const modal = document.getElementById('imagePreviewModal');
        const zoomInBtn = document.getElementById('zoomInBtn');
        const zoomOutBtn = document.getElementById('zoomOutBtn');
        const resetZoomBtn = document.getElementById('resetZoomBtn');
        const closeBtn = document.getElementById('closePreviewBtn');
        const downloadBtn = document.getElementById('downloadImageBtn');
        const previewImage = document.getElementById('fullscreenPreviewImage');
        const imageContainer = document.getElementById('fullscreenImageContainer');
        const zoomLevel = document.getElementById('zoomLevel');
        
        function zoomIn() {
            if (currentZoom < 3) {
                currentZoom += 0.25;
                updateZoom();
            }
        }
        
        function zoomOut() {
            if (currentZoom > 0.5) {
                currentZoom -= 0.25;
                updateZoom();
            }
        }
        
        function resetZoom() {
            currentZoom = 1;
            translateX = 0;
            translateY = 0;
            updateZoom();
            updateTransform();
        }
        
        function updateZoom() {
            if (zoomLevel) {
                zoomLevel.textContent = Math.round(currentZoom * 100) + '%';
            }
            updateTransform();
        }
        
        function updateTransform() {
            if (previewImage) {
                previewImage.style.transform = `translate(${translateX}px, ${translateY}px) scale(${currentZoom})`;
            }
        }
        
        function startPan(e) {
            if (currentZoom > 1) {
                isPanning = true;
                const clientX = e.clientX || (e.touches && e.touches[0].clientX);
                const clientY = e.clientY || (e.touches && e.touches[0].clientY);
                startX = clientX - translateX;
                startY = clientY - translateY;
                if (imageContainer) imageContainer.style.cursor = 'grabbing';
                e.preventDefault();
            }
        }
        
        function pan(e) {
            if (isPanning && currentZoom > 1 && previewImage) {
                const clientX = e.clientX || (e.touches && e.touches[0].clientX);
                const clientY = e.clientY || (e.touches && e.touches[0].clientY);
                translateX = clientX - startX;
                translateY = clientY - startY;
                
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
            if (imageContainer) imageContainer.style.cursor = 'grab';
        }
        
        function handleWheelZoom(e) {
            e.preventDefault();
            const delta = e.deltaY > 0 ? -0.1 : 0.1;
            const newZoom = currentZoom + delta;
            
            if (newZoom >= 0.5 && newZoom <= 3) {
                currentZoom = newZoom;
                updateZoom();
            }
        }
        
        if (zoomInBtn) zoomInBtn.addEventListener('click', zoomIn);
        if (zoomOutBtn) zoomOutBtn.addEventListener('click', zoomOut);
        if (resetZoomBtn) resetZoomBtn.addEventListener('click', resetZoom);
        
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
                resetZoom();
            });
        }
        
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                if (previewImage && previewImage.src) {
                    const link = document.createElement('a');
                    link.href = previewImage.src;
                    link.download = 'event-image.jpg';
                    link.click();
                }
            });
        }
        
        if (imageContainer) {
            imageContainer.addEventListener('mousedown', startPan);
            window.addEventListener('mousemove', pan);
            window.addEventListener('mouseup', stopPan);
            imageContainer.addEventListener('wheel', handleWheelZoom);
            imageContainer.addEventListener('touchstart', startPan);
            window.addEventListener('touchmove', pan);
            window.addEventListener('touchend', stopPan);
        }
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
                resetZoom();
            }
        });
        
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                modal.style.display = 'none';
                resetZoom();
            }
        });
    }
    
    // Show image preview
    function showImagePreview(imageUrl, title = 'Image Preview') {
        createImagePreviewModal();
        
        const modal = document.getElementById('imagePreviewModal');
        const previewImg = document.getElementById('fullscreenPreviewImage');
        
        if (previewImg) {
            previewImg.src = imageUrl;
            previewImg.alt = title;
            currentZoom = 1;
            translateX = 0;
            translateY = 0;
            updateTransform();
        }
        
        if (modal) {
            modal.style.display = 'flex';
            modal.style.flexDirection = 'column';
        }
    }
    
    function updateTransform() {
        const previewImage = document.getElementById('fullscreenPreviewImage');
        if (previewImage) {
            previewImage.style.transform = `translate(${translateX}px, ${translateY}px) scale(${currentZoom})`;
        }
    }
    
    // Load events via AJAX (using jQuery like announcements module)
    function loadEvents(search = '', page = 1, type = 'all') {
        if (isLoading) return;
        isLoading = true;
        
        $('#eventsContainer').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');
        
        $.ajax({
            url: window.location.pathname,
            type: 'GET',
            data: {
                ajax: 1,
                page: page,
                search: search,
                type: type
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.html) {
                    $('#eventsContainer').html(response.html);
                } else if (response.data) {
                    // Handle JSON response
                    renderEventsWithjQuery(response.data);
                    updatePaginationWithjQuery(response);
                } else {
                    $('#eventsContainer').html(response);
                }
                
                // Reattach image click handlers
                attachImageClickHandlers();
                isLoading = false;
            },
            error: function(xhr) {
                console.error('Error loading events:', xhr);
                $('#eventsContainer').html('<div class="empty-state"><div class="empty-icon"><i class="fas fa-exclamation-triangle"></i></div><h3>Error Loading Events</h3><p>Please try again later.</p></div>');
                isLoading = false;
            }
        });
    }
    
    // Render events using jQuery
    function renderEventsWithjQuery(events) {
        let html = '';
        const defaultImage = '/images/default-image.png';
        
        events.forEach(function(item) {
            let imageUrl = item.image_url || defaultImage;
            const eventDate = new Date(item.event_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            
            html += `
                <div class="col-md-6 col-lg-4 event-item" data-type="${item.type}">
                    <div class="card h-100 shadow-sm border-0 event-card" data-bs-toggle="modal" data-bs-target="#eventModal${item.id}">
                        <div class="position-relative overflow-hidden">
                            <img src="${imageUrl}" class="card-img-top" alt="${escapeHtml(item.title)}" style="height: 240px; width: 100%; object-fit: cover;" onerror="this.src='${defaultImage}'">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge ${item.type === 'event' ? 'bg-primary' : 'bg-success'} px-3 py-2 rounded-pill">
                                    <i class="fas ${item.type === 'event' ? 'fa-calendar-alt' : 'fa-users'} me-1"></i>
                                    ${item.type.charAt(0).toUpperCase() + item.type.slice(1)}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-2" style="color: #0E334C;">${escapeHtml(item.title).substring(0, 60)}${item.title.length > 60 ? '...' : ''}</h5>
                            <p class="card-text text-muted mb-0">
                                <i class="fas fa-calendar-alt me-2" style="color: #3988BD;"></i>
                                ${eventDate}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#eventsContainer').html(html);
    }
    
    function updatePaginationWithjQuery(data) {
        if (!data.last_page) return;
        
        let paginationHtml = '<ul class="pagination">';
        
        if (data.current_page > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page - 1}">Previous</a></li>`;
        } else {
            paginationHtml += `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
        }
        
        for (let i = 1; i <= data.last_page; i++) {
            if (i === data.current_page) {
                paginationHtml += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }
        
        if (data.current_page < data.last_page) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page + 1}">Next</a></li>`;
        } else {
            paginationHtml += `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
        }
        
        paginationHtml += '</ul>';
        $('#paginationContainer').html(paginationHtml);
        
        $('.pagination a').on('click', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page) {
                currentPage = page;
                loadEvents($('#searchInput').val(), currentPage, currentType);
                $('html, body').animate({ scrollTop: 0 }, 300);
            }
        });
    }
    
    // Attach image click handlers (EXACT same as announcements)
    function attachImageClickHandlers() {
        $('.clickable-image').off('click').on('click', function(e) {
            e.stopPropagation();
            const imageUrl = $(this).data('full-image') || $(this).attr('src');
            const imageTitle = $(this).data('image-title') || 'Event Image';
            if (imageUrl && !imageUrl.includes('default-image.png')) {
                showImagePreview(imageUrl, imageTitle);
            }
        });
        
        $('.image-expand-icon').off('click').on('click', function(e) {
            e.stopPropagation();
            const container = $(this).closest('.modal-image-container');
            const img = container.find('.clickable-image');
            if (img.length) {
                const imageUrl = img.data('full-image') || img.attr('src');
                const imageTitle = img.data('image-title') || 'Event Image';
                if (imageUrl && !imageUrl.includes('default-image.png')) {
                    showImagePreview(imageUrl, imageTitle);
                }
            }
        });
    }
    
    // Escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        return $('<div>').text(text).html();
    }
    
    // Search functionality
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val();
        
        searchTimeout = setTimeout(function() {
            currentPage = 1;
            loadEvents(searchTerm, currentPage, currentType);
        }, 500);
    });
    
    // Filter buttons
    let currentType = 'all';
    $('.filter-btn').on('click', function() {
        currentType = $(this).data('type');
        currentPage = 1;
        
        $('.filter-btn').each(function() {
            const btnType = $(this).data('type');
            if (btnType === 'all') {
                $(this).removeClass('btn-primary').addClass('btn-outline-primary');
            } else if (btnType === 'event') {
                $(this).removeClass('btn-primary').addClass('btn-outline-primary');
            } else if (btnType === 'activity') {
                $(this).removeClass('btn-success').addClass('btn-outline-success');
            }
        });
        
        if (currentType === 'all') {
            $(this).removeClass('btn-outline-primary').addClass('btn-primary');
        } else if (currentType === 'event') {
            $(this).removeClass('btn-outline-primary').addClass('btn-primary');
        } else if (currentType === 'activity') {
            $(this).removeClass('btn-outline-success').addClass('btn-success');
        }
        
        loadEvents($('#searchInput').val(), currentPage, currentType);
    });
    
    // Smooth scroll
    $('.hero-scroll-indicator').on('click', function() {
        $('html, body').animate({
            scrollTop: $('.search-box').offset().top - 50
        }, 800);
    });
    
    // Initialize
    attachImageClickHandlers();
    
    console.log('EventActivity.js initialized successfully');
});