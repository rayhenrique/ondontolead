# Delimitação de Escopo do MVP - OdontoLead AI

## 1. O que ENTRA no MVP (Aprovado)
- **Painel SuperAdmin:** Gestão de clínicas, planos, configurações globais com cache, gestão de Releases/Novidades e endpoints para webhooks do Mercado Pago.
- **Painel Tenant (Clínica):** Single Database via Scoping, gestão de agenda customizada, visualização de agendamentos, tela de Novidades e configuração de chave BYOK de IA (Gemini/OpenAI).
- **Área Pública (Paciente):** Landing page mobile-first por slug, formulário multi-step Livewire com triagem e transbordo qualificado para o WhatsApp.
- **Engines Híbridas:** Transição transparente de triagem baseada em regras estáticas para IA caso a clínica possua chave configurada. Notificações de sistema via modal global (Changelog).

## 2. O que NÃO ENTRA no MVP (Postergado)
- Agendas múltiplas e acesso segmentado para vários profissionais/funcionários na mesma clínica.
- Cobrança do valor da consulta diretamente ao paciente via plataforma (o SaaS cobra a clínica, não o paciente final).
- Chatbots bidirecionais rodando diretamente no WhatsApp (Evolution API / Z-API).
- Sincronização em tempo real com Google Calendar.

## 3. Métricas de Sucesso Técnico Esperadas
- **Confiabilidade da Agenda:** Zero falhas e zero 'double-bookings' durante pico de acessos graças à camada de transação do banco.
- **Integração Agnóstica:** Funcionalidade perfeita de agendamento 100% livre da obrigatoriedade de créditos LLM pela clínica (Fallback testado e aprovado).
- **Segurança de Dados:** Rígido controle multi-tenant impedindo vazamento trans-clínicas sob qualquer condição.
- **Adoção de Atualizações:** Alto engajamento no rastreio da tabela `user_release_reads` (Modais de novidades vistos e fechados conscientemente pelos usuários).