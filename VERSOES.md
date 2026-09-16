# Controle de Versões — OdontoLead AI

Este arquivo registra releases do produto, mudanças relevantes, estado de validação e eventuais ações necessárias para atualização do ambiente.

## Política de versionamento

O projeto usa versionamento semântico no formato `MAJOR.MINOR.PATCH`:

- **MAJOR:** versão estável ou mudança incompatível com versões anteriores.
- **MINOR:** novo módulo ou funcionalidade compatível.
- **PATCH:** correção ou ajuste compatível, sem nova funcionalidade relevante.

Enquanto o MVP não estiver completo, as versões permanecem em `0.x`. A versão `v1.0.0` representa o primeiro MVP validado e apto para produção.

Cada nova release deve informar:

- data e status da versão;
- tarefas ou módulos incluídos;
- migrations e mudanças de configuração;
- incompatibilidades conhecidas;
- comandos ou cuidados necessários no deploy;
- validações executadas.

## Sincronização com o Módulo "Novidades"

O controle de versão é orientado pelos commits e pushes no repositório. Para refletir todo o histórico de versões deste arquivo (`VERSOES.md`) na interface do aplicativo — tanto no módulo **Novidades** da clínica (`/app/novidades`) quanto no painel do SuperAdmin (`/admin/releases`) — execute:

```bash
php artisan releases:sync
```

O comando lê automaticamente o `VERSOES.md`, extrai cada release com título, resumo e itens adicionados, e atualiza a tabela `app_releases`. O seeder `AppReleaseSeeder` também realiza essa sincronização automaticamente durante a instalação e deploy.

## Não lançado

### Planejado (Pós-MVP)

- Múltiplas agendas e cadeiras odontológicas por clínica.
- Cobrança de consultas diretamente do paciente via checkout transparente.
- Chatbot bidirecional no WhatsApp para confirmação e reagendamento automático.
## v1.1.0 — 2026-09-16

**Status:** Landing Page Institucional modular, redesign completo de autenticação e Painel SuperAdmin com Sidebar concluídos; apta para produção.

### Adicionado

