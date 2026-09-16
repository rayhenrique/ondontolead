# OdontoLead AI

Micro-SaaS B2B multi-tenant para clínicas odontológicas captarem, qualificarem e agendarem leads vindos de tráfego pago. A experiência pública combina triagem clínica, seleção segura de horário e encaminhamento qualificado para o WhatsApp da clínica.

> **Status atual: Produção / MVP Concluído (`v1.0.0`).** O projeto está 100% implementado, testado e apto para uso em produção. Todas as 8 fases do roadmap foram concluídas e validadas por suíte automatizada de ponta a ponta.

## Estado da implementação

| Fase | Situação |
| --- | --- |
| 1. Setup e base de dados | Concluída |
| 2. Models, tenancy e scopes | Concluída |
| 3. Autenticação e middlewares | Concluída |
| 4. Core Services | Concluída |
| 5. Módulo SuperAdmin | Concluída |
| 6. Módulo da clínica | Concluída |
| 7. Formulário público | Concluída |
| 8. Testes críticos do MVP | Concluída |

O andamento detalhado e o checklist completo ficam registrados em [`TASKS.md`](TASKS.md). O histórico de versões e releases está documentado em [`VERSOES.md`](VERSOES.md).

## Escopo do MVP

- Painel global do SuperAdmin para clínicas, planos, configurações, releases e webhooks do Mercado Pago.
- Painel da clínica com agenda, agendamentos, novidades e configuração BYOK para Gemini ou OpenAI.
- Landing page pública por slug, com formulário Livewire multi-step e encaminhamento para WhatsApp.
- Triagem híbrida: IA quando houver chave configurada e fallback determinístico quando não houver.
- Banco único com isolamento rigoroso por tenant.
- Prevenção de agendamentos duplicados por transação, bloqueio pessimista e índice único.
- Período de testes gratuito (trial) de 14 dias sem exigência de cartão de crédito para novas clínicas cadastradas.

Não fazem parte do MVP: múltiplas agendas por clínica, cobrança do paciente, chatbot bidirecional no WhatsApp e sincronização com Google Calendar.

## Stack

- PHP 8.3+
- Laravel 13
- Livewire 3 e Jetstream
- MySQL 8.0+ com InnoDB
- Tailwind CSS 4
- Vite 8
- PHPUnit 12

As versões resolvidas das dependências estão registradas em `composer.lock` e `package-lock.json`.

## Requisitos locais

- PHP 8.3 ou superior com `pdo_mysql`
- Composer 2
- Node.js e npm
- MySQL 8.0 ou superior

O banco precisa utilizar InnoDB, pois o projeto depende de transações, chaves estrangeiras e bloqueios pessimistas.

## Instalação local

```bash
git clone https://github.com/rayhenrique/ondontolead.git
cd ondontolead
composer install
npm ci
cp .env.example .env
php artisan key:generate
```

Configure no `.env` uma base MySQL dedicada:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=odontolead
DB_USERNAME=root
DB_PASSWORD=
DB_ENGINE=InnoDB
```

Em seguida:

```bash
php artisan migrate
npm run build
php artisan serve
```

O arquivo `.env` contém segredos e não deve ser versionado.

Para exercitar as integrações da Fase 4, configure também as variáveis aplicáveis ao ambiente. As chaves de IA não são globais: cada clínica fornece sua própria chave, armazenada criptografada no banco.

```dotenv
AI_CONNECT_TIMEOUT=3
AI_TIMEOUT=12
OPENAI_TRIAGE_MODEL=gpt-5-mini
GEMINI_TRIAGE_MODEL=gemini-3.5-flash

