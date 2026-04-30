// resources/js/home.js

document.addEventListener('DOMContentLoaded', function() {
    // Card click handling with smooth navigation
    const cards = document.querySelectorAll('.card-item');
    
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            const url = this.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        });
    });

    // Optional: Add fade-in animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe text container for fade-in effect
    const textContainer = document.querySelector('.text-container');
    if (textContainer) {
        textContainer.style.opacity = '0';
        textContainer.style.transform = 'translateY(20px)';
        textContainer.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        observer.observe(textContainer);
    }

    // Observe bottom cards for fade-in effect
    const bottomCards = document.querySelector('.bottom-cards');
    if (bottomCards) {
        bottomCards.style.opacity = '0';
        bottomCards.style.transform = 'translateY(20px)';
        bottomCards.style.transition = 'opacity 0.8s ease 0.3s, transform 0.8s ease 0.3s';
        observer.observe(bottomCards);
    }
});