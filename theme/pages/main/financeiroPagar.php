<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>A Pagar</h4>
            </div><!-- card-header -->

            <div class="card-body">

                <form method="get">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Empresa</label>
                                <input  type="text" name="empresa" id="empresa" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Motorista</label>
                                <input type="text" name="motorista" id="motorista" class="form-control">
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
                            Empresa
                        </th>
                        <th>
                            Nome do Motorista
                        </th>
                        <th>
                            Valor Motorista
                        </th>
                        <th style="text-align: center">
                            CTe
                        </th>
                        <th style="text-align: center">
                            Mdfe
                        </th>
                        <th style="text-align: center">
                            Pagamento
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

                                            <a class="items-close <?= $fiscal->id ?>"
                                               href="<?= $router->route('Mailer.email', ['type' => 'financeiroPagar', 'id' => $fiscal->id]) ?>">
                                                Enviar Email
                                            </a>

                                            <a class="items-close <?= $fiscal->id ?> delete" data-action="<?= $router->route('Fiscal.delete', ['id' => $fiscal->id]) ?>" href="#">Deletar</a>
                                        </div>

                                    </td>

                                    <td> <?= $fiscal->empresa ?> </td>

                                    <td>
                                        <a href="<?= $router->route('Motoristas.visualizar', ['token' => $fiscal->transportes()->token, 'id' => $fiscal->transportes()->id]) ?>">
                                            <?= $fiscal->transportes()->motorista_name ?>
                                        </a>
                                    </td>

                                    <td>R$<?= number_format($fiscal->motoristas()->valor,2,",",".") ?></td>

                                    <td style="text-align: center">
                                            <a target="_blank" href="<?= $router->route('Fiscal.visualizar', ['id' => $fiscal->id, 'type' => 'cte']) ?>"><?= $fiscal->cte ?></a>
                                    </td>
                                    <td style="text-align: center">
                                            <a target="_blank" href="<?= $router->route('Fiscal.visualizar', ['id' => $fiscal->id, 'type' => 'mdfe']) ?>"><?= $fiscal->mdfe ?></a>
                                    </td>

                                    <td style="display: flex; gap: 1rem; justify-content: center">

                                        <?php if($fiscal->comprovantes): ?>
                                            <a href="<?= $router->route('Financeiro.addC', ['id' => $fiscal->id]) ?>">

                                                <?php if($fiscal->motoristas()->pagamento === "adiantamento" || $fiscal->motoristas()->pagamento === "adiamento"): ?>
                                                    <?= $fiscal->motoristas()->porcentagem ?>
                                                <?php else: echo strtoupper($fiscal->motoristas()->pagamento);  endif; ?>
                                            </a>
                                        <?php else: echo strtoupper($fiscal->motoristas()->pagamento); endif; ?>

                                        <?php if(!$fiscal->comprovantes): ?>
                                        <a href="<?= $router->route('Financeiro.addC', ['id' => $fiscal->id]) ?>">
                                            <i class="far fa-plus-square"></i>
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

    })
</script>

<?php $this->stop(); ?>

