// public/js/trucks.js
document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    const modal = document.getElementById('truckModal');
    const closeModal = document.querySelector('.close-modal');
    const viewButtons = document.querySelectorAll('.view-details');

    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.truck-card');
            const truckId = card.querySelector('.info-row:nth-child(1) .info-value').textContent;
            const truckName = card.querySelector('.info-row:nth-child(2) .info-value').textContent;
            const truckSize = card.querySelector('.info-row:nth-child(3) .info-value').textContent;
            const truckColor = card.querySelector('.info-row:nth-child(4) .info-value').textContent;
            const truckImage = card.querySelector('img') ? card.querySelector('img').src : '';
            const truckStatus = card.querySelector('.truck-status').textContent;
            
            const modalContent = `
                <div class="modal-truck-details">
                    ${truckImage ? `
                    <div class="modal-image">
                        <img src="${truckImage}" alt="${truckName}">
                    </div>
                    ` : ''}
                    <div class="modal-info">
                        <h3>ព័ត៌មានលម្អិតអំពីរថយន្ត</h3>
                        <div class="modal-info-grid">
                            <div class="modal-info-row">
                                <span class="modal-label">Truck ID:</span>
                                <span class="modal-value">${truckId}</span>
                            </div>
                            <div class="modal-info-row">
                                <span class="modal-label">Truck Name:</span>
                                <span class="modal-value">${truckName}</span>
                            </div>
                            <div class="modal-info-row">
                                <span class="modal-label">Truck Size:</span>
                                <span class="modal-value">${truckSize}</span>
                            </div>
                            <div class="modal-info-row">
                                <span class="modal-label">Truck Color:</span>
                                <span class="modal-value">${truckColor}</span>
                            </div>
                            <div class="modal-info-row">
                                <span class="modal-label">ស្ថានភាព:</span>
                                <span class="modal-value">${truckStatus}</span>
                            </div>
                        </div>
                        <button class="btn btn-primary book-now">
                            <i class="fas fa-calendar-check"></i> កក់រថយន្តនេះ
                        </button>
                    </div>
                </div>
            `;
            
            document.getElementById('modalContent').innerHTML = modalContent;
            modal.style.display = 'block';
        });
    });

    closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
});