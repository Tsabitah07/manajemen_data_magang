<script setup lang="ts">
import { ref } from 'vue'

const menuOpen = ref(false)

const closeMenu = () => {
  menuOpen.value = false
}
</script>

<template>
  <div class="landing-page">
    <header class="site-header">
      <a class="brand" href="#" aria-label="RuangMagang, beranda" @click="closeMenu">
        <span class="brand-mark" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M4 8.5 12 4l8 4.5v7L12 20l-8-4.5v-7Z" />
            <path d="m8 10.5 4 2.2 4-2.2M12 13v4" />
          </svg>
        </span>
        <span>ruang<span class="brand-accent">magang</span></span>
      </a>

      <button
        class="menu-toggle"
        type="button"
        :aria-expanded="menuOpen"
        aria-label="Buka menu navigasi"
        @click="menuOpen = !menuOpen"
      >
        <span></span>
        <span></span>
        <span></span>
      </button>

      <nav class="main-nav" :class="{ 'is-open': menuOpen }" aria-label="Navigasi utama">
        <a href="#manfaat" @click="closeMenu">Manfaat</a>
        <a href="#alur" @click="closeMenu">Cara kerja</a>
        <a href="#cerita" @click="closeMenu">Cerita mereka</a>
        <a class="nav-login" href="#mulai" @click="closeMenu">Masuk</a>
        <a class="button button-small" href="#mulai" @click="closeMenu">Ajukan magang</a>
      </nav>
    </header>

    <main>
      <slot />
    </main>

    <footer class="site-footer">
      <a class="brand footer-brand" href="#" aria-label="RuangMagang, kembali ke atas">
        <span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M4 8.5 12 4l8 4.5v7L12 20l-8-4.5v-7Z" /><path d="m8 10.5 4 2.2 4-2.2M12 13v4" /></svg></span>
        <span>ruang<span class="brand-accent">magang</span></span>
      </a>
      <p>Tempat langkah pertama menuju karier impian.</p>
      <span class="copyright">© 2026 RuangMagang</span>
    </footer>
  </div>
</template>

<style>
.landing-page {
  --ink: #17251f;
  --muted: #75817b;
  --green: #196b4a;
  --mint: #dff2e7;
  color: var(--ink);
  font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  overflow: hidden;
}

.site-header,
.hero-section,
.trust-strip,
.benefits-section,
.steps-section,
.site-footer {
  width: min(1160px, calc(100% - 64px));
  margin-inline: auto;
}

.site-header {
  height: 88px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.brand {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  color: var(--ink);
  font-size: 20px;
  font-weight: 800;
  letter-spacing: -1px;
  text-decoration: none;
}

.brand-mark {
  width: 35px;
  height: 35px;
  display: grid;
  place-items: center;
  border-radius: 11px;
  background: var(--green);
}

.brand-mark svg {
  width: 23px;
  stroke: #fff;
  stroke-linecap: round;
  stroke-linejoin: round;
  stroke-width: 1.6;
}

.brand-accent {
  color: var(--green);
}

.main-nav {
  display: flex;
  align-items: center;
  gap: 33px;
  font-size: 13px;
  font-weight: 600;
}

.main-nav > a:not(.button) {
  color: #526059;
  text-decoration: none;
  transition: color 160ms ease;
}

.main-nav > a:not(.button):hover,
.text-link:hover {
  color: var(--green);
}

.nav-login {
  margin-left: 10px;
}

.button {
  min-height: 52px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 11px;
  padding: 0 21px;
  border: 1px solid var(--green);
  border-radius: 9px;
  background: var(--green);
  color: #fff;
  font-size: 14px;
  font-weight: 700;
  text-decoration: none;
  transition: background 160ms ease, transform 160ms ease;
}

.button:hover {
  transform: translateY(-2px);
  background: #12583c;
}

.button svg {
  width: 18px;
  fill: none;
  stroke: currentColor;
  stroke-linecap: round;
  stroke-linejoin: round;
  stroke-width: 1.7;
}

.button-small {
  min-height: 42px;
  padding-inline: 17px;
  border-radius: 8px;
  font-size: 12px;
}

.menu-toggle {
  display: none;
}

.hero-section {
  min-height: 595px;
  display: grid;
  grid-template-columns: 1fr 1.02fr;
  align-items: center;
  gap: 42px;
  padding-block: 45px 63px;
}

.hero-copy {
  position: relative;
  z-index: 1;
  padding-bottom: 10px;
}

.eyebrow,
.section-label {
  color: var(--green);
  font-size: 10px;
  font-weight: 800;
  letter-spacing: 1.3px;
}

.eyebrow {
  display: flex;
  align-items: center;
  gap: 9px;
  margin-bottom: 22px;
}

.eyebrow-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #f2ad5d;
  box-shadow: 0 0 0 4px #fff2df;
}

