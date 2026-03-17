-- =============================================================================
-- LibreBooking Large Sample Data
-- =============================================================================
-- This file adds a larger dataset on top of the existing sample-data-utf8.sql.
-- Load order:
--   1. create-schema.sql + upgrades
--   2. create-data.sql
--   3. sample-data-utf8.sql
--   4. sample-data-large-utf8.sql (this file)
--
-- This file is purely ADDITIVE — no deletes, no truncates.
-- IDs are chosen to avoid conflicts with existing sample data.
-- =============================================================================

-- =============================================================================
-- 1. User Groups (ids 5-9) — functional groups
-- =============================================================================

INSERT INTO `groups` (`group_id`, `name`) VALUES
  (5, 'Engineering'),
  (6, 'Marketing'),
  (7, 'Facilities'),
  (8, 'Management'),
  (9, 'Interns');

-- =============================================================================
-- 2. Users (ids 3-20) — 18 new users
--    Password hash is for "password" with salt "3b3dbb9b"
--    (same credentials as the existing "user" account)
-- =============================================================================

INSERT INTO `users`
  (`user_id`, `fname`, `lname`, `email`, `username`, `password`, `salt`, `timezone`, `lastlogin`, `status_id`, `date_created`, `language`, `organization`)
VALUES
  (3,  'Alice',    'Chen',      'achen@example.com',      'achen',     '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Acme Corp'),
  (4,  'Bob',      'Martinez',  'bmartinez@example.com',  'bmartinez', '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Acme Corp'),
  (5,  'Carol',    'Johnson',   'cjohnson@example.com',   'cjohnson',  '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Acme Corp'),
  (6,  'David',    'Kim',       'dkim@example.com',       'dkim',      '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Globex Inc'),
  (7,  'Eva',      'Singh',     'esingh@example.com',     'esingh',    '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Globex Inc'),
  (8,  'Frank',    'Brown',     'fbrown@example.com',     'fbrown',    '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Globex Inc'),
  (9,  'Grace',    'Taylor',    'gtaylor@example.com',    'gtaylor',   '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Initech'),
  (10, 'Henry',    'Wilson',    'hwilson@example.com',    'hwilson',   '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Initech'),
  (11, 'Irene',    'Garcia',    'igarcia@example.com',    'igarcia',   '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Initech'),
  (12, 'James',    'Lee',       'jlee@example.com',       'jlee',      '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Acme Corp'),
  (13, 'Karen',    'Patel',     'kpatel@example.com',     'kpatel',    '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Acme Corp'),
  (14, 'Leo',      'Anderson',  'landerson@example.com',  'landerson', '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Globex Inc'),
  (15, 'Maria',    'Thomas',    'mthomas@example.com',    'mthomas',   '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Globex Inc'),
  (16, 'Nathan',   'Jackson',   'njackson@example.com',   'njackson',  '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Initech'),
  (17, 'Olivia',   'White',     'owhite@example.com',     'owhite',    '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Initech'),
  (18, 'Peter',    'Harris',    'pharris@example.com',    'pharris',   '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Acme Corp'),
  (19, 'Quinn',    'Clark',     'qclark@example.com',     'qclark',    '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Globex Inc'),
  (20, 'Rachel',   'Lewis',     'rlewis@example.com',     'rlewis',    '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', NULL, 1, NOW(), 'en_us', 'Initech');

-- =============================================================================
-- 3. User-Group Assignments
--    Each user in 1-2 groups; existing admin (id=2) added to Management
-- =============================================================================

INSERT INTO `user_groups` (`user_id`, `group_id`) VALUES
  -- Admin user -> Management
  (2, 8),
  -- Engineering: Alice, Bob, David, Henry, James, Nathan (6 members)
  (3, 5), (4, 5), (6, 5), (10, 5), (12, 5), (16, 5),
  -- Marketing: Carol, Eva, Karen, Maria, Olivia (5 members)
  (5, 6), (7, 6), (13, 6), (15, 6), (17, 6),
  -- Facilities: Frank, Grace, Leo, Peter (4 members)
  (8, 7), (9, 7), (14, 7), (18, 7),
  -- Management: Irene, Quinn, Rachel (3 members + admin from above)
  (11, 8), (19, 8), (20, 8),
  -- Interns: Bob, Carol, Grace (3 members — also in other groups)
  (4, 9), (5, 9), (9, 9);

-- =============================================================================
-- 4. Layouts and Schedules
--    Existing: layout_id=1 (Default 8am-6pm/30min), schedule_id=1
--    New: layout_id=2 (Extended Hours), layout_id=3 (24-Hour)
-- =============================================================================

-- Extended Hours layout: 6am-10pm, 1-hour blocks
INSERT INTO `layouts` (`layout_id`, `timezone`) VALUES (2, 'America/New_York');

INSERT INTO `time_blocks` (`availability_code`, `layout_id`, `start_time`, `end_time`) VALUES
  (2, 2, '00:00', '06:00'),
  (1, 2, '06:00', '07:00'),
  (1, 2, '07:00', '08:00'),
  (1, 2, '08:00', '09:00'),
  (1, 2, '09:00', '10:00'),
  (1, 2, '10:00', '11:00'),
  (1, 2, '11:00', '12:00'),
  (1, 2, '12:00', '13:00'),
  (1, 2, '13:00', '14:00'),
  (1, 2, '14:00', '15:00'),
  (1, 2, '15:00', '16:00'),
  (1, 2, '16:00', '17:00'),
  (1, 2, '17:00', '18:00'),
  (1, 2, '18:00', '19:00'),
  (1, 2, '19:00', '20:00'),
  (1, 2, '20:00', '21:00'),
  (1, 2, '21:00', '22:00'),
  (2, 2, '22:00', '00:00');

