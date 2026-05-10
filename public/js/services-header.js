// document.addEventListener('DOMContentLoaded', function() {
//     // Set language to Khmer
//     document.documentElement.lang = 'km';
    
//     // Tab functionality
//     const tabButtons = document.querySelectorAll('.tab-btn');
//     const tabPanes = document.querySelectorAll('.tab-pane');
    
//     // Tab data for other panes
//     const tabData = {
//         storage: {
//             title: "សេវាកម្មឃ្លាំងផ្ទុកដ៏ទំនើប",
//             description: "យើងផ្តល់ជូននូវដំណោះស្រាយឃ្លាំងផ្ទុកដ៏ទំនើប ដោយមានប្រព័ន្ធគ្រប់គ្រងសារពើភ័ណ្ឌ និងសុវត្ថិភាពខ្ពស់បំផុត។",
//             image: "https://images.unsplash.com/photo-1553413077-190dd305871c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
//             features: [
//                 {
//                     icon: "fas fa-temperature-low",
//                     title: "ឃ្លាំងត្រជាក់",
//                     description: "ឃ្លាំងផ្ទុកត្រជាក់សម្រាប់ផលិតផលដែលត្រូវការសីតុណ្ហភាពពិសេស"
//                 },
//                 {
//                     icon: "fas fa-lock",
//                     title: "សុវត្ថិភាពខ្ពស់",
//                     description: "ប្រព័ន្ធសុវត្ថិភាពទំនើបជាមួយការត្រួតពិនិត្យ CCTV"
//                 },
//                 {
//                     icon: "fas fa-boxes",
//                     title: "ការគ្រប់គ្រងសារពើភ័ណ្ឌ",
//                     description: "ប្រព័ន្ធគ្រប់គ្រងសារពើភ័ណ្ឌដោយដៃគុណ"
//                 },
//                 {
//                     icon: "fas fa-truck-loading",
//                     title: "ការចែកចាយយ៉ាងរហ័ស",
//                     description: "ដំណើរការចែកចាយដ៏មានប្រសិទ្ធភាពសម្រាប់ការបញ្ជាទិញរហ័ស"
//                 }
//             ]
//         },
//         distribution: {
//             title: "ប្រព័ន្ធចែកចាយដ៏មានប្រសិទ្ធភាព",
//             description: "យើងផ្តល់ជូននូវដំណោះស្រាយចែកចាយពេញលេញ ពីឃ្លាំងរបស់អ្នកទៅកាន់ដៃអតិថិជន។",
//             image: "https://images.unsplash.com/photo-1562887189-e5d078343de4?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
//             features: [
//                 {
//                     icon: "fas fa-route",
//                     title: "ផែនការផ្លូវប្រសើរ",
//                     description: "ការរៀបចំផ្លូវដ៏មានប្រសិទ្ធភាពសម្រាប់ពេលវេលានិងថ្លៃដើមទាបបំផុត"
//                 },
//                 {
//                     icon: "fas fa-map-marker-alt",
//                     title: "តាមដានជូនជាពេលវេលាពិត",
//                     description: "ការតាមដានជូនដោយ GPS សម្រាប់ការតាមដានជូនភ្លាមៗ"
//                 },
//                 {
//                     icon: "fas fa-users",
//                     title: "ក្រុមចែកចាយអាជីព",
//                     description: "ក្រុមអ្នកដឹកជញ្ជូនដែលមានជំនាញ និងបទពិសោធន៍"
//                 },
//                 {
//                     icon: "fas fa-chart-line",
//                     title: "របាយការណ៍ការអនុវត្ត",
//                     description: "ការវិភាគលម្អិត និងរបាយការណ៍សម្រាប់ការកែលម្អប្រសិទ្ធភាព"
//                 }
//             ]
//         },
//         consulting: {
//             title: "សេវាកម្មប្រឹក្សាយោបល់ឡូជីខ្ទិក",
//             description: "ក្រុមអ្នកជំនាញរបស់យើងផ្តល់ជូននូយការប្រឹក្សាយោបល់ឡូជីខ្ទិកដើម្បីបង្កើនប្រសិទ្ធភាពនៃប្រតិបត្តិការដឹកជញ្ជូនរបស់អ្នក។",
//             image: "https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
//             features: [
//                 {
//                     icon: "fas fa-search",
//                     title: "ការវាយតម្លៃដំណើរការ",
//                     description: "ការវាយតម្លៃដំណើរការដឹកជញ្ជូនដើម្បីកំណត់តំបន់ដែលត្រូវកែលម្អ"
//                 },
//                 {
//                     icon: "fas fa-lightbulb",
//                     title: "ដំណោះស្រាយបង្កើតថ្មី",
//                     description: "ការណែនាំដំណោះស្រាយដែលបង្កើតថ្មីសម្រាប់បង្កើនប្រសិទ្ធភាព"
//                 },
//                 {
//                     icon: "fas fa-cogs",
//                     title: "ការអនុវត្តបច្ចេកវិទ្យា",
//                     description: "ការណែនាំ និងអនុវត្តប្រព័ន្ធបច្ចេកវិទ្យាដឹកជញ្ជូនទំនើប"
//                 },
//                 {
//                     icon: "fas fa-graduation-cap",
//                     title: "ការបណ្តុះបណ្តាលបុគ្គលិក",
//                     description: "កម្មវិធីបណ្តុះបណ្តាលសម្រាប់ក្រុមដឹកជញ្ជូនរបស់អ្នក"
//                 }
//             ]
//         }
//     };
    
