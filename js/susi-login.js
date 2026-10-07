window.location.href = new URL('index.php/Auth/login', document.body.dataset.baseUrl).href;
const container = document.querySelector('.login-parallax');
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

container.addEventListener('mousemove', handleMovement);
container.addEventListener('touchmove', handleMovement, { passive: true });
container.addEventListener('mouseleave', function () {
  targetX = 0;
  targetY = 0;

  if (!animationFrame) {
    animationFrame = requestAnimationFrame(updateParallax);
  }
});


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
        window.location.href = '<?= base_url("index.php/Auth/login") ?>';
    }, 300);
}



function mostrarLoginGoogle() {
    $('#login_google_access').removeClass('is-hidden');
}

document.getElementById('mostrarLoginGoogleBtn')
    ?.addEventListener('click', mostrarLoginGoogle);

document.getElementById('iniciarGoogleBtn')
    ?.addEventListener('click', iniciarGoogle);


