<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Adicionar Documentos</h4>
            </div>

            <div class="card-body">

                <form method="post" enctype="multipart/form-data" class="form" action="<?= $router->route('Fiscal.add', ['id' => $fiscal->id]) ?>">

                    <div class="row mb-4">
                        <div class="col-md-6" style="display: flex; align-items: center">
                            <label>NÃO INCLUIR DOCUMENTO CTe:</label>
                            <input style="margin-left: 1rem" id="no_cte" type="checkbox" name="no_cte">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">

                                <label> Adicionar arquivo XML CTe </label>

                                <input multiple id="xmlcte-input" accept="application/xml, text/xml" type="file" class="form-control cte" name="XML-CTe[]">

                        </div><!-- input XML -->

                        <div class="col-md-6">

                                <label> Adicionar arquivo PDF CTe</label>

                                <input multiple id="pdfcte-input" accept="application/pdf" type="file" class="form-control cte" name="PDF-CTe[]">

                        </div><!-- input PDF -->
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6" style="display: flex; align-items: center">
                            <label>NÃO INCLUIR DOCUMENTO MDFe:</label>
                            <input style="margin-left: 1rem" id="no_mdfe" type="checkbox" name="no_mdfe">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">

                            <label> Adicionar arquivo XML MDFe </label>

                            <input multiple id="xmlmdf-input" accept="application/xml, text/xml" type="file" class="form-control mdfe" name="XML-MDFe[]">

                        </div><!-- input XML -->

                        <div class="col-md-6">

                            <label> Adicionar arquivo PDF MDFe</label>

                            <input multiple id="pdfmdf-input" accept="application/pdf" type="file" class="form-control mdfe" name="PDF-MDFe[]">

                        </div><!-- input PDF -->
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <input type="submit" class="form-control" name="submit" value="Adicionar Documentos" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                        </div>
                    </div>
                </form>

            </div>

            <div class="card-footer">


                <b>Arquivos CTe</b>
                <div class="row" style="gap: 2rem">
                    <?php if($arquivoscte): foreach ($arquivoscte as $arquivocte):?>

                        <div class="col-md-2">
                            <label style="max-width: 100%; overflow-x: hidden"><?= pathinfo($arquivocte)['basename'] ?></label>
                            <div class="buttons" style="display: flex; gap: 1rem">
                                <a target="_blank" href="<?= url($arquivocte) ?>"> Abrir </a>
                                <a class="delete" href="#" data-type="cte"
                                   data-base="xml" data-id="<?= $fiscal->id ?>"
                                   data-link="<?= url($arquivocte) ?>"
                                   data-action="<?= $router->route('Fiscal.deletedoc', ['id' => $fiscal->id,
                                    'type' => 'cte',
                                    'base' => 'xml',
                                    'link' => url($arquivocte)]) ?>"> Deletar </a>
                            </div>
                        </div>

                    <?php endforeach; endif; ?>
                </div>
                <br>
                <div class="row" style="gap: 2rem">
                    <?php if($pdfscte): foreach ($pdfscte as $pdfcte): ?>

                        <div class="col-md-2" style="overflow-x: hidden">
                            <label style="max-width: 100%; overflow-x: hidden"><?= pathinfo($pdfcte)['basename'] ?></label>
                            <div class="buttons" style="display: flex; gap: 1rem">
                                <a target="_blank" href="<?= url($pdfcte) ?>"> Abrir </a>
                                <a class="delete" href="#" data-type="cte"
                                   data-base="pdf" data-id="<?= $fiscal->id ?>"
                                   data-link="<?= url($pdfcte) ?>"
                                   data-action="<?= $router->route('Fiscal.deletedoc', ['id' => $fiscal->id,
                                    'type' => 'cte',
                                    'base' => 'pdf',
                                    'link' => url($pdfcte)]) ?>"> Deletar </a>
                            </div>
                        </div>

                    <?php endforeach; endif; ?>
                </div>

                <br>
                <br>

                <b>Arquivos MDFe</b>
                <div class="row" style="gap: 2rem">
                        <?php if($arquivosmdfe): foreach ($arquivosmdfe as $arquivomdfe): ?>

                            <div class="col-md-2" style="overflow-x: hidden">
                                <label><?= pathinfo($arquivomdfe)['basename'] ?></label>
                                <div class="buttons" style="display: flex; gap: 1rem">
                                    <a target="_blank" href="<?= url($arquivomdfe) ?>"> Abrir </a>
                                    <a class="delete" href="#" data-type="mdfe"
                                       data-base="xml" data-id="<?= $fiscal->id ?>"
                                       data-link="<?= url($arquivomdfe) ?>"
                                       data-action="<?= $router->route('Fiscal.deletedoc', ['id' => $fiscal->id,
                                        'type' => 'mdfe',
                                        'base' => 'xml',
                                        'link' => url($arquivomdfe)]) ?>"> Deletar </a>
                                </div>
                            </div>

                        <?php endforeach; endif; ?>
                </div>
                <br>
                <div class="row" style="gap: 2rem">
                    <?php if($pdfsmdfe): foreach ($pdfsmdfe as $pdfmdfe): ?>

                        <div class="col-md-2" style="overflow-x: hidden">
                            <label><?= pathinfo($pdfmdfe)['basename'] ?></label>
                            <div class="buttons" style="display: flex; gap: 1rem">
                                <a target="_blank" href="<?= url($pdfmdfe) ?>"> Abrir </a>
                                <a class="delete" href="#" data-type="mdfe"
                                   data-base="pdf" data-id="<?= $fiscal->id ?>"
                                   data-link="<?= url($pdfmdfe) ?>"
                                   data-action="<?= $router->route('Fiscal.deletedoc', ['id' => $fiscal->id,
                                    'type' => 'mdfe',
                                    'base' => 'pdf',
                                    'link' => url($pdfmdfe)]) ?>"> Deletar </a>
                            </div>
                        </div>

                    <?php endforeach; endif; ?>
                </div>

                <br>
                <br>

                <b>Histórico</b>
                <div class="row" style="gap: 2rem">

                    <?php if($historico):
                    foreach ($historico as $item): ?>

                        <div class="col-md-2" style="overflow-x: hidden; display: flex; flex-direction: column">
                            <label><?= pathinfo($item)['basename'] ?></label>
                            <a target="_blank" href="<?= url($item) ?>"> Abrir </a>
                        </div>

                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->start("script") ?>
<script>

    $(function () {

        $(".delete").click(function (e) {
            e.preventDefault();

            var data = $(this).data();

            $.post(data.action, {"id": data.id, "type": data.type, "base": data.base, "link": data.link},function (result) {
                alert(result);
            }, "json")

        })

        $("#no_cte").click(function (e) {

            if(this.checked) {
                $(".cte").prop("disabled", true);
            } else {
                $(".cte").prop("disabled", false);
            }

        })

        $("#no_mdfe").click(function (e) {

            if(this.checked) {
                $(".mdfe").prop("disabled", true);
            } else {
                $(".mdfe").prop("disabled", false);
            }

        })

    })
</script>
<?php $this->stop() ?>
