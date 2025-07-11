<!-- Log In page -->
<div id="particles-js"></div>
<div class="container">
    <div class="row vh-100 ">
        <div class="col-12 align-self-center">
            <div class="auth-page">
                <div class="card auth-card shadow-lg">
                    <div class="card-body">
                        <div class="px-3">
                            <div class="auth-logo-box">
                                <a href="../dashboard/analytics-index.html" class="logo logo-admin"><img
                                        src="<?php echo base_url();?>assets/images/backgrounds/logo.png" height="55" alt="logo"
                                        class="auth-logo"></a>
                            </div>
                            <!--end auth-logo-box-->

                            <div class="text-center auth-logo-text">
                                <h4 class="mt-0 mb-3 mt-5">SUSI</h4>
                                <p class="text-muted mb-0">Sistema Unificado SECTURI</p>
                            </div>
                            <!--end auth-logo-text-->
                            <div class="form-horizontal auth-form my-4">

                                <div class="form-group">
                                    <label for="usuario">Usuario</label>
                                    <div class="input-group mb-3">
                                        <span class="auth-form-icon">
                                            <i class="dripicons-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="usuario" name="usuario"
                                            placeholder="Ingresar Usuario">
                                    </div>
                                </div>
                                <!--end form-group-->
                                <div class="form-group">
                                    <label for="contrasenia">Contraseña</label>
                                    <div class="input-group mb-3">
                                        <span class="auth-form-icon">
                                            <i class="dripicons-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" id="contrasenia" name="contrasenia"
                                            placeholder="Ingresar Contraseña">
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
                                        <a href="auth-recover-pw.html" class="text-muted font-13"><i
                                                class="dripicons-lock"></i> ¿Olvido su contraseña?</a>
                                    </div>
                                    <!--end col-->
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
                                    <!--end col-->
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
                    <h6 class="my-4">Ingresar con</h6>
                    <ul class="list-inline mb-4">
                        <li class="list-inline-item">
                            <a href="" class="">
                                <i class="fab fa-facebook-f facebook"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="" class="">
                                <i class="fab fa-twitter twitter"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="" class="">
                                <i class="fab fa-google google"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <!--end account-social-->
            </div>
            <!--end auth-page-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
</div>
<!--end container-->
<!-- End Log In page -->
