
# Database Requirements for Device Hub Migration

## Overview

This document outlines the database schema extensions required to fully migrate the Device Hub functionality from Appsmith to LibreBooking. The current LibreBooking `resources` table lacks several custom attributes that are essential for equipment management at IPF Dresden.

**Status:** 📋 Planning  
**Priority:** High  
**Estimated Effort:** 2–3 days (schema design + migration scripts)  
**Blocking:** Server-side filtering, complete Device Hub replication

---

## Current LibreBooking Schema

### `resources` Table (Existing Fields)

```sql
resource_id         INT PRIMARY KEY AUTO_INCREMENT
name                VARCHAR(255)
location            VARCHAR(255)
contact_info        VARCHAR(255)
description         TEXT
notes               TEXT
min_duration        INT
max_duration        INT
requires_approval   BOOLEAN
allow_multiday      BOOLEAN
max_participants    INT
schedule_id         INT
status_id           INT
...
```

### What's Missing

- Division/Department categorization
- Platform / Platform group
- Technical contact (separate from general contact)
- User access level
- UUID for external system integration
- Method / Technique categorization

---

## Required Custom Attributes

Based on Device Hub (Appsmith) analysis and CSV exports:

### 1. Division
Type: **Enum/Reference**  
Purpose: High-level organizational unit  
Examples: `"M1 (Materials Engineering)"`, `"P2 (Nanostructured Materials)"`

### 2. Department
Type: **VARCHAR(255)**  
Format: `"CODE (Full Name)"`  
Nullable: **No**

### 3. Method / Technique
Type: **TEXT**  
Format: `"Category/Subcategory"`  
Examples: `"Spectroscopy/UV-VIS"`, `"Chromatography/Size Exclusion Chromatography"`

### 4. Platform
Type: **VARCHAR(100)**  
Nullable: Yes

### 5. Platform Group / Cluster
Type: **VARCHAR(100)**

### 6. User Level
Type: **ENUM**  
Values: All, Basic, Intermediate, Advanced  
Default: **All**

### 7. Technical Contact
Type: **VARCHAR(255)**

### 8. Scientific Contact
Type: **VARCHAR(255)**

### 9. UUID
Type: **CHAR(36)** or **VARCHAR(255)**  
Unique: **Yes**

---

## Proposed Schema Solutions

### Option A — Extend `resources` Table

```sql
ALTER TABLE resources
ADD COLUMN division VARCHAR(50),
ADD COLUMN department VARCHAR(255) NOT NULL,
ADD COLUMN method TEXT,
ADD COLUMN platform VARCHAR(100),
ADD COLUMN cluster VARCHAR(100),
ADD COLUMN user_level ENUM('All', 'Basic', 'Intermediate', 'Advanced') DEFAULT 'All',
ADD COLUMN technical_contact VARCHAR(255),
ADD COLUMN scientific_contact VARCHAR(255),
ADD COLUMN external_uuid VARCHAR(255) UNIQUE;
```

---

### Option B — Custom Attributes System

Multiple JOINs required but highly flexible.

---

### Option C — Hybrid Approach (**Recommended**)

Extend `resources` table with core fields and use custom attributes for dynamic metadata.

---

## Data Migration Strategy

### Phase 1 — Schema Extension
- Create migration SQL script  
- Backup database  
- Apply changes

### Phase 2 — Data Import
- Parse CSV  
- Map to resource IDs  
- Populate attributes

### Phase 3 — Code Integration
- Update Repository  
- Update Presenters  
- Update templates

### Phase 4 — API Development
- Create `/api/resources`  
- Implement filtering & pagination  

---

## Example Queries

```sql
SELECT r.*, ca_method.attribute_value AS method
FROM resources r
LEFT JOIN resource_custom_attributes ca_method 
    ON r.resource_id = ca_method.resource_id
WHERE r.department = 'M2 (Processing Technology)';
```

---

## API Endpoint Specification

`GET /api/resources`

Query parameters include:  
`search`, `department`, `division`, `method`, `platform`,  
`cluster`, `user_level`, `page`, `per_page`, `sort`, `order`.

---

## Testing Plan

- Unit tests  
- Integration tests  
- Manual UI tests  
- Performance checks  

---

## Performance Considerations

Recommended indexes:

```sql
CREATE INDEX idx_resources_department ON resources(department);
CREATE INDEX idx_resources_division ON resources(division);
```

---

## Migration Checklist

- [ ] Choose schema approach  
- [ ] Write migration script  
- [ ] Implement API  
- [ ] Import CSV data  
- [ ] Validate and deploy  

---

## Disclaimer

The content of this document was translated from Spanish into English using the AI from the institute itself. 

## Contact

Developer: **Moreno**  
Version: **1.0**  
Last Updated: **November 2025**
