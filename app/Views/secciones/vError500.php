    <h2>Obteniendo ubicación...</h2>
    <p id="status">Esperando GPS...</p>

    <script>
    const id_user = <?= json_encode($id_user) ?>;
    const statusEl = document.getElementById("status");

    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const latitud = position.coords.latitude;
            const longitud = position.coords.longitude;
            const precision = position.coords.accuracy;

            statusEl.textContent = `Ubicación detectada: ${latitud}, ${longitud}`;

            fetch("<?= base_url('index.php/Login/guardarUbicacion') ?>", {
                method: "POST",
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_user,
                    latitud,
                    longitud,
                    precision
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    statusEl.textContent += " ✅ Enviada al servidor.";
                } else {
                    statusEl.textContent += " ❌ Error al guardar.";
                }
            });
        }, function(error) {
            statusEl.textContent = "No se pudo obtener la ubicación.";
        });
    } else {
        statusEl.textContent = "Este dispositivo no soporta geolocalización.";
    }
    </script>