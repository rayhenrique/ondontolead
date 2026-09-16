# LANDING-PAGE-SPEC.md
# OdontoLead AI - Cinematic Landing Page Specification

## 1. Visão Geral
* **Produto:** OdontoLead AI - Micro-SaaS B2B multi-tenant para clínicas odontológicas[cite: 2].
* **Objetivo da Página:** Conversão institucional focada em cadastro para Teste Grátis (Trial)[cite: 2, 7].
* **Público-Alvo:** Donos de clínicas, gestores e dentistas autônomos sofrendo com perda de leads no WhatsApp da recepção[cite: 7].
* **Core Offer:** "A recepção cirúrgica que nunca dorme: Da dor do paciente à cadeira confirmada sem travar o seu WhatsApp."

## 2. Design System (High-Tech Editorial Light)
* **Atmosfera:** Look & feel de software médico de precisão, limpo, confiável e ultra-rápido.
* **Paleta de Cores:**
  * Background Primário: Off-White Cirúrgico (`#F8FAFC` - slate-50) e Branco (`#FFFFFF`).
  * Textos Principais: Grafite Escuro (`#0F172A` - slate-900).
  * Textos Secundários: Cinza Titânio (`#475569` - slate-600).
  * Brand Accent (Confiança/Saúde): Verde Sálvia (`#10B981` - emerald-500).
  * Tech Accent (Tecnologia/Precisão): Azul Clínico (`#0284C7` - sky-600).
* **Tipografia:**
  * Títulos (Headings): `Plus Jakarta Sans` ou `Outfit` (Geométrica, limpa, pesos 600/700).
  * Corpo (Body): `Inter` ou `Geist` (Alta legibilidade técnica, pesos 400/500).
* **Efeitos Visuais (Tailwind):**
  * `backdrop-blur-md` e `bg-white/70` para glassmorphism no header e cards flutuantes.
  * `shadow-sm` e `shadow-lg` com cor ajustada (ex: `shadow-slate-200/50`) para profundidade sem sujeira visual.
  * Bordas sutis `border-slate-200`.

## 3. Estrutura Narrativa (Seções)

1. **Header (Sticky Glassmorphism):**
   * Logo OdontoLead AI, Links âncora invisíveis (Problema, Funcionalidades, Planos).
   * Call to Action (CTA): Botão sólido Emerald "Começar Grátis".

2. **Hero Section:**
   * Headline: "Encha a sua cadeira odontológica no piloto automático. Zero perda de pacientes."
   * Sub-headline: "Sua clínica gasta com anúncios, mas seus leads evaporam no WhatsApp da recepção. Automatize a triagem e o agendamento 24/7."
   * CTAs: "Iniciar Teste Grátis" (Primary Emerald) e "Ver Demonstração" (Secondary Outline Slate).
   * Visual: Mockup de interface flutuante mostrando a triagem da IA vs. WhatsApp lotado.

3. **Authority Ticker:**
   * Faixa horizontal com rolagem contínua (marquee): "Sem Risco de Duplo Agendamento" | "+180 testes rigorosos de concorrência[cite: 1]" | "Triagem via Gemini & OpenAI[cite: 2, 7]" | "Transbordo direto pro WhatsApp[cite: 2, 7]".

4. **Contraste (Problema vs. Solução):**
   * Grid 1x2. 
   * Esquerda: "O Gargalo Atual" (Cards vermelhos/âmbar simbolizando mensagens ignoradas).
   * Direita: "O Padrão OdontoLead" (Cards verdes/limpos simbolizando o agendamento via link público `/{slug}`[cite: 1, 2]).

5. **Showcase Interativo (Triagem Híbrida & BYOK):**
   * Visualização do funil do paciente (Formulário multi-step do Livewire[cite: 1, 2, 7]):
     1. Identificação.
     2. Queixa e Dor (0-10)[cite: 1, 7].
     3. Análise da IA (Urgência e Resumo)[cite: 1, 2, 7].
     4. Agendamento com travamento de horário (lock pessimista)[cite: 1, 2, 7].

6. **Calculadora de ROI (Alpine.js):**
   * Slider de 10 a 500 leads/mês.
   * Resultado em tempo real mostrando faturamento potencial recuperado.

7. **Pricing (Planos Transparentes):**
   * Cards de planos (Mensal/Anual), destacando a integração com checkout transparente Mercado Pago[cite: 1, 2, 7].

8. **FAQ & Footer:**
   * Accordion sanfona.
   * Footer branco/slate com Copyright KL Tecnologia[cite: 2].

## 4. Roteiro de Animações (GSAP ScrollTrigger)
* **Hero:** `gsap.from()` no headline (`y: 30`, `opacity: 0`, `duration: 1`, `ease: 'power3.out'`). Mockup com `yoyo` contínuo flutuando (`y: -15`).
* **Seções (Fade Up):** Todas as seções usam um observer genérico para `opacity: 0` -> `1` e `y: 40` -> `0` ao entrarem no viewport (`start: "top 85%"`).
* **Stagger Cards:** Na seção de Contraste e Pricing, os cards entram com `stagger: 0.15` para criar um efeito de cascata.
* **Mobile Fallback:** Desativar parallax complexo em `< 768px`; manter apenas fade-ins básicos para não comprometer o framerate do scroll nativo.