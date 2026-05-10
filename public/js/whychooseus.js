// Add animation on scroll
document.addEventListener('DOMContentLoaded', function() {
    // Initialize animations
    initAnimations();
    
    // Add hover effects
    initHoverEffects();
    
    // Add click effects to buttons
    initButtonEffects();
    
    // Initialize stats counter animation
    initStatsCounter();
});

function initAnimations() {
    // Add scroll animations to reason items
    const reasonItems = document.querySelectorAll('.reason-item');
    
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__fadeInUp');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    reasonItems.forEach(item => {
        observer.observe(item);
    });
    
    // Add animation to stats section
    const statsSection = document.querySelector('.stats-section');
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__fadeIn');
            }
        });
    }, { threshold: 0.3 });
    
    if (statsSection) {
        statsObserver.observe(statsSection);
    }
}

function initHoverEffects() {
    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('.cta-button');
    
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function(e) {
            const x = e.pageX - this.offsetLeft;
            const y = e.pageY - this.offsetTop;
            
            const ripple = document.createElement('span');
            ripple.classList.add('ripple-effect');
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Add tilt effect to reason items on mouse move
    const reasonItems = document.querySelectorAll('.reason-item');
    
    reasonItems.forEach(item => {
        item.addEventListener('mousemove', function(e) {
            const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
            
            this.style.transform = `translateY(-15px) scale(1.02) rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-15px) scale(1.02) rotateY(0deg) rotateX(0deg)';
        });
    });
}

function initButtonEffects() {
    // Add click animation to buttons
    const buttons = document.querySelectorAll('.cta-button');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Create click effect
            const circle = document.createElement('div');
            const diameter = Math.max(this.clientWidth, this.clientHeight);
            const radius = diameter / 2;
            
            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${e.clientX - this.getBoundingClientRect().left - radius}px`;
            circle.style.top = `${e.clientY - this.getBoundingClientRect().top - radius}px`;
            circle.classList.add('click-effect');
            
            this.appendChild(circle);
            
            setTimeout(() => {
                circle.remove();
            }, 600);
        });
    });
}

function initStatsCounter() {
    // Animate stats numbers
    const statNumbers = document.querySelectorAll('.stat-content h3');
    
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumber = entry.target;
                const target = parseInt(statNumber.textContent.replace('+', ''));
                const suffix = statNumber.textContent.includes('+') ? '+' : '';
                const duration = 2000; // 2 seconds
                const increment = target / (duration / 16); // 60fps
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    statNumber.textContent = Math.floor(current) + suffix;
                }, 16);
                
                statsObserver.unobserve(statNumber);
            }
        });
    }, { threshold: 0.5 });
    
    statNumbers.forEach(number => {
        statsObserver.observe(number);
    });
}

// Add CSS for ripple and click effects
const style = document.createElement('style');
style.textContent = `
    .ripple-effect {
        position: absolute;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 50%;
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    
    .click-effect {
        position: absolute;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        transform: scale(0);
        animation: click-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    @keyframes click-animation {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
`;

document.head.appendChild(style);