- **Landing Page Institucional e Pública (`welcome.blade.php`):**
  - Layout dedicado [`LandingLayout`](file:///c:/Users/rayhe/Downloads/ondontolead/app/View/Components/LandingLayout.php) com tipografia Google Fonts (Plus Jakarta Sans e Inter) e Tailwind CSS v4.
  - 8 componentes anônimos modulares em `resources/views/components/landing/`: Header Glassmorphic com drawer mobile, Hero com mockup CSS flutuante em GSAP, Ticker contínuo Marquee, Comparativo Problema vs. Solução, Jornada do paciente em 4 etapas, Calculadora de ROI reativa em Alpine.js, Cards de Planos e FAQ Accordion.
  - Destaque em toda a experiência para a oferta de **14 dias de teste grátis sem cartão de crédito**.
  - Rodapé oficial com menção de copyright e link seguro para a **KL Tecnologia** (`kltecnologia.com`).
- **Redesign de Autenticação (`/login` e `/register`):**
  - Alinhamento total com a estética *High-Tech Editorial Light*, glows ambientais, novos campos estilizados e links de retorno seguro.
- **Painel SuperAdmin com Sidebar Lateral (`/admin`):**
  - Migração da topbar para barra lateral (Sidebar) fixa no desktop (`w-64`) e drawer deslizante no mobile com Alpine.js.
  - Novo layout dedicado [`AdminLayout`](file:///c:/Users/rayhe/Downloads/ondontolead/app/View/Components/AdminLayout.php).
  - Modernização completa dos módulos de Dashboard (KPIs de MRR e agendamentos), Gestão de Clínicas, Planos, Releases e Configurações Globais.

### Validação

- 193 testes automatizados aprovados (0 falhas).
- Formatação de código alinhada ao Laravel Pint.
- Build de produção gerado com sucesso pelo Vite.

### Changelog sugerido para `app_releases`

- **Versão:** `v1.1.0`
- **Título:** Landing Page Institucional, Redesign de Autenticação e SuperAdmin com Sidebar
- **Resumo:** Nova página inicial de alta conversão com Motion Design em GSAP, calculadora de ROI em Alpine.js, redesign das telas de login/cadastro e painel do SuperAdmin com layout Sidebar responsiva.

## v1.0.0 — 2026-09-16

**Status:** Primeiro MVP 100% concluído, validado por suíte automatizada e apto para produção.

### Adicionado

- Conclusão da **Fase 8 (Testes Críticos do MVP)**:
  - **Isolamento Rigoroso de Banco de Dados (`TenantDatabaseIsolationTest`):**
    - Verificação de isolamento cruzado: Tenant A não pode visualizar, filtrar nem mutar consultas, triagens, horários de atendimento, bloqueios e chaves BYOK do Tenant B.
    - Teste de comportamento fail-closed: acessos desautenticados ou sem contexto de tenant têm injeção automática de `clinic_id = 0`, impedindo qualquer vazamento de registros.
    - Teste de governança do SuperAdmin: bypass legítimo do escopo para visualização e gerenciamento global do SaaS.
  - **Concorrência e Prevenção de Double-Booking (`BookingConcurrencyTest`):**
    - Agendamentos simultâneos para o mesmo horário exato processados via transação pessimista (`lockForUpdate()`), garantindo que apenas 1 obtenha sucesso e o concorrente receba exceção de horário indisponível.
    - Garantia dupla no nível de engine de banco (MySQL InnoDB) através do índice de unicidade `UNIQUE(clinic_id, scheduled_at)`.
    - Simulação concorrente via componente Livewire `ClinicBookingWizard`.
    - Garantia de que clínicas distintas agendando o mesmo horário exato não colidem nem sofrem interferência.
  - **Idempotência de Webhook do Mercado Pago (`MercadoPagoWebhookIdempotencyTest`):**
    - Disparos duplicados da mesma notificação HTTP via `POST /api/webhooks/mercadopago` respondem HTTP 200 sem duplicar registros em `payment_logs`.
    - Job em fila `ProcessMercadoPagoWebhookJob` detecta se o log já foi processado e encerra com sucesso sem chamar API externa do gateway.
    - Eventos com tipos não suportados são registrados e marcados como processados sem gerar erros ou reprocessamentos infinitos.
    - Rejeição estrita (HTTP 400) com verificação de assinatura HMAC antes de enfileirar jobs ou gravar logs.
  - **Período de Testes Gratuito (Trial de 14 Dias Sem Cartão):**
    - Novas clínicas cadastradas recebem automaticamente 14 dias de acesso irrestrito a todos os recursos da plataforma, sem necessidade de informar cartão de crédito no momento do cadastro.
    - Banner informativo com contador de dias restantes e aviso no painel da clínica (`/app`).
    - Formulário público de registro (`/register`) com auto-provisionamento de clínica e landing page (`welcome.blade.php`) destacando a oferta de 14 dias grátis.

### Validação

- **188 testes automatizados** executados (181 aprovados, 7 condicionais ignorados, 0 falhas e 0 erros), totalizando **639 asserções**.
- Formatação de código validada e corrigida com Laravel Pint (`vendor/bin/pint --format agent`).
- Compilação dos assets com Vite e Tailwind CSS concluída com sucesso (`npm run build`).
- Auditoria de segurança aprovada.

### Changelog sugerido para `app_releases`

- **Versão:** `v1.0.0`
- **Título:** Release Oficial de Produção do MVP — Isolamento, Concorrência e Webhooks
- **Resumo:** primeiro MVP 100% concluído e validado, com isolamento multi-tenant fail-closed de banco de dados, agendamento concorrente sob transação pessimista e processamento idempotente de webhooks do Mercado Pago.

### Deploy em Produção

- O MVP está formalmente aprovado e pronto para implantação em produção.
- Requisitos para deploy:
  1. Configurar variáveis de ambiente de produção (`APP_ENV=production`, `APP_DEBUG=false`, chave gerada com `php artisan key:generate`).
  2. Banco de dados MySQL 8+ com engine InnoDB configurado com charset `utf8mb4`.
  3. Executar migrations: `php artisan migrate --force`.
  4. Executar seeders iniciais (Planos, SuperAdmin, Release `v1.0.0`): `php artisan db:seed --force`.
  5. Iniciar worker permanente de filas para o processamento de webhooks: `php artisan queue:work --queue=webhooks,default --tries=4 --timeout=30`.
  6. Configurar webhooks do Mercado Pago apontando para a URL pública HTTPS: `https://[dominio]/api/webhooks/mercadopago`.

## v0.7.0 — 2026-09-16

**Status:** Módulo Público (`/{slug}`), Wizard Multi-step em Livewire, Triagem Híbrida e Transbordo para WhatsApp concluídos; não apta para produção.

### Adicionado

- Landing Page pública da clínica identificada pelo slug na URL (`/{slug}`), com design responsivo mobile-first, paleta escura com destaques em esmeralda, badges de autoridade e rodapé white-label.
- Controlador público [`PublicClinicBookingController.php`](file:///c:/Users/rayhe/Downloads/ondontolead/app/Http/Controllers/PublicClinicBookingController.php) com tratamento de slug não encontrado (404) e aviso de clínica com agendamentos pausados se a assinatura estiver inadimplente ou cancelada.
- Componente Livewire multi-step [`ClinicBookingWizard.php`](file:///c:/Users/rayhe/Downloads/ondontolead/app/Livewire/Public/ClinicBookingWizard.php) e view reativa [`clinic-booking-wizard.blade.php`](file:///c:/Users/rayhe/Downloads/ondontolead/resources/views/livewire/public/clinic-booking-wizard.blade.php):
  - **Etapa 1 (Dados):** Coleta e validação de nome do paciente e telefone WhatsApp.
  - **Etapa 2 (Sintomas):** Descrição da queixa principal, seletor visual interativo de nível de dor (0 a 10), chips de sinais de alerta (inchaço, sangramento, trauma, febre, dificuldade respiratória) e histórico médico/alergias.
  - **Etapa 3 (Triagem):** Execução do `AiTriageService` (OpenAI / Gemini via BYOK ou fallback determinístico), com exibição de nível de urgência colorido, resumo explicativo e procedimento odontológico sugerido.
  - **Etapa 4 (Horários):** Cálculo dinâmico de horários disponíveis (`AppointmentBookingService::getAvailableSlots`), filtrando dias bloqueados, intervalos de almoço, horários passados e vagas já preenchidas, com seleção interativa de slot.
  - **Etapa 5 (Confirmação):** Criação atômica em transação pessimista do `Appointment` e do `TriageRecord`, exibição de comprovante e botão de ação para abertura direta do WhatsApp com mensagem pré-formatada completa.
- Atualização do [`AppointmentBookingService`](file:///c:/Users/rayhe/Downloads/ondontolead/app/Services/AppointmentBookingService.php) com cálculo de slots livres em tempo real e criação atômica de `TriageRecord` durante a reserva.
- 8 novos testes de Feature em `tests/Feature/Public/ClinicPublicBookingTest.php` cobrindo acesso anônimo, validações de etapas, cálculo de slots e bloqueios, prevenção de colisões de reserva concorrente e geração de link do WhatsApp.

### Validação

- 174 testes executados: 167 aprovados e 7 condicionais ignorados, totalizando 570 asserções.
- Formatação Pint executada e aprovada.
- Build do Vite concluído sem erros.

### Changelog sugerido para `app_releases`

- **Versão:** `v0.7.0`
- **Título:** Landing Page Pública, Triagem Interativa e Agendamento Online
- **Resumo:** disponibilização da página pública da clínica por slug com formulário multi-step em Livewire, triagem prévia inteligente de sintomas, seleção de horários em tempo real e transbordo qualificado para o WhatsApp.

### Limitações conhecidas

- Os testes finais de homologação, carga/concorrência e validação do MVP pertencem à Fase 8.

### Deploy

Não publicar esta versão para usuários finais. Em staging, o link público `/{slug}` já pode ser divulgado para testes com pacientes reais.

## v0.6.0 — 2026-09-16

**Status:** Módulo Tenant / Clínica (`/app`), Grade com Livewire, Gestão de Agendamentos & Triagens, BYOK de IA e Modal de Releases concluídos; não apta para produção.

### Adicionado

- Dashboard da clínica (`/app`) com métricas em tempo real (hoje, próximos 7 dias, mês, contadores por status), link público de agendamento e alerta onboarding de grade inativa.
- Componente Livewire `ScheduleManager` (`/app/grade`) gerenciando a grade semanal (dias 0 a 6, início, fim, intervalos e duração do slot) e bloqueio de feriados/recessos com motivo.
- Painel de Agendamentos e Triagens (`/app/agendamentos`) com abas por status, busca por texto (nome/telefone), filtro por data, detalhamento de triagem com IA (nível de dor, queixa e resumo), link direto para WhatsApp e atualização de status.
- Configurações da Clínica (`/app/configuracoes`) com validação de unicidade de slug, atualização de WhatsApp e gerenciador BYOK de IA (Gemini, OpenAI ou Fallback padrão) com chave criptografada em AES-256.
- Módulo de Novidades In-App (`/app/novidades`) com histórico de releases em Markdown e ação de marcação de leitura.
- Componente global Livewire `AppReleaseModal` integrado ao layout principal (`layouts/app.blade.php`), com fechamento assíncrono e cumprimento estrito da Regra RN03 (modal exibido apenas uma vez por usuário por release).
- Menus de navegação do Jetstream integrados para clínicas com alternância para painel admin quando acessado por SuperAdmin.
- 29 novos testes de Feature (`tests/Feature/Clinic/`) cobrindo Dashboard, Grade, Agendamentos, Configurações e Changelog.

### Validação

- 166 testes executados: 159 aprovados e 7 condicionais ignorados, totalizando 534 asserções.
- Formatação Pint executada e aprovada.
- Build do Vite concluído sem erros.

### Changelog sugerido para `app_releases`

- **Versão:** `v0.6.0`
- **Título:** Painel da Clínica, Grade de Horários e Gestão de Pacientes
- **Resumo:** módulo completo da clínica com dashboard operacional, gestão de grade semanal e bloqueios em Livewire, acompanhamento de agendamentos com triagem IA, configurações BYOK e modal interativo de novidades.

### Limitações conhecidas

- O formulário público multi-step de agendamento (`/{slug}`) pertence à Fase 7.
- Os testes finais de concorrência e carga do MVP pertencem à Fase 8.

### Deploy

Não publicar esta versão para usuários finais. Em staging, os gestores de clínica já podem configurar horários, chaves de IA e gerenciar seus agendamentos.

## v0.5.0 — 2026-09-16

**Status:** Módulo SuperAdmin, CRUDs de Clínicas, Planos, Releases, Configurações e Webhook HTTP concluídos; não apta para produção.

### Adicionado

- Dashboard global do SuperAdmin (`/admin`) com métricas em tempo real (MRR projetado, clínicas por status, agendamentos do mês e lista recente).
- Gestão completa de Clínicas/Tenants com filtros, busca textual, criação com usuário gestor inicial e ajuste manual de status e prorrogação de trial.
- Sistema de Personificação (Impersonation) para o SuperAdmin acessar o painel de qualquer clínica com banner de alerta e botão de retorno seguro.
- CRUD de Planos com limites mensais, precificação e bloqueio de exclusão quando vinculado a clínicas existentes.
- Módulo de Novidades e Changelog (`app_releases`) com suporte a Markdown, controle de trigger para modais e contador de leituras por usuário.
- `SystemSettingService` com cache persistente (`Cache::rememberForever()`) e tela de configurações globais.
- Endpoint HTTP `POST /api/webhooks/mercadopago` com validação de assinatura HMAC e integração ao serviço idempotente em fila.
- Menus de navegação dedicados ao SuperAdmin no Jetstream e banner responsivo de impersonation.
- 26 novos testes de Feature cobrindo todas as áreas administrativas e o endpoint HTTP de webhook.

### Validação

- 137 testes executados: 130 aprovados e 7 condicionais ignorados, totalizando 437 asserções.
- Formatação Pint executada e aprovada.
- Build do Vite concluído sem erros.

### Changelog sugerido para `app_releases`

- **Versão:** `v0.5.0`
- **Título:** Painel Global do SuperAdmin e Gestão do SaaS
- **Resumo:** módulo SuperAdmin com métricas financeiras (MRR), gestão de clínicas com impersonation, CRUD de planos, changelog de releases e configurações globais.

### Limitações conhecidas

- O painel da clínica (`/app`) com grade de horários e gestão de agendamentos pertence à Fase 6.
- O formulário público multi-step de triagem e agendamento pertence à Fase 7.
- Os testes finais de concorrência e homologação pertencem à Fase 8.

### Deploy

Não publicar esta versão para usuários finais. Em staging, o SuperAdmin já pode cadastrar planos e clínicas para homologação.

## v0.4.0 — 2026-09-16

**Status:** Core Services de agendamento, triagem e assinatura concluídos; não apta para produção.

### Adicionado

- `AppointmentBookingService` com transação, bloqueios pessimistas e validação de agenda, intervalo, duração, bloqueios, passado e conflito de horário.
- Exceção de domínio para indisponibilidade de horário e proteção complementar pelo índice único do banco.
- Contrato, DTOs e adaptadores de triagem estruturada para OpenAI e Gemini usando a chave BYOK criptografada da clínica.
- Fallback determinístico de triagem para ausência de chave, provedor indisponível ou resposta inválida.
- Cliente do Mercado Pago com limites explícitos de conexão e resposta.
- Serviço idempotente de webhook com hash canônico do evento, consulta da assinatura e atualização transacional da clínica.
- Job de webhook único, criptografado, executado após commit e configurado com tentativas e backoff.
- Testes de serviço, integrações HTTP simuladas, validações de agendamento, fallback de IA, reprocessamento e idempotência.

### Ajustado

- Configuração e `.env.example` documentados para modelos de IA, timeouts e credenciais do Mercado Pago.
- Licenciamento proprietário formalizado em nome da KL Tecnologia, com atualização do `README.md`, metadados do Composer e arquivo `LICENSE`.

### Validação

- 111 testes executados: 104 aprovados e 7 condicionais ignorados, totalizando 325 asserções.
- Formatação Pint aprovada.
- Auditoria Composer sem vulnerabilidades conhecidas.
- `composer.json` validado, mantendo apenas o aviso já existente sobre a restrição exata do Jetstream.

### Changelog sugerido para `app_releases`

- **Versão:** `v0.4.0`
- **Título:** Agendamento seguro, triagem híbrida e assinaturas
- **Resumo:** serviços transacionais de agendamento, triagem com IA e fallback determinístico, além do processamento idempotente das assinaturas do Mercado Pago.

### Limitações conhecidas

- Os painéis e formulários que consomem estes serviços pertencem às Fases 5 a 7.
- O endpoint HTTP e a verificação da assinatura do webhook do Mercado Pago pertencem à Fase 5.
- Os testes finais de concorrência real em MySQL e idempotência pela camada HTTP pertencem à Fase 8.

### Deploy

Não publicar esta versão para usuários reais. Em staging, configure as credenciais do Mercado Pago e mantenha um worker para a fila `webhooks`; as chaves de IA continuam sendo fornecidas individualmente por clínica.

## v0.3.0 — 2026-09-15

**Status:** autenticação, bloqueio por assinatura e autorização multi-tenant concluídos; não apta para produção.

### Adicionado

- Middleware `IsSuperAdmin` e rota protegida `/admin`.
- Middleware `TenantSubscription` em `/app` e no `/dashboard` legado, com suporte a assinatura ativa, trial vigente e bypass de SuperAdmin.
- Middleware `CheckUnreadReleases`, que seleciona a release publicada mais recente ainda não lida pelo usuário e disponibiliza seu ID na sessão.
- Rotas-base nomeadas `admin.dashboard` e `app.dashboard` para os módulos das próximas fases.
- Policies para clínicas, agendamentos, horários, datas bloqueadas, triagens, usuários, releases e recibos de leitura.
- Bypass centralizado de Policies para SuperAdmin e negação explícita de acesso entre tenants.
- Testes de autenticação, assinatura, detecção de releases e matriz de permissões.

### Validação

- 76 testes executados: 69 aprovados e 7 condicionais ignorados, totalizando 210 asserções.
- Rotas `/admin`, `/app` e `/dashboard` verificadas com seus middlewares.
- Formatação Pint aprovada.
- Auditoria Composer sem vulnerabilidades conhecidas.

### Changelog sugerido para `app_releases`

- **Versão:** `v0.3.0`
- **Título:** Segurança de acesso e isolamento por clínica
- **Resumo:** proteção das áreas administrativa e da clínica, bloqueio por situação da assinatura e detecção individual de novidades.

### Limitações conhecidas

- As rotas protegidas ainda exibem a tela-base; os painéis completos pertencem às Fases 5 e 6.
- O modal visual de novidades será implementado na Fase 6.4.
- Serviços transacionais, triagem e webhook da Fase 4 ainda não implementados.
- Formulário público e testes críticos finais do MVP ainda pendentes.

### Deploy

Não publicar esta versão para usuários reais. Uso permitido somente em desenvolvimento local ou staging restrito.

## v0.2.0 — 2026-09-15

**Status:** fundação de dados e tenancy concluída; não apta para produção.

### Adicionado

- Seeders idempotentes para planos, SuperAdmin e release inicial `v1.0.0`.
- Configuração segura das credenciais iniciais do SuperAdmin por variáveis de ambiente.
- `TenantScope` fail-closed baseado em autenticação ou `tenant_id` de sessão.
- Bypass explícito do escopo para SuperAdmin.
- Associação automática e protegida de `clinic_id` em Models tenant-aware.
- Models, casts, proteção de mass assignment e relacionamentos Eloquent para todas as tabelas do domínio.
- Cast `encrypted` e ocultação de `Clinic::ai_api_key` na serialização.
- Factories e estados para planos, clínicas, usuários, agenda, triagem, releases e logs.
- Testes de seeders, casts, criptografia, relacionamentos e isolamento por tenant.

### Validação

- 43 testes executados: 36 aprovados e 7 condicionais ignorados.
- Migrations e seeders reaplicados com sucesso em MySQL 8.4/InnoDB.
- Formatação Pint aprovada.
- Auditoria Composer sem vulnerabilidades conhecidas.

### Changelog sugerido para `app_releases`

- **Versão:** `v0.2.0`
- **Título:** Fundação de Dados, Tenancy e Models
- **Resumo:** isolamento multi-tenant com BelongsToTenant e TenantScope fail-closed, models de domínio, relacionamentos Eloquent e cast encrypted para chaves de IA.

### Limitações conhecidas

- Middlewares e Policies da Fase 3 ainda não implementados.
- Serviços transacionais, triagem e webhook da Fase 4 ainda não implementados.
- Interfaces SuperAdmin, clínica e formulário público ainda não implementadas.
- Testes críticos finais do MVP ainda pendentes.

### Deploy

Não publicar esta versão para usuários reais. Uso permitido somente em desenvolvimento local ou staging restrito.

## v0.1.0 — 2026-09-15

**Status:** base de desenvolvimento; não apta para produção.

### Adicionado

- Projeto Laravel 13 com Jetstream e Livewire 3.
- Pipeline frontend com Tailwind CSS 4 e Vite 8.
- Configuração MySQL 8 com engine InnoDB.
- Migrations ordenadas para planos, clínicas, usuários, agenda, triagem, releases, configurações e logs de pagamento.
- Restrições de unicidade para slug, horários, datas bloqueadas, leitura de releases e eventos de pagamento.
- Chaves estrangeiras e exclusões em cascata definidas conforme o domínio.
- Documentação de requisitos, esquema, escopo e checklist sequencial.

### Validação

- Migrations executadas, revertidas e reaplicadas com sucesso em MySQL 8.4/InnoDB.
- Suíte inicial do Laravel/Jetstream aprovada.
- Formatação Pint aprovada.
- Build Vite concluído.
- Auditorias Composer e npm sem vulnerabilidades conhecidas no momento da release.

### Changelog sugerido para `app_releases`

- **Versão:** `v0.1.0`
- **Título:** Base de Desenvolvimento e Setup Inicial
- **Resumo:** estruturação inicial do projeto com Laravel 13, Jetstream, Livewire 3, Tailwind CSS 4 e migrations relacionais em MySQL InnoDB.

### Limitações conhecidas

- Seeders da Fase 1 ainda não implementados.
- Isolamento multi-tenant e Models de domínio ainda não implementados.
- Serviços de agendamento, triagem e webhook ainda não implementados.
- Módulos SuperAdmin, clínica e formulário público ainda não implementados.
- Testes críticos do MVP ainda pendentes.

### Deploy

Não publicar esta versão para usuários reais. Uso permitido somente em desenvolvimento local ou staging restrito.
