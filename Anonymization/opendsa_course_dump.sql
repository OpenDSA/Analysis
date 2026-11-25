-- ============================================================================
-- OpenDSA-LTI course data dump — ANONYMIZED — four data files + one key file
-- ============================================================================
-- Extracts exercise attempts, exercise progresses, user interactions, and
-- time tracking for a single course offering from the OpenDSA-LTI
-- `opendsa` database, with direct identifiers replaced by a stable
-- pseudonym. Built for DataGrip.
--
-- PRIVACY MODEL (read this before exporting)
--   * Direct identifiers are removed at READ TIME — the database itself is
--     never modified (unlike docs/export_data.md, which destructively
--     rewrites the users table and still leaves IPs in the telemetry).
--   * Each student gets an anon_id = salted hash of their email. The same
--     student has the SAME anon_id in all four data files, so they remain
--     joinable, but nothing in those files directly identifies them.
--   * THE KEY = the salt (SET below) + result grid 5 (anon_id -> identity).
--     Export grid 5 SEPARATELY, store it securely, never share it. Anyone
--     holding both the key and the data files can re-identify students.
--   * Still present in the data files (kept for analytic value, but they
--     are quasi-identifiers): timestamps, browser_family/version,
--     os_family/version, device, and free-text `answer` in attempts.
--     In a small class these can be fingerprinting. If your data-sharing
--     agreement requires stronger anonymity (k-anonymity, coarsened
--     dates, no fingerprints), do that as a SECOND, separate pass on the
--     exported CSVs (pandas/R) — it cannot be done with a simple column
--     substitution.
--
-- HOW TO USE (DataGrip)
--   1. Edit BOTH SET statements below: the course_offering_id, and the
--      salt (any long random string — generate once and KEEP IT FIXED for
--     the whole dataset; changing it mid-study breaks joinability across
--     exports).
--   2. Execute the whole file. Five result grids come back, in order:
--        result 1 = exercise attempts        -> opendsa_attempts.csv
--        result 2 = exercise progresses      -> opendsa_progresses.csv
--        result 3 = user interactions        -> opendsa_interactions.csv
--        result 4 = time tracking            -> opendsa_time_tracking.csv
--        result 5 = KEY FILE                 -> keep private, never share
--   3. Save each grid via right-click -> "Export Data to File(s)" -> CSV.
--      In the export dialog choose to extract ALL rows, not just the
--      fetched page — DataGrip pages large result sets. Use CSV (not TSV)
--      so the embedded newlines/tabs in description / sections_time are
--      quoted correctly. Export grid 5 individually — do not bulk-export
--      all grids to one folder you intend to share.
--   * The session variables live as long as the console's connection.
--     In a NEW console, re-run both SET lines (or the whole file) first.
--
-- SCOPE NOTES
--   * Telemetry rows have three alternate FK paths (book section /
--     standalone module version / course-offering exercise); exactly one
--     is set per row. Each query unions the book path with the
--     standalone-module path so nothing is missed. (If your course also
--     embeds bare exercises directly on the offering — the third path,
--     inst_course_offering_exercise_id — tell me and I'll add that
--     branch; most courses don't use it.)
--   * Rows from the "student view" pseudo-account are excluded from the
--     data files, matching the app's own exports (it may still appear in
--     the key file; that is harmless).
--   * These queries cover ALL books of the course offering — more data
--     than the built-in instructor CSV exports, which use the first
--     book only.
--   * Removed vs. the identified version of this script: user_id, email,
--     ip_address, and raw row ids. The interaction `description` JSON is
--     scrubbed of the row-owner's email address as a defensive measure.
-- ============================================================================


-- ============================================================================
-- SETTINGS — EDIT THESE
-- ============================================================================
SET @course_offering_id = 123;                -- <-- your course offering id
SET @salt = 'CHANGE-ME-to-a-long-random-string';  -- <-- part of the key; keep fixed


