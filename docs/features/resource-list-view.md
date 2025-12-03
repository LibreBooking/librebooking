# Resource List View

## Overview

This document describes the implementation of a resource list view page that replicates the main table functionality from the Device Hub (Appsmith). This is a critical component of the Device Hub migration to LibreBooking, providing users with a searchable, filterable table of all available equipment.

**Status:** ✅ Functional (Mock Data)  
**Version:** 1.0  
**Author:** Moreno  
**Date:** November 2025  
**Branch:** `feat/resource-list-view`

---

## Objectives

1. Replicate Device Hub's main equipment table interface
2. Implement client-side filtering and sorting functionality
3. Establish modular architecture for future database integration
4. Provide seamless navigation to resource detail pages
5. Prepare foundation for server-side pagination and filtering

---

## Features Implemented

### 1. Equipment Table Display

**Location:** `/Web/resource_list.php`  
**Template:** `/tpl/resource_list.tpl`  
**Controller:** `/Pages/ResourceListPage.php`

#### Current Features:

- **Responsive Table Layout:**
  - Name, Location, Department, Method columns
  - Click-to-detail functionality (links to resource_detail.php)
  - Hover effects for better UX
  - Mobile-responsive design

- **Data Display:**
  - 20+ equipment entries from real Device Hub CSV
  - Handles empty location fields gracefully
  - Badge styling for method categories
  - Empty state when no results found

### 2. Filtering System

#### Search Filter:
- Real-time text search across all fields
- Case-insensitive matching
- Searches: Name, Location, Department, Method

#### Department Filter:
- Dropdown with 7 department options
- Extracted from real Device Hub data:
  - B (all divisions)
  - C2 (Bioactive and Responsive Polymers)
  - C4 (Advanced Macromolecular Structure Analysis)
  - M1 (Materials Engineering)
  - M2 (Processing Technology)
  - P1 (Functional Colloidal Materials)
  - P2 (Nanostructured Materials)

#### Method Filter:
- Dropdown with 11+ method categories
- Examples:
  - Additive Manufacturing/3D Printer
  - Chromatography/Size Exclusion Chromatography
  - Spectroscopy/UV-VIS
  - Simulation Software/FEM

#### Reset Functionality:
- One-click reset of all filters
- Restores full dataset view

### 3. Sorting System

- **Sortable Columns:** Name, Location, Department, Method
- **Toggle Behavior:** Click to sort ascending, click again for descending
- **Visual Feedback:** Sort icons in column headers
- **Maintained Across Filters:** Sort persists when filtering

### 4. Record Counter

- Live update of filtered results count
- Format: "X Records"

---

## Architecture

### Modular JavaScript Design

**Module:** `/Web/scripts/resource-list-manager.js`

#### Key Components:

```javascript
const ResourceListManager = {
    config: {
        itemsPerPage: 20,
        loadDelay: 500,
        debug: false
    },

    state: {
        resources: [],
        filteredResources: [],
        currentFilters: { search, department, method },
        currentSort: { field, ascending }
    },

    // Public API
    init()
    configure(options)
    loadResources()
    applyFiltersAndRender()
}
```

**Design Principles:**  
- Separation of Concerns  
- State Management  
- Reusability  
- Maintainability  
- Testability  

---

## Data Flow

```
Mock Data (mock-resources.js)
    ↓
ResourceListManager.loadResources()
    ↓
User applies filters
    ↓
ResourceListManager.applyFilters()
    ↓
ResourceListManager.applySorting()
    ↓
ResourceListManager.renderTable()
    ↓
DOM updated with filtered/sorted results
```

---

## File Structure

```
librebooking/app/
├── Pages/
│   ├── ResourceListPage.php
│   └── ResourceDetailPage.php
├── Presenters/
│   └── ResourceListPresenter.php
├── Web/
│   ├── resource_list.php
│   └── scripts/
│       ├── mock-resources.js
│       └── resource-list-manager.js
└── tpl/
    └── resource_list.tpl
```

---

## Mock Data Structure

Sample Entry:

```javascript
{
    id: 1,
    name: "(Abaqus) Abaqus FEA Software",
    location: "",
    department: "M1 (Materials Engineering)",
    method: "Simulation Software/FEM"
}
```

---

## Dependencies

- LibreBooking Core: 2.8.x+
- Bootstrap 5.1.3
- Font Awesome 6.4.0
- jQuery (minimal use)

---

## User Flow

1. User navigates to `/Web/resource_list.php`
2. Page loads with all equipment visible
3. Users can search, filter, sort, and click entries
4. Reset button clears filters

---

## Integration Points

### Current:
- Links to resource_detail.php  
- Uses LibreBooking permission system  
- Presenter pattern

### Future:
- API endpoint `/api/resources`
- Custom attributes table
- Server-side pagination

---

## Testing Performed

**Manual Testing:**  
All filters, sorting, navigation, record counter, and responsiveness tested.

**Browser Compatibility:**  
✔ Chrome  
✔ Firefox  
⚠ Safari  
⚠ Mobile browsers

---

## Known Limitations

- Client-side only  
- Not scalable beyond ~100 items  
- Missing advanced filters  
- Pagination not yet implemented  
- Export disabled  

---

## Future Enhancements

**Short-term:** Pagination, breadcrumbs, animations  
**Medium-term:** Multiselect filters, API integration  
**Long-term:** Full feature parity, analytics dashboard

---

## Code Examples

```javascript
ResourceListManager.configure({
    itemsPerPage: 50,
    debug: true
});
```

---

## Changelog

| Date       | Version | Changes                                      | Author  |
|-----------|---------|----------------------------------------------|---------|
| 2025-11-27| 1.0     | Initial implementation with mock data        | Moreno  |

---

## Contact

Developer: **Moreno**  
Repository: LibreBooking fork  
Branch: `feat/resource-list-view`