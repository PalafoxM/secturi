<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html,
body {
  width: 100%;
  height: 100%;
  min-height: 100%;
  overflow: hidden;
}

body {
  background: #d9e1e8;
}

.login-page-loader {
  position: fixed;
  inset: 0;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 18px;
  color: #17436f;
  background:
    radial-gradient(circle at 50% 38%, rgba(255, 255, 255, 0.96), rgba(232, 240, 247, 0.98) 58%, #dce7f0 100%);
  opacity: 1;
  visibility: visible;
  transition: opacity 0.45s ease, visibility 0.45s ease;
}

.login-page-loader.is-hidden {
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
}

.login-page-loader__spinner {
  width: 58px;
  height: 58px;
  border: 5px solid rgba(31, 115, 216, 0.16);
  border-top-color: #1f73d8;
  border-right-color: #7b2cbf;
  border-radius: 50%;
  animation: login-page-spin 0.85s linear infinite;
}

.login-page-loader__text {
  font-family: Arial, sans-serif;
  font-size: 15px;
  font-weight: 700;
  letter-spacing: 0.02em;
}

@keyframes login-page-spin {
  to {
    transform: rotate(360deg);
  }
}

.login-parallax {
  position: fixed;
  inset: 0;
  width: 100vw;
  height: 100vh;
  height: 100dvh;
  overflow: hidden;
  background: #d9e1e8;
}

.parallax-wrapper {
  position: absolute;
  inset: 0;
  overflow: hidden;
}

.login-background {
  filter: brightness(0.82) saturate(1.08);
}

.login-particles {
  position: absolute;
  inset: 0;
  z-index: 5;
  width: 100%;
  height: 100%;
  pointer-events: none;
}

.login-background-shade {
  position: absolute;
  inset: 0;
  z-index: 2;
  background:
    radial-gradient(circle at 50% 45%, transparent 20%, rgba(21, 8, 35, 0.18) 100%),
    linear-gradient(180deg, rgba(16, 7, 28, 0.12), rgba(16, 7, 28, 0.28));
  pointer-events: none;
}

.parallax {
  position: absolute;
  top: -60px;
  left: -60px;
  width: calc(100% + 120px);
  height: calc(100% + 120px);
  max-width: none;
  object-fit: cover;
  object-position: center center;
  opacity: 0;
  pointer-events: none;
  will-change: transform;
  animation: fadeIn 1s ease-out forwards;
  transition: transform 0.16s ease-out;
}

.logo {
  position: absolute;

  top: 30%;
  left: 50%;
  width: clamp(300px, 45vw, 720px);
  max-width: 82vw;
  height: auto;
  transform: translate(-50%, -50%);
  pointer-events: none;
}

/* Fade-in Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@media (max-width: 768px) {
  .parallax {
    object-position: center center;
  }

  .logo {
    top: 46%;
    width: min(78vw, 520px);
  }
}

@media (prefers-reduced-motion: reduce) {
  .parallax {
    animation: none;
    opacity: 1;
    transition: none;
  }

  .login-page-loader__spinner {
    animation-duration: 1.8s;
  }
}

   .login-auth-panel {
        position: fixed;
        top: 55%;
        left: 50%;
        z-index: 25;
        width: min(92vw, 420px);
        padding: 28px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 20px 60px rgba(16, 24, 40, 0.22);
        transform: translate(-50%, -50%);
    }

    .login-auth-panel .alert {
        margin-bottom: 14px;
        padding: 10px 12px;
        border-radius: 8px;
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    .login-auth-panel .alert-danger {
        color: #842029;
        background: #f8d7da;
        border: 1px solid #f5c2c7;
    }

    .login-auth-panel .alert-success {
        color: #0f5132;
        background: #d1e7dd;
        border: 1px solid #badbcc;
    }
     .login-auth-panel .btn {
        display: inline-block;
        width: 100%;
        border: 0;
        padding: 13px 18px;
        border-radius: 999px;
        color: #fff;
        font-family: Arial, sans-serif;
        font-size: 15px;
        font-weight: 700;
        text-align: center;
        cursor: pointer;
    }

   .login-auth-panel .btn-gradient-primary {
        background: linear-gradient(135deg, #1f73d8, #7b2cbf);
        box-shadow: 0 12px 28px rgba(31, 115, 216, 0.28);
    }

    .login-auth-panel .btn:disabled {
        cursor: wait;
        opacity: 0.82;
    }

    .login-auth-panel .spinner-border {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        margin-right: 8px;
        vertical-align: -2px;
        border: 2px solid rgba(255, 255, 255, 0.45);
        border-top-color: #fff;
        border-radius: 50%;
        animation: login-spinner 0.7s linear infinite;
    }

    @keyframes login-spinner {
        to {
            transform: rotate(360deg);
        }
    }

    .login-google-access.is-hidden {
        display: none;
    }

    .login-traditional-form {
        display: none;
    }

    .login-traditional-form.is-active {
        display: block;
    }

    .login-traditional-form__title {
        margin: 0 0 20px;
        color: #17365f;
        font-family: Arial, sans-serif;
        font-size: 23px;
        font-weight: 700;
        text-align: center;
    }

    .login-traditional-form .form-group {
        margin-bottom: 16px;
    }

    .login-traditional-form label {
        display: block;
        margin-bottom: 7px;
        color: #344767;
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-weight: 700;
    }

    .login-traditional-form .form-control {
        display: block;
        width: 100%;
        min-height: 46px;
        padding: 10px 14px;
        border: 1px solid #cfd9e6;
        border-radius: 10px;
        outline: none;
        color: #253858;
        background: rgba(255, 255, 255, 0.98);
        font-family: Arial, sans-serif;
        font-size: 15px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .login-traditional-form .form-control:focus {
        border-color: #3979db;
        box-shadow: 0 0 0 3px rgba(57, 121, 219, 0.16);
    }

    .login-password-field {
        position: relative;
    }

    .login-password-field .form-control {
        padding-right: 48px;
    }

    .login-password-toggle {
        position: absolute;
        top: 50%;
        right: 8px;
        width: 34px;
        height: 34px;
        border: 0;
        border-radius: 50%;
        color: #526887;
        background: transparent;
        font-size: 18px;
        line-height: 1;
        cursor: pointer;
        transform: translateY(-50%);
    }

    .login-password-toggle:hover,
    .login-password-toggle:focus {
        color: #1f73d8;
        background: rgba(31, 115, 216, 0.1);
        outline: none;
    }

    .login-google-link {
        display: block;
        width: 100%;
        margin-top: 14px;
        border: 0;
        color: #2869c7;
        background: transparent;
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-weight: 700;
        text-align: center;
        cursor: pointer;
    }

    .login-google-link:hover,
    .login-google-link:focus {
        text-decoration: underline;
        outline: none;
    }

    .login-brand-logos {
        position: fixed;
        top: 24px;
        z-index: 20;
        display: flex;
        align-items: center;
        justify-content: center;
        max-width: min(30vw, 260px);
        pointer-events: none;
    }

    .login-brand-logos--left {
        left: 28px;
    }

    .login-brand-logos--right {
        right: 28px;
    }

    .login-brand-logos img {
        display: block;
        max-width: 100%;
        max-height: 92px;
        object-fit: contain;
    }


 </style> 

  <div id="login_page_loader" class="login-page-loader" role="status" aria-live="polite" aria-label="Cargando imágenes del inicio de sesión">
      <span class="login-page-loader__spinner" aria-hidden="true"></span>
      <span class="login-page-loader__text">Cargando inicio de sesión...</span>
  </div>

   <div class="login-brand-logos login-brand-logos--left" style="pointer-events: auto; cursor: pointer;" id="mostrarLoginTradicionalBtn" role="button" tabindex="0" aria-label="Mostrar acceso con usuario y contraseña" title="Acceso con usuario y contraseña">
        <img src="<?= base_url() ?>assets/images/logo-guanajuato.png" alt="Marca Guanajuato">
    </div>

    <div class="login-parallax">
   
    <div class="parallax-wrapper">
      <img
        id="login_random_background"
        class="parallax login-background"
        data-depth="1"
        data-backgrounds="<?= esc(implode('|', [
            base_url('assets/images/backgrounds/globo_03.jpg'),
            base_url('assets/images/backgrounds/globo_gto.jpg'),
            base_url('assets/images/backgrounds/globos_01.jpg'),
            base_url('assets/images/backgrounds/globo_02.jpg'),
        ]), 'attr') ?>"
        alt=""
      />
      <span class="login-background-shade" aria-hidden="true"></span>
      <canvas id="login_particles" class="login-particles" aria-hidden="true"></canvas>
     </div>
  </div>
   <div class="login-auth-panel">
    

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div id="login_google_access" class="login-google-access">
            <div class="form-group mb-0 row" id="btn_login">
                <div class="col-12 mt-2">
                    <button
                        class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light"
                        id="iniciarGoogleBtn" type="button">Ingresar a SUSI con Google <i class="fab fa-google google mr-2"></i></button>
                </div>
            </div>
            <div class="form-group mb-0 row" id="btn_load" style="display:none;">
                <div class="col-12 mt-2">
                    <button
                        class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light"
                        type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status"
                            aria-hidden="true"></span>
                        Validando...
                    </button>
                </div>
            </div>
        </div>

        <div id="login_traditional_access" class="login-traditional-form" aria-hidden="true">
            <h2 class="login-traditional-form__title">Acceso tradicional</h2>
            <form id="loginTraditionalForm" autocomplete="on" novalidate>
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input
                        type="text"
                        class="form-control"
                        id="usuario"
                        name="usuario"
                        placeholder="Ingresa tu usuario"
                        autocomplete="username"
                        required>
                </div>

                <div class="form-group">
                    <label for="contrasenia">Contraseña</label>
                    <div class="login-password-field">
                        <input
                            type="password"
                            class="form-control"
                            id="contrasenia"
                            name="contrasenia"
                            placeholder="Ingresa tu contraseña"
                            autocomplete="current-password"
                            required>
                        <button
                            type="button"
                            id="toggleLoginPassword"
                            class="login-password-toggle"
                            aria-label="Mostrar contraseña"
                            aria-pressed="false"
                            title="Mostrar contraseña">&#128065;</button>
                    </div>
                </div>

                <button id="btnTraditionalLogin" class="btn btn-gradient-primary" type="submit">
                    Ingresar
                </button>
                <button id="mostrarLoginGoogleBtn" class="login-google-link" type="button">
                    Ingresar con Google
                </button>
            </form>
        </div>

    </div>
  
 <script src="<?= base_url('js/susi-login.js') ?>?v=<?= time() ?>"></script>
