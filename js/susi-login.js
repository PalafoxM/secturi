
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
  const colores = ['#fff7a8', '#ffe66d', '#ffd43b', '#d9ff70'];
  let ancho = 0;
  let alto = 0;
  let escala = 1;
  let tiempo = 0;
  let particulas = [];

  function crearParticula(aleatoriaEnPantalla) {
    return {
      x: Math.random() * ancho,
      y: aleatoriaEnPantalla ? Math.random() * alto : alto + 15 + Math.random() * 60,
      radio: 1.1 + Math.random() * 2.1,
      velocidadX: -0.06 + Math.random() * 0.12,
      velocidadY: -(0.04 + Math.random() * 0.12),
      faseViento: Math.random() * Math.PI * 2,
      frecuenciaViento: 0.007 + Math.random() * 0.01,
      fuerzaViento: 0.08 + Math.random() * 0.18,
      faseBrillo: Math.random() * Math.PI * 2,
      frecuenciaBrillo: 0.018 + Math.random() * 0.025,
      color: colores[Math.floor(Math.random() * colores.length)],
      opacidad: 0.32 + Math.random() * 0.48
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

    const cantidadBase = ancho < 768 ? 18 : 32;
    const cantidad = Math.min(52, cantidadBase + Math.round((ancho * alto) / 80000));
    particulas = Array.from({ length: cantidad }, () => crearParticula(true));
  }

  function dibujarLuciernaga(particula) {
    const pulso = 0.42 + ((Math.sin(
      tiempo * particula.frecuenciaBrillo + particula.faseBrillo
    ) + 1) / 2) * 0.58;
    const opacidad = particula.opacidad * pulso;
    const radioBrillo = particula.radio * (4.5 + pulso * 2.5);

    context.save();
    context.translate(particula.x, particula.y);
    context.globalAlpha = opacidad * 0.45;
    const halo = context.createRadialGradient(0, 0, 0, 0, 0, radioBrillo);
    halo.addColorStop(0, particula.color);
    halo.addColorStop(0.35, 'rgba(255, 230, 109, 0.55)');
    halo.addColorStop(1, 'rgba(255, 214, 59, 0)');
    context.fillStyle = halo;
    context.beginPath();
    context.arc(0, 0, radioBrillo, 0, Math.PI * 2);
    context.fill();

    context.globalAlpha = Math.min(1, opacidad + 0.25);
    context.fillStyle = particula.color;
    context.beginPath();
    context.arc(0, 0, particula.radio, 0, Math.PI * 2);
    context.fill();
    context.restore();
  }

  function dibujar() {
    context.clearRect(0, 0, ancho, alto);

    particulas.forEach((particula) => {
      dibujarLuciernaga(particula);
    });

    context.globalAlpha = 1;
  }

  function animar() {
    tiempo += 1;

    particulas.forEach((particula, indice) => {
      const viento = Math.sin(
        tiempo * particula.frecuenciaViento + particula.faseViento
      ) * particula.fuerzaViento;

      particula.x += particula.velocidadX + viento;
      particula.y += particula.velocidadY + Math.cos(
        tiempo * particula.frecuenciaViento * 0.7 + particula.faseViento
      ) * 0.05;

      const margen = 20;
      if (particula.y < -margen || particula.x < -margen || particula.x > ancho + margen) {
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