//     // Initialize tabs
//     tabButtons.forEach(button => {
//         button.addEventListener('click', function() {
//             const tabId = this.getAttribute('data-tab');
            
//             // Remove active class from all buttons and panes
//             tabButtons.forEach(btn => btn.classList.remove('active'));
//             tabPanes.forEach(pane => pane.classList.remove('active'));
            
//             // Add active class to clicked button
//             this.classList.add('active');
            
//             // Load tab content
//             loadTabContent(tabId);
//         });
//     });
    
//     function loadTabContent(tabId) {
//         const tabContent = document.querySelector('.tab-content');
        
//         if (tabId === 'logistics') {
//             // First tab is already loaded in HTML
//             document.getElementById('logistics').classList.add('active');
//         } else if (tabData[tabId]) {
//             const data = tabData[tabId];
            
//             const tabPane = document.createElement('div');
//             tabPane.className = 'tab-pane active';
//             tabPane.id = tabId;
            
//             const featuresHTML = data.features.map(feature => `
//                 <div class="feature-item">
//                     <div class="feature-icon">
//                         <i class="${feature.icon}"></i>
//                     </div>
//                     <h4>${feature.title}</h4>
//                     <p>${feature.description}</p>
//                 </div>
//             `).join('');
            
//             tabPane.innerHTML = `
//                 <div class="tab-pane-content">
//                     <div class="tab-image">
//                         <img src="${data.image}" alt="${data.title}">
//                         <div class="image-badge">
//                             <span>សេវាកម្មពិសេស</span>
//                         </div>
//                     </div>
//                     <div class="tab-info">
//                         <h3>${data.title}</h3>
//                         <p>${data.description}</p>
//                         <div class="features-grid">
//                             ${featuresHTML}
//                         </div>
//                         <a href="#contact" class="cta-button">
//                             <span>ស្នើសុំការប្រឹក្សាយោបល់</span>
//                             <i class="fas fa-arrow-right"></i>
//                         </a>
//                     </div>
//                 </div>
//             `;
            
//             tabContent.innerHTML = '';
//             tabContent.appendChild(tabPane);
//         }
//     }
    
//     // Animate elements on scroll
//     function animateOnScroll() {
//         const elements = document.querySelectorAll('.category-card, .why-choose-card, .process-step, .pricing-card');
        