-- 24-Hour layout: all day, 1-hour blocks
INSERT INTO `layouts` (`layout_id`, `timezone`) VALUES (3, 'America/New_York');

INSERT INTO `time_blocks` (`availability_code`, `layout_id`, `start_time`, `end_time`) VALUES
  (1, 3, '00:00', '01:00'),
  (1, 3, '01:00', '02:00'),
  (1, 3, '02:00', '03:00'),
  (1, 3, '03:00', '04:00'),
  (1, 3, '04:00', '05:00'),
  (1, 3, '05:00', '06:00'),
  (1, 3, '06:00', '07:00'),
  (1, 3, '07:00', '08:00'),
  (1, 3, '08:00', '09:00'),
  (1, 3, '09:00', '10:00'),
  (1, 3, '10:00', '11:00'),
  (1, 3, '11:00', '12:00'),
  (1, 3, '12:00', '13:00'),
  (1, 3, '13:00', '14:00'),
  (1, 3, '14:00', '15:00'),
  (1, 3, '15:00', '16:00'),
  (1, 3, '16:00', '17:00'),
  (1, 3, '17:00', '18:00'),
  (1, 3, '18:00', '19:00'),
  (1, 3, '19:00', '20:00'),
  (1, 3, '20:00', '21:00'),
  (1, 3, '21:00', '22:00'),
  (1, 3, '22:00', '23:00'),
  (1, 3, '23:00', '00:00');

-- New schedules
INSERT INTO `schedules` (`schedule_id`, `name`, `isdefault`, `weekdaystart`, `layout_id`) VALUES
  (2, 'Extended Hours', 0, 0, 2),
  (3, '24-Hour',        0, 0, 3);

-- =============================================================================
-- 5. Resource Types (ids 1-6)
-- =============================================================================

INSERT INTO `resource_types` (`resource_type_id`, `resource_type_name`, `resource_type_description`) VALUES
  (1, 'Conference Room', 'Meeting and conference rooms of various sizes'),
  (2, 'Office',          'Private offices and hot desks'),
  (3, 'Lab',             'Laboratories and workshop spaces'),
  (4, 'Equipment',       'Portable equipment available for checkout'),
  (5, 'Vehicle',         'Company vehicles for business use'),
  (6, 'Outdoor Space',   'Outdoor areas and event spaces');

-- =============================================================================
-- 6. Resources (ids 3-150)
--    Existing: Conference Room 1 (id=1), Conference Room 2 (id=2) on schedule 1
--    Uses autoassign=1 by default so all users can book
-- =============================================================================