.hero-copy h1 {
  max-width: 540px;
  margin: 0;
  font-size: clamp(43px, 5.2vw, 64px);
  font-weight: 750;
  letter-spacing: -3.8px;
  line-height: 1.08;
}

.hero-copy h1 span,
.section-heading h2 span,
.steps-copy h2 span {
  color: var(--green);
}

.hero-description {
  max-width: 440px;
  margin: 22px 0 25px;
  color: #707c75;
  font-size: 15px;
  line-height: 1.8;
}

.hero-actions {
  display: flex;
  align-items: center;
  gap: 25px;
}

.text-link {
  color: #3a4941;
  font-size: 13px;
  font-weight: 700;
  text-decoration: none;
  transition: color 160ms ease;
}

.text-link span {
  margin-left: 5px;
}

.hero-proof {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-top: 39px;
}

.avatar-stack {
  display: flex;
  padding-left: 3px;
}

.avatar-stack span {
  width: 29px;
  height: 29px;
  display: grid;
  place-items: center;
  margin-left: -4px;
  border: 2px solid #fff;
  border-radius: 50%;
  background: #d9b494;
  color: #fff;
  font-size: 9px;
  font-weight: 800;
}

.avatar-stack span:nth-child(2) {
  background: #789a81;
}

.avatar-stack span:nth-child(3) {
  background: #d18771;
}

.avatar-stack span:nth-child(4) {
  background: #e9efe9;
  color: var(--green);
}

.hero-proof p {
  margin: 0;
  color: #7c8781;
  font-size: 11px;
}

.hero-proof strong {
  color: var(--ink);
}

.hero-visual {
  min-height: 456px;
  position: relative;
  display: grid;
  place-items: center;
  isolation: isolate;
}

.hero-visual::before {
  width: 400px;
  height: 400px;
  position: absolute;
  z-index: -2;
  border-radius: 50%;
  background: #edf5ee;
  content: "";
}

.visual-orbit {
  position: absolute;
  z-index: -1;
  border: 1px solid #dae9dd;
  border-radius: 50%;
}

.orbit-one {
  width: 460px;
  height: 460px;
}

.orbit-two {
  width: 515px;
  height: 515px;
  border-color: #e6eee6;
}

.application-card {
  width: min(390px, 78%);
  position: relative;
  z-index: 1;
  padding: 24px 23px 21px;
  border: 1px solid #edf0ed;
  border-radius: 17px;
  background: #fff;
  box-shadow: 0 24px 65px #244c361a, 0 4px 12px #244c360a;
}

.card-topline,
.progress-heading,
.progress-caption,
.application-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.card-kicker,
.muted-label {
  color: #9aa49d;
  font-size: 8px;
  font-weight: 800;
  letter-spacing: 1px;
}

.card-topline h2 {
  margin: 6px 0 0;
  font-size: 21px;
  letter-spacing: -0.8px;
}

.card-topline h2 span {
  font-size: 16px;
}

.profile-avatar {
  width: 37px;
  height: 37px;
  display: grid;
  place-items: center;
  border-radius: 50%;
  background: #f6dfcb;
  color: #80583e;
  font-size: 13px;
  font-weight: 800;
}

.progress-panel {
  margin-top: 23px;
  padding: 16px 15px 13px;
  border: 1px solid #edf1ed;
  border-radius: 11px;
  background: #fbfcfa;
}

.progress-heading strong {
  display: block;
  margin-top: 5px;
  font-size: 12px;
}

