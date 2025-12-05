<style>
.qr-wrapper {
    position: absolute;
    top: 215mm;
    left: 28mm;
    width: 30mm;
    height: 30mm;
    background: white;
    z-index: 10;
}
.nombre {
    position: absolute;
    top: 225mm;
    left: 60mm;
    width: 100mm;
    height: 5mm;
    z-index: 10;
    color:white;
}

.qr-wrapper img {
    width: 100%;
    height: 100%;
    border-radius: 5mm;
}
</style>

<div class="qr-wrapper">
    <img src="<?= $dataImagen ?>" />
</div>
<div class="nombre">
   <strong> <?= $nombre ?></strong>
</div>