//         const observer = new IntersectionObserver((entries) => {
//             entries.forEach(entry => {
//                 if (entry.isIntersecting) {
//                     entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
//                     observer.unobserve(entry.target);
//                 }
//             });
//         }, {
//             threshold: 0.1,
//             rootMargin: '0px 0px -50px 0px'
//         });
        
//         elements.forEach(element => {
//             element.style.opacity = '0';
//             element.style.transform = 'translateY(20px)';
//             observer.observe(element);
//         });
//     }
    
//     // Initialize animations
//     animateOnScroll();
    
//     // Add scroll event listener for animations
//     window.addEventListener('scroll', animateOnScroll);
    
//     // Service category filtering (if needed)
//     const categoryCards = document.querySelectorAll('.category-card');
    
//     // Add hover effect to service cards
//     categoryCards.forEach(card => {
//         card.addEventListener('mouseenter', function() {
//             this.style.transform = 'translateY(-10px)';
//             this.style.boxShadow = '0 20px 50px rgba(0, 0, 0, 0.15)';
//         });
        
//         card.addEventListener('mouseleave', function() {
//             this.style.transform = 'translateY(0)';
//             this.style.boxShadow = 'none';
//         });
//     });
    
//     // Contact form submission (placeholder)
//     const contactForms = document.querySelectorAll('.cta-button, .pricing-button');
//     contactForms.forEach(form => {
//         form.addEventListener('click', function(e) {
//             if (this.getAttribute('href') === '#contact') {
//                 e.preventDefault();
//                 showContactModal();
//             }
//         });
//     });
    
//     function showContactModal() {
//         const modal = document.createElement('div');
//         modal.className = 'contact-modal';
//         modal.innerHTML = `
//             <div class="modal-content">
//                 <div class="modal-header">
//                     <h3><i class="fas fa-headset"></i> ទាក់ទងយើងខ្ញុំ</h3>
//                     <button class="close-modal">&times;</button>
//                 </div>
//                 <div class="modal-body">
//                     <p>សូមផ្តល់ព័ត៌មានទំនាក់ទំនងរបស់អ្នក ហើយយើងនឹងទាក់ទងអ្នកវិញក្នុងរយៈពេល ២៤ម៉ោង។</p>
//                     <div class="contact-methods">
//                         <a href="tel:+85512345678" class="contact-method">
//                             <i class="fas fa-phone-alt"></i>
//                             <span>ទូរស័ព្ទ: +855 12 345 678</span>
//                         </a>
//                         <a href="mailto:info@trucking.com" class="contact-method">
//                             <i class="fas fa-envelope"></i>
//                             <span>អ៊ីមែល: info@trucking.com</span>
//                         </a>
//                         <a href="https://wa.me/85512345678" target="_blank" class="contact-method">
//                             <i class="fab fa-whatsapp"></i>
//                             <span>WhatsApp: +855 12 345 678</span>
//                         </a>
//                     </div>
//                 </div>
//             </div>
//         `;
        
//         modal.style.cssText = `
//             position: fixed;
//             top: 0;
//             left: 0;
//             width: 100%;
//             height: 100%;
//             background: rgba(0,0,0,0.8);
//             display: flex;
//             align-items: center;
//             justify-content: center;
//             z-index: 10000;
//             animation: fadeIn 0.3s ease;
//             backdrop-filter: blur(5px);
//         `;
        
//         document.body.appendChild(modal);
        
//         // Close modal on X click
//         modal.querySelector('.close-modal').addEventListener('click', () => {
//             modal.style.animation = 'fadeOut 0.3s ease';
//             setTimeout(() => modal.remove(), 300);
//         });
        
//         // Close modal on outside click
//         modal.addEventListener('click', (e) => {
//             if (e.target === modal) {
//                 modal.style.animation = 'fadeOut 0.3s ease';
//                 setTimeout(() => modal.remove(), 300);
//             }
//         });
        