-- ============================================================================
-- QUERY 1: Exercise attempts  (anonymized)
-- ============================================================================
SELECT
  CONCAT('S_', LEFT(SHA2(CONCAT_WS(':', u.email, @salt), 256), 12)) AS anon_id,
  ib.title               AS book,
  im.name                AS module,
  s.name                 AS section,
  ie.name                AS exercise,
  ie.short_name          AS exercise_short_name,
  a.question_name,
  a.request_type,        -- PE / AE / PI / KA
  a.correct,
  a.worth_credit,
  a.count_attempts,
  a.count_hints,
  a.hint_used,
  a.points_earned,
  a.earned_proficiency,
  a.pe_score,
  a.pe_steps_fixed,
  a.answer,              -- CAUTION: free text; may be authorship-identifiable
  a.time_done,
  a.time_taken           -- seconds
FROM odsa_exercise_attempts a
JOIN inst_book_section_exercises ibse ON ibse.id = a.inst_book_section_exercise_id
JOIN inst_exercises       ie  ON ie.id  = ibse.inst_exercise_id
JOIN inst_sections        s   ON s.id   = ibse.inst_section_id
JOIN inst_chapter_modules icm ON icm.id = s.inst_chapter_module_id
JOIN inst_chapters        ich ON ich.id = icm.inst_chapter_id
JOIN inst_modules         im  ON im.id  = icm.inst_module_id
JOIN inst_books           ib  ON ib.id  = ich.inst_book_id
JOIN users                u   ON u.id   = a.user_id
WHERE ib.course_offering_id = @course_offering_id
  AND u.email != 'student_view@example.com'

UNION ALL

-- standalone module attempts
SELECT
  CONCAT('S_', LEFT(SHA2(CONCAT_WS(':', u.email, @salt), 256), 12)) AS anon_id,
  CONCAT('standalone: ', imv.name)   AS book,
  imv.name               AS module,
  ims.name               AS section,
  ie.name                AS exercise,
  ie.short_name          AS exercise_short_name,
  a.question_name,
  a.request_type,
  a.correct,
  a.worth_credit,
  a.count_attempts,
  a.count_hints,
  a.hint_used,
  a.points_earned,
  a.earned_proficiency,
  a.pe_score,
  a.pe_steps_fixed,
  a.answer,
  a.time_done,
  a.time_taken
FROM odsa_exercise_attempts a
JOIN inst_module_section_exercises imse ON imse.id = a.inst_module_section_exercise_id
JOIN inst_module_sections   ims ON ims.id = imse.inst_module_section_id
JOIN inst_module_versions  imv ON imv.id = imse.inst_module_version_id
JOIN inst_exercises         ie ON ie.id  = imse.inst_exercise_id
JOIN users                  u  ON u.id   = a.user_id
WHERE imv.course_offering_id = @course_offering_id
  AND u.email != 'student_view@example.com'

ORDER BY anon_id, time_done;


-- ============================================================================
-- QUERY 2: Exercise progresses  (anonymized; one row per student x exercise)
-- ============================================================================
SELECT
  CONCAT('S_', LEFT(SHA2(CONCAT_WS(':', u.email, @salt), 256), 12)) AS anon_id,
  ib.title               AS book,
  im.name                AS module,
  s.name                 AS section,
  ie.name                AS exercise,
  ie.short_name          AS exercise_short_name,
  p.current_score,
  p.highest_score,
  p.total_correct,
  p.total_worth_credit,
  p.first_done,
  p.last_done,
  p.proficient_date,
  ibse.points            AS points_possible,
  CASE WHEN p.proficient_date IS NOT NULL
       THEN ibse.points ELSE 0 END AS points_earned
FROM odsa_exercise_progresses p
JOIN inst_book_section_exercises ibse ON ibse.id = p.inst_book_section_exercise_id
JOIN inst_exercises       ie  ON ie.id  = ibse.inst_exercise_id
JOIN inst_sections        s   ON s.id   = ibse.inst_section_id
JOIN inst_chapter_modules icm ON icm.id = s.inst_chapter_module_id
JOIN inst_chapters        ich ON ich.id = icm.inst_chapter_id
JOIN inst_modules         im  ON im.id  = icm.inst_module_id
JOIN inst_books           ib  ON ib.id  = ich.inst_book_id
JOIN users                u   ON u.id   = p.user_id
WHERE ib.course_offering_id = @course_offering_id
  AND u.email != 'student_view@example.com'

UNION ALL

