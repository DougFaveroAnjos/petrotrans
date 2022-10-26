<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Cadastrar login empresa</h5>
            </div>
            <div class="card-body">
                <form action="<?= $router->route('Main.cadastrar') ?>" method="post" data-action="<?= $router->route('Main.cadastrar') ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Empresa</label>
                                <div class="typeahead__container">
                                    <select class="form-control status" name="empresa" id="empresa">
                                        <?php
                                            if(!empty($empresas)): foreach ($empresas as $empresa):
                                        ?>
                                            <option value="<?= $empresa->id?>"><?= $empresa->name?></option>
                                        <?php
                                            endforeach;endif;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Senha</label>
                                <input type="password" class="form-control" placeholder="Insira a senha" name="password" value="12345678">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control status" name="status" id="status">
                                    <option value="approved" selected>Ativo</option>
                                    <option value="waiting">Desativado</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <button type="submit" class="btn form-control" name="submit" value="Criar Conta">Criar Conta</button>
                        </div>
                    </div>
                </form>
                <?php
                if(array_key_exists("errormsg", $_COOKIE)):
                    echo $_COOKIE["errormsg"];
                endif;
                ?>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Login empresas</h4>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table" style= "white-space: nowrap;">
                        <thead class="text-primary" style="font-size: .6rem; gap: 1rem">
                        <th>
                            #
                        </th>
                        <th>
                            Nome
                        </th>
                        <th>
                            CNPJ
                        </th>
                        <th>
                            Opções
                        </th>
                        </thead>
                        <tbody>

                        <?php if(!empty($users)): foreach ($users as $user): ?>

                            <tr>
                                <td><?= $user->id?></td>
                                <td><?= "{$user->first_name} {$user->last_name}"?></td>
                                <td><?= $user->email?></td>
                                <td>
                                    <form action="<?= $router->route('Main.aprovar') ?>" method="post" data-action="<?= $router->route('Main.aprovar') ?>" style="display: inline-block">
                                        <input type="hidden" name="type" value="repproved">
                                        <input type="hidden" name="id" value="<?=  $user->id ?>">
                                        <button class="btn btn-danger"> Excluir</button>
                                    </form>
                                </td>
                            </tr>

                        <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->start("script"); ?>
<script>
    $(function () {

        $(".items-open").click(function (e) {
            e.preventDefault();

            let id = this.classList[1];

            this.classList.toggle("active");
            $(`.items.${id}`).toggleClass("active");

        })
        $(".items-close").click(function (e) {

            let id = this.classList[1];

            $(`.items-open.${id}`).toggleClass("active");
            $(`.items.${id}`).toggleClass("active");
        })
        $(".delete").click(function (e) {
            e.preventDefault();

            var id = this.classList[1];
            var data = $(this).data();

            $.post(data.action, {'id': id}, function (result) {
            }, "json")

            setInterval(function () {
                window.location.reload()
            }, 500)
        })
    })
</script>
<?php $this->stop(); ?>
