<!-- Log In page -->
 <style>

.logo-bottom {
  position: fixed;
  bottom: 40px;
  left: 40px;
  z-index: 9999;
}

#lgo {
    background-color: white;
    border: solid 2px #506ee4;
    border-radius: 25px;
}




 </style>

<div class="container">
    <div class="row vh-100 ">
        <div class="col-12 align-self-center">
            <div class="auth-page">
                <div class="card auth-card shadow-lg">
                    <div class="card-body">
                        <div class="px-3">
                            <div class="auth-logo-box">
                                <a class="logo logo-admin"><img
                                        src="<?= base_url(); ?>assets/huella.png" height="80" alt="logo"
                                        class="auth-logo"></a>
                            </div>
                            <!--end auth-logo-box-->

                            <div class="text-center auth-logo-text">
                                <h4 class="mt-0 mb-3 mt-5">SUSI</h4>
                                <p class="text-muted mb-0">SISTEMA UNIFICADO SECTURI</p>
                            </div>
                            <!--end auth-logo-text-->
                            <div class="form-horizontal auth-form my-4">

                                <div class="form-group">
                                    <label for="usuario">Persona Usuaria</label>
                                    <div class="input-group mb-3">
                                        <span class="auth-form-icon">
                                            <i class="dripicons-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="usuario" name="usuario"
                                            placeholder="Ingresar Clave">
                                    </div>
                                </div>
                                <!--end form-group-->
                                <div class="form-group">
                                    <label for="contrasenia">Contraseña</label>
                                    <div class="input-group mb-3">
                                      <span class="auth-form-icon">
                                        <i id="icon-pass" onclick="showPass();" class="dripicons-lock" style="cursor:pointer"></i>
                                      </span>
                                      <input type="password" class="form-control" id="contrasenia" name="contrasenia" placeholder="Ingresar Contraseña">
                                    </div>
                                </div>
                                <!--end form-group-->

                                <div class="form-group row mt-4">
                                    <div class="col-sm-6">
                                        <div class="custom-control custom-switch switch-success">
                                            <input type="checkbox" class="custom-control-input"
                                                id="customSwitchSuccess">
                                            <label class="custom-control-label text-muted"
                                                for="customSwitchSuccess">Recordar</label>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-sm-6 text-right">
                                        <a href="javascript:void(0)" onclick="losePass();" class="text-muted font-13"><i
                                                class="dripicons-lock"></i> ¿Olvido su contraseña?</a>
                                    </div>
                             
                                </div>
                                <!--end form-group-->

                                <div class="form-group mb-0 row" id="btn_login">
                                    <div class="col-12 mt-2">
                                        <button
                                            class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light"
                                            onclick="saeg.principal.login();" type="button">Ingresar <i
                                                class="fas fa-sign-in-alt ml-1"></i></button>

                                    </div>
                                    <!--end col-->
                                </div>
                                <div class="form-group mb-0 row" id="btn_load" style="display:none;">
                                    <div class="col-12 mt-2">
                                        <button
                                            class="btn btn-gradient-primary  btn-round btn-block waves-effect waves-light"
                                            type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Validando...
                                        </button>
                                    </div>
                                     
                                </div>
                                <!--end form-group-->
                            </div>
                            <!--end form-->
                        </div>
                        <!--end /div-->

                        <!--     <div class="m-3 text-center text-muted">
                            <p class="">Don't have an account ?  <a href="../authentication/auth-register.html" class="text-primary ml-2">Free Resister</a></p>
                        </div> -->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
                <div class="account-social text-center mt-4">
                    <h6 class="my-4">   Version 1.6</h6>
                    <ul class="list-inline mb-4">
                      
                         <li class="list-inline-item">
                         <!--    <a href="<?= base_url() . 'index.php/Auth/login' ?>" class="">
                                <i class="fab fa-google google"></i>
                            </a> -->
                        </li> 
                    </ul>
                </div>
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

                <!--end account-social-->
            </div>
         
            <!--end auth-page-->
        </div>
      
        <!--end col-->
    </div>
    
    <!--end row-->
</div>
<div class="logo-bottom">
  <img src="<?= base_url(); ?>assets/logo-guanajuato.png" height="75" alt="logo" id="lgo" >
</div>
<script>

function showPass() {
  const input = document.getElementById("contrasenia");
  const icon  = document.getElementById("icon-pass");
  const show  = input.type === "password";

  input.type = show ? "text" : "password";

  // Dripicons: usa lock / lock-open (NO 'unlock')
  icon.classList.toggle("dripicons-lock", !show);
  icon.classList.toggle("dripicons-lock-open", show);

  // opcional accesibilidad / tooltip
  icon.setAttribute("aria-pressed", show ? "true" : "false");
  icon.title = show ? "Ocultar contraseña" : "Mostrar contraseña";
}
function  losePass() {
    Swal.fire("Para restablecer la contraseña", '<p>Favor de comunicarte con el Administrador</p>', 'info'); 
}

</script>




<!--end container-->
<!-- End Log In page -->
