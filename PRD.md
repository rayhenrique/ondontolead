# Product Requirements Document (PRD) - OdontoLead AI

## 1. Visão Geral do Produto
O **OdontoLead AI** é um micro-SaaS multi-tenant B2B desenvolvido para clínicas odontológicas e dentistas autônomos que investem em tráfego pago. A aplicação substitui a perda de leads no WhatsApp por uma landing page de alta conversão contendo triagem clínica interativa (com ou sem IA semântica via BYOK), agendamento em grade própria sem concorrência e transbordo qualificado direto para a recepção.

## 2. Perfis de Usuário e Matriz de Permissões

| Perfil | Escopo | Acessos e Responsabilidades |
| :--- | :--- | :--- |
| **SuperAdmin** | Global (`/admin`) | Gestão de planos, clínicas, faturamento Mercado Pago, configurações globais (white-label) e publicação de releases (Novidades). |
| **Dentista / Gestor** | Tenant (`/app`) | Gestão da agenda, configuração de slots, visualização de leads, configuração de chave de IA (BYOK), visualização de novidades e assinatura. |
| **Paciente** | Público (`/{slug}`) | Acesso anônimo à página da clínica, preenchimento de triagem, seleção de horário vago e envio de dados para o WhatsApp da clínica. |

## 3. Requisitos Funcionais por Módulo

### 3.1 Módulo SuperAdmin (`/admin`)
- **RF01 - Dashboard Global:** Visualizar total de clínicas (Trial, Ativo, Inadimplente), faturamento MRR e volume de agendamentos.
- **RF02 - Gestão de Tenants:** Listar, bloquear, alterar plano manualmente, prorrogar trial e personificar (impersonate) conta de uma clínica.
- **RF03 - Gestão de Planos:** CRUD de planos com limites mensais, periodicidade, valor e ID do plano no Mercado Pago.
- **RF04 - Webhooks Mercado Pago:** Endpoint assíncrono para notificações de pagamentos/assinaturas com atualização de status do tenant.
- **RF05 - Configurações Globais:** Configuração de nome do sistema, logo (light/dark), favicon e rodapé com cache persistente.
- **RF06 - Gestão de Novidades (Changelog):** CRUD de versões (semver), título, descrição e trigger para exibição de modal global de atualizações.

### 3.2 Módulo Tenant / Clínica (`/app`)
- **RF07 - Onboarding Guiado:** Configuração do nome, slug único, WhatsApp e horários de atendimento.
- **RF08 - Grade de Agendamento:** Definição de dias ativos, início/fim de expediente, intervalo e duração do slot.
- **RF09 - Gestão de Leads:** Listagem e Kanban com filtros de status e histórico de respostas da triagem.
- **RF10 - Configuração de IA (BYOK):** Inserção opcional de API Key (Gemini ou OpenAI). Se vazia, ativa modo de questionário fixo.
- **RF11 - Menu de Novidades:** Acesso ao histórico de versões (`/app/novidades`) e exibição automática de modal de nova release após push/deploy.

### 3.3 Módulo Público de Triagem (`/{slug}`)
- **RF12 - Formulário Multi-step (Livewire):** Coleta de dados básicos, queixa principal, dor e histórico médico simplificado.
- **RF13 - Processamento de Triagem:** 
  - *Com IA (BYOK ativo):* LLM gera JSON estruturado com resumo clínico e urgência.
  - *Sem IA (Fallback):* Regras condicionais analisam opções selecionadas.
- **RF14 - Seleção de Horário Disponível:** Exibição dinâmica de slots livres e prevenção de colisões no banco (lock pessimista).
- **RF15 - Transbordo para WhatsApp:** Geração do link `wa.me` com mensagem pré-formatada contendo horário escolhido e resumo da triagem.

## 4. Requisitos Não-Funcionais
- **RND01 - Stack Exigida:** Laravel 13.x, PHP 8.3+, MySQL 8.0+, Livewire v3, Tailwind CSS v4.
- **RND02 - Isolamento Rigoroso:** O `clinic_id` deve ser aplicado em todas as queries de domínio via Eloquent Global Scope.
- **RND03 - Idempotência Financeira:** Webhooks do Mercado Pago devem registrar hash de eventos para evitar duplicações.
- **RND04 - Segurança:** Chaves de IA (BYOK) armazenadas criptografadas (`encrypted` cast).

## 5. Regras de Negócio Críticas
- **RN01:** O slug da clínica deve ser único globalmente.
- **RN02:** Um slot de agendamento não pode ser duplicado (`UNIQUE(clinic_id, scheduled_at)`).
- **RN03:** O modal de novidades só deve ser exibido uma vez por usuário para cada release publicada.
- **RN04:** Toda nova clínica tem direito a 14 dias de teste grátis (trial) sem exigência de cartão de crédito. Durante esse período, o acesso a todos os módulos, triagens e agendamentos é integral. Após os 14 dias, o acesso é bloqueado até a assinatura de um plano via Mercado Pago.