.progress-percent {
  color: var(--green);
  font-size: 17px;
  font-weight: 800;
}

.progress-track {
  height: 6px;
  overflow: hidden;
  margin-top: 13px;
  border-radius: 10px;
  background: #e8eee8;
}

.progress-track span {
  width: 75%;
  height: 100%;
  display: block;
  border-radius: inherit;
  background: #55a277;
}

.progress-caption {
  margin-top: 8px;
  color: #89938c;
  font-size: 9px;
}

.application-list {
  display: grid;
  gap: 15px;
  margin-top: 21px;
}

.application-row {
  justify-content: flex-start;
  gap: 10px;
}

.company-logo {
  width: 35px;
  height: 35px;
  flex: 0 0 auto;
  display: grid;
  place-items: center;
  border-radius: 10px;
  font-size: 18px;
  font-weight: 800;
}

.logo-blue {
  background: #e5eefb;
  color: #5275b9;
}

.logo-yellow {
  background: #fff1d8;
  color: #db9d36;
}

.company-details {
  min-width: 0;
  display: grid;
  gap: 4px;
}

.company-details strong {
  font-size: 10px;
}

.company-details span {
  color: #89938c;
  font-size: 9px;
}

.status-pill {
  margin-left: auto;
  padding: 5px 7px;
  border-radius: 20px;
  background: #fff2d9;
  color: #b77c18;
  font-size: 8px;
  font-weight: 700;
  white-space: nowrap;
}

.status-done {
  background: #e5f3e9;
  color: #408258;
}

.floating-note,
.floating-date {
  position: absolute;
  z-index: 2;
  display: flex;
  align-items: center;
  gap: 10px;
  border: 1px solid #eef0ed;
  border-radius: 11px;
  background: #fff;
  box-shadow: 0 12px 32px #244c3618;
}

.floating-note {
  top: 66px;
  right: -1px;
  padding: 11px 13px;
}

.note-icon,
.date-icon {
  width: 29px;
  height: 29px;
  display: grid;
  place-items: center;
  border-radius: 9px;
  background: #e5f3e9;
  color: var(--green);
  font-weight: 800;
}

.floating-note strong,
.floating-date strong {
  display: block;
  font-size: 9px;
}

.floating-note small,
.floating-date small {
  display: block;
  margin-top: 4px;
  color: #89938c;
  font-size: 8px;
}

.floating-date {
  bottom: 57px;
  left: -8px;
  padding: 10px 13px;
}

.date-icon {
  background: #fff0dc;
  color: #cc8731;
}

.trust-strip {
  min-height: 91px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  border-top: 1px solid #edf0ed;
  border-bottom: 1px solid #edf0ed;
}

.trust-strip p {
  max-width: 170px;
  margin: 0;
  color: #7d8881;
  font-size: 11px;
  line-height: 1.6;
}

.trust-strip div {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #4d5a53;
  font-size: 11px;
  font-weight: 650;
  white-space: nowrap;
}

.trust-icon {
  color: var(--green);
  font-size: 19px;
  font-weight: 700;
}

.benefits-section {
  padding-block: 105px 108px;
}

.section-heading {
  max-width: 500px;
  margin-inline: auto;
  text-align: center;
}

.section-heading h2,
.steps-copy h2 {
  margin: 15px 0 12px;
  font-size: 39px;
  letter-spacing: -2px;
  line-height: 1.18;
}

.section-heading > p,
.steps-copy > p {
  margin: 0;
  color: #7b8780;
  font-size: 13px;
  line-height: 1.75;
}

.benefit-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-top: 48px;
}

.benefit-card {
  min-height: 195px;
  padding: 25px 24px;
  border: 1px solid #edf0ed;
  border-radius: 13px;
  background: #fff;
}

.benefit-icon {
  width: 39px;
  height: 39px;
  display: grid;
  place-items: center;
  border-radius: 11px;
  font-size: 21px;
  font-weight: 700;
}

.icon-mint {
  background: #e1f3e7;
  color: #28744e;
}

.icon-lilac {
  background: #efebfb;
  color: #7961b3;
}

.icon-peach {
  background: #fff0df;
  color: #ce8735;
}

