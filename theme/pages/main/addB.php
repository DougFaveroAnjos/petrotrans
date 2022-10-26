<?php $this->layout("_theme", ["title" => $title]) ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-9"><h4>Adicionar Boletos</h4></div>
                        <div class="col-md-1">
                            <a href="#" id="add-file"><i style="font-size: 1.5rem; color: green" class="fas fa-plus"></i></a>
                        </div>
                        <div class="col-md-1">
                            <a href="#" id="remove-file"><i style="font-size: 1.5rem; color: green" class="fas fa-minus"></i></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <b> Forma de Pagamento: <?= $fiscal->empresas()->pagamento ?> </b>
                        </div>

                        <div class="col-md-auto">
                            <b> R$<?= $fiscal->cotacoes()->valor_cotacao ?>
                            <?php $f = floatval($fiscal->cotacoes()->valor_cotacao); foreach (explode(" ; ", $fiscal->boletos_valores) as $item):
                                $f = floatval($f) - floatval($item);
                                ?>
                             - R$<?= $item ?>
                            <?php endforeach; ?>
                            = <?= $f ?>

                            </b>
                        </div>

                    </div>

                    <form enctype="multipart/form-data" method="post" action="<?= $router->route('Financeiro.addBol', ['id' => $fiscal->id]) ?>">
                        <div class="row mb-4">

                            <div class="col-md-5 file-inputs" style="display: flex; flex-direction: column; gap: 1rem">
                                <input type="file" id="arquivos" class="arquivos" name="boletos" accept="image/jpeg, image/jpg, image/png, application/pdf">
                                <input style="max-width: 15rem" type="text" id="arquivos_names" class="form-control arquivos_names" name="boletos_names" placeholder="Nome do Boleto">
                                <input style="max-width: 15rem" required type="number" id="arquivos_valores" class="form-control arquivos_valores" name="boletos_valores" placeholder="Valor do Boleto">
                                <input style="max-width: 15rem" required type="date" id="arquivos_datas" class="form-control arquivos_datas" name="boletos_datas" placeholder="Data do Boleto">

                            </div>

                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descrição dos Boletos</label>
                                    <textarea placeholder="Descrição dos boletos anexados acima." class="form-control" name="boletos_descricao" id="boletos_descricao" cols="30" rows="10"><?= $fiscal->boletos_descricao ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-md-3">
                                <input type="submit" class="form-control" name="submit" value="Adicionar Boletos" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            <div class="card">

                <div class="card-header" style="display: flex; justify-content: space-between">
                    <h4>Boletos Anexados</h4>
                    <a href="#" id="deletar-boletos" data-action="<?= $router->route('Financeiro.deleteBol', ['id' => $fiscal->id]) ?>">Excluir Boletos</a>
                </div>

                <div class="card-body">
                    <div class="row" style="padding: 1rem">
                        <?php foreach ($boletos as $index => $boleto): ?>

                            <div class="col-md-2">
                                <h6><?= pathinfo($boleto)['filename'] ?></h6>
                                <span>R$<?= $valores[$index] ?> -- <?= $datas[$index] ?></span>

                                <div style="display: flex; gap: 1rem" class="buttons">
                                    <a target="_blank" href="<?= $boleto ?>">Abrir</a>
                                    <a download="download" href="<?= $boleto ?>">Download</a>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Histórico de Boletos</h4>
                </div>

                <div class="card-body">
                    <div class="row" style="padding: 1rem">
                        <?php foreach ($historico as $boleto): ?>

                            <div class="col-md-2">
                                <h6><?= pathinfo($boleto)['filename'] ?></h6>

                                <div style="display: flex; gap: 1rem" class="buttons">
                                    <a target="_blank" href="<?= $boleto ?>">Abrir</a>
                                    <a download="download" href="<?= $boleto ?>">Download</a>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $this->start("script") ?>
    <script>
        $(function () {

            $("#add-file").click(function (e) {
                e.preventDefault();

                var file = $("#arquivos").clone();
                var name = $("#arquivos_names").clone();
                var valor = $("#arquivos_valores").clone();
                var data = $("#arquivos_datas").clone();

                file.prop("id", `arquivos_${$(".arquivos").length}`);
                file.prop("name", `boletos_${$(".arquivos").length}`);

                name.prop("id", `arquivos_names_${$(".arquivos_names").length}`);
                name.prop("name", `boletos_names_${$(".arquivos_names").length}`);

                valor.prop("id", `arquivos_valores_${$(".arquivos_valores").length}`);
                valor.prop("name", `boletos_valores_${$(".arquivos_valores").length}`);

                data.prop("id", `arquivos_datas_${$(".arquivos_datas").length}`);
                data.prop("name", `boletos_datas_${$(".arquivos_datas").length}`);

                file.appendTo($(".file-inputs"));
                name.appendTo($(".file-inputs"));
                valor.appendTo($(".file-inputs"));
                data.appendTo($(".file-inputs"));
            })

            $("#remove-file").click(function (e) {
                e.preventDefault();

                var lenghtA = $(".arquivos").length - 1;
                var lenghtB = $(".arquivos_names").length - 1;
                var lenghtC = $(".arquivos_valores").length - 1;
                var lenghtD = $(".arquivos_datas").length - 1;

                $(`#arquivos_${lenghtA}`).remove();
                $(`#arquivos_names_${lenghtB}`).remove();
                $(`#arquivos_valores_${lenghtC}`).remove();
                $(`#arquivos_datas_${lenghtD}`).remove();
            })

            $("#deletar-boletos").click(function (e) {
                e.preventDefault();

                var data = $(this).data();
                var id = <?= $fiscal->id ?>

                $.post(data.action, {"id": id}, function (result) {
                    alert(result);

                    window.location.href = "<?= $router->route('Main.financeiro') ?>";
                })
            })
        })
    </script>
<?php $this->stop() ?>