MERCADO_PAGO_ACCESS_TOKEN=
MERCADO_PAGO_WEBHOOK_SECRET=
```

O processamento assíncrono dos webhooks depende de um worker de filas ativo:

```bash
php artisan queue:work --queue=webhooks,default --tries=4 --timeout=30
```

## Validação
 
```bash
php artisan test
vendor/bin/pint --format agent
npm run build
composer audit --locked
npm audit --audit-level=moderate
```

Na versão de produção (`v1.0.0`), a suíte automatizada conta com **188 testes executados (181 aprovados, 7 condicionais ignorados e 639 asserções)** cobrindo:
1. **Isolamento de Banco de Dados Multi-tenant:** impossibilidade de Tenant A ler ou gravar dados do Tenant B em agendamentos, triagens, horários, bloqueios e chaves BYOK de IA, além de comportamento seguro fail-closed.
2. **Concorrência e Prevenção de Double-Booking:** agendamentos simultâneos sob transação pessimista (`lockForUpdate()`) e integridade dupla no MySQL (`UNIQUE(clinic_id, scheduled_at)`).
3. **Idempotência de Webhook:** validação de assinatura HMAC no Mercado Pago, canonical hash em `payment_logs` e proteção contra requisições duplicadas.
4. **Módulos Administrativo e da Clínica:** governança global SuperAdmin, impersonation, dashboard da clínica, grade Livewire e novidades in-app (RN03).
5. **Módulo Público de Agendamento:** wizard multi-step reativo com triagem híbrida e transbordo qualificado para WhatsApp.

## Segurança de acesso implementada

- `/admin` exige autenticação e perfil SuperAdmin.
- `/app` e o `/dashboard` legado exigem clínica com assinatura ativa ou trial válido.
- Releases publicadas e ainda não lidas são detectadas por usuário e exibidas via modal interativo do Livewire (RN03).
- Policies e `TenantScope` impedem acesso cruzado entre clínicas em agendamentos, horários, datas bloqueadas, triagens e usuários.
- Leituras de releases são privadas por usuário; apenas SuperAdmin pode gerenciar releases.
- A página pública `/{slug}` opera com isolamento atômico e previne qualquer interferência entre tenants.

## Core Services implementados

- Agendamento centralizado em transação, com bloqueios pessimistas, validação integral da agenda e proteção contra horários duplicados.
- Triagem por OpenAI ou Gemini usando a chave BYOK da clínica, saída estruturada e fallback determinístico quando a IA não estiver configurada ou disponível.
- Recepção idempotente de eventos de assinatura do Mercado Pago, consulta da assinatura, atualização transacional da clínica e processamento assíncrono por Job criptografado.
- Registro do ciclo de processamento em `payment_logs`, sem persistir credenciais ou cabeçalhos sensíveis.

## Módulo SuperAdmin implementado

- **Dashboard Global (`/admin`):** visualização em tempo real de MRR projetado, clínicas ativas, em trial e inadimplentes, além de agendamentos mensais e clínicas recentes.
- **Gestão de Clínicas (Tenants):** listagem com paginação e filtros, busca textual, cadastro transacional com gestor inicial, edição de dados cadastrais, alteração de plano e prorrogação de trial.
- **Personificação (Impersonation):** capacidade de acessar diretamente o painel da clínica com banner superior persistente para retorno rápido ao SuperAdmin.
- **Gestão de Planos:** CRUD de planos com limites mensais de agendamentos, precificação e integridade que impede a exclusão de planos em uso.
- **Releases e Changelog (`app_releases`):** publicação de notas de atualização em Markdown com gatilho de modal global e auditoria de leitura por usuário.
- **Configurações do Sistema (`system_settings`):** parametrização visual (nome, logo, favicon, rodapé) com cache persistente.
- **Endpoint HTTP de Webhook:** rota `POST /api/webhooks/mercadopago` com validação de assinatura HMAC e despacho assíncrono.

## Módulo da Clínica implementado (`/app`)

- **Dashboard da Clínica (`/app`):** métricas operacionais (agendamentos de hoje, próximos 7 dias, total do mês e contadores por status), link público de agendamento e alerta de onboarding caso a grade semanal não esteja configurada.
- **Grade & Horários (`/app/grade`):** componente Livewire `ScheduleManager` para gestão interativa dos dias da semana (0 a 6), horários de abertura/fechamento, intervalo de almoço, duração customizada dos slots e bloqueio de feriados/recessos com motivo.
- **Gestão de Agendamentos & Triagens (`/app/agendamentos`):** listagem paginada com abas de status rápido (pendente, confirmado, concluído, cancelado, não compareceu), filtros textuais por paciente/telefone, filtro por data, detalhamento de dor, queixa e resumo clínico gerado por IA, link direto para WhatsApp e atualização de status em tempo real.
- **Configurações da Clínica (`/app/configuracoes`):** edição de dados públicos, validação de slug com garantia de unicidade, canal do WhatsApp e configuração BYOK para Google Gemini e OpenAI com armazenamento seguro da chave de API em AES-256 (`encrypted`).
- **Novidades In-App & Modal Changelog (`/app/novidades` e `AppReleaseModal`):** página de histórico de novidades em Markdown e modal global Livewire exibido automaticamente no primeiro acesso após uma nova release, respeitando a Regra RN03 (exibição única por usuário). O histórico completo é sincronizado a partir de `VERSOES.md` via `php artisan releases:sync`.

## Módulo Público e Formulário implementado (`/{slug}`)

- **Landing Page da Clínica (`/{slug}`):** página de alta conversão responsiva (mobile-first), com identidade visual da clínica, badges de confiança e proteção contra clínicas com assinaturas canceladas/inadimplentes.
- **Formulário Multi-step em Livewire (`ClinicBookingWizard`):**
  - *Etapa 1 (Identificação):* captura de nome completo e WhatsApp com validações de formato.
  - *Etapa 2 (Sintomas & Dor):* queixa principal detalhada, seletor de escala de dor de 0 a 10 e lista de verificação de sinais de alerta (inchaço, sangramento, trauma, febre, dificuldade respiratória).
  - *Etapa 3 (Resultado da Triagem):* diagnóstico prévio com classificação de urgência, resumo explicativo e procedimento odontológico sugerido gerado por IA (ou fallback inteligente).
  - *Etapa 4 (Escolha de Horário):* calendário com seleção de data e grade de horários livres em tempo real, calculados por `AppointmentBookingService::getAvailableSlots`.
  - *Etapa 5 (Confirmação & WhatsApp):* gravação atômica da consulta e do registro de triagem sob lock pessimista no MySQL, com botão de transbordo direto para o WhatsApp da recepção com mensagem pré-formatada.

## Arquitetura obrigatória

- Regras de negócio em Service Classes, nunca em Controllers, rotas ou Views.
- Validação de entrada em Form Requests dedicados.
- Tabelas de domínio isoladas por `BelongsToTenant` e `TenantScope`.
- SuperAdmin operando fora do escopo de tenant apenas nas rotas protegidas de `/admin`.
- Agendamentos concorrentes usando `DB::transaction()` e `lockForUpdate()`.
- `Clinic::ai_api_key` armazenada com cast `encrypted`.
- Formulário público funcional mesmo sem chave de IA.

## Publicação em Produção

O MVP `v1.0.0` está homologado e pronto para implantação em produção. Passos recomendados:

1. Configurar o ambiente com `APP_ENV=production`, `APP_DEBUG=false`, HTTPS obrigatório e chaves de segurança geradas via `php artisan key:generate`.
2. Provisionar MySQL 8 com engine InnoDB e charset `utf8mb4`.
3. Executar migrations e seeders em produção:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```
4. Subir o worker contínuo de filas com supervisor:
   ```bash
   php artisan queue:work --queue=webhooks,default --tries=4 --timeout=30
   ```
5. Cadastrar a URL de Webhook no painel do Mercado Pago: `https://[seu-dominio]/api/webhooks/mercadopago` com a chave secreta correspondente.

## Documentação

- [`PRD.md`](PRD.md): requisitos de negócio e regras.
- [`DATABASE-SCHEMA.md`](DATABASE-SCHEMA.md): estrutura relacional.
- [`MVP-SCOPE.md`](MVP-SCOPE.md): limites do MVP.
- [`TASKS.md`](TASKS.md): checklist sequencial de implementação.
- [`VERSOES.md`](VERSOES.md): histórico e política de versões.

## Licença

O OdontoLead AI é um software proprietário da [KL Tecnologia](https://kltecnologia.com). Todos os direitos são reservados e nenhuma permissão de uso, cópia, modificação ou distribuição é concedida sem autorização prévia e expressa da titular.

Consulte o arquivo [`LICENSE`](LICENSE) para os termos aplicáveis ao projeto. O framework Laravel e as demais dependências de terceiros permanecem sujeitos às suas respectivas licenças.
