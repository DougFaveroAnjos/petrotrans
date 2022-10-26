<?php $this->layout("_theme", ["title" => $title]) ?>

    <div class="row align-self-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="text-align: center">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <h1 style="font-weight: bold; margin-top: 5rem">Seja Bem Vindo <br><?php if($_SESSION['name']): echo $_SESSION['name']; endif ?></h1>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <a href="<?= $router->route('Main.perfil') ?>" class="btn btn-primary">Editar Perfil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