.benefit-card h3,
.step-item h3 {
  margin: 17px 0 8px;
  font-size: 14px;
}

.benefit-card p,
.step-item p {
  margin: 0;
  color: #7c8780;
  font-size: 11px;
  line-height: 1.7;
}

.steps-section {
  display: grid;
  grid-template-columns: 0.82fr 1.18fr;
  gap: 85px;
  padding-block: 0 105px;
}

.steps-copy {
  padding-top: 12px;
}

.steps-copy h2 {
  margin-top: 17px;
  font-size: 42px;
}

.steps-copy > p {
  max-width: 310px;
}

.steps-link {
  display: inline-block;
  margin-top: 25px;
  color: var(--green);
}

.steps-list {
  display: grid;
}

.step-item {
  display: grid;
  grid-template-columns: 40px 1fr 27px;
  align-items: start;
  gap: 13px;
  padding: 20px 0;
  border-bottom: 1px solid #edf0ed;
}

.step-item:first-child {
  padding-top: 6px;
}

.step-number {
  padding-top: 1px;
  color: #91aa97;
  font-size: 12px;
  font-weight: 800;
}

.step-item h3 {
  margin: 0 0 6px;
  font-size: 13px;
}

.step-item p {
  max-width: 370px;
}

.step-check {
  width: 24px;
  height: 24px;
  display: grid;
  place-items: center;
  border-radius: 50%;
  background: #e8f4eb;
  color: var(--green);
  font-size: 11px;
  font-weight: 800;
}

.quote-section {
  width: min(100% - 64px, 1160px);
  min-height: 267px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin-inline: auto;
  padding: 35px;
  border-radius: 18px;
  background: #f3f7f2;
  text-align: center;
}

.quote-mark {
  height: 32px;
  color: #85aa8f;
  font-family: Georgia, serif;
  font-size: 55px;
  line-height: 1;
}

.quote-section blockquote {
  max-width: 630px;
  margin: 18px 0 20px;
  font-size: 19px;
  font-weight: 650;
  letter-spacing: -0.4px;
  line-height: 1.6;
}

.quote-author {
  display: flex;
  align-items: center;
  gap: 9px;
  text-align: left;
}

.author-avatar {
  width: 32px;
  height: 32px;
  display: grid;
  place-items: center;
  border-radius: 50%;
  background: #e8c4a8;
  color: #694d3a;
  font-size: 11px;
  font-weight: 800;
}

.quote-author strong,
.quote-author small {
  display: block;
}

.quote-author strong {
  font-size: 10px;
}

.quote-author small {
  margin-top: 3px;
  color: #7f8b83;
  font-size: 9px;
}

.cta-section {
  width: min(100% - 64px, 1160px);
  min-height: 220px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  margin: 75px auto 0;
  padding: 40px 55px;
  border-radius: 16px;
  background: #1b6849;
  color: #fff;
}

.cta-section .section-label {
  color: #c8e5d1;
}

.cta-section h2 {
  margin: 12px 0 0;
  font-size: 31px;
  letter-spacing: -1.3px;
  line-height: 1.25;
}

.button-light {
  flex: 0 0 auto;
  border-color: #fff;
  background: #fff;
  color: var(--green);
}

.button-light:hover {
  background: #f0f7f2;
}

.site-footer {
  min-height: 100px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  margin-top: 15px;
}

.footer-brand {
  font-size: 16px;
}

.footer-brand .brand-mark {
  width: 30px;
  height: 30px;
  border-radius: 9px;
}

.footer-brand .brand-mark svg {
  width: 20px;
}

.site-footer > p,
.copyright {
  color: #839087;
  font-size: 10px;
}

@media (max-width: 900px) {
  .hero-section {
    grid-template-columns: 1fr 0.9fr;
    gap: 10px;
  }

  .hero-copy h1 {
    font-size: 51px;
  }

  .hero-visual {
    transform: scale(0.9);
  }

  .trust-strip {
    flex-wrap: wrap;
    justify-content: center;
    padding-block: 17px;
  }

  .trust-strip p {
    max-width: none;
    width: 100%;
    text-align: center;
  }

  .steps-section {
    gap: 45px;
  }
}

