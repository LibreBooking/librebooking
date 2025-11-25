const IframeManager = {
    config: {
        defaultPadding: 30,
        defaultHeight: 400,
        autoSelector: 'iframe[data-auto-resize="true"]',
        resizeDebounce: 100,
        debug: false
    },

    log: function(...args) {
        if (this.config.debug) {
            console.log('[IframeManager]', ...args);
        }
    },

    // Allow override configuration: 
    configure: function(options) {
        this.config = { ...this.config, ...options };
        console.log('Configuration updated:', this.config);
    },
    
    trackedIframes: new Set(),
    resizeTimer: null,

    init: function() {
        this.bindAutoResize();
        this.bindWindowResize();
    },

    bindAutoResize: function() {
        const iframes = document.querySelectorAll(this.config.autoSelector);
        iframes.forEach(iframe => {
            if (!this.trackedIframes.has(iframe.id)) {
                this.trackedIframes.add(iframe.id);
                
                // UNA SOLA VEZ después de load
                iframe.addEventListener('load', () => {
                    // Espera a que TODO termine de renderizar
                    setTimeout(() => {
                        this.adjustHeight(iframe.id);
                    }, 1000); // ← 1 segundo, no más ajustes
                });
                
                // Si ya está cargado
                if (iframe.contentDocument && iframe.contentDocument.readyState === 'complete') {
                    setTimeout(() => {
                        this.adjustHeight(iframe.id);
                    }, 600);
                }
            }
        });
    },

    bindWindowResize: function() {
        window.addEventListener('resize', () => {
            clearTimeout(this.resizeTimer);
            this.resizeTimer = setTimeout(() => {
                this.adjustAllTracked();
            }, this.config.resizeDebounce);
        });
    },

    adjustAllTracked: function() {
        this.trackedIframes.forEach(iframeId => {
            this.adjustHeight(iframeId, null, true); // true = forceReset
        });
    },
    
    adjustHeight: function(iframeId, padding, forceReset = false) {
        const iframe = document.getElementById(iframeId);
        if (!iframe) {
            console.error('Iframe not found:', iframeId);
            return false;
        }
        
        const extraPadding = padding || this.config.defaultPadding;
        
        try {
            // Reset solo en resize, no en carga inicial
            if (forceReset) {
                iframe.style.height = '100px';
                iframe.offsetHeight;
            }
            
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            const contentHeight = Math.max(
                iframeDoc.body.scrollHeight,
                iframeDoc.body.offsetHeight,
                iframeDoc.documentElement.scrollHeight,
                iframeDoc.documentElement.offsetHeight
            );
            
            iframe.style.height = (contentHeight + extraPadding) + 'px';
            this.log('Height adjusted to:', contentHeight + extraPadding);
            
            return true;
            
        } catch (e) {
            console.warn('Cannot access iframe content, using default height');
            iframe.style.height = this.config.defaultHeight + 'px';
            iframe.dataset.adjusting = 'false';
            return false;
        }
    },

    // Public method to add iframes dinamically 
    track: function(iframeId) {
        this.trackedIframes.add(iframeId);
        this.adjustHeight(iframeId);
    }

};

// Inicialize when dom ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => IframeManager.init());
} else {
    IframeManager.init();
}

window.IframeManager = IframeManager;