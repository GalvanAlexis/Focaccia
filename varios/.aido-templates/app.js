/* AIDO Base JS - Bootstrap + Alpine + Utils */

// Bootstrap core
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// Alpine.js (opcional, pero muy útil)
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Axios para peticiones AJAX
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// CSRF Token para Laravel
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.warn('CSRF token not found');
}

// AIDO Utilities
const AIDO = {
    // Sistema de notificaciones
    notify(message, type = 'info', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `aido-notification aido-notification-${type}`;
        notification.innerHTML = `
            <i class="bi bi-${this.getIcon(type)}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => notification.classList.add('show'), 10);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, duration);
    },
    
    getIcon(type) {
        const icons = {
            success: 'check-circle-fill',
            error: 'exclamation-circle-fill',
            warning: 'exclamation-triangle-fill',
            info: 'info-circle-fill'
        };
        return icons[type] || icons.info;
    },
    
    // Loading overlay
    showLoading() {
        if (!document.getElementById('aido-loading')) {
            const loading = document.createElement('div');
            loading.id = 'aido-loading';
            loading.className = 'aido-loading';
            loading.innerHTML = '<div class="aido-spinner"></div>';
            document.body.appendChild(loading);
        }
        document.getElementById('aido-loading').classList.add('active');
    },
    
    hideLoading() {
        const loading = document.getElementById('aido-loading');
        if (loading) {
            loading.classList.remove('active');
        }
    },
    
    // Formato de moneda ARS
    formatMoney(amount) {
        return new Intl.NumberFormat('es-AR', {
            style: 'currency',
            currency: 'ARS',
            minimumFractionDigits: 0
        }).format(amount);
    },
    
    // Debounce utility
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// Exponer AIDO globalmente
window.AIDO = AIDO;

// Event listener para debugging
if (import.meta.env.DEV) {
    console.log('🚀 AIDO App initialized in DEV mode');
}

// Agregar estilos de notificaciones dinámicamente
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    .aido-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 10001;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transform: translateX(400px);
        opacity: 0;
        transition: all 0.3s ease;
        font-weight: 500;
        max-width: 400px;
    }
    
    .aido-notification.show {
        transform: translateX(0);
        opacity: 1;
    }
    
    .aido-notification-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }
    
    .aido-notification-error {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }
    
    .aido-notification-warning {
        background: #fff3cd;
        color: #856404;
        border-left: 4px solid #ffc107;
    }
    
    .aido-notification-info {
        background: #d1ecf1;
        color: #0c5460;
        border-left: 4px solid #17a2b8;
    }
    
    .aido-notification i {
        font-size: 1.3rem;
    }
    
    .aido-loading {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }
    
    .aido-loading.active {
        display: flex;
    }
    
    .aido-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(255, 255, 255, 0.2);
        border-top: 5px solid #FF0000;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(notificationStyles);
