<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <h5 class="title">Cadastrar Transporte</h5>
            </div>
            <div class="card-body">
                <form action="<?= $router->route('Transportes.new') ?>" method="post" data-action="<?= $router->route('Transportes.new') ?>">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data de Coleta</label>
                                <input type="text" class="form-control" name="data_coleta" placeholder="Data da Coleta" value="<?= $cotacao->data_coleta ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data da Liberação</label>
                                <input type="text" class="form-control" placeholder="Data de Liberação" name="data_liberacao" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <input placeholder="Status do Transporte" type="text" name="status" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cor do Status</label>
                                <input required type="color" name="color" style="display: block; border: 1px solid #f96332; border-radius: .5rem" title="Escolha a cor de fundo do status">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descrição do Transporte</label>
                                <textarea name="obs" rows="4" cols="80" class="form-control" placeholder="Descrição"></textarea>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="id" value="<?= $cotacao->id ?>">
                    <input type="hidden" name="empresa_name" value="<?= $cotacao->cliente ?>">
                    <input type="hidden" name="valor_motorista" value="Entre: R$ <?= number_format($cotacao->valor_motorista_min,2,",",".") ?> E: R$ <?= number_format($cotacao->valor_motorista_max,2,",",".") ?>">
                    <input type="hidden" name="origem" value="<?= $cotacao->cidade_origem."/".$cotacao->uf_origem ?>">
                    <input type="hidden" name="destino" value="<?= $cotacao->cidade_destino."/".$cotacao->uf_destino ?>">
                    <input type="hidden" name="carroceria" value="<?= $cotacao->veiculo ?>">
                    <input type="hidden" name="tipo_carga" value="<?= $cotacao->tipo_carga ?>">
                    <input type="hidden" name="peso" value="<?= $cotacao->peso ?>">

                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <input type="submit" class="form-control" name="submit" value="Criar Transporte" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->start("script") ?>
<script>
    $(function () {

        $('form').submit(function (e) {
            e.preventDefault();

            var data = $(this).data();

            $.post(data.action, $(this).serialize(), function (response) {

                alert(response);

                window.location.href = "<?= $router->route('Main.transportes') ?>"
            }, 'json')


        })

    })
</script>
<?php $this->stop() ?>
