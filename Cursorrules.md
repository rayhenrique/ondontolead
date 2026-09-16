# Contexto e Regras Operacionais para a IA (OdontoLead AI)

## Stack e Ferramentas
- Versão Principal: Laravel 13.x (PHP 8.3+), MySQL 8.0+
- Frontend Reativo: Livewire v3, Tailwind CSS v4, Alpine.js
- Padrão Visual: Estilo SaaS Clean (Inspirado em Filament/Tailwind UI).

## Padrões Arquiteturais Exigidos
1. NUNCA adicione lógicas pesadas, queries longas ou tratamento de APIs externas diretamente nas Views ou Controllers.
2. Utilize Services Layer (`App\Services\...`) para regras de negócio complexas como `AppointmentBookingService` e `AiTriageService`.
3. Use Form Requests (`App\Http\Requests\...`) para validação em rotas web regulares, e validação integrada `#[Validate]` em componentes Livewire.
4. Consultas escopadas de tenants DEVEM usar a Trait/Scope apropriada. O acesso do SuperAdmin NUNCA obedece ao escopo de tenant.
5. Sempre proteja persistências concorrentes (como slots de agendamento) com `DB::transaction()` e travas no banco.

## Sistema de Changelog e Git
1. Todo push/commit que incluir features visíveis deve ter seu equivalente sugerido para a tabela `app_releases`.
2. A aplicação deve injetar automaticamente modais na tela inicial (via componente global Livewire ou Alpine) se houver nova release não lida.

## Regras de Workflow (TASKS.md)
1. Antes de iniciar qualquer tarefa, leia o arquivo `TASKS.md`.
2. Execute passo a passo rigorosamente. 
3. Ao concluir um subitem, atualize o arquivo `TASKS.md` marcando a caixa `[x]`.