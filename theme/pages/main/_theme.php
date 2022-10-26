<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        Petrotrans | <?= $title ?>
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link rel="icon" href="<?= url('theme/images/logo.svg') ?>">
    <!-- CSS Files -->
    <link href="<?= url('theme/css/bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= url('theme/css/now-ui-dashboard.css?v=1.5.0') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-typeahead/2.11.0/jquery.typeahead.min.css" integrity="sha512-7zxVEuWHAdIkT2LGR5zvHH7YagzJwzAurFyRb1lTaLLhzoPfcx3qubMGz+KffqPCj2nmfIEW+rNFi++c9jIkxw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>
</head>
<body>
<div class="wrapper">

    <!-- custom styles -->
    <style>
    *{
        font-style: sans-serif;
    }

    .menu-item.ocult {
            display: none;
        }

        /*input style*/
    .form-control:active, .form-control:focus{
        border: solid 1px #008DD5;

    }

    .fa-bars{
        color: #373f51;
    }

    .global-button{
        color: #008DD5;
        transition: .1s ease;
    }

    .global-button:hover{
        text-decoration: none;
        color: #373f51;

    }

    a{
        color: #008DD5;
        transition: .3s ease;
    }

    
    a:hover{
        text-decoration: none;
        color: #373f51;
    }
    a:after{
        color: #373f51;
        text-decoration: none;
    }
    a:active{
        color: #373f51;
        text-decoration: none;
    }

    tr{
        color: #373f51;
    }

    td a{
        color: #008DD5;
        transition: .3s ease-in-out;
    }
    td a:hover{
        color: #373f51;
        text-decoration: none;
    }

    td a:after:active{
        color: #373f51;
        text-decoration: none;
    }

    .menuOc{
        color: #008DD5;
    }

    .paginator_item{
        background: #008DD5;
        transition: .5s ease;
    }

    .paginator_item:hover{
        background: #373f51;
        text-decoration: none;
    }

    .paginator_active{
        background: #373f51;
    }

        /* buttons */
    .btn {
        padding: 15px 25px;
        border: unset;
        border-radius: 10px;
        color: #fff;
        z-index: 1;
        background: #008DD5;
        position: relative;
        font-weight: bold;
        font-size: 11px;
        -webkit-box-shadow: 4px 8px 19px -3px rgba(0,0,0,0.27);
        box-shadow: 4px 8px 19px -3px rgba(0,0,0,0.27);
        transition: all .7s;
        overflow: hidden;
        margin-top: 1px;
        }

    .btn::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 0;
        border-radius: 10px;
        background-color: #373f51;
        z-index: -1;
        -webkit-box-shadow: 4px 8px 19px -3px rgba(0,0,0,0.27);
        box-shadow: 4px 8px 19px -3px rgba(0,0,0,0.27);
        transition: all .5s;
    }

    .btn:hover {
        color: #008DD5;
        background-color: #008DD5;
        }

    .btn:hover::before {
        width: 100%;
        }


    .empresas{
        white-space: nowrap;
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .form-control{
        height: 40px;
        border-radius: 10px;
    }

    .dados_td{
        color: #008DD5;
    }

    #desconto_check{
        height: 20px;
    }
    #dono{
        height: 20px;
    }

    /*modal whatsapp*/

    .items{ 
        border: solid 2px #008DD5;
        border-radius: 10px;
    }

    .cadastrar-transportes{
        border: solid 2px #008DD5;
        border-radius: 10px
    }

    .add-button:before{
        color: #373f51
    }


    </style>

    <div class="sidebar" data-color="green">
        <!--
          Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
      -->
        <div class="logo">
            <a style="text-align: center" href="<?= url('main'); ?>" class="simple-text logo-normal">
                <img style="height: 8rem; border-radius: 100%; background-color: white; padding: 5px" src="<?= url('theme/images/logo.png') ?>" alt="Logo Petrotrans">
            </a>
        </div>
        <div class="sidebar-wrapper" id="sidebar-wrapper">
            <ul class="nav" style="font-size: .9rem">

                <?php
                 $userLogado = (new \Source\Models\Users())->findById($_SESSION['id']);
                 $userL = (!empty($userLogado->empresa) ? false : true);
                ?>
                <li>
                    <a href="<?= $router->route('Main.transportes', ['categoria' => 'liberado']) ?>">
                        <i class="fas fa-truck"></i>
                        <p>Transportes</p>
                    </a>
                </li>
                <?php
                    if($userL):
                ?>
                <li>
                    <a href="<?= $router->route('Mailer.email', ['type' => 'Petrotrans', 'id' => 'none']) ?>">
                        <i class="fas fa-inbox"></i>
                        <p>Enviar Email</p>
                    </a>
                </li>

                <li>
                    <a href="<?= $router->route('Main.whatsapp') ?>">
                        <i class="fas fa-inbox"></i>
                        <p>Enviar Whatsapp</p>
                    </a>
                </li>
                        <?php
                    endif;
                ?>
                <li style="border-bottom: 1px solid white; border-top: 1px solid white">
                    <a href="#" id="cadastro">
                        <p style="font-weight: normal; font-size: 1.1em; margin-left: .5rem"> Cadastro    <i class="fas fa-chevron-down"></i></p>
                    </a>
                </li>

                <li class="cadastro menu-item ocult">
                    <a href="<?= $router->route('Main.main') ?>">
                        <i class="far fa-user"></i>
                        <p>perfil</p>
                    </a>
                </li>

                <?php
                if($userL):
                ?>
                    <li class="cadastro menu-item ocult">
                        <a href="<?= $router->route('Main.usuarios') ?>">
                            <i class="far fa-user"></i>
                            <p>Usuários</p>
                        </a>
                    </li>
                <li class="cadastro menu-item ocult">
                    <a style="padding-bottom: 0" href="<?= $router->route('Main.empresas') ?>">
                        <i class="fas fa-city"></i>
                        <p>Empresas</p>
                    </a>
                </li>
                <li class="cadastro menu-item ocult">
                    <a href="<?= $router->route('Empresas.petrotrans') ?>">
                        <i class="fas fa-cog"></i>
                        <p>Petrotrans</p>
                    </a>
                </li>

                <li class="cadastro menu-item ocult">
                    <a href="<?= $router->route('Main.aprovar') ?>">
                        <i class="fas fa-check-circle"></i>
                        <p>Aprovar</p>
                    </a>
                </li>
                <li class="cadastro menu-item ocult">
                    <a href="<?= $router->route('Main.cadastrar') ?>">
                        <i class="fas fa-plus"></i>
                        <p>Cadastrar</p>
                    </a>
                </li>
                <li class="cadastro menu-item ocult">
                    <a href="<?= $router->route('Main.msgdefault') ?>">
                        <i class="fas fa-plus"></i>
                        <p>Mensagem Padrão</p>
                    </a>
                </li>

                    <li style="border-bottom: 1px solid white; border-top: 1px solid white">
                        <a href="#" id="config">
                            <p style="font-weight: normal; font-size: 1.1em; margin-left: .5rem"> Configurações    <i class="fas fa-chevron-down"></i></p>
                        </a>
                    </li>
                    <li class="config menu-item ocult">
                        <a href="<?= $router->route('Main.email') ?>">
                            <i class="fas fa-cog"></i>
                            <p>E-mails</p>
                        </a>
                    </li>
                    <li class="config menu-item ocult">
                        <a href="<?= $router->route('Main.configEmail') ?>">
                            <i class="fas fa-cog"></i>
                            <p>Cron</p>
                        </a>
                    </li>
                <?php
                    endif;
                ?>
                <li style="border-bottom: 1px solid white; border-top: 1px solid white">
                    <a href="#" id="comercial">
                        <p style="font-weight: normal; font-size: 1.1em; margin-left: .5rem"> Comercial    <i class="fas fa-chevron-down"></i></p>
                    </a>
                </li>

                <li class="comercial menu-item ocult">
                    <a href="<?= $router->route('Main.table') ?>">
                        <i class="fas fa-table"></i>
                            <p>Cotações</p>
                    </a>
                </li>

                <?php
                    if($userL):
                ?>
                <li class="comercial menu-item ocult">
                    <a href="<?= $router->route('Main.contatos') ?>">
                        <i class="fas fa-users"></i>
                        <p>Contatos</p>
                    </a>
                </li>

                <li style="border-bottom: 1px solid white; border-top: 1px solid white">
                    <a href="#" id="operacional">
                        <p style="font-weight: normal; font-size: 1.1em; margin-left: .5rem"> Operacional    <i class="fas fa-chevron-down"></i></p>
                    </a>
                </li>
                <li class="operacional menu-item ocult">
                    <a href="<?= $router->route('Main.motoristas') ?>">
                        <i class="fas fa-dharmachakra"></i>
                        <p>Motoristas</p>
                    </a>
                </li>
                <li class="operacional menu-item ocult">
                    <a href="<?= $router->route('Main.coletas') ?>">
                        <i class="fas fa-box"></i>
                        <p>Ordem de Coleta</p>
                    </a>
                </li>

                <li style="border-bottom: 1px solid white; border-top: 1px solid white">
                    <a href="#" id="fiscal">
                        <p style="font-weight: normal; font-size: 1.1em; margin-left: .5rem"> Fiscal    <i class="fas fa-chevron-down"></i></p>
                    </a>
                </li>
                <li class="fiscal menu-item ocult">
                    <a href="<?= $router->route('Main.fiscal') ?>">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <p>Documentos</p>
                    </a>
                </li>
                <li class="fiscal menu-item ocult">
                    <a href="<?= $router->route('Main.apolice') ?>">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <p>Apolice de seguro</p>
                    </a>
                </li>

                <li style="border-bottom: 1px solid white; border-top: 1px solid white">
                    <a href="#" id="financeiro">
                        <p style="font-weight: normal; font-size: 1.1em; margin-left: .5rem"> Financeiro    <i class="fas fa-chevron-down"></i></p>
                    </a>
                </li>
                <li class="financeiro menu-item ocult">
                    <a href="<?= $router->route('Main.financeiroPagar') ?>">
                        <i class="fas fa-dollar-sign"></i>
                        <p>A Pagar</p>
                    </a>
                </li>
                <li class="financeiro menu-item ocult">
                    <a href="<?= $router->route('Main.financeiroReceber') ?>">
                        <i class="fas fa-hand-holding-usd"></i>
                        <p>A Receber</p>
                    </a>
                </li>
                <?php
                    endif;
                ?>
            </ul>
        </div>
    </div>

    <div class="main-panel" id="main-panel">
        <nav class="navbar navbar-expand-lg navbar-transparent  bg-primary  navbar-absolute">
            <div class="container-fluid">
                <div class="navbar-wrapper">
                    <div class="navbar-toggle">
                        <button type="button" class="navbar-toggler">
                            <span class="navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </button>
                    </div>
                    <a class="navbar-brand" href="#">Bem Vindo <?php if($_SESSION['name']): echo $_SESSION['name']; endif ?></a>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-bar navbar-kebab"></span>
                    <span class="navbar-toggler-bar navbar-kebab"></span>
                    <span class="navbar-toggler-bar navbar-kebab"></span>
                </button>

                <marquee style="max-width: 770px;" behavior="scroll" direction="right">

                    <?php if((new \Source\Models\TransportesModel())->find("liberacao = :liberado", "liberado=liberado")->order("data_liberacao ASC")->fetch(true)):
                        foreach ((new \Source\Models\TransportesModel())->find("liberacao = :liberado", "liberado=liberado")->order("data_liberacao ASC")->fetch(true) as $liberado): ?>

                            <span style="margin-right: 1.5rem"><b><?= strtoupper($liberado->empresa_name) ?></b> – <?= $liberado->origem ?> X <?= $liberado->destino ?> </span>

                        <?php endforeach; endif; ?>
                </marquee>

                <div class="collapse navbar-collapse justify-content-end" id="navigation">
                    <ul class="navbar-nav" style="margin-left: 1rem">
                        <li class="nav-item">
                            <a style="display: flex; align-items: center" class="nav-link" href="<?= $router->route('Login.logout') ?>">
                                <i class="fas fa-sign-out-alt"></i>
                                <p style="margin-left: .5rem">
                                    <span class="d-md-block">Logout</span>
                                </p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="panel-header panel-header-sm">
            <canvas id="bigDashboardChart"></canvas>
        </div>

        <div class="content">
            <?= $this->section("content") ?>
        </div>


        <footer class="footer">
            <div class=" container-fluid ">
                <div class="copyright" id="copyright">
                    &copy; <script>
                        document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
                    </script>, Designed by <a href="https://www.invisionapp.com" target="_blank">Invision</a>. Coded by <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a>, <a href="https://portifolio-doug.vercel.app" target="_blank">DougFavero</a>.
                </div>
            </div>
        </footer>
    </div>

