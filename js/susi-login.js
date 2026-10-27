
const container = document.querySelector('.login-parallax');
const fondoLogin = document.getElementById('login_random_background');

function seleccionarFondoLogin() {
  if (!fondoLogin) {
    return;
  }

  const fondos = (fondoLogin.dataset.backgrounds || '').split('|').filter(Boolean);
  if (!fondos.length) {
    return;
  }

  let fondoAnterior = '';

  try {
    fondoAnterior = window.sessionStorage.getItem('susi_login_background') || '';
  } catch (error) {
    fondoAnterior = '';
  }

  const fondosDisponibles = fondos.length > 1
    ? fondos.filter((fondo) => fondo !== fondoAnterior)
    : fondos;
  const indice = Math.floor(Math.random() * fondosDisponibles.length);
  const fondoSeleccionado = fondosDisponibles[indice];

  fondoLogin.src = fondoSeleccionado;

  try {
    window.sessionStorage.setItem('susi_login_background', fondoSeleccionado);
  } catch (error) {
    // El fondo sigue funcionando aunque el navegador bloquee sessionStorage.
  }
}

seleccionarFondoLogin();

const parallaxElements = document.querySelectorAll('.parallax');

let targetX = 0;
let targetY = 0;
let animationFrame;

function handleMovement(e) {
  const x = e.touches ? e.touches[0].clientX : e.clientX;
  const y = e.touches ? e.touches[0].clientY : e.clientY;

  const w = window.innerWidth / 2;
  const h = window.innerHeight / 2;

  targetX = (x - w) / w;
  targetY = (y - h) / h;

  if (!animationFrame) {
    animationFrame = requestAnimationFrame(updateParallax);
  }
}

function updateParallax() {
  parallaxElements.forEach((el) => {
    const depth = Number(el.getAttribute('data-depth')) || 1;
    const moveX = targetX * 10 * depth;
    const moveY = targetY * 10 * depth;

    el.style.transform = `translate3d(${moveX}px, ${moveY}px, 0)`;
  });

  animationFrame = null;
}

if (container) {
  container.addEventListener('mousemove', handleMovement);
  container.addEventListener('touchmove', handleMovement, { passive: true });
  container.addEventListener('mouseleave', function () {
    targetX = 0;
    targetY = 0;

    if (!animationFrame) {
      animationFrame = requestAnimationFrame(updateParallax);
    }
  });
}

function iniciarParticulasLogin() {
  const canvas = document.getElementById('login_particles');

  if (!canvas) {
    return;
  }

  const context = canvas.getContext('2d');
  const reducirMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const colores = ['#ff9f1c', '#f15a24', '#ffd166', '#b56cff', '#ffffff'];
  let ancho = 0;
  let alto = 0;
  let escala = 1;
  let particulas = [];

  function crearParticula(aleatoriaEnPantalla) {
    return {
      x: Math.random() * ancho,
      y: aleatoriaEnPantalla ? Math.random() * alto : alto + Math.random() * 80,
      radio: 1.2 + Math.random() * 2.8,
      velocidadX: -0.18 + Math.random() * 0.36,
      velocidadY: -(0.18 + Math.random() * 0.52),
      oscilacion: Math.random() * Math.PI * 2,
      color: colores[Math.floor(Math.random() * colores.length)],
      opacidad: 0.28 + Math.random() * 0.5
    };
  }

  function ajustarCanvas() {
    ancho = window.innerWidth;
    alto = window.innerHeight;
    escala = Math.min(window.devicePixelRatio || 1, 2);
    canvas.width = Math.round(ancho * escala);
    canvas.height = Math.round(alto * escala);
    canvas.style.width = ancho + 'px';
    canvas.style.height = alto + 'px';
    context.setTransform(escala, 0, 0, escala, 0, 0);

    const cantidad = Math.max(28, Math.min(75, Math.round((ancho * alto) / 18000)));
    particulas = Array.from({ length: cantidad }, () => crearParticula(true));
  }

  function dibujar() {
    context.clearRect(0, 0, ancho, alto);

    particulas.forEach((particula) => {
      context.beginPath();
      context.arc(particula.x, particula.y, particula.radio, 0, Math.PI * 2);
      context.fillStyle = particula.color;
      context.globalAlpha = particula.opacidad;
      context.shadowBlur = 8;
      context.shadowColor = particula.color;
      context.fill();
      context.shadowBlur = 0;
    });

    context.globalAlpha = 1;
  }

  function animar() {
    particulas.forEach((particula, indice) => {
      particula.oscilacion += 0.012;
      particula.x += particula.velocidadX + Math.sin(particula.oscilacion) * 0.08;
      particula.y += particula.velocidadY;

      if (particula.y < -12 || particula.x < -12 || particula.x > ancho + 12) {
        particulas[indice] = crearParticula(false);
      }
    });

    dibujar();
    window.requestAnimationFrame(animar);
  }

  ajustarCanvas();
  window.addEventListener('resize', ajustarCanvas);

  if (reducirMovimiento) {
    dibujar();
    return;
  }

  animar();
}

iniciarParticulasLogin();


function ocultarLoaderLogin() {
    const loader = document.getElementById('login_page_loader');

    if (!loader) {
        return;
    }

    loader.classList.add('is-hidden');

    setTimeout(() => {
        if (loader && loader.parentNode) {
            loader.parentNode.removeChild(loader);
        }
    }, 450);
}

function esperarImagenLogin(imagen) {
    return new Promise((resolve) => {
        const finalizar = () => {
            if (imagen.naturalWidth > 0 && typeof imagen.decode === 'function') {
                imagen.decode().catch(() => {}).finally(resolve);
                return;
            }

            resolve();
        };

        if (imagen.complete) {
            finalizar();
            return;
        }

        imagen.addEventListener('load', finalizar, { once: true });
        imagen.addEventListener('error', resolve, { once: true });
    });
}

const imagenesLogin = Array.from(document.querySelectorAll(
    '.login-parallax img, .login-brand-logos img'
));

Promise.all(imagenesLogin.map(esperarImagenLogin)).then(() => {
    requestAnimationFrame(() => {
        requestAnimationFrame(ocultarLoaderLogin);
    });
});



function iniciarGoogle() {
    $('#btn_login').hide();
    $('#btn_load').show();

    setTimeout(() => {
        window.location.href =  new URL('index.php/Auth/login', document.body.dataset.baseUrl).href;
    }, 300);
}



function mostrarLoginGoogle() {
    $('#login_google_access').removeClass('is-hidden');
}

document.getElementById('mostrarLoginGoogleBtn')
    ?.addEventListener('click', mostrarLoginGoogle);

document.getElementById('iniciarGoogleBtn')
    ?.addEventListener('click', iniciarGoogle);


