<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>A Receber</h4>
            </div><!-- card-header -->

            <div class="card-body">

                <form method="get">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Empresa</label>
                                <input placeholder="Nome da Empresa" type="text" name="empresa" id="empresa" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Motorista</label>
                                <input placeholder="Nome do Motorista" type="text" name="motorista" id="motorista" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>CTe</label>
                                <input value="CTe" type="number" name="cte" id="cte" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>MDFe</label>
                                <input value="MDFe" type="number" name="mdfe" id="mdfe" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary" name="submit" value="Filtrar">Filtrar</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive" style="max-height: 80vh; overflow-y: auto; position: static">

                    <table class="table" style="white-space: nowrap; position: static; ">

                        <thead style="font-size: .8rem; gap: 1rem">
                        <th>
                            #
                        </th>
                        <th>
                            Data Coleta
                        </th>
                        <th>
                            Empresa
                        </th>
                        <th>
                            Nº Cotação
                        </th>
                        <th style="text-align: center">
                            CTe
                        </th>
                        <th>
                            Valor
                        </th>
                        <th>
                            Origem
                        </th>
                        <th>
                            Destino
                        </th>
                        <th style="text-align: center">
                            Vencimento Previsto
                        </th>
                        <th style="text-align: center">
                            Boleto
                        </th>
                        </thead>


                        <tbody>

                        <?php if($fiscais):
                            /** @var \Source\Models\FiscalModel $fiscal */
                            foreach ($fiscais as $fiscal): ?>

                                <tr>
                                    <td style="display: flex;  gap: 2rem; align-items: center">
                                        <a href="#" class="items-open <?= $fiscal->id ?>"> <i class="fas fa-bars"></i> </a>

                                        <div class="items <?= $fiscal->id ?>">
                                            <a class="items-close <?= $fiscal->id ?>" href="#"><i class="fas fa-times"></i></a>

                                            <a class="items-close <?= $fiscal->id ?> sendmail"
                                               href="#" data-id="<?= $fiscal->id ?>" data-action="<?= $router->route('Fiscal.getmail', ['id' => $fiscal->id]) ?>">
                                                Enviar Email</a>

                                            <a class="items-close <?= $fiscal->id ?> delete" data-action="<?= $router->route('Fiscal.delete', ['id' => $fiscal->id]) ?>" href="#">Deletar</a>
                                        </div>

                                    </td>

                                    <td> <?= $fiscal->coletas()->date ?> </td>

                                    <td> <?= $fiscal->empresa ?> </td>

                                    <td> <?= $fiscal->cotacao ?> </td>

                                    <td style="text-align: center">
                                        <a target="_blank" href="<?= $router->route('Fiscal.visualizar', ['id' => $fiscal->id, 'type' => 'cte']) ?>"><?= $fiscal->cte ?></a>
                                    </td>

                                    <td><?= $fiscal->boleto_valores ?></td>
                                    <td><?= $fiscal->origem ?></td>
                                    <td><?= $fiscal->destino ?></td>
                                    <td style="text-align: center">
                                      <?= $fiscal->cotacoes()->pagamento ?>
                                    </td>

                                    <td style="display: flex; gap: 1rem; justify-content: center">
                                        <?php if(!$fiscal->boletos): ?>
                                        <a target="_blank" href="<?= $router->route('Financeiro.addB', ['id' => $fiscal->id]) ?>">
                                            <i style="font-size: 1.2rem" class="fas fa-plus-circle"></i>
                                        </a>
                                        <?php else: ?>
                                        <a target="_blank" href="<?= $router->route('Financeiro.addB', ['id' => $fiscal->id]) ?>">
                                            Visualizar
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                            <?php endforeach; endif; ?>
                        </tbody>

                    </table><!-- table -->

                </div>
            </div><!-- cardbody -->
            <div class="card-footer" style="display: flex; justify-content: center">
                <?= $pager ?>
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

            var data = $(this).data();
            var id = this.classList[1];

            $.post(data.action, {"id": id}, function (result) {

                alert(result);
                window.location.reload();
            }, 'json')

        })



        $(".sendmail").click(function (e) {
            e.preventDefault();

            var data = $(this).data();
            var id = this.classList[1];

            $(`.cadastrar-transportes`).toggleClass("active");

            $.post(data.action, {'id': id}, function (result) {
                $(`.cadastrar-transportes #checkboxphone`).html(result.msg);
            }, 'json');
        });

        $(".ajax_on").submit(function (e) {
            e.preventDefault();
            var form = $(this);

            form.ajaxSubmit({
                url: form.attr("action"),
                type: "POST",
                dataType: "json",
                beforeSend: function () {

                },
                success: function (response) {
                    alert(response.msg);
                    $(`.cadastrar-transportes`).removeClass("active");
                },
                complete: function () {
                    if (form.data("reset") === true) {
                        form.trigger("reset");
                    }
                }
            });
        });

    })
</script>

<?php $this->stop(); ?>


<div class="row cadastrar-transportes">
    <div class="col-md-12" style="padding: 0">

        <div class="card">
            <div class="card-header">
                <h5 class="title">Enviar dados</h5>
            </div>
            <div class="card-body">
                <form class="transporte-create ajax_on" action="<?= url("financeiro/email/send")?>" method="post" data-action="<?= url("financeiro/email/send")?>">
                    <div id="checkboxphone"></div>
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <input type="submit" class="form-control" name="submit" value="Enviar dados" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem;">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

