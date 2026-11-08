# RHU Health Information System — Rebuild Implementation Plan

> Rebuild of `health-capstone` (Laravel 11 + Vue 3 + Inertia + MySQL) as a **Next.js + Postgres** application deployed on **Vercel**.
> This document covers the tech stack, architecture, roles, database schema, every module and feature, the main user flows, the data migration from the old system, security, testing, deployment, and a phased timeline.

---

## Table of Contents

1. [Goals and Scope](#1-goals-and-scope)
2. [Tech Stack](#2-tech-stack)
3. [System Architecture](#3-system-architecture)
4. [Roles and Permissions](#4-roles-and-permissions)
5. [Database Design](#5-database-design)
6. [Modules and Features](#6-modules-and-features)
7. [End-to-End Flows](#7-end-to-end-flows)
8. [Pages and Routes Map](#8-pages-and-routes-map)
9. [Project Folder Structure](#9-project-folder-structure)
10. [Security and Data Privacy (RA 10173)](#10-security-and-data-privacy-ra-10173)
11. [Data Migration from the Old System](#11-data-migration-from-the-old-system)
12. [Testing Strategy](#12-testing-strategy)
13. [Deployment on Vercel](#13-deployment-on-vercel)
14. [Implementation Phases and Timeline](#14-implementation-phases-and-timeline)
15. [Definition of Done and Acceptance Criteria](#15-definition-of-done-and-acceptance-criteria)
16. [Risks and Mitigations](#16-risks-and-mitigations)
17. [Appendix: Lookup Values](#17-appendix-lookup-values)

---

## 1. Goals and Scope

### 1.1 What the system does
A records, queueing, and analytics system for a Rural Health Unit (RHU). Staff register patients and record encounters; doctors work through a consultation queue; admins manage staff and view analytics.

### 1.2 Programs / modules carried over from the current system
| # | Module | Current implementation |
|---|---|---|
| 1 | Patient Registry | `personal_information` |
| 2 | Individual Treatment Record (ITR) / General Consultation | `consultation_details`, `visit_information`, `prescriptions` (+ legacy `itr`, `check_ups`) |
| 3 | Prenatal Care (with Trimester visits) | `prenatal_consultation_details`, `prenatal_visit_information`, `general_trimester`, `checkbox1/2/3` (+ legacy `prenatal`) |
| 4 | Postpartum / Delivery | `postpartum` |
| 5 | Expanded Program on Immunization (EPI) child profile | `national_immunization_programs` |
| 6 | Vaccination records and appointments | `vaccination_records`, `vaccine_appointments` |
| 7 | Risk Management (NCD / PhilPEN risk assessment) | `risk_management` (**no migration exists today**) |
| 8 | Mental Health sessions | `mental_health_sessions` (**no migration, not linked to patients**) |
| 9 | Doctor consultation queue | `status` column on consultation tables |
| 10 | Referrals | columns on `consultation_details` |
| 11 | Disease cases / Critical cases | queries over `visit_information.diagnosis` |
| 12 | System analytics and dashboards | `SystemAnalyticsController`, `DashboardController` |
| 13 | Staff management (admin) | `users` with `role`, `status` |
| 14 | CSV import and PDF printing | `import()` methods, jsPDF |

### 1.3 Goals of the rebuild
- **Secure by default:** every patient page and action requires login and the correct role (several routes are public today).
- **One unified encounter/queue model** instead of unioning four tables.
- **Clean, normalized schema** with real foreign keys, enums, and a migration history that runs on an empty database.
- **Validated input** end to end (shared Zod schemas).
- **Audit trail** of who viewed or changed patient records.
- **Deployable on Vercel** with a managed Postgres database and preview environments.

### 1.4 Out of scope (v1)
- Patient-facing portal / patient self-registration.
- Billing, PhilHealth e-Claims submission.
- Offline mode (listed as a future enhancement).
- Native mobile apps (the web app will be responsive).

---

## 2. Tech Stack

### 2.1 Core
| Layer | Technology | Purpose / Reason |
|---|---|---|
| Language | **TypeScript** (strict mode) | Type safety across UI, server, and DB. |
| Framework | **Next.js (App Router)** — latest stable | Native to Vercel; React Server Components + Server Actions replace Laravel controllers. |
| UI library | **React** | Ships with Next.js. |
| Runtime | **Node.js** (Vercel Functions, Node runtime) | Required by the DB driver and auth library. |
| Package manager | **pnpm** | Fast, strict dependency resolution. |

### 2.2 Data
| Layer | Technology | Purpose / Reason |
|---|---|---|
| Database | **PostgreSQL on Neon** (via Vercel Marketplace) | Serverless Postgres, billed through Vercel, database branching for previews. Region: **Singapore (`ap-southeast-1`)**. |
| DB driver | **`@neondatabase/serverless`** | Works in serverless functions with connection pooling. |
| ORM / migrations | **Drizzle ORM + drizzle-kit** | Lightweight, SQL-like, typed schema, versioned SQL migrations. |
| Search | **Postgres `pg_trgm`** extension | Fast fuzzy patient name search. |
| File storage | **Vercel Blob** | Profile pictures, uploaded CSVs, generated reports. |

### 2.3 Auth and security
| Layer | Technology | Purpose / Reason |
|---|---|---|
| Authentication | **Better Auth** (email + password) | Sessions in Postgres, Drizzle adapter, **admin plugin** for roles, ban/deactivate, and impersonation. |
| Password hashing | Better Auth built-in (scrypt) | Secure default. |
| Rate limiting | Better Auth rate limiter + **Upstash Redis** (optional) | Throttle login attempts. |
| Validation | **Zod** | One schema used by forms and server actions. |

### 2.4 UI and UX
| Layer | Technology | Purpose / Reason |
|---|---|---|
| Styling | **Tailwind CSS** | Utility-first, consistent design. |
| Components | **shadcn/ui** (Radix UI primitives) | Accessible dialogs, selects, tabs, date pickers, toasts. |
| Icons | **lucide-react** | Replaces FontAwesome + Heroicons. |
| Forms | **react-hook-form** + `@hookform/resolvers/zod` | Large multi-section clinical forms with validation. |
| Tables | **TanStack Table** | Server-side pagination, sorting, filtering. |
| Charts | **Recharts** (via shadcn/ui charts) | Single chart library (replaces Chart.js + ApexCharts). |
| Dates | **date-fns** | Single date library (replaces moment + date-fns + flatpickr). |
| Toasts | **sonner** | Notifications. |
| URL state | **nuqs** | Filters/pagination stored in the URL (shareable, back-button safe). |

### 2.5 Documents, import, background work
| Layer | Technology | Purpose / Reason |
|---|---|---|
| PDF generation | **@react-pdf/renderer** | Printable patient records, referral slips, prescriptions, reports. |
| CSV parsing | **Papa Parse** (client-side) | Parse in the browser, send validated batches to the server. |
| Excel export (optional) | **SheetJS (xlsx)** or CSV | Report exports. |
| Scheduled jobs | **Vercel Cron Jobs** | Mark missed appointments, appointment reminder list, nightly stats. |
| Email (optional) | **Resend** | Password reset, staff invitations. |
| SMS reminders (optional, future) | Semaphore / Twilio | Vaccination appointment reminders. |

### 2.6 Quality, tooling, monitoring
| Layer | Technology |
|---|---|
| Lint / format | **ESLint** + **Prettier** (or **Biome**) |
| Unit / integration tests | **Vitest** + Testing Library |
| End-to-end tests | **Playwright** |
| CI | **GitHub Actions** (lint, typecheck, test, migration check) |
| Error monitoring | **Sentry** (Next.js SDK) |
| Analytics / performance | **Vercel Analytics** + **Speed Insights** |
| Logs | Vercel Logs (no patient data in logs) |

---

## 3. System Architecture

### 3.1 High-level diagram
```
 Browser (staff, doctor, admin)
        │  HTTPS
        ▼
 ┌──────────────────────── Vercel ────────────────────────┐
 │  Next.js App Router                                     │
 │   ├─ middleware.ts  → session check, redirect to /login │
 │   ├─ Server Components (pages, read data)               │
 │   ├─ Server Actions  (mutations, Zod validation, RBAC)  │
 │   ├─ Route Handlers  (/api/auth/*, /api/cron/*, PDFs)   │
 │   └─ Cron Jobs       (missed appointments, reminders)   │
 └───────────────┬───────────────────────┬────────────────┘
                 │                       │
                 ▼                       ▼
        Neon Postgres (SG)        Vercel Blob
        (Drizzle ORM)             (files, CSVs, PDFs)
```

### 3.2 Layering rules
1. **Pages (Server Components)** call functions in `src/server/<module>/queries.ts` only. No raw DB calls in pages.
2. **Mutations** go through **Server Actions** in `src/server/<module>/actions.ts`. Each action:
   1. gets the session (`requireUser()`),
   2. checks permission (`requirePermission('itr:write')`),
   3. validates input with the shared Zod schema,
   4. writes inside a DB transaction,
   5. writes an `audit_logs` row,
   6. calls `revalidatePath` / returns a typed result.
3. **Zod schemas** live in `src/lib/validators/<module>.ts` and are imported by both client forms and server actions.
4. **Permissions** are defined once in `src/lib/auth/permissions.ts` and checked on the server. The UI only hides buttons; the server enforces.
5. **No patient data in logs.** Log IDs and action names only.

### 3.3 Rendering strategy
- Lists and dashboards: Server Components with server-side pagination (`?page=&q=&barangay=`).
- Forms: Client Components (react-hook-form) submitting to Server Actions.
- Doctor queue: Server Component + `router.refresh()` polling every 15–30 s (simple, no websocket infrastructure needed). Optional upgrade later: Pusher/Ably for real-time.

---

## 4. Roles and Permissions

### 4.1 Roles
| Role | Who | Summary |
|---|---|---|
| `admin` | RHU administrator / IT | Manages staff accounts, settings, lookups; views all analytics and audit logs. Read-only on clinical records by default. |
| `doctor` | Physicians | Works the consultation queue, records diagnosis, lab requests, prescriptions, referrals; completes encounters. |
| `nurse` | Nurses | Registers patients, takes vitals, creates encounters, records vaccinations and risk assessments. |
| `midwife` | Midwives | Same as nurse + full prenatal/postpartum module. |
| `encoder` | Data encoders / BHWs | Registers patients, creates encounters, CSV imports. No diagnosis or prescriptions. |

Account `status`: `active` | `inactive` (deactivated users cannot log in; sessions are revoked on deactivation).

### 4.2 Permission matrix
| Permission | admin | doctor | nurse | midwife | encoder |
|---|:-:|:-:|:-:|:-:|:-:|
| `patient:read` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `patient:write` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `patient:delete` (soft) | ✅ | — | — | — | — |
| `encounter:create` (add to queue) | — | ✅ | ✅ | ✅ | ✅ |
| `vitals:write` | — | ✅ | ✅ | ✅ | ✅ |
| `consultation:write` (diagnosis, labs, Rx) | — | ✅ | — | — | — |
| `encounter:complete` | — | ✅ | — | — | — |
| `encounter:cancel` | — | ✅ | ✅ | ✅ | — |
| `referral:write` | — | ✅ | ✅ | ✅ | — |
| `prenatal:write` | — | ✅ | — | ✅ | — |
| `immunization:write` | — | ✅ | ✅ | ✅ | — |
| `risk:write` | — | ✅ | ✅ | ✅ | — |
| `mental_health:write` | — | ✅ | ✅ | — | — |
| `import:run` | ✅ | — | — | — | ✅ |
| `reports:read` (analytics) | ✅ | ✅ | ✅ | ✅ | — |
| `staff:manage` | ✅ | — | — | — | — |
| `settings:manage` | ✅ | — | — | — | — |
| `audit:read` | ✅ | — | — | — | — |

---

## 5. Database Design

### 5.1 Conventions
- PostgreSQL, `snake_case` table and column names.
- Primary keys: `uuid` (`gen_random_uuid()`), plus human-readable codes where users need them (`patient_no`).
- Every table has `created_at`, `updated_at` (`timestamptz`). Clinical tables also have `created_by` (FK → `user.id`).
- Soft delete via `deleted_at` on `patients` and clinical records; never hard-delete medical history.
- **Ages are computed** from `birthdate`, not stored (the old system stored `age`, which goes stale).
- **Blood pressure is split** into `bp_systolic` / `bp_diastolic` integers (the old system stored text such as `"120/80"`, which cannot be analysed).
- Postgres **enums** for fixed lists; **lookup tables** for lists the admin may edit (barangays, vaccines, lab tests).
- No sentinel values like `"None"` — use `NULL`.

### 5.2 Entity relationship overview
```
user ─┬─< session / account (Better Auth)
      └─1 staff_profiles

barangays ─< patients ─┬─< encounters ─┬─1 vital_signs
                       │               ├─1 consultations ─┬─< encounter_diagnoses
                       │               │                  ├─< lab_requests
                       │               │                  └─< prescriptions
                       │               ├─< referrals
                       │               ├─< prenatal_visits ─< prenatal_visit_checklist
                       │               ├─< vaccinations
                       │               └─1 risk_assessments
                       ├─< pregnancies ─┬─1 prenatal_labs
                       │                ├─< prenatal_visits
                       │                └─1 deliveries ─┬─< newborns
                       │                                └─< postpartum_visits
                       ├─1 immunization_profiles
                       ├─< vaccine_appointments >─ vaccines
                       └─< mental_health_participants >─ mental_health_sessions

audit_logs, import_jobs, facility_settings, lab_tests, vaccines (lookups)
```

### 5.3 Enums
```sql
user_role            : admin | doctor | nurse | midwife | encoder
user_status          : active | inactive
sex                  : male | female
civil_status         : single | married | widowed | separated | live_in
program              : general | prenatal | postpartum | immunization | vaccination | risk | mental_health
encounter_status     : in_queue | in_progress | completed | cancelled | referred_out
mode_of_transaction  : walk_in | emergency | referral
nature_of_visit      : new_consultation | new_admission | follow_up
visit_type           : general | dental_care | child_care | injury | adult_immunization | tuberculosis
                       | child_immunization | sick_children | firecracker_injury | mental_health
referral_direction   : incoming | outgoing
lab_status           : requested | in_progress | resulted | cancelled
philhealth_status    : member | dependent | none
pregnancy_status     : active | delivered | closed
trimester            : first | second | third
delivery_mode        : nsd | cs | assisted
postpartum_visit     : within_24h | within_1_week | other
appointment_status   : scheduled | completed | missed | cancelled
vaccine_category     : child_epi | adult | maternal | other
breastfeeding_status : exclusive | mixed | none
risk_level           : low | moderate | high | very_high
session_status       : scheduled | completed | cancelled
session_type         : individual | group
import_type          : patients | itr | prenatal | immunization | vaccination
import_status        : pending | processing | completed | failed
```

### 5.4 Tables

#### 5.4.1 Auth (managed by Better Auth — Drizzle adapter)
**`user`**
| Column | Type | Notes |
|---|---|---|
| id | text PK | Better Auth id |
| name | text | display name (`first last`) |
| email | text unique | |
| email_verified | boolean | |
| image | text null | Blob URL of profile picture |
| role | user_role | admin plugin field, default `encoder` |
| banned | boolean | admin plugin; used for **deactivation** |
| ban_reason | text null | e.g. "Deactivated by admin" |
| ban_expires | timestamptz null | |
| created_at / updated_at | timestamptz | |

**`session`**, **`account`**, **`verification`** — standard Better Auth tables (generated by `@better-auth/cli generate`).

**`staff_profiles`** (1:1 with `user`)
| Column | Type | Notes |
|---|---|---|
| user_id | text PK, FK → user.id | |
| first_name, middle_name, last_name | text | |
| phone | varchar(13) | |
| purok, barangay_id, city | text / FK | |
| prc_number | text null | required for doctor/nurse/midwife |
| prc_validity | date null | warn admin when expiring |
| specialization | text null | doctors |
| status | user_status | mirrors `banned` for reporting |

#### 5.4.2 Lookups
**`barangays`**: `id serial PK`, `name text unique`, `is_active boolean`. Seeded with the 16 barangays (see Appendix).

**`lab_tests`**: `id serial PK`, `name text unique`, `is_active`. Seed: CBC, Urinalysis, Stool Examination, Blood Sugar Test, Electrolyte Panel, X-ray.

**`vaccines`**
| Column | Type | Notes |
|---|---|---|
| id | serial PK | |
| code | text unique | e.g. `BCG`, `PENTA`, `OPV`, `IPV`, `PCV`, `MMR`, `HEPB`, `TD`, `FLU`, `PNEUMO` |
| name | text | |
| category | vaccine_category | |
| total_doses | smallint | |
| schedule | jsonb | recommended age per dose, e.g. `[{"dose":1,"ageWeeks":6},…]` |
| is_active | boolean | |

**`facility_settings`** (single row): facility name, address, logo URL, municipality, province, report header text.

#### 5.4.3 Patients
**`patients`** (replaces `personal_information`)
| Column | Type | Notes |
|---|---|---|
| id | uuid PK | |
| patient_no | text unique | `RHU-YYYY-000001` (sequence-generated) |
| first_name | varchar(100) | required |
| middle_name | varchar(100) null | |
| last_name | varchar(100) | required |
| suffix | varchar(10) null | |
| sex | sex | |
| birthdate | date | required; age is computed |
| civil_status | civil_status null | |
| contact_no | varchar(15) null | PH mobile format validated |
| purok | varchar(100) null | |
| barangay_id | int FK → barangays | required |
| philhealth_no | varchar(20) null | |
| philhealth_status | philhealth_status null | |
| is_4ps_member | boolean default false | |
| blood_type | varchar(5) null | |
| created_by | text FK → user | |
| created_at / updated_at / deleted_at | timestamptz | |

Indexes: `gin (first_name gin_trgm_ops, last_name gin_trgm_ops)`, `(barangay_id)`, `(last_name, first_name, birthdate)` for duplicate detection.

#### 5.4.4 Encounters (unified visits + queue)
**`encounters`** — one row per visit, for every program. This replaces the four-table union and drives the doctor queue.
| Column | Type | Notes |
|---|---|---|
| id | uuid PK | |
| patient_id | uuid FK → patients | |
| program | program | which module the visit belongs to |
| status | encounter_status default `in_queue` | |
| mode_of_transaction | mode_of_transaction | |
| nature_of_visit | nature_of_visit | |
| visit_type | visit_type null | for general consultations |
| encounter_at | timestamptz | date + time of visit |
| queue_number | int | daily sequence, reset each day |
| assigned_doctor_id | text null FK → user | |
| provider_name | text null | free text for external/legacy provider |
| started_at / completed_at | timestamptz null | |
| cancelled_reason | text null | |
| created_by | text FK → user | |
| created_at / updated_at / deleted_at | timestamptz | |

Indexes: `(status, encounter_at)`, `(patient_id, encounter_at desc)`, `(program, encounter_at)`.

**`vital_signs`** (1:1 with encounter)
| Column | Type |
|---|---|
| encounter_id | uuid PK, FK |
| bp_systolic, bp_diastolic | smallint null |
| temperature_c | numeric(4,1) null |
| height_cm | numeric(5,2) null |
| weight_kg | numeric(5,2) null |
| heart_rate | smallint null |
| respiratory_rate | smallint null |
| oxygen_saturation | smallint null |
| bmi | numeric(5,2) generated (weight / (height/100)²) |

**`referrals`**
| Column | Type | Notes |
|---|---|---|
| id | uuid PK | |
| encounter_id | uuid FK | |
| direction | referral_direction | incoming (referred to RHU) / outgoing (RHU refers out) |
| referred_from | text | facility |
| referred_to | text | facility |
| reason | text | |
| referred_by | text | name of referring provider |
| referred_at | timestamptz | |

#### 5.4.5 General consultation (ITR)
**`consultations`** (1:1 with a `general` encounter; written by the doctor)
| Column | Type |
|---|---|
| encounter_id | uuid PK, FK |
| chief_complaints | text |
| history_of_present_illness | text null |
| physical_exam | text null |
| assessment_notes | text null |
| plan | text null |
| doctor_id | text FK → user |

**`encounter_diagnoses`**
| Column | Type | Notes |
|---|---|---|
| id | uuid PK | |
| encounter_id | uuid FK | |
| icd10_code | varchar(10) null | optional coding |
| label | text | e.g. "Hypertension" |
| is_primary | boolean | |
| is_notifiable | boolean | flags reportable diseases for Disease Cases page |

**`lab_requests`**
| Column | Type |
|---|---|
| id | uuid PK |
| encounter_id | uuid FK |
| lab_test_id | int FK → lab_tests |
| status | lab_status |
| result_text | text null |
| result_file_url | text null (Blob) |
| resulted_at | timestamptz null |

**`prescriptions`**
| Column | Type |
|---|---|
| id | uuid PK |
| encounter_id | uuid FK |
| medication | text |
| dosage | text |
| frequency | text |
| duration | text |
| quantity | text null |
| notes | text null |
| prescribed_by | text FK → user |

#### 5.4.6 Prenatal and postpartum
**`pregnancies`** (one per pregnancy episode; replaces `prenatal_consultation_details` + `prenatal_visit_information`)
| Column | Type | Notes |
|---|---|---|
| id | uuid PK | |
| patient_id | uuid FK | must be female |
| status | pregnancy_status | |
| spouse_name | text null | |
| emergency_contact | varchar(15) null | |
| menarche_age | smallint null | |
| sexual_onset_age | smallint null | |
| period_duration_days | smallint null | |
| interval_cycle_days | smallint null | |
| birth_control_method | text null | |
| menopause | boolean null | |
| lmp | date | last menstrual period |
| edc | date | computed (LMP + 280 days), editable |
| gravidity, parity, term, preterm, abortion, living | smallint | G-P (TPAL) |
| opened_encounter_id | uuid FK → encounters | first prenatal encounter |
| closed_at | timestamptz null | |

**`prenatal_labs`** (1:1 with pregnancy, updatable)
| Column | Type |
|---|---|
| pregnancy_id | uuid PK, FK |
| syphilis_result | text null |
| penicillin_given | boolean null |
| hemoglobin | numeric(5,2) null |
| hematocrit | numeric(5,2) null |
| urinalysis | text null |
| tt_status | text null |
| td_date | date null |

**`prenatal_visits`** (replaces `general_trimester`)
| Column | Type |
|---|---|
| id | uuid PK |
| pregnancy_id | uuid FK |
| encounter_id | uuid FK |
| visit_date | date |
| trimester | trimester (auto-computed from AOG, editable) |
| aog_weeks, aog_days | smallint |
| weight_kg | numeric(5,2) |
| bp_systolic, bp_diastolic | smallint |
| heart_rate | smallint |
| fetal_heart_beat | text null |
| fetal_position | text null |
| presentation | text null |
| fundal_height_cm | numeric(4,1) null |
| folic_acid_tabs | smallint null |
| remarks | text null |

**`prenatal_visit_checklist`** (replaces `checkbox1`, `checkbox2`, `checkbox3`)
| Column | Type | Notes |
|---|---|---|
| prenatal_visit_id | uuid FK | composite PK with item_key |
| item_key | text | e.g. `pe_done`, `birth_plan_done`, `health_teachings`, `reminded_dangers`, `breastfeeding_counseling`, `referred_utz`, `fes04_folic`, `newborn_info`, `family_planning_info`, `nkfda`, `healthy_diet` |
| done | boolean | |

Which items appear for which trimester is defined in code (`src/lib/prenatal/checklists.ts`), so adding an item does not need a migration.

**`deliveries`** (1:1 with pregnancy)
| Column | Type |
|---|---|
| id | uuid PK |
| pregnancy_id | uuid FK unique |
| delivered_at | timestamptz |
| place_of_delivery | text |
| facility_type | text null (RHU, hospital, home, lying-in) |
| delivery_mode | delivery_mode |
| birth_attendant | text |
| outcome | text (live birth, stillbirth, etc.) |

**`newborns`**
| Column | Type |
|---|---|
| id | uuid PK |
| delivery_id | uuid FK |
| patient_id | uuid null FK → patients (when the baby is registered as a patient for EPI) |
| first_name, middle_name, last_name | text |
| sex | sex |
| birth_length_cm | numeric(5,2) |
| birth_weight_kg | numeric(5,2) |
| breastfeeding_initiated_at | timestamptz null |

**`postpartum_visits`**
| Column | Type |
|---|---|
| id | uuid PK |
| delivery_id | uuid FK |
| encounter_id | uuid FK null |
| visit_kind | postpartum_visit |
| visit_date | date |
| vitamin_a_date | date null |
| iron_given_date | date null |
| iron_tabs_count | smallint null |
| danger_signs_mother | text null |
| danger_signs_baby | text null |

#### 5.4.7 Immunization (EPI) and vaccination
**`immunization_profiles`** (1:1 with a child patient; replaces `national_immunization_programs`)
| Column | Type |
|---|---|
| patient_id | uuid PK, FK |
| birthplace | text |
| mother_name | text |
| guardian_name | text null |
| dswd_nhts_no | text null |
| facility_household_no | text null |
| household_no | text null |
| pcb_member | boolean |
| family_member_relation | text null |
| mother_tt_status | text null |
| date_assessed | date |
| place_assessed | text null |

**`vaccinations`** (each dose given; replaces `vaccination_records` + the "given" part of `vaccine_appointments`)
| Column | Type | Notes |
|---|---|---|
| id | uuid PK | |
| patient_id | uuid FK | |
| encounter_id | uuid FK null | |
| vaccine_id | int FK → vaccines | |
| dose_number | smallint | |
| date_given | date | |
| weight_kg, height_cm | numeric(5,2) null | |
| temperature_c | numeric(4,1) null | |
| breastfeeding | breastfeeding_status null | infants |
| lot_number | text null | |
| site | text null | e.g. left thigh |
| administered_by | text FK → user | |
| notes | text null | |

Unique: `(patient_id, vaccine_id, dose_number)` to prevent duplicate doses.

**`vaccine_appointments`**
| Column | Type |
|---|---|
| id | uuid PK |
| patient_id | uuid FK |
| vaccine_id | int FK |
| dose_number | smallint |
| scheduled_date | date |
| status | appointment_status |
| vaccination_id | uuid null FK → vaccinations (set when completed) |
| reminder_sent_at | timestamptz null |
| notes | text null |

#### 5.4.8 Risk management (NCD risk assessment)
**`risk_assessments`** (fields based on the current `RiskManagement` model)
| Column | Type |
|---|---|
| id | uuid PK |
| patient_id | uuid FK |
| encounter_id | uuid FK null |
| assessed_at | date |
| smoker | boolean null |
| alcohol_use | boolean null |
| food_intake | text (e.g. high fat/salt, adequate fruits & vegetables) |
| physical_activity | text (sufficient / insufficient) |
| waist_cm | numeric(5,1) null |
| blood_glucose_done | boolean |
| fbs_rbs_mgdl | numeric(6,2) null |
| blood_glucose_date | date null |
| blood_lipids_done | boolean |
| total_cholesterol | numeric(6,2) null |
| blood_lipids_date | date null |
| urine_ketones_done | boolean |
| urine_ketone_level | text null |
| urine_ketones_date | date null |
| urine_protein_done | boolean |
| urine_protein_level | text null |
| urine_protein_date | date null |
| risk_level | risk_level (computed on save) |
| remarks | text null |

#### 5.4.9 Mental health
**`mental_health_sessions`**
| Column | Type |
|---|---|
| id | uuid PK |
| title | text |
| session_type | session_type |
| scheduled_at | timestamptz |
| facilitator_id | text FK → user |
| status | session_status |
| notes | text null (restricted access) |

**`mental_health_participants`**
| Column | Type |
|---|---|
| session_id | uuid FK (composite PK) |
| patient_id | uuid FK (composite PK) |
| attended | boolean |
| encounter_id | uuid null FK |

#### 5.4.10 System tables
**`audit_logs`**
| Column | Type | Notes |
|---|---|---|
| id | bigserial PK | |
| actor_id | text FK → user | |
| action | text | `patient.view`, `patient.update`, `encounter.complete`, `staff.deactivate`, `import.run`, … |
| entity_type | text | |
| entity_id | text | |
| patient_id | uuid null | for "who accessed this patient" reports |
| changed_fields | text[] null | field names only, **no values** |
| ip_address | inet null | |
| user_agent | text null | |
| created_at | timestamptz | |

**`import_jobs`**
| Column | Type |
|---|---|
| id | uuid PK |
| type | import_type |
| file_name | text |
| file_url | text (Blob) |
| status | import_status |
| total_rows, success_rows, failed_rows | int |
| errors | jsonb (row number + message) |
| created_by | text FK → user |
| created_at / finished_at | timestamptz |

### 5.5 Views for analytics (SQL views or materialized views)
- `v_daily_encounters` — counts by date, program, status.
- `v_patients_by_barangay` — counts by barangay and sex.
- `v_top_diagnoses` — diagnosis counts by month/barangay.
- `v_vaccination_coverage` — fully immunized child (FIC) status per child.
- `v_prenatal_coverage` — pregnancies with ≥4 visits / ≥1 per trimester.
- `v_risk_distribution` — risk level counts.
Refresh materialized views nightly with a Vercel Cron job if needed.

---

## 6. Modules and Features

### 6.1 Authentication and account
- Email + password login; redirect by role (admin → `/admin`, doctor → `/doctor`, others → `/dashboard`).
- Deactivated accounts cannot log in; deactivation revokes active sessions.
- Login rate limiting and lockout message.
- Forgot / reset password via email (Resend) — admins can also reset a staff password.
- Profile page: update name, phone, photo, password.
- Session timeout after inactivity (e.g. 30 minutes) — shared workstations.

### 6.2 Staff management (admin)
- Create staff account (role, PRC number, PRC validity, specialization).
- List active / inactive staff with search and role filter.
- Edit, deactivate, reactivate.
- PRC expiry warnings (30 days before).
- Staff distribution charts (by role).
- View currently active users (sessions in the last N minutes).

### 6.3 Patient registry
- Register patient with **duplicate detection** (same name + birthdate warns before saving).
- Fuzzy search by name, patient number, contact.
- Patient list with filters: barangay, sex, age group, program.
- **Patient profile** page with tabs: Overview, Encounters timeline, Consultations, Prenatal, Immunization, Risk, Mental health, Referrals, Documents.
- Edit demographics (audit logged); soft delete (admin only).
- Print patient record (PDF).

### 6.4 Encounters and queue
- Start an encounter for a patient: choose program, mode of transaction, nature of visit, visit type; record vitals.
- Daily queue number assigned automatically.
- Queue board for staff (today's queue by status).
- Cancel encounter with a reason.
- Doctors pick or are assigned patients; status moves `in_queue → in_progress → completed` (or `cancelled` / `referred_out`).
- End-of-day job marks stale `in_queue` encounters as `cancelled` (replaces the current manual "mark as cancelled").

### 6.5 Doctor workspace
- Doctor dashboard: today's queue, in-progress patient, completed today, counts.
- Consultation screen: patient summary + history sidebar, vitals, chief complaints, findings, diagnoses (multiple, primary flag), lab requests, prescriptions, referral, follow-up date.
- Prenatal consultation screen for prenatal encounters.
- Complete encounter → prints prescription / referral slip (PDF).
- Consultation history list (completed/cancelled) with filters.

### 6.6 General consultation (ITR)
- ITR table: all general encounters with filters (date range, barangay, visit type, diagnosis, status).
- View encounter details modal.
- CSV import of historical ITR records.
- Print ITR form.

### 6.7 Prenatal care
- Open a pregnancy record (obstetric history, menstrual history, labs, spouse/emergency contact). EDC auto-computed from LMP.
- Record prenatal visits; trimester auto-computed from AOG; per-trimester checklist.
- Pregnancy timeline: visits by trimester, labs, warnings (missed visit, high BP, low Hgb).
- Close pregnancy (delivered / closed with reason).
- Prenatal table with filters (barangay, trimester, EDC month, status).
- CSV import; print prenatal record.

### 6.8 Delivery and postpartum
- Record delivery details and newborn(s).
- Option to register the newborn as a patient (pre-fills EPI profile).
- Postpartum visits (24 h, 1 week), Vitamin A, iron supplementation, danger signs.

### 6.9 EPI / immunization
- Child immunization profile (household, mother, guardian, NHTS, PhilHealth).
- Immunization card view: grid of vaccines × doses, given/due/overdue.
- CSV import; print immunization card.

### 6.10 Vaccination and appointments
- Record a vaccine dose (vaccine, dose number, vitals, lot number, site, administered by).
- Auto-suggest next appointment from the vaccine schedule.
- Appointment list: today, upcoming, missed; reschedule, cancel, complete.
- Vaccination history per patient.
- Cron job marks past `scheduled` appointments as `missed` and builds a daily reminder list.
- Optional: SMS reminders.

### 6.11 Risk management (NCD)
- Risk assessment form (lifestyle, glucose, lipids, urine ketones/protein).
- Automatic risk level classification.
- Risk table with filters (barangay, risk level, date).
- Follow-up flag for high-risk patients.

### 6.12 Mental health
- Create sessions (individual/group), add participants (linked to patients), mark attendance and status.
- Session calendar and list.
- Restricted notes (only doctor/nurse roles).

### 6.13 Referrals
- Record incoming and outgoing referrals on any encounter.
- Referred patients table (direction, facility, date, reason).
- Printable referral slip.

### 6.14 Disease cases and critical cases
- Disease cases page: diagnoses filtered by name, barangay, date range; counts and list.
- Notifiable disease flag.
- Critical cases page: e.g. high BP readings, high-risk pregnancies, very high NCD risk, danger signs.

### 6.15 Analytics and dashboards
- **Staff dashboard:** today's encounters, queue length, upcoming appointments.
- **Admin dashboard:** totals (patients, encounters, staff), trends.
- **System analytics page** (date range + barangay filter):
  - Total patients over time and by barangay.
  - Encounters by program and month.
  - Top 10 diagnoses.
  - Referrals (incoming vs outgoing).
  - Vaccinations per month, FIC coverage.
  - Prenatal coverage (≥4 visits), deliveries.
  - NCD risk distribution.
  - Mental health sessions and attendance.
- Export charts' data to CSV; printable monthly summary (optional FHSIS-style monthly report).

### 6.16 Data import / export
- CSV templates downloadable per type.
- Client-side parse and validation preview (shows row errors before submit).
- Batched server inserts (e.g. 200 rows per request) to stay under function time limits.
- Import history with success/failed counts and downloadable error report.
- Export filtered tables to CSV.

### 6.17 Settings (admin)
- Facility info and logo (used on PDFs).
- Manage barangays, lab tests, vaccines and schedules.

### 6.18 Audit log (admin)
- Search by user, patient, action, date.
- "Who accessed this patient" view on the patient profile (admin only).

### 6.19 Public pages
- Landing page (`/`) describing RHU services (replaces the current Home + About pages). **No patient data.**
- Service description pages (ITR, EPI, Prenatal, Vaccination, Risk, Mental health).

---

## 7. End-to-End Flows

### 7.1 Login
```
/login → enter email + password
  → Better Auth verifies credentials (rate limited)
  → banned (inactive)? → "Your account has been deactivated."
  → create session cookie
  → redirect by role: admin → /admin | doctor → /doctor | others → /dashboard
```

### 7.2 General consultation (walk-in)
```
Staff: Search patient → not found → Register patient (duplicate check)
     → New encounter: program=general, mode, nature, visit type
     → Record vitals → Save  → encounter.status = in_queue, queue_number assigned
Doctor: Queue → open patient → status = in_progress
     → Chief complaints, findings, diagnoses, labs, prescriptions, (optional) referral
     → Complete → status = completed, completed_at set
     → Print prescription / referral slip
System: audit_logs rows written at each step
```

### 7.3 Referral in / out
```
Incoming: Staff selects mode_of_transaction = referral → fills referral (direction=incoming)
Outgoing: Doctor adds referral (direction=outgoing) → encounter.status = referred_out → prints referral slip
Both appear in Referred Patients table and analytics.
```

### 7.4 Prenatal → delivery → postpartum → EPI
```
Midwife: patient (female) → New encounter program=prenatal
       → Open pregnancy (LMP → EDC auto, OB history, labs)
       → Each visit: prenatal_visit (AOG → trimester auto) + checklist
       → Doctor may review in queue (prenatal consultation screen)
Delivery: record delivery + newborn(s) → pregnancy.status = delivered
       → optionally register newborn as patient → creates immunization_profile
Postpartum: 24 h and 1 week visits, Vitamin A, iron, danger signs
EPI: newborn follows vaccination schedule (7.5)
```

### 7.5 Vaccination
```
Nurse: patient → New encounter program=vaccination
     → Select vaccine + dose → vitals → save vaccination
     → System suggests next dose date from vaccines.schedule → create vaccine_appointment
Cron (daily): scheduled appointments past date → missed; build today's reminder list
Nurse: appointments page → complete (links vaccination) / reschedule / cancel
```

### 7.6 Risk assessment
```
Nurse: patient → New encounter program=risk → risk form
     → server computes risk_level → save
     → high/very_high → appears in Critical Cases + follow-up list
```

### 7.7 Mental health session
```
Staff: create session (type, date, facilitator) → add participants (patients)
     → after session: mark attendance, status=completed, notes (restricted)
```

### 7.8 Staff lifecycle (admin)
```
Admin: create staff (role, PRC) → staff gets credentials / invite email
     → edit → deactivate (banned=true, sessions revoked) → reactivate
```

### 7.9 CSV import
```
Encoder/Admin: choose import type → download template → upload CSV
  → browser parses (Papa Parse) + Zod validates each row → preview with errors
  → confirm → batches of N rows → server action inserts in transaction per batch
  → import_jobs updated (success/failed, errors) → downloadable error report
```

---

## 8. Pages and Routes Map

Route groups: `(public)`, `(auth)`, `(app)` (requires session). Permission checks happen in each page/action.

| Route | Page | Roles |
|---|---|---|
| `/` | Landing page | public |
| `/services/[slug]` | Service description pages | public |
| `/login`, `/forgot-password`, `/reset-password` | Auth | public |
| `/dashboard` | Staff dashboard | nurse, midwife, encoder |
| `/patients` | Patient list + search | all staff |
| `/patients/new` | Register patient | all staff |
| `/patients/[id]` | Patient profile (tabs) | all staff |
| `/patients/[id]/edit` | Edit patient | all staff |
| `/patients/[id]/print` | Printable record (PDF) | all staff |
| `/encounters/new?patientId=` | Start encounter + vitals | all except admin |
| `/queue` | Today's queue board | all except admin |
| `/doctor` | Doctor dashboard | doctor |
| `/doctor/consult/[encounterId]` | Consultation screen | doctor |
| `/doctor/history` | Completed/cancelled consultations | doctor |
| `/itr` | ITR table | clinical roles |
| `/prenatal` | Prenatal table | doctor, midwife |
| `/prenatal/[pregnancyId]` | Pregnancy record + visits | doctor, midwife |
| `/prenatal/[pregnancyId]/delivery` | Delivery + newborn + postpartum | doctor, midwife |
| `/immunization` | EPI profiles table | clinical roles |
| `/vaccinations` | Vaccination records | clinical roles |
| `/vaccinations/appointments` | Appointments (today/upcoming/missed) | clinical roles |
| `/risk` | Risk assessments | clinical roles |
| `/mental-health` | Sessions list/calendar | doctor, nurse |
| `/referrals` | Referred patients | clinical roles |
| `/cases/disease` | Disease cases | clinical roles, admin |
| `/cases/critical` | Critical cases | clinical roles, admin |
| `/analytics` | System analytics | admin, clinical roles |
| `/imports` | CSV import + history | admin, encoder |
| `/admin` | Admin dashboard | admin |
| `/admin/staff` | Staff (active/inactive) | admin |
| `/admin/staff/new`, `/admin/staff/[id]` | Create / edit staff | admin |
| `/admin/settings` | Facility + lookups | admin |
| `/admin/audit` | Audit log | admin |
| `/profile` | Own profile | all |
| `/api/auth/[...all]` | Better Auth handler | — |
| `/api/cron/appointments` | Mark missed + reminders | Vercel Cron (secret) |
| `/api/cron/queue-cleanup` | Cancel stale queue entries | Vercel Cron (secret) |
| `/api/pdf/[type]/[id]` | PDF generation | permission-checked |

---

## 9. Project Folder Structure

```
rhu-his/
├─ drizzle/                    # generated SQL migrations
├─ public/                     # logo, static images
├─ scripts/
│  ├─ seed.ts                  # barangays, lab tests, vaccines, first admin
│  └─ migrate-legacy.ts        # MySQL → Postgres data migration
├─ src/
│  ├─ app/
│  │  ├─ (public)/             # landing, services
│  │  ├─ (auth)/               # login, forgot/reset password
│  │  ├─ (app)/                # authenticated area (layout with sidebar)
│  │  │  ├─ dashboard/
│  │  │  ├─ patients/
│  │  │  ├─ encounters/
│  │  │  ├─ queue/
│  │  │  ├─ doctor/
│  │  │  ├─ itr/
│  │  │  ├─ prenatal/
│  │  │  ├─ immunization/
│  │  │  ├─ vaccinations/
│  │  │  ├─ risk/
│  │  │  ├─ mental-health/
│  │  │  ├─ referrals/
│  │  │  ├─ cases/
│  │  │  ├─ analytics/
│  │  │  ├─ imports/
│  │  │  ├─ admin/
│  │  │  └─ profile/
│  │  └─ api/
│  │     ├─ auth/[...all]/route.ts
│  │     ├─ cron/…/route.ts
│  │     └─ pdf/[type]/[id]/route.ts
│  ├─ components/
│  │  ├─ ui/                   # shadcn/ui
│  │  ├─ data-table/           # TanStack table wrapper
│  │  ├─ charts/
│  │  ├─ forms/                # shared form fields
│  │  └─ pdf/                  # react-pdf documents
│  ├─ db/
│  │  ├─ index.ts              # Drizzle client (Neon)
│  │  └─ schema/               # one file per module (patients.ts, encounters.ts, …)
│  ├─ lib/
│  │  ├─ auth/                 # Better Auth config, session helpers, permissions.ts
│  │  ├─ validators/           # Zod schemas per module
│  │  ├─ prenatal/             # EDC/AOG/trimester helpers, checklists
│  │  ├─ vaccines/             # schedule helpers
│  │  ├─ risk/                 # risk level calculator
│  │  └─ utils.ts
│  ├─ server/
│  │  ├─ audit.ts
│  │  ├─ patients/{queries,actions}.ts
│  │  ├─ encounters/{queries,actions}.ts
│  │  └─ …                     # one folder per module
│  └─ middleware.ts
├─ tests/
│  ├─ unit/                    # Vitest
│  └─ e2e/                     # Playwright
├─ .env.example
├─ drizzle.config.ts
├─ vercel.json                 # cron schedules, function region (sin1)
└─ README.md
```

---

## 10. Security and Data Privacy (RA 10173)

Patient health data is **sensitive personal information** under the Philippine Data Privacy Act of 2012 (RA 10173).

### 10.1 Access control
- `middleware.ts` redirects any unauthenticated request outside `(public)` and `(auth)` to `/login`.
- Every query/action checks role permissions server-side (`requirePermission`).
- Least privilege: admins do not edit clinical content; encoders cannot diagnose or prescribe.
- Session idle timeout and absolute session lifetime.

### 10.2 Data protection
- TLS everywhere (Vercel), encryption at rest (Neon).
- Secrets only in Vercel Environment Variables; never committed.
- **No patient data in logs**, error reports (Sentry `beforeSend` scrubbing), or analytics events.
- Production database never copied to preview environments; previews use seeded fake data (Neon branch from a seed-only parent).
- Daily backups / point-in-time restore (Neon); test a restore once before go-live.

### 10.3 Application security
- Zod validation on every server action; Drizzle parameterized queries (no SQL injection).
- CSRF protection built into Server Actions; Better Auth cookie settings (`httpOnly`, `secure`, `sameSite=lax`).
- Login rate limiting; generic error messages.
- Security headers (CSP, `X-Frame-Options`, `Referrer-Policy`) via `next.config.ts`.
- File uploads: type/size checks; Blob URLs not publicly listed.
- Dependency updates via Dependabot/Renovate.

### 10.4 Accountability
- `audit_logs` for views and changes of patient data, staff changes, imports, exports.
- Privacy notice and consent capture on patient registration (checkbox + date).
- Data retention policy documented with the RHU.

---

## 11. Data Migration from the Old System

### 11.1 Approach
1. Export the current MySQL database (`mysqldump`).
2. Run `scripts/migrate-legacy.ts`, which reads MySQL (via `mysql2`) and writes Postgres (via Drizzle), in a transaction per table, with a mapping table of old IDs → new UUIDs.
3. Clean data during migration (see rules below) and write a report of rejected/changed rows.
4. Verify counts and spot-check records with RHU staff.

### 11.2 Table mapping
| Old table | New table(s) | Notes |
|---|---|---|
| `users` | `user` + `account` + `staff_profiles` | Bcrypt hashes cannot be reused by default — **force password reset** for all staff (or configure Better Auth to verify bcrypt on first login). `status=inactive` → `banned=true`. `role=staff/user` → `encoder` (admin reassigns). |
| `personal_information` | `patients` | Drop `age`; map `barangay` text → `barangay_id`; `sex` normalized; generate `patient_no`. |
| `consultation_details` | `encounters` (program=general) + `vital_signs` + `referrals` | Split `bloodPressure` "120/80" → systolic/diastolic; `status 'in queue'` → `in_queue`; `consultationDate`+`consultationTime` → `encounter_at`; referral columns → `referrals` row when filled. |
| `visit_information` | `consultations` + `encounter_diagnoses` + `lab_requests` | `diagnosis` text → `encounter_diagnoses.label` (skip `"None"`); `selectedLabTests` JSON → `lab_requests` rows. |
| `prescriptions` | `prescriptions` | Re-link via visit → encounter. |
| `itr`, `check_ups` (legacy) | `encounters` + `consultations` | Only if they contain data not already in `consultation_details`. |
| `prenatal_consultation_details` | `encounters` (program=prenatal) + `vital_signs` + `pregnancies` | One pregnancy per consultation chain (group by patient + LMP). |
| `prenatal_visit_information` | `pregnancies` (history fields) + `prenatal_labs` | Convert string numbers (`gravidity` etc.) to integers. |
| `prenatal` (legacy) | same as above | Only if not duplicated. |
| `general_trimester` | `prenatal_visits` | `bp` text split; `aog_months` → weeks (approx.) flagged for review. |
| `checkbox1/2/3` | `prenatal_visit_checklist` | One row per true checkbox; fix typo `compliane_routine` → `compliance_routine`. |
| `postpartum` | `deliveries` + `newborns` + `postpartum_visits` | Split delivery, baby, and the 24 h / 1 week visit dates. |
| `national_immunization_programs` | `immunization_profiles` (+ patient fields such as blood type, PhilHealth) | |
| `vaccination_records` | `vaccinations` (+ `vaccine_appointments` from `nextAppointment`) | Map `vaccineType`/`antigenGiven` text → `vaccines.id`; infer dose numbers by date order. |
| `vaccine_appointments` | `vaccinations` (given) + `vaccine_appointments` | |
| `risk_management` | `risk_assessments` | |
| `mental_health_sessions` | `mental_health_sessions` | No patient link exists; migrate as sessions without participants. |
| `sessions`, `cache`, `jobs`, `password_reset_tokens` | — | Not migrated. |

---

## 12. Testing Strategy

| Level | Tool | What is tested |
|---|---|---|
| Unit | Vitest | EDC/AOG/trimester calculation, vaccine schedule, risk level calculator, BP parsing, Zod schemas, permission matrix. |
| Integration | Vitest + Neon test branch (or local Postgres via Docker) | Server actions: create patient, start encounter, complete consultation, prenatal visit, vaccination + appointment, import batch; permission denials. |
| End-to-end | Playwright | Login per role; staff registers patient → doctor completes consultation; prenatal flow; vaccination + appointment; admin deactivates staff and that user can no longer log in; unauthenticated user cannot open `/patients`. |
| Access control | Playwright / integration | Every `(app)` route returns redirect for guests and 403 for wrong role. |
| Accessibility | `@axe-core/playwright` | Key pages have no critical violations. |
| Performance | Vercel Speed Insights | Patient list and analytics load under ~2 s with 50k encounters (seeded). |

CI (GitHub Actions) on every PR: `pnpm lint`, `pnpm typecheck`, `pnpm test`, `drizzle-kit check`, Playwright against the Vercel preview URL.

---

## 13. Deployment on Vercel

### 13.1 One-time setup
1. Create a GitHub repository for the new app and push the Next.js project.
2. In Vercel: **Add New → Project → Import** the repository (framework auto-detected: Next.js).
3. **Storage → Marketplace → Neon** → create a Postgres database in **Singapore**, connect it to the project (adds `DATABASE_URL` env vars automatically). Enable the Neon **preview branching** integration so each preview deployment gets its own database branch.
4. **Storage → Blob** → create a store and connect it (adds `BLOB_READ_WRITE_TOKEN`).
5. Set the function region to **`sin1`** (Singapore) in `vercel.json` or Project Settings → Functions.
6. Add environment variables (below) for Production and Preview.
7. Run migrations and seed the first admin (`pnpm db:migrate && pnpm db:seed`) — or run migrations automatically in the build step (`"build": "drizzle-kit migrate && next build"`).
8. (Optional) Add a custom domain.

### 13.2 Environment variables
| Variable | Purpose |
|---|---|
| `DATABASE_URL` | Neon pooled connection (auto from integration) |
| `DATABASE_URL_UNPOOLED` | Direct connection for migrations |
| `BETTER_AUTH_SECRET` | Random 32+ byte secret |
| `BETTER_AUTH_URL` | Production URL |
| `BLOB_READ_WRITE_TOKEN` | Vercel Blob (auto from integration) |
| `CRON_SECRET` | Protects `/api/cron/*` |
| `RESEND_API_KEY` | Email (optional) |
| `SENTRY_DSN` | Error monitoring (optional) |
| `SEED_ADMIN_EMAIL`, `SEED_ADMIN_PASSWORD` | First admin (seed only, remove after) |

### 13.3 `vercel.json` (example)
```json
{
  "regions": ["sin1"],
  "crons": [
    { "path": "/api/cron/appointments", "schedule": "0 22 * * *" },
    { "path": "/api/cron/queue-cleanup", "schedule": "0 16 * * *" }
  ]
}
```
(Cron times are UTC: 22:00 UTC = 06:00 PH time; 16:00 UTC = 00:00 PH time.)

### 13.4 Environments
| Environment | Branch | Database |
|---|---|---|
| Production | `main` | Neon main branch |
| Preview | any PR branch | Neon branch per preview (seeded fake data only) |
| Local | — | Local Postgres (Docker) or a personal Neon branch |

### 13.5 Plan note
The Vercel **Hobby** plan is intended for personal, non-commercial use — fine for the capstone demo. If the RHU will use the system in production, review Vercel's current terms; the **Pro** plan is likely required. Check Neon and Vercel plan limits (storage, function duration, cron frequency) against expected usage.

---

## 14. Implementation Phases and Timeline

Estimated for 1–2 developers. Adjust to your schedule.

| Phase | Duration | Deliverables |
|---|---|---|
| **0. Setup** | Week 1 | Next.js + TS + Tailwind + shadcn/ui; Drizzle + Neon; Better Auth with roles; middleware; app layout + sidebar; CI; first Vercel deploy; seed script (barangays, lab tests, vaccines, admin). |
| **1. Staff & patients** | Weeks 2–3 | Staff management; patient registry (register, duplicate check, search, list, profile, edit); audit logging helper; landing page. |
| **2. Encounters & doctor** | Weeks 4–5 | Encounters + vitals + queue; doctor dashboard; consultation screen (diagnoses, labs, prescriptions, referrals); ITR table; referral table; PDFs (prescription, referral slip). |
| **3. Prenatal & postpartum** | Weeks 6–7 | Pregnancy record, visits with trimester checklists, labs, delivery, newborns, postpartum visits; prenatal table; prenatal PDF. |
| **4. EPI & vaccination** | Week 8 | Immunization profiles; vaccinations; schedule-based appointments; appointment management; cron for missed appointments; immunization card PDF. |
| **5. Risk & mental health** | Week 9 | Risk assessment + calculator; risk table; mental health sessions + participants. |
| **6. Cases & analytics** | Week 10 | Disease cases, critical cases, dashboards, analytics page with filters and CSV export. |
| **7. Import & migration** | Week 11 | CSV import (all types) with preview and error report; legacy MySQL migration script and verification. |
| **8. Hardening & launch** | Week 12 | Full E2E suite, access-control tests, security headers, Sentry, performance pass, backup/restore test, user training, go-live. |

---

## 15. Definition of Done and Acceptance Criteria

A feature is **done** when:
- [ ] Server action validates input with Zod and checks permissions.
- [ ] Unauthenticated access redirects to `/login`; wrong role gets 403.
- [ ] Audit log row written for reads/writes of patient data.
- [ ] Unit/integration tests cover the logic; an E2E test covers the main path.
- [ ] Works on a phone-width screen and a desktop.
- [ ] No patient data in logs or error reports.
- [ ] Deployed to a preview URL and reviewed.

Go-live acceptance:
- [ ] All modules in Section 6 implemented.
- [ ] Legacy data migrated with counts verified and signed off by the RHU.
- [ ] Every staff account created/reset; first-login password change enforced.
- [ ] Backups enabled and a restore tested.
- [ ] Privacy notice and consent flow in place.

---

## 16. Risks and Mitigations

| Risk | Impact | Mitigation |
|---|---|---|
| Unreliable internet at the RHU | Staff cannot work | Keep pages light; optimistic UI; future: offline PWA with sync. |
| Messy legacy data (free-text BP, `"None"` diagnoses, string numbers) | Bad analytics | Cleaning rules in migration script + review report. |
| Serverless function time limits on big imports | Failed imports | Client-side parsing and batched inserts. |
| Password hashes not portable | Staff locked out | Force password reset / bcrypt verification on first login. |
| Scope creep | Missed deadline | Phase-based delivery; v1 scope in Section 1. |
| Vercel/Neon plan limits or cost | Downtime / bills | Monitor usage; upgrade plan before go-live. |
| Privacy breach | Legal and patient harm | RBAC, audit logs, no PHI in logs, seeded-only previews, training. |

---

## 17. Appendix: Lookup Values

### 17.1 Barangays (seed)
Apas, Aluna, Andales, Calacapan, Gimangpang, Jampason, Kamelon, Kanitoan, Oguis, Pagahan, Poblacion, Pontacon, San Pedro, Sinalac, Tawantawan, Tubigan.

### 17.2 Mode of transaction
Walk-in (Consultation), Emergency, Referral.

### 17.3 Nature of visit
New Consultation/Case, New Admission, Follow-Up Visit.

### 17.4 Visit type (purpose of visit)
General, Dental Care, Child Care, Injury, Adult Immunization, Tuberculosis, Child Immunization, Sick Children, Firecracker Injury, Mental Health.

### 17.5 Lab tests (seed)
Complete Blood Count (CBC), Urinalysis, Stool Examination, Blood Sugar Test, Electrolyte Panel, X-ray.

### 17.6 Prenatal checklist items (from current checkbox tables)
| Item key | 1st tri | 2nd tri | 3rd tri |
|---|:-:|:-:|:-:|
| prenatal_checkup | ✅ | | ✅ |
| pe_done | ✅ | | ✅ |
| prenatal_record | ✅ | ✅ | ✅ |
| birth_plan_done | ✅ | | |
| nkfda | ✅ | | |
| health_teachings | ✅ | ✅ | ✅ |
| referred_for | ✅ | | |
| healthy_diet | ✅ | ✅ | ✅ |
| fes04_folic | ✅ | ✅ | ✅ |
| reminded_importance | | ✅ | ✅ |
| reminded_dangers | | ✅ | ✅ |
| breastfeeding_counseling | | ✅ | ✅ |
| compliance_routine | | ✅ | ✅ |
| referred_utz | | ✅ | ✅ |
| newborn_info | | | ✅ |
| family_planning_info | | | ✅ |

### 17.7 Calculations
- **EDC** = LMP + 280 days (Naegele's rule).
- **AOG** = (visit date − LMP) in weeks + days.
- **Trimester**: 1st < 14 weeks, 2nd 14–27 weeks, 3rd ≥ 28 weeks.
- **BMI** = weight (kg) / height (m)².
- **Age** = computed from birthdate at the time of display/encounter.
- **Risk level**: rules defined with the RHU (based on PhilPEN protocol); implemented in `src/lib/risk/calculate.ts` with unit tests.
