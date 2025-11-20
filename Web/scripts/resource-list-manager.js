/**
 * Resource List Manager
 * 
 * Handles filtering, sorting, and rendering of resource/equipment list.
 * Currently works with mock data, will be adapted for API calls when
 * database schema includes custom attributes.
 * 
 * Usage:
 *   ResourceListManager.init();
 *   ResourceListManager.configure({ itemsPerPage: 25 });
 */

const ResourceListManager = {
    config: {
        itemsPerPage: 20,
        loadDelay: 500,        // Simulated loading delay in ms
        debug: false
    },

    state: {
        resources: [],
        filteredResources: [],
        currentFilters: {
            search: '',
            department: '',
            method: ''
        },
        currentSort: {
            field: 'name',
            ascending: true
        }
    },

    /**
     * Configure manager options
     * @param {Object} options - Configuration options
     */
    configure: function(options) {
        this.config = { ...this.config, ...options };
        this.log('Configuration updated:', this.config);
    },

    /**
     * Log messages when debug is enabled
     */
    log: function(...args) {
        if (this.config.debug) {
            console.log('[ResourceListManager]', ...args);
        }
    },

    /**
     * Initialize the resource list manager
     */
    init: function() {
        this.log('Initializing ResourceListManager');
        
        // Load resources (mock data for now)
        this.loadResources();
        
        // Bind event listeners
        this.bindSearchFilter();
        this.bindDepartmentFilter();
        this.bindMethodFilter();
        this.bindResetButton();
        this.bindSortHeaders();
        
        // Simulate loading delay
        setTimeout(() => {
            this.populateFilterOptions();
            this.renderTable();
        }, this.config.loadDelay);
    },

    /**
     * Load resources data
     * TODO: Replace with API call when database is ready
     */
    loadResources: function() {
        // Currently uses mock data from mock-resources.js
        if (typeof mockResources !== 'undefined') {
            this.state.resources = mockResources;
            this.state.filteredResources = mockResources;
            this.log('Loaded', this.state.resources.length, 'resources');
        } else {
            console.error('Mock resources not found. Include mock-resources.js before this script.');
        }
    },

    /**
     * Populate filter dropdown options
     */
    populateFilterOptions: function() {
        // Populate department filter
        const departmentSelect = document.getElementById('departmentFilter');
        if (departmentSelect && typeof filterOptions !== 'undefined') {
            filterOptions.departments.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept;
                option.textContent = dept;
                departmentSelect.appendChild(option);
            });
        }

        // Populate method filter
        const methodSelect = document.getElementById('methodFilter');
        if (methodSelect && typeof filterOptions !== 'undefined') {
            filterOptions.methods.forEach(method => {
                const option = document.createElement('option');
                option.value = method;
                option.textContent = method;
                methodSelect.appendChild(option);
            });
        }
    },

    /**
     * Bind search input filter
     */
    bindSearchFilter: function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.state.currentFilters.search = e.target.value.toLowerCase();
                this.applyFiltersAndRender();
            });
        }
    },

    /**
     * Bind department filter
     */
    bindDepartmentFilter: function() {
        const departmentFilter = document.getElementById('departmentFilter');
        if (departmentFilter) {
            departmentFilter.addEventListener('change', (e) => {
                this.state.currentFilters.department = e.target.value;
                this.applyFiltersAndRender();
            });
        }
    },

    /**
     * Bind method filter
     */
    bindMethodFilter: function() {
        const methodFilter = document.getElementById('methodFilter');
        if (methodFilter) {
            methodFilter.addEventListener('change', (e) => {
                this.state.currentFilters.method = e.target.value;
                this.applyFiltersAndRender();
            });
        }
    },

    /**
     * Bind reset filters button
     */
    bindResetButton: function() {
        const resetButton = document.getElementById('resetFilters');
        if (resetButton) {
            resetButton.addEventListener('click', () => {
                this.resetFilters();
            });
        }
    },

    /**
     * Bind sort functionality to table headers
     */
    bindSortHeaders: function() {
        const headers = document.querySelectorAll('[data-sort]');
        headers.forEach(header => {
            header.addEventListener('click', () => {
                const field = header.getAttribute('data-sort');
                this.toggleSort(field);
            });
        });
    },

    /**
     * Reset all filters
     */
    resetFilters: function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('departmentFilter').value = '';
        document.getElementById('methodFilter').value = '';
        
        this.state.currentFilters = {
            search: '',
            department: '',
            method: ''
        };
        
        this.applyFiltersAndRender();
    },

    /**
     * Toggle sort direction or change sort field
     * @param {string} field - Field name to sort by
     */
    toggleSort: function(field) {
        if (this.state.currentSort.field === field) {
            this.state.currentSort.ascending = !this.state.currentSort.ascending;
        } else {
            this.state.currentSort.field = field;
            this.state.currentSort.ascending = true;
        }
        
        this.applyFiltersAndRender();
    },

    /**
     * Apply current filters to resources
     * @returns {Array} Filtered resources
     */
    applyFilters: function() {
        const filters = this.state.currentFilters;
        
        return this.state.resources.filter(resource => {
            // Search filter
            if (filters.search) {
                const searchableText = [
                    resource.name,
                    resource.location,
                    resource.department,
                    resource.method
                ].join(' ').toLowerCase();
                
                if (!searchableText.includes(filters.search)) {
                    return false;
                }
            }

            // Department filter
            if (filters.department && resource.department !== filters.department) {
                return false;
            }

            // Method filter
            if (filters.method && resource.method !== filters.method) {
                return false;
            }

            return true;
        });
    },

    /**
     * Sort resources by current sort settings
     * @param {Array} resources - Resources to sort
     * @returns {Array} Sorted resources
     */
    applySorting: function(resources) {
        const sortField = this.state.currentSort.field;
        const ascending = this.state.currentSort.ascending;
        
        return resources.sort((a, b) => {
            let aVal = a[sortField] || '';
            let bVal = b[sortField] || '';
            
            if (aVal < bVal) return ascending ? -1 : 1;
            if (aVal > bVal) return ascending ? 1 : -1;
            return 0;
        });
    },

    /**
     * Apply filters, sort, and render table
     */
    applyFiltersAndRender: function() {
        const filtered = this.applyFilters();
        const sorted = this.applySorting(filtered);
        
        this.state.filteredResources = sorted;
        this.renderTable();
    },

    /**
     * Render the resource table
     */
    renderTable: function() {
        const resources = this.state.filteredResources;
        
        const tableBody = document.getElementById('resourceTableBody');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const tableContainer = document.getElementById('resourceTableContainer');
        const noResults = document.getElementById('noResults');
        const recordCount = document.getElementById('recordCount');

        // Hide loading indicator
        if (loadingIndicator) {
            loadingIndicator.style.display = 'none';
        }

        // Update record count
        if (recordCount) {
            recordCount.textContent = resources.length;
        }

        // Show appropriate view
        if (resources.length === 0) {
            if (tableContainer) tableContainer.style.display = 'none';
            if (noResults) noResults.style.display = 'block';
            return;
        }

        if (tableContainer) tableContainer.style.display = 'block';
        if (noResults) noResults.style.display = 'none';

        // Clear and populate table
        if (tableBody) {
            tableBody.innerHTML = '';

            resources.forEach(resource => {
                const row = this.createTableRow(resource);
                tableBody.appendChild(row);
            });
        }

        this.log('Rendered', resources.length, 'resources');
    },

    /**
     * Create a table row for a resource
     * @param {Object} resource - Resource data
     * @returns {HTMLElement} Table row element
     */
    createTableRow: function(resource) {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td><strong>${this.escapeHtml(resource.name)}</strong></td>
            <td>${resource.location ? this.escapeHtml(resource.location) : '<em class="text-muted">-</em>'}</td>
            <td>${this.escapeHtml(resource.department)}</td>
            <td><span class="badge bg-secondary">${this.escapeHtml(resource.method)}</span></td>
        `;
        
        // Click handler to view details
        row.addEventListener('click', () => {
            window.location.href = 'resource_detail.php?id=' + resource.id;
        });
        
        return row;
    },

    /**
     * Escape HTML to prevent XSS
     * @param {string} text - Text to escape
     * @returns {string} Escaped HTML
     */
    escapeHtml: function(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => ResourceListManager.init());
} else {
    ResourceListManager.init();
}

// Export for global use
window.ResourceListManager = ResourceListManager;