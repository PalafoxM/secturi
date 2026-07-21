  <style>
    .ci-shell {
      --bg: #eef3fb;
      --bg-soft: rgba(255, 255, 255, 0.72);
      --panel: rgba(255, 255, 255, 0.88);
      --panel-border: rgba(110, 138, 196, 0.18);
      --text: #20345f;
      --muted: #7281a6;
      --brand: #3566d8;
      --brand-strong: #234eb4;
      --brand-soft: #e8efff;
      --accent: #ff6d5f;
      --shadow: 0 18px 45px rgba(39, 72, 139, 0.12);
      --radius-xl: 28px;
      --radius-lg: 20px;
      --radius-md: 14px;
      position: relative;
      overflow: hidden;
      color: var(--text);
      background:
        radial-gradient(circle at top left, rgba(81, 127, 255, 0.18), transparent 30%),
        radial-gradient(circle at top right, rgba(255, 109, 95, 0.12), transparent 24%),
        linear-gradient(180deg, #f6f9ff 0%, #eef3fb 42%, #e9eff8 100%);
    }

    .ci-shell,
    .ci-shell * {
      box-sizing: border-box;
    }

    .ci-shell {
      font-family: "Aptos", "Segoe UI", sans-serif;
    }

    .ci-shell::before,
    .ci-shell::after {
      content: "";
      position: absolute;
      inset: auto;
      width: 320px;
      height: 320px;
      border-radius: 50%;
      filter: blur(18px);
      opacity: 0.45;
      pointer-events: none;
      z-index: 0;
    }

    .ci-shell::before {
      top: -120px;
      right: -90px;
      background: rgba(53, 102, 216, 0.18);
    }

    .ci-shell::after {
      bottom: -120px;
      left: -70px;
      background: rgba(255, 109, 95, 0.14);
    }

    .ci-page {
      position: relative;
      z-index: 1;
      width: calc(100% - 32px);
      max-width: 1180px;
      margin: 0 auto;
      padding: 32px 0 48px;
    }

    .ci-hero {
      position: relative;
      overflow: hidden;
      padding: 34px;
      border-radius: var(--radius-xl);
      background:
        linear-gradient(135deg, rgba(25, 56, 123, 0.96), rgba(53, 102, 216, 0.9)),
        linear-gradient(120deg, rgba(255, 255, 255, 0.08), transparent);
      box-shadow: var(--shadow);
      color: #fff;
    }

    .ci-hero::after {
      content: "";
      position: absolute;
      right: -40px;
      top: -40px;
      width: 220px;
      height: 220px;
      border-radius: 32px;
      background: linear-gradient(180deg, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0));
      transform: rotate(24deg);
    }

    .ci-hero-top {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 24px;
      position: relative;
      z-index: 1;
    }

    .ci-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 14px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.12);
      font-size: 0.84rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    .ci-page h1 {
      margin: 18px 0 10px;
      font-size: clamp(2rem, 4vw, 3rem);
      line-height: 1.05;
      color: #fff;
    }

    .ci-hero p {
      max-width: 720px;
      margin: 0;
      color: rgba(255, 255, 255, 0.84);
      font-size: 1rem;
      line-height: 1.6;
    }

    .ci-year-chip {
      flex-shrink: 0;
      padding: 16px 18px;
      min-width: 150px;
      border-radius: 22px;
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(10px);
      text-align: center;
      box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.14);
    }

    .ci-year-chip strong {
      display: block;
      font-size: 2.2rem;
      line-height: 1;
      margin-top: 8px;
    }

    .ci-dashboard {
      margin-top: -28px;
      padding: 0 14px;
    }

    .ci-panel {
      border: 1px solid var(--panel-border);
      border-radius: var(--radius-xl);
      background: var(--bg-soft);
      backdrop-filter: blur(14px);
      box-shadow: var(--shadow);
    }

    .ci-content {
      padding: 24px;
    }

    .ci-toolbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 20px;
      margin-bottom: 24px;
      flex-wrap: wrap;
    }

    .ci-toolbar-copy h2 {
      margin: 0;
      font-size: 1.4rem;
    }

    .ci-toolbar-copy p {
      margin: 8px 0 0;
      color: var(--muted);
    }

    .ci-toggle {
      display: inline-flex;
      padding: 6px;
      border-radius: 999px;
      background: #dfe8fb;
      gap: 6px;
      box-shadow: inset 0 0 0 1px rgba(53, 102, 216, 0.1);
    }

    .ci-toggle button {
      border: 0;
      border-radius: 999px;
      padding: 12px 18px;
      font: inherit;
      font-weight: 700;
      color: var(--brand-strong);
      background: transparent;
      cursor: pointer;
      transition: 0.25s ease;
    }

    .ci-toggle button.active {
      background: linear-gradient(135deg, var(--brand), var(--brand-strong));
      color: #fff;
      box-shadow: 0 10px 20px rgba(35, 78, 180, 0.25);
    }

    .ci-stats {
      display: grid;
      grid-template-columns: repeat(2, minmax(180px, 240px));
      gap: 16px;
      margin-bottom: 22px;
    }

    .ci-stat-card {
      padding: 18px 20px;
      border-radius: var(--radius-lg);
      background: var(--panel);
      border: 1px solid rgba(53, 102, 216, 0.12);
      box-shadow: 0 10px 25px rgba(39, 72, 139, 0.08);
    }

    .ci-stat-card span {
      display: block;
      color: var(--muted);
      font-size: 0.9rem;
      margin-bottom: 8px;
    }

    .ci-stat-card strong {
      font-size: 1.65rem;
    }

    .ci-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 16px;
    }

    .ci-link-card {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 18px;
      padding: 18px 20px;
      border-radius: var(--radius-lg);
      background: linear-gradient(135deg, rgba(53, 102, 216, 0.08), rgba(255, 255, 255, 0.92));
      border: 1px solid rgba(53, 102, 216, 0.12);
      text-decoration: none;
      color: inherit;
      min-height: 92px;
      transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    }

    .ci-link-card:hover,
    .ci-link-card:focus-visible {
      transform: translateY(-3px);
      box-shadow: 0 18px 30px rgba(53, 102, 216, 0.12);
      border-color: rgba(53, 102, 216, 0.24);
      outline: none;
    }

    .ci-link-card.is-disabled {
      cursor: default;
      opacity: 0.88;
    }

    .ci-link-card.is-disabled:hover,
    .ci-link-card.is-disabled:focus-visible {
      transform: none;
      box-shadow: none;
      border-color: rgba(53, 102, 216, 0.12);
    }

    .ci-link-card__text strong {
      display: block;
      font-size: 1.03rem;
      line-height: 1.35;
    }

    .ci-link-card__text span {
      display: inline-block;
      margin-top: 6px;
      color: var(--muted);
      font-size: 0.9rem;
    }

    .ci-link-card__icon {
      display: grid;
      place-items: center;
      width: 48px;
      height: 48px;
      flex-shrink: 0;
      border-radius: 16px;
      background: linear-gradient(135deg, var(--brand), var(--brand-strong));
      color: #fff;
      font-size: 1.25rem;
      box-shadow: 0 12px 20px rgba(35, 78, 180, 0.26);
    }

    .ci-full-width {
      grid-column: 1 / -1;
    }

    .ci-document-panel {
      margin-top: 22px;
      padding: 22px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 18px;
      border-radius: var(--radius-lg);
      background: linear-gradient(135deg, rgba(255, 109, 95, 0.08), rgba(255, 255, 255, 0.96));
      border: 1px solid rgba(255, 109, 95, 0.14);
    }

    .ci-document-panel small {
      color: var(--muted);
      display: block;
      margin-bottom: 6px;
      font-size: 0.88rem;
    }

    .ci-document-panel strong {
      font-size: 1.12rem;
      line-height: 1.35;
    }

    .ci-pdf-button {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 14px 18px;
      border-radius: 14px;
      background: linear-gradient(135deg, #ff6d5f, #ef4a3f);
      color: #fff;
      text-decoration: none;
      font-weight: 700;
      box-shadow: 0 14px 26px rgba(239, 74, 63, 0.28);
      white-space: nowrap;
    }

    .ci-timeline {
      margin-top: 24px;
      padding: 20px 22px;
      border-radius: var(--radius-lg);
      background: rgba(227, 236, 255, 0.66);
      border: 1px solid rgba(53, 102, 216, 0.1);
    }

    .ci-timeline h3 {
      margin: 0 0 14px;
      font-size: 1rem;
    }

    .ci-timeline-track {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 14px;
    }

    .ci-timeline-step {
      padding: 16px;
      border-radius: 16px;
      background: rgba(255, 255, 255, 0.82);
      border: 1px solid rgba(53, 102, 216, 0.12);
    }

    .ci-timeline-step span {
      display: inline-flex;
      width: 32px;
      height: 32px;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      margin-bottom: 10px;
      background: var(--brand-soft);
      color: var(--brand-strong);
      font-weight: 800;
    }

    .ci-timeline-step strong {
      display: block;
      margin-bottom: 6px;
      font-size: 0.98rem;
    }

    .ci-timeline-step p {
      margin: 0;
      color: var(--muted);
      line-height: 1.45;
      font-size: 0.92rem;
    }

    .ci-fade-in {
      animation: ci-fade-in 0.45s ease;
    }

    @keyframes ci-fade-in {
      from {
        opacity: 0;
        transform: translateY(8px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 900px) {
      .ci-hero-top,
      .ci-document-panel {
        flex-direction: column;
        align-items: flex-start;
      }

      .ci-stats,
      .ci-grid,
      .ci-timeline-track {
        grid-template-columns: 1fr;
      }

      .ci-dashboard {
        padding: 0;
        margin-top: 20px;
      }
    }

    @media (max-width: 640px) {
      .ci-page {
        width: calc(100% - 20px);
        padding-top: 18px;
      }

      .ci-hero,
      .ci-content {
        padding: 20px;
      }

      .ci-toggle {
        width: 100%;
        flex-direction: column;
        border-radius: 18px;
      }

      .ci-toggle button {
        flex: 1;
      }

      .ci-link-card {
        min-height: auto;
      }
    }
  </style>
  <div class="page-content-tab ci-shell">
    <div class="container-fluid">
  <main class="ci-page">
    <section class="ci-hero">
      <div class="ci-hero-top">
        <div>
          <div class="ci-eyebrow">Sistema Integrado de Gestión de Riesgos</div>
          <h1>Control Interno</h1>
        </div>
        <div class="ci-year-chip">
          Vista principal
          <strong id="hero-year">2026</strong>
        </div>
      </div>
    </section>

    <section class="ci-dashboard">
      <div class="ci-panel ci-content">
        <div class="ci-toolbar">
          <div class="ci-toolbar-copy">
            <h2 id="section-title">Control Interno 2026</h2>
            <p id="section-subtitle">Mostrando los accesos correspondientes al ejercicio 2025.</p>
          </div>
          <div class="ci-toggle" aria-label="Selector de año">
            <button id="btn-2024" type="button">Control Interno 2024</button>
            <button id="btn-2025" type="button" class="active">Control Interno 2025</button>
            <button id="btn-2026" type="button">Control Interno 2026</button>
          </div>
        </div>

        <div class="ci-stats">
          <article class="ci-stat-card">
            <span>Año visible</span>
            <strong id="stat-display-year">2026</strong>
          </article>
          <article class="ci-stat-card">
            <span>Total de accesos</span>
            <strong id="stat-total-links">8</strong>
          </article>
        </div>

        <div id="links-grid" class="ci-grid ci-fade-in"></div>

        <div class="ci-document-panel">
          <div>
            <small>Documento externo (PDF)</small>
            <strong>Lineamientos Generales de Control Interno · Poder Ejecutivo GTO</strong>
          </div>
          <a
            class="ci-pdf-button"
            href="https://secturnet.guanajuato.gob.mx/susi/assets/pdf/plantillas/LGCI-2022.pdf"
            target="_blank"
            rel="noopener noreferrer"
          >
            Abrir PDF
          </a>
        </div>

        <div class="ci-timeline">
          <h3>Ruta rápida de consulta</h3>
          <div class="ci-timeline-track">
            <div class="ci-timeline-step">
              <span>1</span>
              <strong>Selecciona el año</strong>
              <p>Usa los botones superiores para alternar entre los recursos de 2024, 2025 y 2026.</p>
            </div>
            <div class="ci-timeline-step">
              <span>2</span>
              <strong>Abre el documento</strong>
              <p>Ingresa a cada hoja o matriz directamente desde las tarjetas de acceso rápido.</p>
            </div>
            <div class="ci-timeline-step">
              <span>3</span>
              <strong>Consulta lineamientos</strong>
              <p>Apóyate en el PDF general para revisar criterios y lineamientos de control interno.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
    </div>
  </div>

  <script src="<?php echo base_url() ?>plugins/moment/moment.js"></script>
  
  <script src="<?php echo base_url() ?>assets/js/jquery-ui.min.js"></script>
  <script src="<?php echo base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo base_url() ?>assets/js/metismenu.min.js"></script>
  <script src="<?php echo base_url() ?>assets/js/waves.js"></script>
  <script src="<?php echo base_url() ?>assets/js/feather.min.js"></script>
  <script src="<?php echo base_url() ?>assets/js/jquery.slimscroll.min.js"></script>

  <script>
  (function () {
    "use strict";

    const controlInternoData = {
      2024: {
        displayYear: "2024",
        headlineYear: "2024",
        title: "Control Interno 2024",
        subtitle: "Consulta los accesos y matrices del ejercicio 2024.",
        links: [
          {
            title: "96 Acciones para Informe de Control Interno 2024",
            url: "https://docs.google.com/spreadsheets/d/1OqRipoFUYM_uUQU6jT8kooZEc9cq4J0J4QhTod6fqUg/edit?gid=0#gid=0",
            note: "Google Sheets"
          },
          {
            title: "PTCI-2024-SECTUR",
            url: "https://docs.google.com/spreadsheets/d/1UtKcOnAq25FJsGoBD0IEKoewrTZXDwwVt23Z1gM-AxI/edit?gid=650086614#gid=650086614",
            note: "Programa de trabajo"
          },
          {
            title: "Reporte anual Comportamiento de Riesgos-2024",
            url: "https://docs.google.com/spreadsheets/d/1-8MUlA1ujlaPhWOPxlGyhOdUWE5iZZvoARtaXPvWJ_g/edit?gid=0#gid=0",
            note: "Reporte anual"
          },
          {
            title: "Evaluación Controles-Riesgos Críticos 2024",
            url: "https://docs.google.com/spreadsheets/d/1mrGesV7_DxE7iRSATbPCxYrAd7kyPrfIYrqdXEnXU8U/edit?gid=0#gid=0",
            note: "Evaluación"
          },
          {
            title: "DDCI y Estrategias CI-2024",
            url: "https://docs.google.com/spreadsheets/d/1-zcgebplFjFw9q0FtG38eiJWSrXvPu1aljIMf8PMKBQ/edit?gid=1757674291#gid=1757674291",
            note: "Seguimiento estratégico"
          },
          {
            title: "B.1. Matriz Evaluación Riesgos SECTUR 2024 · julio-diciembre 2024",
            url: "https://docs.google.com/spreadsheets/d/12OOMzV0Foy2mH5ZsKVcmFJbjRnh2u6VAIba7PWZof0g/edit?gid=0#gid=0",
            note: "Matriz de riesgos"
          },
          {
            title: "B.1. Matriz Evaluación Riesgos SECTUR 2021 · 1er trim 2023",
            url: "https://docs.google.com/spreadsheets/d/1-FTZ4ivXhZ5BrDW9_H2UbG05S_rworSs/edit?gid=2022387048#gid=2022387048",
            note: "Histórico",
            fullWidth: true
          }
        ]
      },
      2025: {
        displayYear: "2025",
        headlineYear: "2025",
        title: "Control Interno 2025",
        subtitle: "Consulta los accesos y matrices del ejercicio 2025.",
        links: [
          {
            title: "96 Acciones para Informe de Control Interno 2025",
            url: "https://docs.google.com/spreadsheets/d/194FLEreGdVfRwRgLgd41Urde5q59_NnzkSQpsr6TLCE/edit?gid=0#gid=0",
            note: "Google Sheets"
          },
          {
            title: "PTCI-2025-SECTURI",
            url: "https://docs.google.com/spreadsheets/d/1qPGCcvG1xBlTATlaW4QJEXmR5PgGUjjiP4sT05mF6Lc/edit?usp=sharing",
            note: "Programa de trabajo"
          },
          {
            title: "Reporte anual Comportamiento de Riesgos-2025",
            url: "https://docs.google.com/spreadsheets/d/1qtwrbVXwYMoTKFKSmmqW4UqtdLA8XRXMYvNqjfo_r6Q/edit?usp=sharing",
            note: "Reporte anual"
          },
          {
            title: "Evaluación de Controles-Riesgo Críticos 2025",
            url: "https://docs.google.com/spreadsheets/d/1276LUkhEdcEaJuDcqWVHLJJMZk_rw6UoRr6hkZ7qWDI/edit?usp=sharing",
            note: "Evaluación"
          },
          {
            title: "DDCI y Estrategias CI-2025",
            url: "https://docs.google.com/spreadsheets/d/1vsb645lc_iakXCe71oTKaSOAaK8bCoAFtm7VJKLctWI/edit?usp=sharing",
            note: "Seguimiento estratégico"
          },
          {
            title: "B.1. Matriz Evaluación Riesgos SECTURI · 1er trimestre 2025",
            url: "https://docs.google.com/spreadsheets/d/1c7AWnAdGSlW59KMXFIU4UNQCYU-vhy3zXw5N4dD6h3g/edit?usp=sharing",
            note: "Matriz de riesgos"
          },
          {
            title: "Proceso de verificación y evaluación de riesgos (Permanencia en matriz)",
            url: "https://docs.google.com/spreadsheets/d/1sj6j3GmASnb0gXGO4L4x_7LWc5eVgRDXMJuK3vk_8Sc/edit?usp=sharing",
            note: "Proceso de verificación",
            fullWidth: true
          },
          {
            title: "Actualización Matriz de Riesgos · 4to. trim. 2025",
            url: "https://docs.google.com/spreadsheets/d/15DQwTwWBgxNzc6AIkHbsQXMb48lG37Hl/edit?usp=drive_link&ouid=109742698001181905449&rtpof=true&sd=true",
            note: "Actualización trimestral",
            fullWidth: true
          }
        ]
      },
      2026: {
        displayYear: "2026",
        headlineYear: "2026",
        title: "Control Interno 2026",
        subtitle: "Vista preparada con los mismos accesos de 2025, pendiente de agregar enlaces.",
        links: [
          {
            title: "96 Acciones para Informe de Control Interno 2026",
            url: "#",
            note: "Enlace pendiente",
            disabled: true
          },
          {
            title: "PTCI-2026-SECTURI",
            url: "#",
            note: "Enlace pendiente",
            disabled: true
          },
          {
            title: "Reporte anual Comportamiento de Riesgos-2026",
            url: "#",
            note: "Enlace pendiente",
            disabled: true
          },
          {
            title: "Evaluación de Controles-Riesgo Críticos 2026",
            url: "#",
            note: "Enlace pendiente",
            disabled: true
          },
          {
            title: "DDCI y Estrategias CI-2026",
            url: "#",
            note: "Enlace pendiente",
            disabled: true
          },
          {
            title: "B.1. Matriz Evaluación Riesgos SECTURI · 1er trimestre 2026",
            url: "#",
            note: "Enlace pendiente",
            disabled: true
          },
          {
            title: "Proceso de verificación y evaluación de riesgos (Permanencia en matriz)",
            url: "#",
            note: "Enlace pendiente",
            fullWidth: true,
            disabled: true
          },
          {
            title: "Actualización Matriz de Riesgos · 4to. trim. 2026",
            url: "#",
            note: "Enlace pendiente",
            fullWidth: true,
            disabled: true
          }
        ]
      }
    };

    const buttons = {
      2024: document.getElementById("btn-2024"),
      2025: document.getElementById("btn-2025"),
      2026: document.getElementById("btn-2026")
    };

    const linksGrid = document.getElementById("links-grid");
    const heroYear = document.getElementById("hero-year");
    const sectionTitle = document.getElementById("section-title");
    const sectionSubtitle = document.getElementById("section-subtitle");
    const statDisplayYear = document.getElementById("stat-display-year");
    const statTotalLinks = document.getElementById("stat-total-links");

    function makeCard(item) {
      const card = document.createElement(item.disabled ? "div" : "a");
      card.className = `ci-link-card${item.fullWidth ? " ci-full-width" : ""}${item.disabled ? " is-disabled" : ""}`;

      if (!item.disabled) {
        card.href = item.url;
        card.target = "_blank";
        card.rel = "noopener noreferrer";
      }

      card.innerHTML = `
        <div class="ci-link-card__text">
          <strong>${item.title}</strong>
          <span>${item.note}</span>
        </div>
        <div class="ci-link-card__icon">↗</div>
      `;
      return card;
    }

    function render(year) {
      const yearData = controlInternoData[year];
      linksGrid.classList.remove("ci-fade-in");
      void linksGrid.offsetWidth;
      linksGrid.classList.add("ci-fade-in");

      heroYear.textContent = yearData.headlineYear;
      sectionTitle.textContent = yearData.title;
      sectionSubtitle.textContent = yearData.subtitle;
      statDisplayYear.textContent = yearData.displayYear;
      statTotalLinks.textContent = yearData.links.length;

      Object.entries(buttons).forEach(([key, button]) => {
        button.classList.toggle("active", key === String(year));
      });

      linksGrid.replaceChildren(...yearData.links.map(makeCard));
    }

    buttons[2024].addEventListener("click", () => render(2024));
    buttons[2025].addEventListener("click", () => render(2025));
    buttons[2026].addEventListener("click", () => render(2026));

    render(2026);
  })();
  </script>