@media (max-width: 680px) {
  .site-header,
  .hero-section,
  .trust-strip,
  .benefits-section,
  .steps-section,
  .site-footer {
    width: min(100% - 40px, 520px);
  }

  .site-header {
    height: 72px;
  }

  .brand {
    font-size: 18px;
  }

  .menu-toggle {
    width: 39px;
    height: 39px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 5px;
    border: 1px solid #e7ece8;
    border-radius: 9px;
    background: #fff;
  }

  .menu-toggle span {
    width: 16px;
    height: 2px;
    border-radius: 2px;
    background: var(--ink);
  }

  .main-nav {
    position: absolute;
    z-index: 5;
    top: 64px;
    right: 20px;
    left: 20px;
    display: none;
    align-items: stretch;
    gap: 0;
    padding: 10px;
    border: 1px solid #edf0ed;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 16px 35px #193c2817;
  }

  .main-nav.is-open {
    display: flex;
    flex-direction: column;
  }

  .main-nav > a:not(.button) {
    padding: 13px 10px;
  }

  .nav-login {
    margin-left: 0;
  }

  .main-nav .button {
    margin-top: 6px;
  }

  .hero-section {
    grid-template-columns: 1fr;
    gap: 0;
    padding-block: 50px 37px;
  }

  .eyebrow {
    font-size: 8px;
    letter-spacing: 1px;
  }

  .hero-copy h1 {
    max-width: 450px;
    font-size: clamp(43px, 12vw, 58px);
    letter-spacing: -2.8px;
  }

  .hero-description {
    margin-block: 18px 22px;
    font-size: 14px;
  }

  .hero-actions {
    gap: 17px;
  }

  .hero-actions .button {
    min-height: 48px;
    padding-inline: 15px;
    font-size: 12px;
  }

  .text-link {
    font-size: 11px;
  }

  .hero-proof {
    margin-top: 26px;
  }

  .hero-visual {
    min-height: 410px;
    margin-top: 10px;
    transform: none;
  }

  .hero-visual::before {
    width: 310px;
    height: 310px;
  }

  .orbit-one {
    width: 360px;
    height: 360px;
  }

  .orbit-two {
    width: 400px;
    height: 400px;
  }

  .application-card {
    width: min(390px, 88%);
    padding: 20px 18px;
  }

  .floating-note {
    top: 31px;
    right: -2px;
  }

  .floating-date {
    bottom: 27px;
    left: -1px;
  }

  .trust-strip {
    justify-content: flex-start;
    gap: 15px 22px;
    padding-block: 18px;
  }

  .trust-strip p {
    text-align: left;
  }

  .trust-strip div {
    font-size: 10px;
  }

  .benefits-section {
    padding-block: 76px;
  }

  .section-heading h2,
  .steps-copy h2 {
    font-size: 34px;
  }

  .section-heading > p {
    font-size: 12px;
  }

  .benefit-grid {
    grid-template-columns: 1fr;
    gap: 12px;
    margin-top: 31px;
  }

  .benefit-card {
    min-height: auto;
    padding: 20px;
  }

  .benefit-card h3 {
    margin-top: 13px;
  }

  .steps-section {
    grid-template-columns: 1fr;
    gap: 32px;
    padding-bottom: 75px;
  }

  .steps-copy h2 {
    margin-bottom: 10px;
  }

  .steps-link {
    margin-top: 17px;
  }

  .step-item {
    grid-template-columns: 32px 1fr 24px;
    gap: 8px;
    padding-block: 17px;
  }

  .quote-section,
  .cta-section {
    width: calc(100% - 40px);
  }

  .quote-section {
    min-height: 250px;
    padding: 30px 22px;
  }

  .quote-section blockquote {
    font-size: 16px;
  }

  .cta-section {
    min-height: 250px;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    gap: 23px;
    margin-top: 55px;
    padding: 30px 25px;
  }

  .cta-section h2 {
    font-size: 27px;
  }

  .site-footer {
    min-height: 125px;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 7px 15px;
    padding-block: 22px;
  }

  .site-footer > p {
    width: 100%;
    order: 1;
    margin: 0;
  }

  .copyright {
    margin-left: auto;
  }
}
</style>
