# Checklist de Execução Sequencial - OdontoLead AI

- [x] **Fase 1: Setup e Base de Dados (Laravel 13.x)**
  - [x] 1.1 Iniciar projeto Laravel com Livewire v3 e Breeze/Jetstream.
  - [x] 1.2 Configurar `.env` e conexão MySQL.
  - [x] 1.3 Criar Migrations estritamente na ordem das dependências: `plans`, `clinics`, `users`, tabelas da agenda, `app_releases`, configurações.
  - [x] 1.4 Criar Seeders (Planos iniciais, SuperAdmin, Primeira Release `v1.0.0`).

- [x] **Fase 2: Models, Tenancy e Scopes**
  - [x] 2.1 Criar Trait `BelongsToTenant` com `TenantScope` baseado na session/auth.
  - [x] 2.2 Configurar Models e `$fillable`. Cast de `ai_api_key` em `Clinic` para `encrypted`.
  - [x] 2.3 Implementar relacionamentos do Eloquent e factory states.

- [x] **Fase 3: Autenticação e Middlewares**
  - [x] 3.1 Middleware `IsSuperAdmin` protegendo a rota `/admin`.
  - [x] 3.2 Middleware `TenantSubscription` limitando acesso a clínicas bloqueadas.
  - [x] 3.3 Middleware `CheckUnreadReleases` detectando novas atualizações para disparar o modal via Livewire.
  - [x] 3.4 Policies para garantir que Tenant A não acesse dados do Tenant B.

- [x] **Fase 4: Core Services**
  - [x] 4.1 `AppointmentBookingService`: Uso de `DB::transaction()` e bloqueio pessimista para agendamentos.
  - [x] 4.2 `AiTriageService`: Integração agnóstica Gemini/OpenAI e fluxo de fallback com questionário fixo.
  - [x] 4.3 `MercadoPagoWebhookService`: Job em Queue para tratar assinaturas garantindo idempotência com `payment_logs`.

- [x] **Fase 5: Módulo SuperAdmin (`/admin`)**
  - [x] 5.1 Dashboard (métricas gerais) e controle de `system_settings` em cache.
  - [x] 5.2 CRUD de Tenants (Clínicas) com funcionalidade de Impersonation.
  - [x] 5.3 CRUD de Planos e tela de Gestão de Novidades (Releases).

- [ ] **Fase 6: Módulo Tenant / Clínica (`/app`)**
  - [ ] 6.1 Dashboard da clínica.
  - [ ] 6.2 Componente Livewire de gerenciamento de grade de horário.
  - [ ] 6.3 Gestão de Agendamentos e configurações (BYOK de IA, slug).
  - [ ] 6.4 Página `/app/novidades` e componente global de Modal de Changelog.

- [ ] **Fase 7: Módulo Público e Formulário (`/{slug}`)**
  - [ ] 7.1 Landing Page da clínica escopada pelo slug.
  - [ ] 7.2 Formulário Multi-step em Livewire (Dados, Queixa, Retorno da IA, Slots livres).
  - [ ] 7.3 Conclusão do agendamento disparando redirect para link do WhatsApp com mensagem pronta.

- [ ] **Fase 8: Testes Automatizados**
  - [ ] 8.1 Teste de Isolamento de DB: Request do Tenant A não afeta Tenant B.
  - [ ] 8.2 Teste de Concorrência: Agendamento simultâneo do mesmo horário.
  - [ ] 8.3 Teste de Webhooks: Idempotência nas assinaturas.
