<style>
    /* Reset y base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    #container {
        position: relative;
        width: 100%;
        max-width: 800px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin: 0 auto;
    }

    /* Header con gradiente */
    .header {
        background: linear-gradient(135deg, #004080 0%, #0066cc 100%);
        padding: 10px;
        text-align: center;
        color: white;
        position: relative;
        
    }

    .header h1 {
        font-size: 2.5em;
        margin-bottom: 10px;
        font-weight: 300;
        
    }

    .header p {
        font-size: 1.1em;
        opacity: 0.9;
    }

    /* Contenido principal */
    .content {
        padding: 30px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    /* Sección de información personal */
    .personal-info {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 15px;
        border-left: 5px solid #004080;
    }

    .info-group {
        margin-bottom: 10px;
    }

    .info-label {
        font-size: 0.9em;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 5px;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 1.1em;
        color: #333;
        font-weight: 500;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }

    /* Sección QR mejorada */
    .qr-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 15px;
        padding: 10px;
        color: white;
        text-align: center;
    }

    .qr-container {
        margin-left: 190px;
        width: 250px;
        height: 250px;
        background: white;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        margin-bottom: 20px;
    }

    #qr {
        width: 100%;
        height: 25%;
        background-image: url('<?= $dataImagen ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 10px;
        text-align: center;
    }

    .qr-text {
        font-size: 0.9em;
        opacity: 0.9;
        line-height: 1.4;
    }

    /* Número de empleado destacado */
    .employee-id {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        color: white;
        padding: 15px 25px;
        border-radius: 25px;
        font-size: 1.3em;
        font-weight: bold;
        text-align: center;
        margin: 20px 0;
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
    }

    /* Footer */
    .footer {
        background: #2c3e50;
        color: white;
        padding: 20px;
        text-align: center;
        font-size: 0.9em;
    }

    .footer p {
        margin: 5px 0;
        opacity: 0.8;
    }

    /* Efectos hover */
    .info-value:hover {
        background: #e3f2fd;
        transform: translateX(5px);
        transition: all 0.3s ease;
    }

    .qr-container:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .content {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        #container {
            margin: 20px;
        }
        
        .header h1 {
            font-size: 2em;
        }
        
        .qr-container {
            width: 150px;
            height: 150px;
        }
    }

    @media (max-width: 480px) {
        .content {
            padding: 20px;
        }
        
        .header {
            padding: 20px;
        }
        
        .employee-id {
            font-size: 1.1em;
            padding: 12px 20px;
        }
    }

    /* Animaciones sutiles */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .content > * {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Badge para información importante */
    .badge {
        display: inline-block;
        padding: 4px 12px;
        background: #004080;
        color: white;
        border-radius: 20px;
        font-size: 0.8em;
        margin-left: 10px;
        vertical-align: middle;
    }
</style>

<div id="container">
    <!-- Header -->
    <div class="header">
        <h1 >SUSI</h1>
        <p>Sistema Unificado SECTURI</p>
    </div>

    <!-- Número de empleado destacado -->
    <div class="employee-id">
        No. Empleado: <span id="no_empleado"><?= $usuario->no_empleado ?? 'N/A' ?></span>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <!-- Información personal -->
        <div class="personal-info">
            <div class="info-group">
                <div class="info-label">Nombre Completo</div>
                <div class="info-value" id="nombre_completo"><?= $usuario->nombre_completo ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">Puesto</div>
                <div class="info-value" id="puesto"><?= $usuario->dsc_puesto ?? '' ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">Área</div>
                <div class="info-value" id="puesto"><?= $usuario->dsc_area ?? '' ?></div>
            </div>
        </div>
   
        <!-- Sección QR -->
        <div class="qr-section">
            
            <div class="qr-container">
               <div id="qr"></div> 
            </div>
           
            <div class="qr-text" >
                <strong>CÓDIGO PARA ASISTENCIA</strong><br>
                Este código funciona exclusivamente dentro de las instalaciones de SECTURI y está destinado únicamente al registro de asistencia de entrada. No permite el registro de salidas ni de comisiones, y su uso se encuentra limitado por la geocerca establecida en la sección de asistencia
            </div>
          
        </div>
    </div>


</div>

<script>
    // Efectos interactivos adicionales
    document.addEventListener('DOMContentLoaded', function() {
        // Efecto de aparición escalonada
        const infoGroups = document.querySelectorAll('.info-group');
        infoGroups.forEach((group, index) => {
            group.style.animationDelay = `${index * 0.1}s`;
        });
        
        // Efecto hover en el QR
        const qrContainer = document.querySelector('.qr-container');
        qrContainer.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05) rotate(2deg)';
        });
        
        qrContainer.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    });
</script>