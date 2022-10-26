<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-10"><h4>Adicionar Comprovantes</h4></div>
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
                        <b> Forma de Pagamento: <?= $fiscal->motoristas()->pagamento ?> </b>
                    </div>

                    <div class="col-md-auto">
                        <b>

                            <?php
                            if($fiscal->motoristas()->pagamento === "adiamento" || $fiscal->motoristas()->pagamento === "adiantamento"):
                            echo $fiscal->motoristas()->porcentagem;
                            elseif($fiscal->motoristas()->pagamento === "vista"):
                            echo $fiscal->motoristas()->vista;
                            elseif($fiscal->motoristas()->pagamento === "cheque"):
                            echo $fiscal->motoristas()->cheque;
                            endif;
                            ?>

                        </b>
                    </div>

                    <div class="col-md-auto">
                        <b>
                            A Pagar: R$ <?= $fiscal->motoristas()->valor ?> <?php foreach ($valores as $valor): echo " - ".$valor; endforeach; ?>
                            = <?php $f = $fiscal->motoristas()->valor; foreach ($valores as $valor): $f = $f - $valor; endforeach; echo "R$".$f; ?>
                        </b>
                    </div>

                </div>

                <form enctype="multipart/form-data" method="post" action="<?= $router->route('Financeiro.add', ['id' => $fiscal->id]) ?>">
                <div class="row mb-4">

                    <div class="col-md-5 file-inputs" style="display: flex; flex-direction: column; gap: 1rem">
                        <input type="file" id="arquivos" class="arquivos" name="comprovantes" accept="image/jpeg, image/jpg, image/png, application/pdf">
                        <input style="max-width: 15rem" type="text" id="arquivos_names" class="form-control arquivos_names" name="comprovantes_names" placeholder="Nome do Comprovante">
                        <input style="max-width: 15rem" required type="number" id="arquivos_valores" class="form-control arquivos_valores" name="comprovantes_valores" placeholder="Valor do Comprovante">
                    </div>

                </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descrição dos Comprovantes</label>
                                <textarea placeholder="Descrição dos comprovantes anexados acima." class="form-control" name="comprovantes_descricao" id="comprovantes_descricao" cols="30" rows="10"><?= $fiscal->comprovantes_descricao ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <input type="submit" class="form-control" name="submit" value="Adicionar Comprovantes" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                        </div>
                    </div>
                </form>
            </div>

        </div>

        <div class="card">

            <div class="card-header" style="display: flex; justify-content: space-between">
                <h4>Comprovantes Anexados</h4>
                <a href="#" id="deletar-comprovantes" data-action="<?= $router->route('Financeiro.delete', ['id' => $fiscal->id]) ?>">Excluir Comprovantes</a>
            </div>

            <div class="card-body">
                <div class="row" style="padding: 1rem">
                    <?php foreach ($comprovantes as $index => $comprovante): ?>

                        <div class="col-md-2">
                            <h6><?= pathinfo($comprovante)['filename'] ?></h6>
                            <span>R$<?= $valores[$index] ?></span>

                            <div style="display: flex; gap: 1rem" class="buttons">
                                <a target="_blank" href="<?= $comprovante ?>">Abrir</a>
                                <a download="download" href="<?= $comprovante ?>">Download</a>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Histórico de Comprovantes</h4>
            </div>

            <div class="card-body">
                <div class="row" style="padding: 1rem">
                    <?php foreach ($historico as $comprovante): ?>

                        <div class="col-md-2">
                            <h6><?= pathinfo($comprovante)['filename'] ?></h6>

                            <div style="display: flex; gap: 1rem" class="buttons">
                                <a target="_blank" href="<?= $comprovante ?>">Abrir</a>
                                <a download="download" href="<?= $comprovante ?>">Download</a>
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

                file.prop("id", `arquivos_${$(".arquivos").length}`);
                file.prop("name", `comprovantes_${$(".arquivos").length}`);

                name.prop("id", `arquivos_names_${$(".arquivos_names").length}`);
                name.prop("name", `comprovantes_names_${$(".arquivos_names").length}`);

                valor.prop("id", `arquivos_valores_${$(".arquivos_valores").length}`);
                valor.prop("name", `comprovantes_valores_${$(".arquivos_valores").length}`);

                file.appendTo($(".file-inputs"));
                name.appendTo($(".file-inputs"));
                valor.appendTo($(".file-inputs"));
            })

            $("#remove-file").click(function (e) {
                e.preventDefault();

                var lenghtA = $(".arquivos").length - 1;
                var lenghtB = $(".arquivos_names").length - 1;
                var lenghtC = $(".arquivos_valores").length - 1;

                $(`#arquivos_${lenghtA}`).remove();
                $(`#arquivos_names_${lenghtB}`).remove();
                $(`#arquivos_valores_${lenghtC}`).remove();
            })

            $("#deletar-comprovantes").click(function (e) {
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