</div>

<!--   Core JS Files   -->
<script src="<?= url('theme/js/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= url('theme/js/jquery.form.js') ?>"></script>
<script src="<?= url('theme/js/popper.min.js') ?>"></script>
<script src="<?= url('theme/js/bootstrap.min.js') ?>"></script>
<script src="<?= url('theme/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= url('theme/js/bootstrap.esm.min.js') ?>"></script>
<script src="<?= url('theme/js/perfect-scrollbar.jquery.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-typeahead/2.11.0/jquery.typeahead.min.js" integrity="sha512-Rc24PGD2NTEGNYG/EMB+jcFpAltU9svgPcG/73l1/5M6is6gu3Vo1uVqyaNWf/sXfKyI0l240iwX9wpm6HE/Tg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!--  Notifications Plugin    -->
<script src="<?= url('theme/js/bootstrap-notify.js') ?>"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="<?= url('theme/js/now-ui-dashboard.js') ?>"></script>
<?= $this->section("script") ?>
<script>
    $(function () {

        $("#cadastro").click(function (e) {
            e.preventDefault();
            $(".cadastro").toggleClass("ocult");
        })

        $("#comercial").click(function (e) {
            e.preventDefault();
            $(".comercial").toggleClass("ocult");
        })

        $("#operacional").click(function (e) {
            e.preventDefault();
            $(".operacional").toggleClass("ocult");
        })
        $("#fiscal").click(function (e) {
            e.preventDefault();
            $(".fiscal").toggleClass("ocult");
        })
        $("#financeiro").click(function (e) {
            e.preventDefault();
            $(".financeiro").toggleClass("ocult");
        })
        $("#usuarios").click(function (e) {
            e.preventDefault();
            $(".usuarios").toggleClass("ocult");
        })
        $("#config").click(function (e) {
            e.preventDefault();
            $(".config").toggleClass("ocult");
        })
    })
</script>
<script>
        CKEDITOR.replace( 'apresentacao' );
        CKEDITOR.replace( 'signature' );
        CKEDITOR.replace( 'corpo' );
</script>
</body>
</html>