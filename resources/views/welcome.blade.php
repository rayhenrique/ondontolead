<x-landing-layout title="OdontoLead AI — Da dor do paciente à cadeira confirmada sem travar o seu WhatsApp">
    
    <!-- 1. Header (Sticky / Glassmorphism) -->
    <x-landing.header />

    <!-- Main Content Flow -->
    <main class="flex-grow">
        <!-- 2. Hero Section with Mockup & 14-day Free Trial -->
        <x-landing.hero />

        <!-- 3. Authority Marquee Ticker -->
        <x-landing.marquee />

        <!-- 4. Contrast: Problem (Manual WhatsApp) vs Solution (OdontoLead Standard) -->
        <x-landing.contrast />

        <!-- 5. Interactive Showcase: The 4-Step Patient Journey -->
        <x-landing.showcase />

        <!-- 6. Alpine.js ROI Calculator (Live Range Sliders & BRL Format) -->
        <x-landing.roi-calculator />

        <!-- 7. Transparent Pricing (Featured Pro Plan) -->
        <x-landing.pricing :plans="$plans ?? null" />

        <!-- 8. Interactive FAQ Accordion -->
        <x-landing.faq />
    </main>

    <!-- 9. Editorial Footer with KL Tecnologia Copyright -->
    <x-landing.footer />

</x-landing-layout>
