{include file='globalheader.tpl'}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container-fluid mt-4">
    <div class="row">
        {* Sidebar - Filters *}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-filter"></i> {translate key='Filters'}
                    </h5>
                </div>
                <div class="card-body">
                    {* Search *}
                    <div class="mb-3">
                        <label for="searchInput" class="form-label fw-bold">
                            {translate key='Search'}
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="searchInput" 
                            placeholder="Search equipment...">
                    </div>

                    {* Department Filter *}
                    <div class="mb-3">
                        <label for="departmentFilter" class="form-label fw-bold">
                            Department
                        </label>
                        <select class="form-select" id="departmentFilter">
                            <option value="">All</option>
                        </select>
                    </div>

                    {* Method Filter *}
                    <div class="mb-3">
                        <label for="methodFilter" class="form-label fw-bold">
                            Method
                        </label>
                        <select class="form-select" id="methodFilter">
                            <option value="">All</option>
                        </select>
                    </div>

                    {* Reset Button *}
                    <button class="btn btn-secondary w-100" id="resetFilters">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>

            {* Info Box *}
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <p class="mb-0">
                        <i class="fas fa-info-circle text-info"></i>
                        <strong id="recordCount">388</strong> Records
                    </p>
                </div>
            </div>
        </div>

        {* Main Content - Table *}
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-microscope"></i> Equipment List
                    </h4>
                    <a href="#" class="btn btn-outline-secondary btn-sm" id="downloadBtn">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>

                <div class="card-body p-0">
                    {* Loading Indicator *}
                    <div id="loadingIndicator" class="text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading equipment...</p>
                    </div>

                    {* Table *}
                    <div class="table-responsive" id="resourceTableContainer" style="display: none;">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th style="width: 40%;">
                                        Name
                                        <i class="fas fa-sort ms-1 cursor-pointer" data-sort="name"></i>
                                    </th>
                                    <th style="width: 15%;">
                                        Location
                                        <i class="fas fa-sort ms-1 cursor-pointer" data-sort="location"></i>
                                    </th>
                                    <th style="width: 25%;">
                                        Department
                                        <i class="fas fa-sort ms-1 cursor-pointer" data-sort="department"></i>
                                    </th>
                                    <th style="width: 20%;">
                                        Method
                                        <i class="fas fa-sort ms-1 cursor-pointer" data-sort="method"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="resourceTableBody">
                                {* Populated by JavaScript *}
                            </tbody>
                        </table>
                    </div>

                    {* No Results *}
                    <div id="noResults" class="text-center p-5" style="display: none;">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5>No equipment found</h5>
                        <p class="text-muted">Try adjusting your filters</p>
                    </div>
                </div>

                {* Pagination *}
                <div class="card-footer">
                    <nav>
                        <ul class="pagination justify-content-center mb-0" id="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">25</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                    <p class="text-center text-muted mt-2 mb-0">
                        Page <span id="currentPage">1</span> of <span id="totalPages">25</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{* Custom CSS *}
<style>
    .cursor-pointer {
        cursor: pointer;
    }
    
    .cursor-pointer:hover {
        color: #007bff;
    }
    
    .table tbody tr {
        cursor: pointer;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
    }
</style>

{* JavaScript *}
<script src="/Web/scripts/mock-resources.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentFilters = {
        search: '',
        department: '',
        method: ''
    };
    
    let currentSort = {
        field: 'name',
        ascending: true
    };

    // Initialize
    loadFilterOptions();
    setTimeout(renderTable, 500); // Simulate loading delay

    // Search input
    document.getElementById('searchInput').addEventListener('input', function(e) {
        currentFilters.search = e.target.value.toLowerCase();
        renderTable();
    });

    // Department filter
    document.getElementById('departmentFilter').addEventListener('change', function(e) {
        currentFilters.department = e.target.value;
        renderTable();
    });

    // Method filter
    document.getElementById('methodFilter').addEventListener('change', function(e) {
        currentFilters.method = e.target.value;
        renderTable();
    });

    // Reset filters
    document.getElementById('resetFilters').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('departmentFilter').value = '';
        document.getElementById('methodFilter').value = '';
        currentFilters = { search: '', department: '', method: '' };
        renderTable();
    });

    // Sorting
    document.querySelectorAll('[data-sort]').forEach(function(th) {
        th.addEventListener('click', function() {
            const field = this.getAttribute('data-sort');
            if (currentSort.field === field) {
                currentSort.ascending = !currentSort.ascending;
            } else {
                currentSort.field = field;
                currentSort.ascending = true;
            }
            renderTable();
        });
    });

    function loadFilterOptions() {
        // Load department options
        const departmentSelect = document.getElementById('departmentFilter');
        filterOptions.departments.forEach(function(dept) {
            const option = document.createElement('option');
            option.value = dept;
            option.textContent = dept;
            departmentSelect.appendChild(option);
        });

        // Load method options
        const methodSelect = document.getElementById('methodFilter');
        filterOptions.methods.forEach(function(method) {
            const option = document.createElement('option');
            option.value = method;
            option.textContent = method;
            methodSelect.appendChild(option);
        });
    }

    function filterResources() {
        return mockResources.filter(function(resource) {
            // Search filter
            if (currentFilters.search) {
                const searchMatch = 
                    resource.name.toLowerCase().includes(currentFilters.search) ||
                    resource.location.toLowerCase().includes(currentFilters.search) ||
                    resource.department.toLowerCase().includes(currentFilters.search) ||
                    resource.method.toLowerCase().includes(currentFilters.search);
                
                if (!searchMatch) return false;
            }

            // Department filter
            if (currentFilters.department && resource.department !== currentFilters.department) {
                return false;
            }

            // Method filter
            if (currentFilters.method && resource.method !== currentFilters.method) {
                return false;
            }

            return true;
        });
    }

    function sortResources(resources) {
        return resources.sort(function(a, b) {
            let aVal = a[currentSort.field] || '';
            let bVal = b[currentSort.field] || '';
            
            if (aVal < bVal) return currentSort.ascending ? -1 : 1;
            if (aVal > bVal) return currentSort.ascending ? 1 : -1;
            return 0;
        });
    }

    function renderTable() {
        const filtered = filterResources();
        const sorted = sortResources(filtered);
        
        const tableBody = document.getElementById('resourceTableBody');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const tableContainer = document.getElementById('resourceTableContainer');
        const noResults = document.getElementById('noResults');
        const recordCount = document.getElementById('recordCount');

        // Hide loading
        loadingIndicator.style.display = 'none';

        // Update record count
        recordCount.textContent = filtered.length;

        if (sorted.length === 0) {
            tableContainer.style.display = 'none';
            noResults.style.display = 'block';
            return;
        }

        tableContainer.style.display = 'block';
        noResults.style.display = 'none';

        // Clear table
        tableBody.innerHTML = '';

        // Render rows (showing all 20 for now, pagination to be implemented later)
        sorted.forEach(function(resource) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>${escapeHtml(resource.name)}</strong></td>
                <td>${escapeHtml(resource.location) || '<em class="text-muted">-</em>'}</td>
                <td>${escapeHtml(resource.department)}</td>
                <td><span class="badge bg-secondary">${escapeHtml(resource.method)}</span></td>
            `;
            
            // Click to view details
            row.addEventListener('click', function() {
                window.location.href = 'resource_detail.php?id=' + resource.id;
            });
            
            tableBody.appendChild(row);
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Download button (mockup)
    document.getElementById('downloadBtn').addEventListener('click', function(e) {
        e.preventDefault();
        alert('Download functionality will be implemented with real database integration.');
    });
});
</script>

{include file='globalfooter.tpl'}