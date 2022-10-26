<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Fiscal</h4>
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

                        <div class="col-md-1">
                            <div class="form-group">
                                <label>CTe</label>
                                <input placeholder="CTe" type="number" name="cte" id="cte" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label>MDFe</label>
                                <input placeholder="MDFe" type="number" name="mdfe" id="mdfe" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Origem</label>
                                <input placeholder="Origem" type="text" name="origem" id="origem" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Destino</label>
                                <input placeholder="Destino" type="text" name="destino" id="destino" class="form-control">
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
                            <i class="fas fa-boxes"></i>
                        </th>
                        <th>
                            Liberação
                        </th>
                        <th>
                            Cotação
                        </th>
                        <th>
                            Empresa
                        </th>
                        <th>
                            Motorista
                        </th>
                        <th>
                            CPF
                        </th>
                        <th style="text-align: center">
                            CTe
                        </th>
                        <th style="text-align: center">
                            MDFe
                        </th>
                        <th>
                            Origem
                        </th>
                        <th>
                            Destino
                        </th>
                        <th>
                            Placa do Veículo
                        </th>
                        <th>
                            Placa do Reboque
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

                                    <a class="items-close <?= $fiscal->id ?> delete" data-action="<?= $router->route('Fiscal.delete', ['id' => $fiscal->id]) ?>" href="#">Deletar</a>

                                    <a class="items-close <?= $fiscal->id ?> finalizar" href="<?= $router->route('Transportes.finalizar', ['id' => $fiscal->transportes()->id]) ?>">Finalizar Transporte</a>
                                </div>

                            </td>

                            <td class="dados_td <?= $fiscal->id ?>">

                                <a href="<?= $router->route('Motoristas.visualizar', ['token' => $fiscal->transportes()->token, 'id' => $fiscal->transportes()->id]) ?>">
                                    <i style="font-size: 1.4rem; color: #f96332; cursor: pointer" class="fas fa-clipboard-list"></i>
                                </a>
                            </td>

                            <td>
                                <span><?= strtoupper($fiscal->transportes()->liberacao) ?></span>
                                <br>
                                Data: <?= $fiscal->transportes()->data_liberacao ?>
                            </td>

                            <td style="text-align: center">
                                <a target="_blank" href="<?= $router->route('Cotacoes.visualizar', ['id'=> $fiscal->cotacao]) ?>"><?= $fiscal->cotacao ?></a>
                            </td>

                            <td style="max-width: 20rem; overflow-x: hidden"> <?= $fiscal->empresa ?> </td>

                            <td> <?= $fiscal->motorista ?> </td>

                            <td> <?= $fiscal->motoristas()->cpf ?> </td>

                            <td style="text-align: center">
                                <?php if(!$fiscal->cte): ?>
                                    <a href="<?= $router->route('Fiscal.addD', ['id' => $fiscal->id]) ?>">  <i class="far fa-plus-square"></i>  </a>
                                <?php else: ?>
                                    <a target="_blank" href="<?= $router->route('Fiscal.addD', ['id' => $fiscal->id]) ?>"><?= $fiscal->cte ?></a>
                                <?php endif; ?>
                            </td>

                            <td style="text-align: center">
                                <?php if(!$fiscal->mdfe): ?>
                                    <a href="<?= $router->route('Fiscal.addD', ['id' => $fiscal->id]) ?>">  <i class="far fa-plus-square"></i>  </a>
                                <?php else: ?>
                                    <a target="_blank" href="<?= $router->route('Fiscal.visualizar', ['id' => $fiscal->id, 'type' => 'mdfe']) ?>"><?= $fiscal->mdfe ?></a>
                                <?php endif; ?>
                            </td>

                            <td><?= $fiscal->transportes()->origem ?></td>

                            <td><?= $fiscal->transportes()->destino ?></td>

                            <td><?= $fiscal->motoristas()->placa_veiculo ?></td>

                            <td><?= $fiscal->motoristas()->placa_reboque ?></td>
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

        $(".email").click(function (e) {
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
