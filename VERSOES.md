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

- Fases 3 a 8 do checklist de implementação.
- Release de produção `v1.0.0` após aprovação de todos os critérios do MVP.

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
