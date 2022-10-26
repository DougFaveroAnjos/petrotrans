<?php $this->layout("_theme", ["title" => $title]) ?>
<style>
    .assinatura{
        width: 100%;
        height: 200px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">E-mail configuração</h5>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" action="<?= $router->route('Main.email'); ?>" method="post" data-action="<?= $router->route('Main.email'); ?>">
                    <input type="hidden" name="tipo" value="create">
                    <?php
                        if(!empty($lista)):
                            foreach ($lista as $item):
                                ?>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Nome</label>
                                        <p><?= $item->name ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>E-mail</label>
                                        <p><?= $item->address ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Tipo</label>
                                        <p><?= ucfirst($item->type) ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label></label>
                                        <p><label style="cursor: pointer; background-color: indianred; padding: 10px; border-radius: 3px; color: #fff" onclick="excluir('<?= $item->id ?>', '<?= $router->route("Main.email")?>')">excluir</label></p>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        else:
                    ?>
                        Não existe nenhuma configuração padrão cadastrada
                    <?php
                        endif;
                    ?>

                    <h4>Cadastrar configuração</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Host</label>
                                <div class="contato-origem">
                                    <input type="text" name="host" class="form-control" placeholder="smtp.exemplo.com.br" value="<?= CONF_MAIL_HOST ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Porta</label>
                                <div class="contato-origem">
                                    <input type="text" name="port" class="form-control" placeholder="587" value="<?= CONF_MAIL_PORT ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Usuário</label>
                                <div class="contato-origem">
                                    <input type="text" name="user" class="form-control" placeholder="Usuário" value="<?= CONF_MAIL_USER ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Senha</label>
                                <div class="contato-origem">
                                    <input type="password" name="pass" class="form-control" placeholder="Senha" value="<?= CONF_MAIL_PASS ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nome</label>
                                <div class="contato-origem">
                                    <input type="text" name="name" class="form-control" placeholder="Usuário" value="<?= CONF_MAIL_SENDER['name'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>E-mail</label>
                                <div class="contato-origem">
                                    <input type="text" name="address" class="form-control" placeholder="Senha" value="<?= CONF_MAIL_SENDER['address'] ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Para</label>
                                <select name="type" class="form-control">
                                    <option value="boleto">Boleto</option>
                                    <option value="cotacao" selected>Cotação</option>
                                    <option value="financeiro">Financeiro</option>
                                    <option value="coleta">Ordem de coleta</option>
                                    <option value="massa">Envio em massa</option>
                                    <option value="apresentacao">Apresentação</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Assinatura e-mail</label>
                                <textarea name="signature"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <button type="submit" class="btn form-control" name="submit" value="Cadastrar configuração">Salvar Configuração</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="msg"></div>

<?php $this->start("script"); ?>
<script>
    function excluir(id, url) {
        $.post(url, {tipo: "delete", id: id}, function (response) {
            alert(response);

            window.location.href = "<?= $router->route('Main.email') ?>"
        }, "json");
    }
</script>
<?php $this->stop() ?>
