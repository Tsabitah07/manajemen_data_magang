<script setup lang="ts">
import BenefitsSection from '@/components/landing_pages/BenefitsSection.vue'
import CallToActionSection from '@/components/landing_pages/CallToActionSection.vue'
import HeroSection from '@/components/landing_pages/HeroSection.vue'
import LandingPageLayout from '@/components/landing_pages/LandingPageLayout.vue'
import QuoteSection from '@/components/landing_pages/QuoteSection.vue'
import StepsSection from '@/components/landing_pages/StepsSection.vue'
import TrustSection from '@/components/landing_pages/TrustSection.vue'
</script>

<template>
  <LandingPageLayout>
    <HeroSection />
    <TrustSection />
    <BenefitsSection />
    <StepsSection />
<!--    <QuoteSection />-->
    <CallToActionSection />
  </LandingPageLayout>
</template>
