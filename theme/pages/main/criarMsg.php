<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Mensagem padrão</h5>
            </div>
            <div class="card-body">
                <form action="<?= $router->route('Main.msgdefault') ?>" method="post" data-action="<?= $router->route('Main.msgdefault') ?>">
                    <input type="hidden" name="tipo" value="create">
                    <?php
                        if(!empty($msg)):
                            foreach ($msg as $item):
                    ?>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Mensagem #<?= $item->id ?></label>
                                <p><?= $item->content ?></p>
                            </div>
                            <div class="col-md-4">
                                <label>Tipo</label>
                                <p><?= ucfirst($item->type) ?></p>
                            </div>
                            <div class="col-md-2">
                                <label></label>
                                <p><label style="cursor: pointer; background-color: indianred; padding: 10px; border-radius: 3px; color: #fff" onclick="excluir('<?= $item->id ?>', '<?= $router->route("Main.msgdefault")?>')">excluir</label></p>
                            </div>
                        </div>
                    <?php
                        endforeach;
                        else:
                    ?>
                            Não existe nenhuma mensagem padrão cadatsrada
                    <?php
                        endif;
                    ?>
                    
                     <h4>Cadastrar Mensagem</h4>
                     
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Mensagem</label> <a href="#" style="color: green; margin-left: 1rem" class="remove-origem">
                                    <div class="contato-origem">
                                        <textarea type="text" name="mensagem" id="contatoorigem" class="form-control contatoorigem" placeholder="Mensagem"></textarea>
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select name="type">
                                    <option value="cliente">Cliente</option>
                                    <option value="motorista" selected>Motorista</option>
                                    <option value="cancelamento coleta (cliente)">Cancelamento Coleta (Cliente)</option>
                                    <option value="cancelamento coleta (motorista)">Cancelamento Coleta (Motorista)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <button type="submit" class="btn form-control" name="submit" value="Cadastrar Mensagem">Cadastrar nova mensagem</button>
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
    $(function () {
        $("[data-action]").submit(function (e) {
            e.preventDefault();

            var data = $(this).data();

            $.post(data.action, $(this).serialize(), function (response) {
                alert(response);

                window.location.href = "<?= $router->route('Main.msgdefault') ?>"
            }, "json");
        });
    });

    function excluir(id, url) {
        $.post(url, {tipo: "delete", id: id}, function (response) {
            alert(response);

            window.location.href = "<?= $router->route('Main.msgdefault') ?>"
        }, "json");
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
<script type="text/javascript">$("#contato-origem").mask("(00) 0000-00009");</script>
<script type="text/javascript">$("#contatoorigem0").mask("(00) 0000-00009");</script>
<script type="text/javascript">$("#contatoorigem1").mask("(00) 0000-00009");</script>
<script type="text/javascript">$("#contatoorigem2").mask("(00) 0000-00009");</script>
<script type="text/javascript">$("#contatoorigem3").mask("(00) 0000-00009");</script>
<script type="text/javascript">$("#contatoorigem4").mask("(00) 0000-00009");</script>

<?php $this->stop() ?>
