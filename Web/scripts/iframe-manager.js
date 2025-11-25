const IframeManager = {
    config: {
        defaultPadding: 60,
        defaultHeight: 400,
        autoSelector: 'iframe[data-auto-resize="true"]',
        resizeDebounce: 100
    },

    // Allow override configuration: 
    configure: function(options) {
        this.config = { ...this.config, ...options };
        console.log('Configuration updated:', this.config);
    },
    
    trackedIframes: new Set(),
    resizeTimer: null,

    init: function() {
        console.log('IframeManager initialized');
        this.bindAutoResize();
        this.bindWindowResize();
    },

    bindAutoResize: function() {
        const iframes = document.querySelectorAll(this.config.autoSelector);
        iframes.forEach(iframe => {
            if (!this.trackedIframes.has(iframe.id)) {
                console.log('Binding auto-resize to:', iframe.id);
                this.trackedIframes.add(iframe.id);
                
                iframe.addEventListener('load', () => {
                    this.adjustHeight(iframe.id);
                });
            }
        });
    },

    bindWindowResize: function() {
        window.addEventListener('resize', () => {
            clearTimeout(this.resizeTimer);
            this.resizeTimer = setTimeout(() => {
                this.adjustAllTracked();
                // Segundo ajuste después de que CSS se estabilice
                setTimeout(() => this.adjustAllTracked(), 100);
            }, this.config.resizeDebounce);
        });
    },

    adjustAllTracked: function() {
        this.trackedIframes.forEach(iframeId => {
            // Pequeño delay para que el contenido se reorganice primero
            setTimeout(() => {
                this.adjustHeight(iframeId);
            }, 50); // 50ms para que CSS se aplique
        });
    },
    
    adjustHeight: function(iframeId, padding) {
        const iframe = document.getElementById(iframeId);
        if (!iframe) {
            console.error('Iframe not found:', iframeId);
            return false;
        }
        
        const extraPadding = padding || this.config.defaultPadding;
        
        try {
            // 🔥 RESETEAR altura primero para forzar reflow
            iframe.style.height = 'auto';
            
            // Pequeño delay para que el navegador recalcule
            setTimeout(() => {
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                const contentHeight = Math.max(
                    iframeDoc.body.scrollHeight,
                    iframeDoc.body.offsetHeight,
                    iframeDoc.documentElement.scrollHeight,
                    iframeDoc.documentElement.offsetHeight
                );
                
                iframe.style.height = (contentHeight + extraPadding) + 'px';
                console.log('Height adjusted to:', contentHeight + extraPadding);
            }, 10);
            
            return true;
            
        } catch (e) {
            console.warn('Cannot access iframe content, using default height');
            iframe.style.height = this.config.defaultHeight + 'px';
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