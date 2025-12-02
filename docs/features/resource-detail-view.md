# Resource Detail View Enhancement

## Overview

This document describes the implementation of an enhanced resource detail view page in LibreBooking, which serves as a foundational component for migrating the Device Hub functionality from Appsmith to the LibreBooking platform.

**Status:** ✅ Completed  
**Version:** 1.0  
**Author:** Moreno  
**Date:** January 2025  
**Branch:** `feat/schedule-week-navigation`

---

## Objectives

1. Create a comprehensive resource detail page displaying all relevant equipment information
2. Integrate a schedule availability widget with week navigation
3. Implement modular, reusable components for iframe management
4. Establish groundwork for complete Device Hub migration

---

## Implementation Details

### 1. Resource Detail Page (`resource_detail.php`)

**Location:** `/Web/resource_detail.php`  
**Template:** `/tpl/resource_detail.tpl`  
**Controller:** `/Pages/ResourceDetailPage.php`

#### Features Implemented:

- **Comprehensive Information Display:**
  - Resource ID, name, and description
  - Location and contact information
  - Capacity (max participants)
  - Availability status
  - Reservation rules (min/max duration, approval requirements)
  - Notes and additional information

- **Visual Design:**
  - Bootstrap 5-based card layout
  - Font Awesome icons for visual clarity
  - Responsive design for mobile and desktop
  - Color-coded status badges

- **Navigation:**
  - Back to list button
  - Direct link to create reservation
  - Breadcrumb support (prepared for future implementation)

#### Technical Approach:

The page follows LibreBooking's MVC pattern:
- **Model:** Retrieves resource data from database via `ResourceRepository`
- **View:** Smarty template with translation support
- **Controller:** Handles request routing and data preparation

Error handling includes validation for non-existent resources and permission checks.

---

### 2. Schedule Widget Integration

**Location:** `/Web/schedule-minimal.php`  
**Template:** `/tpl/Schedule/schedule-minimal.tpl`  
**CSS:** `/Web/css/schedule-widgets.css`

#### Features Implemented:

- **Week-based Availability View:**
  - 7-day grid display (Monday-Sunday)
  - Day name, date, and availability status
  - Visual indicators (green=available, red=booked)
  
- **Navigation Controls:**
  - Previous/Next week arrows
  - "Today" button to return to current week
  - URL parameter-based week offset (`?rid=X&week=N`)

- **Embedded Display:**
  - Iframe integration in resource detail page
  - Auto-adjusting height based on content
  - Minimal design optimized for embedding

#### Widget Behavior:

- **Week Offset Logic:**
  - `week=0` → Current week
  - `week=1` → Next week
  - `week=-1` → Previous week
  
- **Data Structure:**
  ```php
  $WeekAvailability = [
      ['dayName' => 'Monday', 'date' => '2025-01-27', 'isAvailable' => true, 'status' => 'available'],
      // ...
  ];

  # IframeManager Module Documentation

### 3. IframeManager Module

**Location:** `/Web/scripts/iframe-manager.js`

## Purpose

A reusable JavaScript module for dynamically adjusting iframe heights based on their content, eliminating excessive whitespace and scrollbars.

## Features

- Auto-detection: Automatically finds and manages iframes with `data-auto-resize="true"`
- Dynamic Height Adjustment: Calculates and applies optimal height based on content
- Responsive Behavior: Recalculates height on window resize events
- Configurable: Supports custom padding and debounce settings
- Cross-origin Safe: Handles CORS restrictions gracefully

## API

```javascript
// Configuration
IframeManager.configure({
    defaultPadding: 30,
    defaultHeight: 400,
    resizeDebounce: 100,
    debug: false
});

// Manual tracking
IframeManager.track('iframeId');

// Manual height adjustment
IframeManager.adjustHeight('iframeId', padding, forceReset);
```

### Usage Example

```html
<iframe 
    id="scheduleFrame" 
    src="schedule-minimal.php?rid=1"
    data-auto-resize="true"
    width="100%" 
    style="border: 1px solid #dee2e6;">
</iframe>

<script src="/Web/scripts/iframe-manager.js"></script>
<script>
    IframeManager.configure({
        defaultPadding: 20,
        debug: false
    });
</script>
```

## Architecture

- Separation of Concerns
- Reusability
- Maintainability
- Testing-ready

## Known Limitations

- Multiple height adjustments may occur during initial page load
- CORS restrictions
- CDN rendering delays

## File Structure

```scss
librebooking/app/
├── Pages/
│   ├── ResourceDetailPage.php
│   └── ScheduleMinimalPage.php
├── Web/
│   ├── resource_detail.php
│   ├── schedule-minimal.php
│   ├── css/
│   │   └── schedule-widgets.css
│   └── scripts/
│       └── iframe-manager.js
└── tpl/
    ├── resource_detail.tpl
    └── Schedule/
        └── schedule-minimal.tpl
```

## Dependencies

- LibreBooking Core 2.8.x+
- Bootstrap 5.1.3
- Font Awesome 6.4.0
- jQuery
- Smarty 3.x

## Testing Performed

### Manual Tests

- Resource detail page loads
- Error handling
- Schedule widget works
- Navigation works
- Iframe auto-resizing
- Responsive behavior
- Mobile compatibility

### Browser Compatibility

- Chrome
- Firefox
- Safari (not tested)
- Mobile browsers (basic)

## Migration Progress

### Completed (20%)

- Resource detail view
- Schedule widget
- Responsive design foundation

### Pending (80%)

- List view
- Filters
- Search
- Pagination
- Cluster tab
- DB schema changes

## Known Issues

### Height adjustments

Minor jumping effect due to progressive rendering.

### Missing DB fields

Blocking custom filtering.

## Future Enhancements

Short, medium, long-term plans.

## Changelog

| Date       | Version | Changes                                        | Author |
|------------|---------|------------------------------------------------|--------|
| 2025-01-26 | 1.0     | Initial resource detail + widget               | Moreno |
| 2025-01-26 | 1.0     | IframeManager module integration               | Moreno |

## Contact

Developer: Moreno  
Branch: `feat/schedule-week-navigation`