-- ---------------------------------------------------------------------------
-- Conference/Meeting Rooms (ids 3-52, schedule 1, type 1) — 50 rooms
-- ---------------------------------------------------------------------------
INSERT INTO `resources` (`resource_id`, `name`, `location`, `description`, `max_participants`, `autoassign`, `requires_approval`, `allow_multiday_reservations`, `schedule_id`, `resource_type_id`) VALUES
  (3,  'Conference Room 3',   'Building A, Floor 1', 'Small meeting room',  6,  1, 0, 1, 1, 1),
  (4,  'Conference Room 4',   'Building A, Floor 1', 'Small meeting room',  6,  1, 0, 1, 1, 1),
  (5,  'Conference Room 5',   'Building A, Floor 2', 'Medium meeting room', 12, 1, 0, 1, 1, 1),
  (6,  'Conference Room 6',   'Building A, Floor 2', 'Medium meeting room', 12, 1, 0, 1, 1, 1),
  (7,  'Conference Room 7',   'Building A, Floor 3', 'Large meeting room',  20, 1, 0, 1, 1, 1),
  (8,  'Conference Room 8',   'Building A, Floor 3', 'Large meeting room',  20, 1, 1, 1, 1, 1),
  (9,  'Boardroom A',         'Building A, Floor 4', 'Executive boardroom', 16, 1, 1, 1, 1, 1),
  (10, 'Boardroom B',         'Building B, Floor 4', 'Executive boardroom', 16, 1, 1, 1, 1, 1),
  (11, 'Huddle Room A-101',   'Building A, Floor 1', 'Quick sync space',    4,  1, 0, 0, 1, 1),
  (12, 'Huddle Room A-102',   'Building A, Floor 1', 'Quick sync space',    4,  1, 0, 0, 1, 1),
  (13, 'Huddle Room A-201',   'Building A, Floor 2', 'Quick sync space',    4,  1, 0, 0, 1, 1),
  (14, 'Huddle Room A-202',   'Building A, Floor 2', 'Quick sync space',    4,  1, 0, 0, 1, 1),
  (15, 'Huddle Room B-101',   'Building B, Floor 1', 'Quick sync space',    4,  1, 0, 0, 1, 1),
  (16, 'Huddle Room B-102',   'Building B, Floor 1', 'Quick sync space',    4,  1, 0, 0, 1, 1),
  (17, 'Training Room 1',     'Building A, Floor 1', 'Classroom-style training room', 30, 1, 1, 1, 1, 1),
  (18, 'Training Room 2',     'Building B, Floor 1', 'Classroom-style training room', 30, 1, 1, 1, 1, 1),
  (19, 'Presentation Hall',   'Building A, Floor 1', 'Large presentation space with stage', 50, 1, 1, 1, 1, 1),
  (20, 'Webinar Studio',      'Building B, Floor 2', 'Soundproofed room for webinars', 6, 1, 1, 0, 1, 1),
  (21, 'Interview Room 1',    'Building A, Floor 1', 'Private interview space', 4, 1, 0, 0, 1, 1),
  (22, 'Interview Room 2',    'Building A, Floor 1', 'Private interview space', 4, 1, 0, 0, 1, 1),
  (23, 'Interview Room 3',    'Building B, Floor 1', 'Private interview space', 4, 1, 0, 0, 1, 1),
  (24, 'Conference Room 9',   'Building B, Floor 1', 'Small meeting room',  6,  1, 0, 1, 1, 1),
  (25, 'Conference Room 10',  'Building B, Floor 1', 'Small meeting room',  6,  1, 0, 1, 1, 1),
  (26, 'Conference Room 11',  'Building B, Floor 2', 'Medium meeting room', 12, 1, 0, 1, 1, 1),
  (27, 'Conference Room 12',  'Building B, Floor 2', 'Medium meeting room', 12, 1, 0, 1, 1, 1),
  (28, 'Conference Room 13',  'Building B, Floor 3', 'Large meeting room',  20, 1, 0, 1, 1, 1),
  (29, 'Conference Room 14',  'Building B, Floor 3', 'Large meeting room',  20, 1, 0, 1, 1, 1),
  (30, 'Conference Room 15',  'Building C, Floor 1', 'Small meeting room',  8,  1, 0, 1, 1, 1),
  (31, 'Conference Room 16',  'Building C, Floor 1', 'Small meeting room',  8,  1, 0, 1, 1, 1),
  (32, 'Conference Room 17',  'Building C, Floor 2', 'Medium meeting room', 14, 1, 0, 1, 1, 1),
  (33, 'Conference Room 18',  'Building C, Floor 2', 'Medium meeting room', 14, 1, 0, 1, 1, 1),
  (34, 'Conference Room 19',  'Building C, Floor 3', 'Large meeting room',  24, 1, 1, 1, 1, 1),
  (35, 'Conference Room 20',  'Building C, Floor 3', 'Large meeting room',  24, 1, 1, 1, 1, 1),
  (36, 'Brainstorm Room 1',   'Building A, Floor 2', 'Creative collaboration space', 8, 1, 0, 0, 1, 1),
  (37, 'Brainstorm Room 2',   'Building B, Floor 2', 'Creative collaboration space', 8, 1, 0, 0, 1, 1),
  (38, 'Phone Booth A-1',     'Building A, Floor 1', 'Single-person phone booth',    1, 1, 0, 0, 1, 1),
  (39, 'Phone Booth A-2',     'Building A, Floor 2', 'Single-person phone booth',    1, 1, 0, 0, 1, 1),
  (40, 'Phone Booth B-1',     'Building B, Floor 1', 'Single-person phone booth',    1, 1, 0, 0, 1, 1),
  (41, 'Phone Booth B-2',     'Building B, Floor 2', 'Single-person phone booth',    1, 1, 0, 0, 1, 1),
  (42, 'Phone Booth C-1',     'Building C, Floor 1', 'Single-person phone booth',    1, 1, 0, 0, 1, 1),
  (43, 'All-Hands Room',      'Building A, Floor 1', 'Large all-hands meeting space', 100, 1, 1, 1, 1, 1),
  (44, 'Executive Suite',     'Building A, Floor 4', 'Premium meeting room with lounge', 10, 1, 1, 1, 1, 1),
  (45, 'Video Conference 1',  'Building A, Floor 2', 'Room with dedicated video conferencing', 8, 1, 0, 0, 1, 1),
  (46, 'Video Conference 2',  'Building B, Floor 2', 'Room with dedicated video conferencing', 8, 1, 0, 0, 1, 1),
  (47, 'Video Conference 3',  'Building C, Floor 2', 'Room with dedicated video conferencing', 8, 1, 0, 0, 1, 1),
  (48, 'Quiet Room 1',        'Building A, Floor 3', 'Silent focused work room',  4, 1, 0, 0, 1, 1),
  (49, 'Quiet Room 2',        'Building B, Floor 3', 'Silent focused work room',  4, 1, 0, 0, 1, 1),
  (50, 'Town Hall',           'Building B, Floor 1', 'Town hall meeting space', 80, 1, 1, 1, 1, 1),
  (51, 'Meditation Room',     'Building A, Floor 3', 'Wellness and meditation space', 6, 1, 0, 0, 1, 1),
  (52, 'Game Room',           'Building B, Floor 3', 'Recreation and team building', 12, 1, 0, 0, 1, 1);