//         // Add fadeOut animation
//         const style = document.createElement('style');
//         style.textContent = `
//             @keyframes fadeOut {
//                 from { opacity: 1; }
//                 to { opacity: 0; }
//             }
            
//             .modal-content {
//                 background: white;
//                 border-radius: 20px;
//                 width: 90%;
//                 max-width: 500px;
//                 animation: slideUp 0.3s ease;
//             }
            
//             @keyframes slideUp {
//                 from { transform: translateY(30px); opacity: 0; }
//                 to { transform: translateY(0); opacity: 1; }
//             }
            
//             .modal-header {
//                 display: flex;
//                 justify-content: space-between;
//                 align-items: center;
//                 padding: 25px 30px;
//                 border-bottom: 1px solid #e2e8f0;
//             }
            
//             .modal-header h3 {
//                 color: #2c3e50;
//                 display: flex;
//                 align-items: center;
//                 gap: 10px;
//             }
            
//             .close-modal {
//                 background: none;
//                 border: none;
//                 font-size: 28px;
//                 color: #64748b;
//                 cursor: pointer;
//                 transition: color 0.3s ease;
//             }
            
//             .close-modal:hover {
//                 color: #ff7e00;
//             }
            
//             .modal-body {
//                 padding: 30px;
//             }
            
//             .modal-body p {
//                 color: #64748b;
//                 margin-bottom: 25px;
//                 line-height: 1.6;
//             }
            
//             .contact-methods {
//                 display: flex;
//                 flex-direction: column;
//                 gap: 15px;
//             }
            
//             .contact-method {
//                 display: flex;
//                 align-items: center;
//                 gap: 15px;
//                 padding: 18px 20px;
//                 background: #f8fafc;
//                 border-radius: 12px;
//                 color: #2c3e50;
//                 text-decoration: none;
//                 transition: all 0.3s ease;
//             }
            
//             .contact-method:hover {
//                 background: #ff7e00;
//                 color: white;
//                 transform: translateY(-3px);
//                 box-shadow: 0 5px 15px rgba(255, 126, 0, 0.3);
//             }
            
//             .contact-method i {
//                 font-size: 20px;
//                 width: 24px;
//             }
//         `;
//         document.head.appendChild(style);
//     }
    
//     // Add animation to stats counter
//     function animateStatsCounter() {
//         const stats = document.querySelectorAll('.stat-number');
        
//         stats.forEach(stat => {
//             const target = parseInt(stat.textContent);
//             const suffix = stat.textContent.includes('%') ? '%' : '';
//             let current = 0;
            
//             const increment = target / 50;
//             const timer = setInterval(() => {
//                 current += increment;
//                 if (current >= target) {
//                     stat.textContent = target + suffix;
//                     clearInterval(timer);
//                 } else {
//                     stat.textContent = Math.floor(current) + suffix;
//                 }
//             }, 30);
//         });
//     }
    
//     // Initialize stats counter when hero section is in view
//     const heroObserver = new IntersectionObserver((entries) => {
//         if (entries[0].isIntersecting) {
//             animateStatsCounter();
//             heroObserver.unobserve(entries[0].target);
//         }
//     }, { threshold: 0.5 });
    
//     const heroSection = document.querySelector('.services-hero');
//     if (heroSection) {
//         heroObserver.observe(heroSection);
//     }
    
//     // Add smooth scrolling for anchor links
//     document.querySelectorAll('a[href^="#"]').forEach(anchor => {
//         anchor.addEventListener('click', function(e) {
//             const href = this.getAttribute('href');
//             if (href !== '#') {
//                 e.preventDefault();
//                 const target = document.querySelector(href);
//                 if (target) {
//                     target.scrollIntoView({
//                         behavior: 'smooth',
//                         block: 'start'
//                     });
//                 }
//             }
//         });
//     });
// });