-- standalone module progresses
SELECT
  CONCAT('S_', LEFT(SHA2(CONCAT_WS(':', u.email, @salt), 256), 12)) AS anon_id,
  CONCAT('standalone: ', imv.name)   AS book,
  imv.name               AS module,
  ims.name               AS section,
  ie.name                AS exercise,
  ie.short_name          AS exercise_short_name,
  p.current_score,
  p.highest_score,
  p.total_correct,
  p.total_worth_credit,
  p.first_done,
  p.last_done,
  p.proficient_date,
  imse.points            AS points_possible,
  CASE WHEN p.proficient_date IS NOT NULL
       THEN imse.points ELSE 0 END AS points_earned
FROM odsa_exercise_progresses p
JOIN inst_module_section_exercises imse ON imse.id = p.inst_module_section_exercise_id
JOIN inst_module_sections   ims ON ims.id = imse.inst_module_section_id
JOIN inst_module_versions  imv ON imv.id = imse.inst_module_version_id
JOIN inst_exercises         ie ON ie.id  = imse.inst_exercise_id
JOIN users                  u  ON u.id   = p.user_id
WHERE imv.course_offering_id = @course_offering_id
  AND u.email != 'student_view@example.com'

ORDER BY anon_id, exercise;


-- ============================================================================
-- QUERY 3: User interactions  (anonymized; the clickstream — biggest table)
-- ============================================================================
SELECT
  CONCAT('S_', LEFT(SHA2(CONCAT_WS(':', u.email, @salt), 256), 12)) AS anon_id,
  ib.title               AS book,
  s.name                 AS section,
  ie.name                AS exercise,
  i.name                 AS event_type,
  i.uiid,
  i.action_time,
  i.browser_family,      -- quasi-identifier: remove these three lines
  i.browser_version,     --   (in BOTH branches) if your sharing agreement
  i.os_family,           --   forbids device fingerprinting
  i.os_version,
  i.device,
  REPLACE(i.description, u.email, '[redacted]') AS description
FROM odsa_user_interactions i
LEFT JOIN inst_book_section_exercises ibse ON ibse.id = i.inst_book_section_exercise_id
LEFT JOIN inst_exercises ie ON ie.id = ibse.inst_exercise_id
LEFT JOIN inst_sections  s  ON s.id  = i.inst_section_id
JOIN inst_books ib ON ib.id = i.inst_book_id
JOIN users u ON u.id = i.user_id
WHERE ib.course_offering_id = @course_offering_id
  AND u.email != 'student_view@example.com'

UNION ALL

-- standalone module interactions
SELECT
  CONCAT('S_', LEFT(SHA2(CONCAT_WS(':', u.email, @salt), 256), 12)) AS anon_id,
  CONCAT('standalone: ', imv.name)   AS book,
  ims.name               AS section,
  ie.name                AS exercise,
  i.name                 AS event_type,
  i.uiid,
  i.action_time,
  i.browser_family,
  i.browser_version,
  i.os_family,
  i.os_version,
  i.device,
  REPLACE(i.description, u.email, '[redacted]') AS description
FROM odsa_user_interactions i
LEFT JOIN inst_module_section_exercises imse ON imse.id = i.inst_module_section_exercise_id
LEFT JOIN inst_exercises ie ON ie.id = imse.inst_exercise_id
LEFT JOIN inst_module_sections ims ON ims.id = imse.inst_module_section_id
JOIN inst_module_versions imv ON imv.id = i.inst_module_version_id
JOIN users u ON u.id = i.user_id
WHERE imv.course_offering_id = @course_offering_id
  AND u.email != 'student_view@example.com'

ORDER BY anon_id, action_time;


-- ============================================================================
-- QUERY 4: Time tracking  (anonymized; aggregated daily time-on-task)
-- ============================================================================
SELECT
  CONCAT('S_', LEFT(SHA2(CONCAT_WS(':', u.email, @salt), 256), 12)) AS anon_id,
  ib.title               AS book,
  im.name                AS module,
  tt.session_date,       -- yyyymmdd string; always >= 1 day in the past by design
  tt.total_time,         -- seconds on the module page that day
  tt.sections_time,      -- JSON array: [{module: total}, {section1: s}, ...]
  tt.uuid,
  tt.created_at
FROM odsa_user_time_trackings tt
JOIN inst_books  ib ON ib.id  = tt.inst_book_id
LEFT JOIN inst_modules im ON im.id = tt.inst_module_id
JOIN users u ON u.id = tt.user_id
WHERE ib.course_offering_id = @course_offering_id
  AND u.email != 'student_view@example.com'

