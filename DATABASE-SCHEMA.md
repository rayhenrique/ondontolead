# Database Schema Specification - MySQL 8.0+

## Entidades e Relacionamentos Base
* `plans` 1 -> N `clinics`
* `clinics` 1 -> N `users`
* `clinics` 1 -> N `clinic_schedules`
* `clinics` 1 -> N `clinic_blocked_dates`
* `clinics` 1 -> N `appointments`
* `appointments` 1 -> 1 `triage_records`
* `app_releases` 1 -> N `user_release_reads` (Relaciona com `users`)
* Tabelas de sistema soltas: `system_settings`, `payment_logs`

## Definição Detalhada das Tabelas

### 1. `plans`
- `id` (BIGINT UNSIGNED, PK, Auto Increment)
- `name` (VARCHAR 100, NOT NULL)
- `slug` (VARCHAR 100, NOT NULL, UNIQUE)
- `price` (DECIMAL 10,2, NOT NULL)
- `max_appointments_per_month` (INT UNSIGNED, DEFAULT 100)
- `mp_plan_id` (VARCHAR 100, NULL) - Integração Mercado Pago
- `is_active` (BOOLEAN, DEFAULT TRUE)
- Timestamps

### 2. `clinics` (Tenants)
- `id` (BIGINT UNSIGNED, PK, Auto Increment)
- `plan_id` (BIGINT UNSIGNED, FK -> plans.id)
- `name` (VARCHAR 150, NOT NULL)
- `slug` (VARCHAR 100, NOT NULL, UNIQUE)
- `whatsapp_number` (VARCHAR 20, NOT NULL)
- `ai_provider` (ENUM 'none', 'gemini', 'openai', DEFAULT 'none')
- `ai_api_key` (TEXT, NULL) - Cast Encrypted no Laravel
- `subscription_status` (ENUM 'trial', 'active', 'past_due', 'canceled', DEFAULT 'trial')
- `trial_ends_at` (TIMESTAMP, NULL)
- `mp_subscription_id` (VARCHAR 100, NULL)
- Timestamps, SoftDeletes

### 3. `users`
- `id` (BIGINT UNSIGNED, PK, Auto Increment)
- `clinic_id` (BIGINT UNSIGNED, NULL, FK -> clinics.id ON DELETE CASCADE)
- `name` (VARCHAR 150, NOT NULL)
- `email` (VARCHAR 150, NOT NULL, UNIQUE)
- `password` (VARCHAR 255, NOT NULL)
- `is_superadmin` (BOOLEAN, DEFAULT FALSE)
- Timestamps

### 4. `clinic_schedules`
- `id` (BIGINT UNSIGNED, PK)
- `clinic_id` (BIGINT UNSIGNED, FK -> clinics.id ON DELETE CASCADE)
- `day_of_week` (TINYINT UNSIGNED) - 0 a 6
- `start_time` (TIME, NOT NULL)
- `end_time` (TIME, NOT NULL)
- `break_start` (TIME, NULL)
- `break_end` (TIME, NULL)
- `slot_duration_minutes` (SMALLINT UNSIGNED, DEFAULT 30)
- `is_active` (BOOLEAN, DEFAULT TRUE)
- UNIQUE INDEX: (clinic_id, day_of_week)
- Timestamps

### 5. `clinic_blocked_dates`
- `id` (BIGINT UNSIGNED, PK)
- `clinic_id` (BIGINT UNSIGNED, FK -> clinics.id ON DELETE CASCADE)
- `blocked_date` (DATE, NOT NULL)
- `reason` (VARCHAR 150, NULL)
- UNIQUE INDEX: (clinic_id, blocked_date)
- Timestamps

### 6. `appointments`
- `id` (BIGINT UNSIGNED, PK)
- `clinic_id` (BIGINT UNSIGNED, FK -> clinics.id ON DELETE CASCADE)
- `patient_name` (VARCHAR 150, NOT NULL)
- `patient_phone` (VARCHAR 20, NOT NULL)
- `scheduled_at` (DATETIME, NOT NULL)
- `status` (ENUM 'pending', 'confirmed', 'completed', 'canceled', 'no_show', DEFAULT 'pending')
- `notes` (TEXT, NULL)
- UNIQUE INDEX: (clinic_id, scheduled_at)
- Timestamps

### 7. `triage_records`
- `id` (BIGINT UNSIGNED, PK)
- `appointment_id` (BIGINT UNSIGNED, UNIQUE, FK -> appointments.id ON DELETE CASCADE)
- `raw_complaint` (TEXT, NOT NULL)
- `pain_level` (TINYINT UNSIGNED, DEFAULT 0)
- `urgency_level` (ENUM 'low', 'medium', 'high', DEFAULT 'low')
- `suggested_procedure` (VARCHAR 150, NULL)
- `ai_summary` (TEXT, NULL)
- `processed_by_ai` (BOOLEAN, DEFAULT FALSE)
- Timestamps

### 8. `app_releases` (Sistema de Novidades)
- `id` (BIGINT UNSIGNED, PK)
- `version` (VARCHAR 20, UNIQUE, NOT NULL) - Ex: v1.0.0
- `title` (VARCHAR 150, NOT NULL)
- `content` (TEXT, NOT NULL) - Markdown
- `show_modal` (BOOLEAN, DEFAULT TRUE)
- `released_at` (TIMESTAMP, NOT NULL)
- Timestamps

### 9. `user_release_reads`
- `id` (BIGINT UNSIGNED, PK)
- `user_id` (BIGINT UNSIGNED, FK -> users.id ON DELETE CASCADE)
- `app_release_id` (BIGINT UNSIGNED, FK -> app_releases.id ON DELETE CASCADE)
- `read_at` (TIMESTAMP, NOT NULL)
- UNIQUE INDEX: (user_id, app_release_id)

### 10. `system_settings`
- `key` (VARCHAR 50, PK)
- `value` (LONGTEXT, NULL)
- Timestamps

### 11. `payment_logs`
- `id` (BIGINT UNSIGNED, PK, Auto Increment)
- `event_id` (VARCHAR 100, NOT NULL, UNIQUE) - Prevenção de duplicidade
- `payload` (JSON, NOT NULL)
- `status` (VARCHAR 50, NOT NULL) - 'processed', 'failed'
- `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)