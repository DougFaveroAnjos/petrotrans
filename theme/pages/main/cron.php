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
                <h5 class="title">Lista cron</h5>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" action="<?= $router->route('Main.configEmail'); ?>" method="post" data-action="<?= $router->route('Main.configEmail'); ?>">
                    <?php
                    if(!empty($lista)):
                        foreach ($lista as $item):
                            ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Tipo</label>
                                    <p><?= ucfirst($item->type) ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label>Dias</label>
                                    <p><?= str_replace(";", ", ", $item->days) ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label>Horário</label>
                                    <p><?= "{$item->start} - {$item->end}" ?></p>
                                </div>
                                <div class="col-md-2">
                                    <label>Limite</label>
                                    <p><?= $item->limite ?></p>
                                </div>
                                <div class="col-md-2">
                                    <label></label>
                                    <p><label style="cursor: pointer; background-color: indianred; padding: 10px; border-radius: 3px; color: #fff" onclick="excluir('<?= $item->id ?>', '<?= $router->route("Main.configEmail")?>')">excluir</label></p>
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

                    <h4>Configuração Cron</h4>
                    <input type="hidden" name="tipo" value="create">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dias</label>
                                <div class="contato-origem">
                                    <label><input type="checkbox" name="dias[]" class="form-control" value="seg"  checked> Segunda</label>
                                    <label><input type="checkbox" name="dias[]" class="form-control" value="ter"  checked> Terça</label>
                                    <label><input type="checkbox" name="dias[]" class="form-control" value="qua"  checked> Quanrta</label>
                                    <label><input type="checkbox" name="dias[]" class="form-control" value="qui"  checked> Quinta</label>
                                    <label><input type="checkbox" name="dias[]" class="form-control" value="sex"  checked> Sexta</label>
                                    <label><input type="checkbox" name="dias[]" class="form-control" value="sab"  checked> Sabádo</label>
                                    <label><input type="checkbox" name="dias[]" class="form-control" value="dom"  checked> Domingo</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Horários</label>
                                <div class="contato-origem">

                                    <label>Início <input type="time" name="inicio" class="form-control" value="00:00"></label>
                                    <label>Fim <input type="time" name="final" class="form-control" value="23:59"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>limite de mensagens enviada por dia</label>
                                <div class="contato-origem">
                                    <label><input type="number" name="limite" class="form-control" value="1000"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tempo de espera (micro-segundos)</label>
                                <div class="contato-origem">
                                    <label><input type="number" name="espera" class="form-control" value="1000000"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select name="type">
                                    <option value="email" selected>E-mail</option>
                                    <option value="whatsapp">Whatsapp</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <button type="submit" class="btn form-control" name="submit" value="Salva cron" >Salva cron</button>
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
    $("[data-action]").submit(function (e) {
        e.preventDefault();

        var data = $(this).data();

        $.post(data.action, $(this).serialize(), function(response) {
            alert(response);

            window.location.href = "<?= $router->route('Main.configEmail') ?>"
        }, "json");
    })

    function excluir(id, url) {
        $.post(url, {tipo: "delete", id: id}, function (response) {
            alert(response);

            window.location.href = "<?= $router->route('Main.configEmail') ?>"
        }, "json");
    }
</script>
<?php $this->stop() ?>
