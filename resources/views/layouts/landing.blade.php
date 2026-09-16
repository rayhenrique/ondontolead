<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth bg-slate-50 text-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'OdontoLead AI — Da dor do paciente à cadeira confirmada sem travar o seu WhatsApp' }}</title>
    <meta name="description" content="Micro-SaaS B2B para clínicas odontológicas. Automatize a qualificação de leads com triagem inteligente e agendamento em tempo real sem concorrência no WhatsApp.">

    <!-- Typography: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
    </style>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-emerald-500 selection:text-white overflow-x-hidden min-h-screen flex flex-col justify-between">

    {{ $slot }}

    <!-- GSAP 3 & ScrollTrigger (CDN) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);

                const isMobile = window.innerWidth < 768;

                // 1. Hero Entrance
                gsap.from('.gsap-hero-el', {
                    y: isMobile ? 20 : 35,
                    opacity: 0,
                    duration: 0.9,
                    stagger: 0.12,
                    ease: 'power3.out',
                    clearProps: 'all'
                });

                // 2. Hero Floating Card Loop
                if (!isMobile) {
                    gsap.to('.gsap-float', {
                        y: -14,
                        duration: 2.8,
                        repeat: -1,
                        yoyo: true,
                        ease: 'sine.inOut'
                    });
                }

                // 3. Reveal Animations on Scroll
                const revealElements = document.querySelectorAll('.gsap-reveal');
                revealElements.forEach(el => {
                    gsap.from(el, {
                        scrollTrigger: {
                            trigger: el,
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        },
                        y: isMobile ? 20 : 35,
                        opacity: 0,
                        duration: 0.8,
                        ease: 'power3.out',
                        clearProps: 'all'
                    });
                });

                // 4. Staggered Cards Animation
                const staggerContainers = document.querySelectorAll('.gsap-stagger-group');
                staggerContainers.forEach(container => {
                    const cards = container.querySelectorAll('.gsap-card');
                    if (cards.length > 0) {
                        gsap.from(cards, {
                            scrollTrigger: {
                                trigger: container,
                                start: 'top 85%',
                                toggleActions: 'play none none none'
                            },
                            y: isMobile ? 20 : 40,
                            opacity: 0,
                            duration: 0.8,
                            stagger: 0.14,
                            ease: 'power3.out',
                            clearProps: 'all'
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>
