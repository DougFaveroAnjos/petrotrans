<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header justify-content-center">
                <h1>Editar Perfil</h1>
            </div>
            <div class="card-body">
                <form method="post" action="" data-action="<?= $router->route('Register.edit', ['id' => $user->id]) ?>">
                    <div class="row justify-content-center form-group">
                        <div style="text-align: center;" class="col-md-3">
                            <label>
                                Nome
                            </label>
                            <input class="form-control" name="first_name" type="text" placeholder="<?= $user->first_name ?>">
                        </div>

                        <div style="text-align: center;" class="col-md-3">
                            <label>
                                Sobrenome
                            </label>
                            <input class="form-control" name="last_name" type="text" placeholder="<?= $user->last_name ?>">
                        </div>

                    </div>
                    <div class="row justify-content-center form-group">
                        <div style="text-align: center;" class="col-md-4">
                            <label>
                                Email
                            </label>
                            <input class="form-control" name="email" type="email" placeholder="<?= $user->email?>">
                        </div>
                    </div>

                    <div class="row justify-content-center form-group">
                        <div style="text-align: center;" class="col-md-3">
                            <label>
                                Senha
                            </label>
                            <input class="form-control" name="password" type="password" placeholder="Senha" required>
                        </div>
                    </div>

                    <div class="row justify-content-center form-group">
                        <div style="text-align: center;" class="col-md-2">
                            <label>
                                Departamento
                            </label>
                            <select style="text-align: center" class="form-control" name="role" id="role">
                                <option value="Fiscal" <?php if($user->role === 'Fiscal'): echo "selected"; endif ?>>Fiscal</option>
                                <option value="Financeiro" <?php if($user->role === 'Financeiro'): echo "selected"; endif ?>>Financeiro</option>
                                <option value="Operacional" <?php if($user->role === 'Operacional'): echo "selected"; endif ?>>Operacional</option>
                                <option value="Comercial" <?php if($user->role === 'Comercial'): echo "selected"; endif ?>>Comercial</option>
                            </select>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <button class='btn form-control' type="submit" value="Editar" style="margin-top:5px">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="body-footer">
                <div class="msg"></div>
            </div>
        </div>
    </div>
</div>

<?php $this->start("script") ?>
<script>
    $(function () {

        $("form").submit(function (e) {
            e.preventDefault();

            var data = $(this).data();

            $.post(data.action, $(this).serialize(), function (response) {

                $(".msg").addClass("on");
                $(".msg").html(response.message);

            }, "json")
        })


    })
</script>
<?php $this->stop() ?>
