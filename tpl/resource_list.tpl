<script>
// Compatibility shim for globalfooter.tpl init() call
// ResourceListManager already handles initialization via auto-init
    function init() {
        // Empty - ResourceListManager.init() already called on DOMContentLoaded
    }
</script>

{include file='globalfooter.tpl'}
{include file='globalheader.tpl'}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container-fluid mt-4">
    <div class="row">
        {* Sidebar - Filters *}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-filter"></i> Filters
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="searchInput" class="form-label fw-bold">Search</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search equipment...">
                    </div>

                    <div class="mb-3">
                        <label for="departmentFilter" class="form-label fw-bold">Department</label>
                        <select class="form-select" id="departmentFilter">
                            <option value="">All</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="methodFilter" class="form-label fw-bold">Method</label>
                        <select class="form-select" id="methodFilter">
                            <option value="">All</option>
                        </select>
                    </div>

                    <button class="btn btn-secondary w-100" id="resetFilters">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <p class="mb-0">
                        <i class="fas fa-info-circle text-info"></i>
                        <strong id="recordCount">0</strong> Records
                    </p>
                </div>
            </div>
        </div>

        {* Main Content - Table *}
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-flask"></i> Equipment List
                    </h4>
                </div>

                <div class="card-body p-0">
                    <div id="loadingIndicator" class="text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading equipment...</p>
                    </div>

                    <div class="table-responsive" id="resourceTableContainer" style="display: none;">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th data-sort="name" style="cursor: pointer;">
                                        Name <i class="fas fa-sort ms-1"></i>
                                    </th>
                                    <th data-sort="location" style="cursor: pointer;">
                                        Location <i class="fas fa-sort ms-1"></i>
                                    </th>
                                    <th data-sort="department" style="cursor: pointer;">
                                        Department <i class="fas fa-sort ms-1"></i>
                                    </th>
                                    <th data-sort="method" style="cursor: pointer;">
                                        Method <i class="fas fa-sort ms-1"></i>
                                    </th>
                                    <th style="width: 15%;">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="resourceTableBody">
                                {* Populated by ResourceListManager *}
                            </tbody>
                        </table>
                    </div>

                    <div id="noResults" class="text-center p-5" style="display: none;">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5>No equipment found</h5>
                        <p class="text-muted">Try adjusting your filters</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{* CSS *}
<style>
.table tbody tr {
    cursor: pointer;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

[data-sort]:hover i {
    color: #007bff;
}
</style>

{* JavaScript Modules *}
<script src="/Web/scripts/mock-resources.js"></script>
<script src="/Web/scripts/resource-list-manager.js"></script>

{* Optional: Page-specific configuration *}
<script>
// Configure if needed
// ResourceListManager.configure({ debug: true });
</script>

{include file='globalfooter.tpl'}