UNION ALL

-- standalone module time tracking
SELECT
  CONCAT('S_', LEFT(SHA2(CONCAT_WS(':', u.email, @salt), 256), 12)) AS anon_id,
  CONCAT('standalone: ', imv.name)   AS book,
  imv.name                AS module,
  tt.session_date,
  tt.total_time,
  tt.sections_time,
  tt.uuid,
  tt.created_at
FROM odsa_user_time_trackings tt
JOIN inst_module_versions imv ON imv.id = tt.inst_module_version_id
JOIN users u ON u.id = tt.user_id
WHERE imv.course_offering_id = @course_offering_id
  AND u.email != 'student_view@example.com'

ORDER BY anon_id, session_date;


-- ============================================================================
-- QUERY 5: *** KEY FILE — EXPORT SEPARATELY, STORE SECURELY, NEVER SHARE ***
-- ============================================================================
-- Maps every anon_id that can appear in the four data files back to the
-- real identity. Scoped to users who actually have data in this course
-- (any of the four tables, book or standalone path). The course_role
-- column lets you filter instructor/admin activity out of analysis.
--
-- NOTE ON PERFORMANCE: the active-user set is computed as a DERIVED TABLE
-- (the JOIN subquery below), materialized once. Do NOT rewrite it as
-- WHERE u.id IN (SELECT ... UNION SELECT ...) — MariaDB plans that form
-- as a DEPENDENT SUBQUERY and re-runs the full union once PER USER ROW;
-- on a production-size database (millions of interaction rows) that runs
-- for many minutes. The derived-table form returns in well under a
-- second at the same scale.
SELECT
  CONCAT('S_', LEFT(SHA2(CONCAT_WS(':', u.email, @salt), 256), 12)) AS anon_id,
  u.id                    AS user_id,
  u.email,
  u.first_name,
  u.last_name,
  cr.name                 AS course_role
FROM (
    SELECT a.user_id
    FROM odsa_exercise_attempts a
    JOIN inst_books ib ON ib.id = a.inst_book_id
    WHERE ib.course_offering_id = @course_offering_id
  UNION
    SELECT a.user_id
    FROM odsa_exercise_attempts a
    JOIN inst_module_section_exercises imse ON imse.id = a.inst_module_section_exercise_id
    JOIN inst_module_versions imv ON imv.id = imse.inst_module_version_id
    WHERE imv.course_offering_id = @course_offering_id
  UNION
    SELECT p.user_id
    FROM odsa_exercise_progresses p
    JOIN inst_book_section_exercises ibse ON ibse.id = p.inst_book_section_exercise_id
    JOIN inst_books ib ON ib.id = ibse.inst_book_id
    WHERE ib.course_offering_id = @course_offering_id
  UNION
    SELECT p.user_id
    FROM odsa_exercise_progresses p
    JOIN inst_module_section_exercises imse ON imse.id = p.inst_module_section_exercise_id
    JOIN inst_module_versions imv ON imv.id = imse.inst_module_version_id
    WHERE imv.course_offering_id = @course_offering_id
  UNION
    SELECT i.user_id
    FROM odsa_user_interactions i
    JOIN inst_books ib ON ib.id = i.inst_book_id
    WHERE ib.course_offering_id = @course_offering_id
  UNION
    SELECT i.user_id
    FROM odsa_user_interactions i
    JOIN inst_module_versions imv ON imv.id = i.inst_module_version_id
    WHERE imv.course_offering_id = @course_offering_id
  UNION
    SELECT tt.user_id
    FROM odsa_user_time_trackings tt
    JOIN inst_books ib ON ib.id = tt.inst_book_id
    WHERE ib.course_offering_id = @course_offering_id
  UNION
    SELECT tt.user_id
    FROM odsa_user_time_trackings tt
    JOIN inst_module_versions imv ON imv.id = tt.inst_module_version_id
    WHERE imv.course_offering_id = @course_offering_id
) AS active
JOIN users u ON u.id = active.user_id
LEFT JOIN course_enrollments ce
       ON ce.user_id = u.id AND ce.course_offering_id = @course_offering_id
LEFT JOIN course_roles cr ON cr.id = ce.course_role_id
ORDER BY anon_id;
