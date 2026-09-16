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

## Não lançado

### Planejado

- Fases 5 a 8 do checklist de implementação.
- Release de produção `v1.0.0` após aprovação de todos os critérios do MVP.

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

### Limitações conhecidas

- Seeders da Fase 1 ainda não implementados.
- Isolamento multi-tenant e Models de domínio ainda não implementados.
- Serviços de agendamento, triagem e webhook ainda não implementados.
- Módulos SuperAdmin, clínica e formulário público ainda não implementados.
- Testes críticos do MVP ainda pendentes.

### Deploy

Não publicar esta versão para usuários reais. Uso permitido somente em desenvolvimento local ou staging restrito.
