# OdontoLead AI

Micro-SaaS B2B multi-tenant para clínicas odontológicas captarem, qualificarem e agendarem leads vindos de tráfego pago. A experiência pública combina triagem clínica, seleção segura de horário e encaminhamento qualificado para o WhatsApp da clínica.

> **Status atual: desenvolvimento (`v0.4.0`).** O projeto ainda não está pronto para uso em produção. As Fases 1 a 4 estão concluídas.

## Estado da implementação

| Fase | Situação |
| --- | --- |
| 1. Setup e base de dados | Concluída |
| 2. Models, tenancy e scopes | Concluída |
| 3. Autenticação e middlewares | Concluída |
| 4. Core Services | Concluída |
| 5. Módulo SuperAdmin | Pendente |
| 6. Módulo da clínica | Pendente |
| 7. Formulário público | Pendente |
| 8. Testes críticos do MVP | Pendente |

O andamento detalhado e a ordem obrigatória de execução ficam em [`TASKS.md`](TASKS.md). A primeira versão de produção será `v1.0.0`, após a conclusão e validação integral do MVP.

## Escopo do MVP

- Painel global do SuperAdmin para clínicas, planos, configurações, releases e webhooks do Mercado Pago.
- Painel da clínica com agenda, agendamentos, novidades e configuração BYOK para Gemini ou OpenAI.
- Landing page pública por slug, com formulário Livewire multi-step e encaminhamento para WhatsApp.
- Triagem híbrida: IA quando houver chave configurada e fallback determinístico quando não houver.
- Banco único com isolamento rigoroso por tenant.
- Prevenção de agendamentos duplicados por transação, bloqueio pessimista e índice único.

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
vendor/bin/pint --test
npm run build
composer audit --locked
npm audit --audit-level=moderate
```

Na versão atual, as migrations também foram validadas com execução, rollback e reaplicação em MySQL/InnoDB.

## Segurança de acesso implementada

- `/admin` exige autenticação e perfil SuperAdmin.
- `/app` e o `/dashboard` legado exigem clínica com assinatura ativa ou trial válido.
- Releases publicadas e ainda não lidas são detectadas por usuário e preparadas para o modal global da Fase 6.
- Policies impedem acesso cruzado entre clínicas em agendamentos, horários, datas bloqueadas, triagens e usuários.
- Leituras de releases são privadas por usuário; apenas SuperAdmin pode gerenciar releases.

## Core Services implementados

- Agendamento centralizado em transação, com bloqueios pessimistas, validação integral da agenda e proteção contra horários duplicados.
- Triagem por OpenAI ou Gemini usando a chave BYOK da clínica, saída estruturada e fallback determinístico quando a IA não estiver configurada ou disponível.
- Recepção idempotente de eventos de assinatura do Mercado Pago, consulta da assinatura, atualização transacional da clínica e processamento assíncrono por Job criptografado.
- Registro do ciclo de processamento em `payment_logs`, sem persistir credenciais ou cabeçalhos sensíveis.

## Arquitetura obrigatória

- Regras de negócio em Service Classes, nunca em Controllers, rotas ou Views.
- Validação de entrada em Form Requests dedicados.
- Tabelas de domínio isoladas por `BelongsToTenant` e `TenantScope`.
- SuperAdmin operando fora do escopo de tenant apenas nas rotas protegidas de `/admin`.
- Agendamentos concorrentes usando `DB::transaction()` e `lockForUpdate()`.
- `Clinic::ai_api_key` armazenada com cast `encrypted`.
- Formulário público funcional mesmo sem chave de IA.

## Publicação

Não publique a versão atual como aplicação de produção. As proteções de autenticação, assinatura e autorização multi-tenant e os Core Services já existem, mas os painéis, o formulário público, o endpoint HTTP autenticado do webhook e os testes finais de concorrência e idempotência ainda não foram implementados.

Uma VPS de **staging**, sem usuários reais e com acesso restrito, pode ser preparada antecipadamente para validar PHP, MySQL, servidor web, SSL, filas e processo de deploy. A publicação para clientes deve ocorrer somente quando:

1. Todas as tarefas de `TASKS.md` estiverem concluídas.
2. Os testes de isolamento multi-tenant, concorrência e idempotência estiverem passando.
3. O ambiente de produção estiver configurado com `APP_ENV=production`, `APP_DEBUG=false`, HTTPS, worker de filas e backups.
4. A release `v1.0.0` estiver registrada e validada.

## Documentação

- [`PRD.md`](PRD.md): requisitos de negócio e regras.
- [`DATABASE-SCHEMA.md`](DATABASE-SCHEMA.md): estrutura relacional.
- [`MVP-SCOPE.md`](MVP-SCOPE.md): limites do MVP.
- [`TASKS.md`](TASKS.md): checklist sequencial de implementação.
- [`VERSOES.md`](VERSOES.md): histórico e política de versões.

## Licença

O OdontoLead AI é um software proprietário da [KL Tecnologia](https://kltecnologia.com). Todos os direitos são reservados e nenhuma permissão de uso, cópia, modificação ou distribuição é concedida sem autorização prévia e expressa da titular.

Consulte o arquivo [`LICENSE`](LICENSE) para os termos aplicáveis ao projeto. O framework Laravel e as demais dependências de terceiros permanecem sujeitos às suas respectivas licenças.
