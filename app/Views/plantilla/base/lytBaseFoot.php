   <!-- jQuery  -->
    
      
        <script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/metismenu.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/waves.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/feather.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
   
        
        <script src="<?php echo base_url(); ?>plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/jvectormap/jquery-jvectormap-us-aea-en.js"></script>
        
        <!-- App js -->
        <script src="<?php echo base_url(); ?>assets/js/app.js?v=<?php echo filemtime(FCPATH . 'assets/js/app.js'); ?>"></script>
 
         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
         <script>
// Funcionalidad de Accesibilidad
document.addEventListener('DOMContentLoaded', function() {
    // Configuración de accesibilidad
    const accessibilityOptions = document.querySelectorAll('.accessibility-option');
    
    accessibilityOptions.forEach(option => {
        option.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            applyAccessibility(action);
        });
    });
    
    function applyAccessibility(action) {
        // Remover todas las clases de accesibilidad primero
        document.body.classList.remove('large-text', 'extra-large-text', 'high-contrast', 'grayscale', 'readable-font');
        
        switch(action) {
            case 'increase-text':
                document.body.classList.add('large-text');
                break;
            case 'decrease-text':
                // Por defecto ya es texto normal
                break;
            case 'toggle-grayscale':
                document.body.classList.toggle('grayscale');
                break;
            case 'normal-contrast':
                // Por defecto ya es contraste normal
                break;
            case 'high-contrast':
                document.body.classList.add('high-contrast');
                break;
            case 'readable-font':
                document.body.classList.add('readable-font');
                break;
            case 'reset-all':
                // Solo remover clases, ya se hizo al inicio
                break;
        }
        
        // Cerrar el modal después de seleccionar una opción (opcional)
        $('.modal-rightbar').modal('hide');
    }
    
    // Persistir configuración entre páginas (opcional)
    function loadSavedPreferences() {
        const savedPrefs = localStorage.getItem('accessibilityPreferences');
        if (savedPrefs) {
            const prefs = JSON.parse(savedPrefs);
            Object.keys(prefs).forEach(pref => {
                if (prefs[pref]) {
                    document.body.classList.add(pref);
                }
            });
        }
    }
    
    function savePreferences() {
        const prefs = {
            'large-text': document.body.classList.contains('large-text'),
            'extra-large-text': document.body.classList.contains('extra-large-text'),
            'high-contrast': document.body.classList.contains('high-contrast'),
            'grayscale': document.body.classList.contains('grayscale'),
            'readable-font': document.body.classList.contains('readable-font')
        };
        localStorage.setItem('accessibilityPreferences', JSON.stringify(prefs));
    }
    
    // Cargar preferencias guardadas al cargar la página
    loadSavedPreferences();
    
    // Guardar preferencias cuando se cambien
    accessibilityOptions.forEach(option => {
        option.addEventListener('click', savePreferences);
    });
});
</script>
    </body>
</html>