-- ---------------------------------------------------------------------------
-- Offices/Desks (ids 53-82, schedule 1, type 2) — 30 spaces
-- ---------------------------------------------------------------------------
INSERT INTO `resources` (`resource_id`, `name`, `location`, `description`, `max_participants`, `autoassign`, `requires_approval`, `allow_multiday_reservations`, `schedule_id`, `resource_type_id`) VALUES
  (53, 'Hot Desk A-101',     'Building A, Floor 1', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (54, 'Hot Desk A-102',     'Building A, Floor 1', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (55, 'Hot Desk A-103',     'Building A, Floor 1', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (56, 'Hot Desk A-104',     'Building A, Floor 1', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (57, 'Hot Desk A-105',     'Building A, Floor 1', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (58, 'Hot Desk A-201',     'Building A, Floor 2', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (59, 'Hot Desk A-202',     'Building A, Floor 2', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (60, 'Hot Desk A-203',     'Building A, Floor 2', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (61, 'Hot Desk A-204',     'Building A, Floor 2', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (62, 'Hot Desk A-205',     'Building A, Floor 2', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (63, 'Hot Desk B-101',     'Building B, Floor 1', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (64, 'Hot Desk B-102',     'Building B, Floor 1', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (65, 'Hot Desk B-103',     'Building B, Floor 1', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (66, 'Hot Desk B-104',     'Building B, Floor 1', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (67, 'Hot Desk B-105',     'Building B, Floor 1', 'Open plan hot desk',    1, 1, 0, 1, 1, 2),
  (68, 'Private Office 301', 'Building A, Floor 3', 'Single-occupant private office', 1, 1, 1, 1, 1, 2),
  (69, 'Private Office 302', 'Building A, Floor 3', 'Single-occupant private office', 1, 1, 1, 1, 1, 2),
  (70, 'Private Office 303', 'Building A, Floor 3', 'Single-occupant private office', 1, 1, 1, 1, 1, 2),
  (71, 'Private Office 304', 'Building B, Floor 3', 'Single-occupant private office', 1, 1, 1, 1, 1, 2),
  (72, 'Private Office 305', 'Building B, Floor 3', 'Single-occupant private office', 1, 1, 1, 1, 1, 2),
  (73, 'Shared Office 201',  'Building A, Floor 2', 'Two-person shared office', 2, 1, 0, 1, 1, 2),
  (74, 'Shared Office 202',  'Building A, Floor 2', 'Two-person shared office', 2, 1, 0, 1, 1, 2),
  (75, 'Shared Office 203',  'Building B, Floor 2', 'Two-person shared office', 2, 1, 0, 1, 1, 2),
  (76, 'Shared Office 204',  'Building B, Floor 2', 'Two-person shared office', 2, 1, 0, 1, 1, 2),
  (77, 'Standing Desk 1',    'Building A, Floor 1', 'Adjustable standing desk', 1, 1, 0, 1, 1, 2),
  (78, 'Standing Desk 2',    'Building A, Floor 2', 'Adjustable standing desk', 1, 1, 0, 1, 1, 2),
  (79, 'Standing Desk 3',    'Building B, Floor 1', 'Adjustable standing desk', 1, 1, 0, 1, 1, 2),
  (80, 'Standing Desk 4',    'Building B, Floor 2', 'Adjustable standing desk', 1, 1, 0, 1, 1, 2),
  (81, 'Corner Office 401',  'Building A, Floor 4', 'Executive corner office', 1, 1, 1, 1, 1, 2),
  (82, 'Corner Office 402',  'Building B, Floor 4', 'Executive corner office', 1, 1, 1, 1, 1, 2);

-- ---------------------------------------------------------------------------
-- Labs/Workshops (ids 83-107, schedule 2 Extended Hours, type 3) — 25 spaces
-- ---------------------------------------------------------------------------
INSERT INTO `resources` (`resource_id`, `name`, `location`, `description`, `max_participants`, `autoassign`, `requires_approval`, `allow_multiday_reservations`, `schedule_id`, `resource_type_id`) VALUES
  (83,  'Electronics Lab',       'Building C, Floor 1', 'Electronics prototyping lab',           8,  1, 1, 1, 2, 3),
  (84,  'Chemistry Lab',         'Building C, Floor 1', 'Wet chemistry lab',                     6,  1, 1, 1, 2, 3),
  (85,  'Physics Lab',           'Building C, Floor 1', 'General physics lab',                   8,  1, 1, 1, 2, 3),
  (86,  'Biology Lab',           'Building C, Floor 2', 'Biology and life sciences lab',         6,  1, 1, 1, 2, 3),
  (87,  '3D Printing Lab',       'Building C, Floor 2', 'Additive manufacturing lab',            4,  1, 1, 1, 2, 3),
  (88,  'CNC Workshop',          'Building C, Basement', 'CNC machining workshop',               4,  1, 1, 1, 2, 3),
  (89,  'Woodworking Shop',      'Building C, Basement', 'Woodworking and carpentry',            6,  1, 1, 1, 2, 3),
  (90,  'Metal Shop',            'Building C, Basement', 'Metalworking and welding',             4,  1, 1, 1, 2, 3),
  (91,  'Computer Lab 1',        'Building A, Floor 2', 'General computing lab',                 20, 1, 0, 0, 2, 3),
  (92,  'Computer Lab 2',        'Building B, Floor 2', 'General computing lab',                 20, 1, 0, 0, 2, 3),
  (93,  'Server Room Lab',       'Building C, Floor 1', 'Server and networking lab',             4,  1, 1, 0, 2, 3),
  (94,  'Robotics Lab',          'Building C, Floor 2', 'Robotics development space',            8,  1, 1, 1, 2, 3),
  (95,  'Optics Lab',            'Building C, Floor 2', 'Optics and photonics lab',              4,  1, 1, 1, 2, 3),
  (96,  'Clean Room',            'Building C, Floor 3', 'ISO Class 7 clean room',                4,  1, 1, 0, 2, 3),
  (97,  'Materials Testing Lab', 'Building C, Floor 1', 'Materials analysis and testing',        6,  1, 1, 1, 2, 3),
  (98,  'Audio Lab',             'Building B, Floor 3', 'Audio engineering lab',                  4,  1, 1, 0, 2, 3),
  (99,  'Video Production Lab',  'Building B, Floor 3', 'Video production and editing suite',    4,  1, 1, 0, 2, 3),
  (100, 'Maker Space 1',         'Building C, Floor 1', 'Open maker space',                     10, 1, 0, 1, 2, 3),
  (101, 'Maker Space 2',         'Building C, Floor 2', 'Open maker space',                     10, 1, 0, 1, 2, 3),
  (102, 'PCB Fabrication Room',  'Building C, Floor 1', 'PCB design and fabrication',             4, 1, 1, 0, 2, 3),
  (103, 'Testing Chamber',       'Building C, Floor 3', 'Environmental testing chamber',          2, 1, 1, 1, 2, 3),
  (104, 'Calibration Lab',       'Building C, Floor 3', 'Instrument calibration facility',        4, 1, 1, 0, 2, 3),
  (105, 'Paint Booth',           'Building C, Basement', 'Ventilated paint and finishing booth',   2, 1, 1, 0, 2, 3),
  (106, 'Laser Cutting Room',    'Building C, Floor 2', 'Laser cutting and engraving',            4, 1, 1, 0, 2, 3),
  (107, 'Prototype Workshop',    'Building C, Floor 1', 'General prototyping workspace',          8, 1, 0, 1, 2, 3);

-- ---------------------------------------------------------------------------
-- Equipment (ids 108-127, schedule 1, type 4) — 20 items
-- ---------------------------------------------------------------------------
INSERT INTO `resources` (`resource_id`, `name`, `location`, `description`, `max_participants`, `autoassign`, `requires_approval`, `allow_multiday_reservations`, `schedule_id`, `resource_type_id`) VALUES
  (108, 'Projector P-001',       'Equipment Room A',  'Portable HD projector',        NULL, 1, 0, 1, 1, 4),
  (109, 'Projector P-002',       'Equipment Room A',  'Portable HD projector',        NULL, 1, 0, 1, 1, 4),
  (110, 'Projector P-003',       'Equipment Room B',  'Portable 4K projector',        NULL, 1, 0, 1, 1, 4),
  (111, 'Laptop L-001',          'Equipment Room A',  'Loaner laptop (Windows)',       NULL, 1, 0, 1, 1, 4),
  (112, 'Laptop L-002',          'Equipment Room A',  'Loaner laptop (Windows)',       NULL, 1, 0, 1, 1, 4),
  (113, 'Laptop L-003',          'Equipment Room A',  'Loaner laptop (Mac)',           NULL, 1, 0, 1, 1, 4),
  (114, 'Laptop L-004',          'Equipment Room B',  'Loaner laptop (Mac)',           NULL, 1, 0, 1, 1, 4),
  (115, 'Video Camera VC-001',   'Equipment Room A',  'Professional video camera',     NULL, 1, 1, 1, 1, 4),
  (116, 'Video Camera VC-002',   'Equipment Room B',  'Professional video camera',     NULL, 1, 1, 1, 1, 4),
  (117, 'DSLR Camera DC-001',    'Equipment Room A',  'DSLR camera with lens kit',     NULL, 1, 1, 1, 1, 4),
  (118, 'Wireless Mic Set WM-1', 'Equipment Room A',  'Wireless microphone set (4-pack)', NULL, 1, 0, 1, 1, 4),
  (119, 'Wireless Mic Set WM-2', 'Equipment Room B',  'Wireless microphone set (4-pack)', NULL, 1, 0, 1, 1, 4),
  (120, 'PA System PA-001',      'Equipment Room A',  'Portable PA system with speakers', NULL, 1, 1, 1, 1, 4),
  (121, 'Whiteboard (Mobile) 1', 'Equipment Room A',  'Mobile whiteboard on wheels',   NULL, 1, 0, 1, 1, 4),
  (122, 'Whiteboard (Mobile) 2', 'Equipment Room B',  'Mobile whiteboard on wheels',   NULL, 1, 0, 1, 1, 4),
  (123, 'Display Monitor 65"',   'Equipment Room A',  'Portable 65-inch display on stand', NULL, 1, 0, 1, 1, 4),
  (124, 'Display Monitor 55"',   'Equipment Room B',  'Portable 55-inch display on stand', NULL, 1, 0, 1, 1, 4),
  (125, 'Tripod Set T-001',      'Equipment Room A',  'Camera tripod set',             NULL, 1, 0, 1, 1, 4),
  (126, 'Lighting Kit LK-001',   'Equipment Room A',  'Studio lighting kit',           NULL, 1, 0, 1, 1, 4),
  (127, 'Teleprompter TP-001',   'Equipment Room B',  'Portable teleprompter',         NULL, 1, 0, 1, 1, 4);

-- ---------------------------------------------------------------------------
-- Vehicles (ids 128-142, schedule 3 24-Hour, type 5) — 15 vehicles
-- ---------------------------------------------------------------------------
INSERT INTO `resources` (`resource_id`, `name`, `location`, `description`, `max_participants`, `autoassign`, `requires_approval`, `allow_multiday_reservations`, `schedule_id`, `resource_type_id`) VALUES
  (128, 'Sedan S-001',       'Parking Garage, Level B1', 'Company sedan (Toyota Camry)',    5,  1, 1, 1, 3, 5),
  (129, 'Sedan S-002',       'Parking Garage, Level B1', 'Company sedan (Toyota Camry)',    5,  1, 1, 1, 3, 5),
  (130, 'Sedan S-003',       'Parking Garage, Level B1', 'Company sedan (Honda Accord)',    5,  1, 1, 1, 3, 5),
  (131, 'SUV SU-001',        'Parking Garage, Level B1', 'Company SUV (Toyota RAV4)',       5,  1, 1, 1, 3, 5),
  (132, 'SUV SU-002',        'Parking Garage, Level B1', 'Company SUV (Ford Explorer)',     7,  1, 1, 1, 3, 5),
  (133, 'Van V-001',         'Parking Garage, Level B2', 'Passenger van (12-seat)',        12,  1, 1, 1, 3, 5),
  (134, 'Van V-002',         'Parking Garage, Level B2', 'Passenger van (12-seat)',        12,  1, 1, 1, 3, 5),
  (135, 'Cargo Van CV-001',  'Parking Garage, Level B2', 'Cargo van (Ford Transit)',       NULL, 1, 1, 1, 3, 5),
  (136, 'Cargo Van CV-002',  'Parking Garage, Level B2', 'Cargo van (Ford Transit)',       NULL, 1, 1, 1, 3, 5),
  (137, 'Pickup Truck PU-1', 'Parking Garage, Level B2', 'Pickup truck (Ford F-150)',      NULL, 1, 1, 1, 3, 5),
  (138, 'Pickup Truck PU-2', 'Parking Garage, Level B2', 'Pickup truck (Chevy Silverado)', NULL, 1, 1, 1, 3, 5),
  (139, 'Electric Car EV-1', 'Parking Garage, Level B1', 'Electric vehicle (Tesla Model 3)', 5, 1, 1, 1, 3, 5),
  (140, 'Electric Car EV-2', 'Parking Garage, Level B1', 'Electric vehicle (Tesla Model 3)', 5, 1, 1, 1, 3, 5),
  (141, 'Minibus MB-001',    'Parking Garage, Level B2', 'Minibus (20-seat)',              20, 1, 1, 1, 3, 5),
  (142, 'Golf Cart GC-001',  'Parking Garage, Level B1', 'Electric golf cart',              4, 1, 0, 0, 3, 5);

-- ---------------------------------------------------------------------------
-- Outdoor/Event Spaces (ids 143-150, schedule 2 Extended Hours, type 6) — 8 spaces
-- ---------------------------------------------------------------------------
INSERT INTO `resources` (`resource_id`, `name`, `location`, `description`, `max_participants`, `autoassign`, `requires_approval`, `allow_multiday_reservations`, `schedule_id`, `resource_type_id`) VALUES
  (143, 'Rooftop Terrace',     'Building A, Roof',     'Rooftop event space',              50, 1, 1, 1, 2, 6),
  (144, 'Garden Pavilion',     'Campus Garden',        'Covered outdoor pavilion',         40, 1, 1, 1, 2, 6),
  (145, 'Courtyard',           'Between Buildings A-B', 'Open courtyard area',             60, 1, 1, 1, 2, 6),
  (146, 'Picnic Area North',   'North Lawn',           'Outdoor picnic area with tables',  30, 1, 0, 0, 2, 6),
  (147, 'Picnic Area South',   'South Lawn',           'Outdoor picnic area with tables',  30, 1, 0, 0, 2, 6),
  (148, 'Sports Field',        'East Campus',          'Multi-purpose sports field',       40, 1, 1, 1, 2, 6),
  (149, 'Amphitheater',        'West Campus',          'Outdoor amphitheater with seating', 120, 1, 1, 1, 2, 6),
  (150, 'BBQ Area',            'South Lawn',           'Barbecue and grilling area',       20, 1, 0, 0, 2, 6);

-- =============================================================================
-- 7. Resource Type Assignments
-- =============================================================================

INSERT INTO `resource_type_assignment` (`resource_id`, `resource_type_id`) VALUES
  -- Conference Rooms (type 1): ids 3-52
  (3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),
  (13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),
  (23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),
  (33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(41,1),(42,1),
  (43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),(51,1),(52,1),
  -- Offices (type 2): ids 53-82
  (53,2),(54,2),(55,2),(56,2),(57,2),(58,2),(59,2),(60,2),(61,2),(62,2),
  (63,2),(64,2),(65,2),(66,2),(67,2),(68,2),(69,2),(70,2),(71,2),(72,2),
  (73,2),(74,2),(75,2),(76,2),(77,2),(78,2),(79,2),(80,2),(81,2),(82,2),
  -- Labs (type 3): ids 83-107
  (83,3),(84,3),(85,3),(86,3),(87,3),(88,3),(89,3),(90,3),(91,3),(92,3),
  (93,3),(94,3),(95,3),(96,3),(97,3),(98,3),(99,3),(100,3),(101,3),(102,3),
  (103,3),(104,3),(105,3),(106,3),(107,3),
  -- Equipment (type 4): ids 108-127
  (108,4),(109,4),(110,4),(111,4),(112,4),(113,4),(114,4),(115,4),(116,4),(117,4),
  (118,4),(119,4),(120,4),(121,4),(122,4),(123,4),(124,4),(125,4),(126,4),(127,4),
  -- Vehicles (type 5): ids 128-142
  (128,5),(129,5),(130,5),(131,5),(132,5),(133,5),(134,5),(135,5),(136,5),(137,5),
  (138,5),(139,5),(140,5),(141,5),(142,5),
  -- Outdoor Spaces (type 6): ids 143-150
  (143,6),(144,6),(145,6),(146,6),(147,6),(148,6),(149,6),(150,6);

-- =============================================================================
-- 8. Resource Groups (hierarchical)
-- =============================================================================

INSERT INTO `resource_groups` (`resource_group_id`, `resource_group_name`, `parent_id`) VALUES
  -- Top-level groups
  (1, 'Conference Rooms',  NULL),
  (2, 'Offices & Desks',   NULL),
  (3, 'Labs & Workshops',  NULL),
  (4, 'Equipment',         NULL),
  (5, 'Vehicles',          NULL),
  (6, 'Outdoor Spaces',    NULL),
  -- Sub-groups under Conference Rooms
  (7,  'Small Rooms (1-6)',   1),
  (8,  'Medium Rooms (7-14)', 1),
  (9,  'Large Rooms (15+)',   1),
  (10, 'Huddle Rooms',        1),
  (11, 'Phone Booths',        1),
  -- Sub-groups under Offices & Desks
  (12, 'Hot Desks',           2),
  (13, 'Private Offices',     2),
  (14, 'Shared Offices',      2),
  -- Sub-groups under Labs & Workshops
  (15, 'Science Labs',        3),
  (16, 'Fabrication',         3),
  (17, 'Computing',           3),
  -- Sub-groups under Equipment
  (18, 'AV Equipment',        4),
  (19, 'IT Equipment',        4),
  (20, 'Photography',         4);

-- =============================================================================
-- 9. Resource Group Assignments
-- =============================================================================

INSERT INTO `resource_group_assignment` (`resource_group_id`, `resource_id`) VALUES
  -- Small Rooms (1-6 people)
  (7,3),(7,4),(7,11),(7,12),(7,13),(7,14),(7,15),(7,16),(7,21),(7,22),(7,23),(7,24),(7,25),(7,30),(7,31),(7,51),
  -- Medium Rooms (7-14 people)
  (8,5),(8,6),(8,26),(8,27),(8,32),(8,33),(8,36),(8,37),(8,45),(8,46),(8,47),(8,52),(8,9),(8,10),(8,44),
  -- Large Rooms (15+)
  (9,7),(9,8),(9,17),(9,18),(9,19),(9,28),(9,29),(9,34),(9,35),(9,43),(9,50),
  -- Huddle Rooms
  (10,11),(10,12),(10,13),(10,14),(10,15),(10,16),
  -- Phone Booths
  (11,38),(11,39),(11,40),(11,41),(11,42),
  -- Hot Desks
  (12,53),(12,54),(12,55),(12,56),(12,57),(12,58),(12,59),(12,60),(12,61),(12,62),(12,63),(12,64),(12,65),(12,66),(12,67),(12,77),(12,78),(12,79),(12,80),
  -- Private Offices
  (13,68),(13,69),(13,70),(13,71),(13,72),(13,81),(13,82),
  -- Shared Offices
  (14,73),(14,74),(14,75),(14,76),
  -- Science Labs
  (15,83),(15,84),(15,85),(15,86),(15,95),(15,97),(15,103),(15,104),
  -- Fabrication
  (16,87),(16,88),(16,89),(16,90),(16,100),(16,101),(16,102),(16,105),(16,106),(16,107),
  -- Computing
  (17,91),(17,92),(17,93),(17,94),(17,98),(17,99),
  -- AV Equipment
  (18,108),(18,109),(18,110),(18,118),(18,119),(18,120),(18,123),(18,124),(18,127),
  -- IT Equipment
  (19,111),(19,112),(19,113),(19,114),(19,121),(19,122),
  -- Photography
  (20,115),(20,116),(20,117),(20,125),(20,126);

-- =============================================================================
-- 10. Group-Resource Permissions
--     Each user group gets access to relevant resource subsets
--     permission_type: 0 = full access (default)
-- =============================================================================

INSERT INTO `group_resource_permissions` (`group_id`, `resource_id`) VALUES
  -- Engineering (group 5): all conference rooms, labs, IT equipment
  (5,3),(5,4),(5,5),(5,6),(5,7),(5,8),(5,9),(5,10),(5,11),(5,12),
  (5,13),(5,14),(5,15),(5,16),(5,17),(5,18),(5,19),(5,20),(5,21),(5,22),
  (5,23),(5,24),(5,25),(5,26),(5,27),(5,28),(5,29),(5,30),(5,31),(5,32),
  (5,33),(5,34),(5,35),(5,36),(5,37),(5,38),(5,39),(5,40),(5,41),(5,42),
  (5,43),(5,44),(5,45),(5,46),(5,47),(5,48),(5,49),(5,50),(5,51),(5,52),
  (5,83),(5,84),(5,85),(5,86),(5,87),(5,88),(5,89),(5,90),(5,91),(5,92),
  (5,93),(5,94),(5,95),(5,96),(5,97),(5,98),(5,99),(5,100),(5,101),(5,102),
  (5,103),(5,104),(5,105),(5,106),(5,107),
  (5,111),(5,112),(5,113),(5,114),

  -- Marketing (group 6): conference rooms, AV & photography equipment, outdoor spaces
  (6,3),(6,4),(6,5),(6,6),(6,7),(6,8),(6,9),(6,10),(6,17),(6,18),(6,19),
  (6,20),(6,36),(6,37),(6,43),(6,44),(6,45),(6,46),(6,47),(6,50),
  (6,108),(6,109),(6,110),(6,115),(6,116),(6,117),(6,118),(6,119),(6,120),
  (6,123),(6,124),(6,125),(6,126),(6,127),
  (6,143),(6,144),(6,145),(6,146),(6,147),(6,148),(6,149),(6,150),

  -- Facilities (group 7): all resources (they manage everything)
  (7,3),(7,4),(7,5),(7,6),(7,7),(7,8),(7,9),(7,10),(7,11),(7,12),
  (7,13),(7,14),(7,15),(7,16),(7,17),(7,18),(7,19),(7,20),(7,21),(7,22),
  (7,23),(7,24),(7,25),(7,26),(7,27),(7,28),(7,29),(7,30),(7,31),(7,32),
  (7,33),(7,34),(7,35),(7,36),(7,37),(7,38),(7,39),(7,40),(7,41),(7,42),
  (7,43),(7,44),(7,45),(7,46),(7,47),(7,48),(7,49),(7,50),(7,51),(7,52),
  (7,53),(7,54),(7,55),(7,56),(7,57),(7,58),(7,59),(7,60),(7,61),(7,62),
  (7,63),(7,64),(7,65),(7,66),(7,67),(7,68),(7,69),(7,70),(7,71),(7,72),
  (7,73),(7,74),(7,75),(7,76),(7,77),(7,78),(7,79),(7,80),(7,81),(7,82),
  (7,83),(7,84),(7,85),(7,86),(7,87),(7,88),(7,89),(7,90),(7,91),(7,92),
  (7,93),(7,94),(7,95),(7,96),(7,97),(7,98),(7,99),(7,100),(7,101),(7,102),
  (7,103),(7,104),(7,105),(7,106),(7,107),
  (7,108),(7,109),(7,110),(7,111),(7,112),(7,113),(7,114),(7,115),(7,116),(7,117),
  (7,118),(7,119),(7,120),(7,121),(7,122),(7,123),(7,124),(7,125),(7,126),(7,127),
  (7,128),(7,129),(7,130),(7,131),(7,132),(7,133),(7,134),(7,135),(7,136),(7,137),
  (7,138),(7,139),(7,140),(7,141),(7,142),
  (7,143),(7,144),(7,145),(7,146),(7,147),(7,148),(7,149),(7,150),

  -- Management (group 8): conference rooms, offices, vehicles, outdoor spaces
  (8,3),(8,4),(8,5),(8,6),(8,7),(8,8),(8,9),(8,10),(8,17),(8,18),(8,19),
  (8,43),(8,44),(8,50),
  (8,68),(8,69),(8,70),(8,71),(8,72),(8,81),(8,82),
  (8,128),(8,129),(8,130),(8,131),(8,132),(8,133),(8,134),(8,135),(8,136),(8,137),
  (8,138),(8,139),(8,140),(8,141),(8,142),
  (8,143),(8,144),(8,145),(8,146),(8,147),(8,148),(8,149),(8,150),

  -- Interns (group 9): small conference rooms, hot desks, huddle rooms
  (9,3),(9,4),(9,11),(9,12),(9,13),(9,14),(9,15),(9,16),(9,24),(9,25),(9,30),(9,31),
  (9,53),(9,54),(9,55),(9,56),(9,57),(9,58),(9,59),(9,60),(9,61),(9,62),
  (9,63),(9,64),(9,65),(9,66),(9,67),(9,77),(9,78),(9,79),(9,80);

-- =============================================================================
-- 11. Accessories (ids 4-6)
--     Existing: 1=whiteboard markers(10), 2=limited(2), 3=unlimited
-- =============================================================================

INSERT INTO `accessories` (`accessory_id`, `accessory_name`, `accessory_quantity`) VALUES
  (4, 'Laptop Charger (USB-C)',  15),
  (5, 'Wireless Presenter Clicker', 8),
  (6, 'Extension Cord (25ft)',  20);

-- =============================================================================
-- 12. Custom Attributes (ids 5-8)
--     Existing: 1-2 (reservation category=1), 3-4 (resource category=4)
-- =============================================================================

INSERT INTO `custom_attributes` (`custom_attribute_id`, `display_label`, `display_type`, `attribute_category`, `validation_regex`, `is_required`, `possible_values`) VALUES
  (5, 'Purpose of Booking', 1, 1, NULL, 0, 'Meeting,Training,Interview,Social Event,Other'),
  (6, 'Number of External Guests', 1, 1, '^[0-9]*$', 0, NULL),
  (7, 'Floor Number', 1, 4, '^[0-9]+$', 0, NULL),
  (8, 'Wheelchair Accessible', 3, 4, NULL, 0, NULL);

-- =============================================================================
-- 13. Announcements
-- =============================================================================

INSERT INTO `announcements` (`announcementid`, `announcement_text`, `priority`, `start_date`, `end_date`) VALUES
  (1, 'Welcome to LibreBooking! Please review the booking guidelines before making your first reservation.', 1, NULL, NULL),
  (2, 'Reminder: All conference room bookings over 2 hours require manager approval.', 2, NULL, NULL),
  (3, 'New resources have been added! Check out our expanded lab spaces and vehicle fleet.', 1, NULL, NULL);

