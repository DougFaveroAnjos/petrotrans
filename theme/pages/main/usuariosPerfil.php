<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Editar usuário</h5>
            </div>
            <div class="card-body">
                <form action="<?= $router->route('main.usuariosPerfil'); ?>" method="post">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nome</label>
                                <div class="typeahead__container">
                                    <input placeholder="Nome" type="text" class="form-control" name="first_name" autocomplete="off" required value="<?= $user->first_name ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Sobrenome</label>
                                <div class="typeahead__container">
                                    <input placeholder="Sobrenome" type="text" class="form-control" name="last_name" autocomplete="off" required value="<?= $user->last_name ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Telefone</label>
                                <div class="typeahead__container">
                                    <input placeholder="Telefone" type="text" class="form-control" name="number" autocomplete="off" required value="<?= $user->number ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <input type="submit" class="form-control submit-button" name="submit" value="Editar usuario" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="msg"></div>
