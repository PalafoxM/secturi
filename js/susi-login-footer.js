function hideLoginLoading() {
    var loader = document.getElementById('loginLoadingScreen');
    if (!loader) {
        return;
    }

    loader.classList.add('is-hidden');
    setTimeout(function () {
        if (loader.parentNode) {
            loader.parentNode.removeChild(loader);
        }
    }, 500);
}

window.addEventListener('load', function () {
    setTimeout(hideLoginLoading, 250);
});

(function (window) {
    if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.parallax) {
        return;
    }

    var $scene = window.jQuery('#scene');
    if (!$scene.length) {
        return;
    }

    function resizeScene() {
        $scene[0].style.width = window.innerWidth + 'px';
        $scene[0].style.height = window.innerHeight + 'px';
    }

    resizeScene();
    $scene.parallax();
    window.addEventListener('resize', resizeScene);
})(window);

(function (window, document) {
    var tokenMeta = document.querySelector('meta[name="csrf-token"]');
    var headerMeta = document.querySelector('meta[name="csrf-header"]');
    if (!tokenMeta || !headerMeta || !window.jQuery) {
        return;
    }

    window.FicCsrf = {
        token: tokenMeta.getAttribute('content'),
        header: headerMeta.getAttribute('content')
    };

    window.jQuery.ajaxSetup({
        beforeSend: function (xhr) {
            xhr.setRequestHeader(window.FicCsrf.header, window.FicCsrf.token);
        }
    });
})(window, document);