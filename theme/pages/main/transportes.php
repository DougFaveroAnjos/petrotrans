<?php $this->layout("_theme", ["title" => $title, "liberados" => $liberados]) ?>

<style>

    .cancel {
        display: none;
    }

    .global-button {

        display: flex;
        gap: 1rem;
        text-align: center;
        transition: .8s;

    }

    #global-icon {
        transition: .8s;
        transform: translateX(6.2rem);

    }

    .global-button:hover .global-text {

        opacity: 1;

    }

    .global-button:hover #global-icon {
        transition: .8s;
        transform: translateX(0px);
    }

    .global-text {
        opacity: 0;
        transition: 1.5s;
    }
</style>
<?php if(!empty($cotacoes)): foreach ($cotacoes as $cotacao): ?>

    <div class="row cadastrar-transportes <?= $cotacao->id ?>">
        <div class="col-md-12" style="padding: 0">

            <div class="card">
                <div class="card-header">
                    <h5 class="title">Cadastrar Transporte</h5>
                </div>
                <div class="card-body">
                    <form class="transporte-create <?= $cotacao->id ?>" action="<?= $router->route('Transportes.new') ?>" method="post" data-action="<?= $router->route('Transportes.new') ?>">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data da Liberação</label>

                                    <div class="radios">

                                        <input value="sim" class="form-check-radio liberacao <?= $cotacao->id ?>" type="radio" name="liberacao-option">
                                        <label style="margin-right: 1rem">Sim</label>
                                        <input value="nao" class="form-check-radio liberacao <?= $cotacao->id ?>" type="radio" name="liberacao-option">
                                        <label>Não</label>

                                    </div>

                                    <input type="date" class="form-control data_liberacao <?= $cotacao->id ?>" placeholder="Data de Liberação" name="data_liberacao" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label>Pagamento</label>
                                    <input value="<?= $cotacao->pagamento ?>" type="text" name="pagamento_cotacao" class="form-control pagamento_cotacao <?= $cotacao->id ?>" placeholder="Forma de Pagamento">
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

                        <input type="hidden" name="status" id="status-hidden-<?= $cotacao->id ?>">
                        <input type="hidden" name="color" id="color-hidden-<?= $cotacao->id ?>">
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
                                <button type="submit" class="btn form-control" name="submit" value="Criar Transporte" >Criar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; endif; ?>
<div class="row" style="position: relative">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header justify-content-end">
                <div class="row">
                    <div class="col-md-auto">

                    </div>
                </div>
            </div>

            <div class="card-header" style="display: flex; justify-content: space-between">
                <h4>Transportes</h4>

                <a class="global-button" href="<?= $router->route('Main.global') ?>">
                    <i id="global-icon" style="font-size: 2rem" class="fas fa-globe-americas"></i>
                    <h5 class="global-text">Listagem Global</h5>
                </a>

                <div class="buttons form-group" style="display: flex; align-items: center; gap: 2rem; min-width: 25rem">

                    <select class="form-control" name="categoria" id="categoria" style="max-height: 2.5rem; max-width: 50%">
                        <option <?php if($_GET['categoria'] === 'liberado'): echo 'selected'; endif; ?> value="<?= $router->route('Main.transportes', ['categoria' => 'liberado']) ?>">Liberados</option>
                        <option <?php if($_GET['categoria'] === 'transitando'): echo 'selected'; endif; ?> value="<?= $router->route('Main.transportes', ['categoria' => 'transitando']) ?>">Transitando</option>
                        <option <?php if($_GET['categoria'] === 'finalizado'): echo 'selected'; endif; ?> value="<?= $router->route('Main.transportes', ['categoria' => 'finalizado']) ?>">Finalizado</option>
                        <option <?php if($_GET['categoria'] === 'devolucao'): echo 'selected'; endif; ?> value="<?= $router->route('Main.transportes', ['categoria' => 'devolucao']) ?>">Devolução</option>
                    </select>


                        <div class="flex-column">
                            <label for="add-cotacoes">Cotação</label>
                            <select class="form-control" name="add-cotacoes" id="add-cotacoes">
                                <?php foreach ($cotacoes as $cotacao): ?>
                                    <option value="<?= $cotacao->id ?>"><?= $cotacao->id ?></option>
                                <?php endforeach; ?>
                            </select>

                            <a id="add-button" href="#"
                               data-action="<?= $router->route('Transportes.new') ?>"
                               style="font-weight: bold"
                            >Adicionar Transporte</a>
                        </div>

                </div>

            </div><!-- card header -->

            <div class="card-body">


                <form method="get" class="filter-form">
                    <input type="hidden" name="categoria" value="<?= $_GET['categoria'] ?>">

                    <div class="row align-items-center">

                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" name="cotacao-id" id="cotacao-id">
                                    <option value="none">Cotação:</option>

                                    <?php foreach ($liberados as $liberado): ?>

                                        <option value="<?= $liberado->cotacao_id ?>"><?= $liberado->cotacao_id ?></option>

                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input placeholder="Nome da Empresa" type="text" name="empresa" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input placeholder="CTe" type="number" name="cte" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input placeholder="Origem" type="text" name="origem" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input placeholder="Destino" type="text" name="destino" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group" style="margin-left: 1rem">
                                <button type="submit" class="btn btn-primary" name="submit" value="Filtrar">Filtrar</button>
                            </div>
                        </div>
                    </div>


                </form>


                <div class="table-responsive" style="max-height: 80vh; overflow-y: auto; position: static">

                    <table class="table" style="white-space: nowrap; position: static">

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
                            <?php if($_GET['categoria'] === "finalizado"): ?>

                            <th>Entrega</th>

                            <?php endif; ?>
                            <th>
                                Cotação + Valor
                            </th>
                            <th>
                                Empresa
                            </th>
                            <th>
                                SPC
                            </th>
                            <th style="text-align: center">
                                Status
                            </th>

                            <?php if($_GET['categoria'] === "liberado"): ?>

                            <th>Valor Medio Do Motorista</th>

                            <?php endif; ?>

                            <?php if($_GET['categoria'] !== "liberado"): ?>
                                <th>Nome do Motorista</th>
                                <th>
                                    Valor Motorista
                                </th>

                                <th>
                                    Pagamento
                                </th>

                            <?php endif; ?>
                            <th>
                                Origem
                            </th>
                            <th>
                                Destino
                            </th>
                            <?php if($_GET['categoria'] === "liberado"): ?>
                                <th>
                                    Postado
                                </th>
                            <?php endif; ?>
                        </thead>


                        <tbody>

                        <?php if(!empty($liberados)): foreach ($liberados as $liberado): ?>
                            <?php if($cotacoes): foreach ($cotacoes as $cotacao): if($liberado->cotacao_id === $cotacao->id): ?>
                        <tr>
                            <td style="display: flex;  gap: 2rem; align-items: center">
                                <a href="#" class="items-open <?= $liberado->id ?>"> <i class="fas fa-bars"></i> </a>

                                <div class="items <?= $liberado->id ?>">
                                    <a class="items-close <?= $liberado->id ?>" href="#"><i class="fas fa-times"></i></a>

                                    <a class="refresh" data-id="<?= $liberado->id ?>" data-action="<?= $router->route('Transportes.refresh'); ?>" href="#">Atualizar</a>

                                    <div class="edit-div" style="display: flex; justify-content: space-around; align-items: center; min-width: 40%">
                                        <a href="#" class="edit-button <?= $liberado->id ?>" >Alterar Status</a>
                                        <input data-action="<?= $router->route('Transportes.edit') ?>" type="submit" name="submit" class="form-control submit <?= $liberado->id ?> " value="Confirmar" style="border: 2px solid #f96332; font-weight: bold;">
                                    </div>

                                    <?php if($_GET['categoria'] === 'liberado'): ?>
                                    <a target="_blank" href="<?= $router->route('Coletas.cadastrar', ['transporte' => $liberado->id]) ?>"> <i class="fas fa-plus"></i> Cadastrar O.C</a>
                                    <?php elseif((new \Source\Models\ColetasModel())->find('transporte = :transporte', 'transporte='.$liberado->id)->fetch()): ?>
                                        <a
                                                href="<?= $router->route('Coletas.editarOC', ['id' => (new \Source\Models\ColetasModel())->find('transporte = :transporte', 'transporte='.$liberado->id)->fetch()->id]) ?>"
                                                target="_blank">
                                            Editar O.C
                                        </a>
                                    <?php endif; ?>

                                    <select style="width: 90%"
                                            class="liberar <?= $liberado->id ?> form-control"
                                            data-action="<?= $router->route('Transportes.liberacao') ?>"
                                            href="#">
                                        <option <?php if($liberado->liberacao === 'liberado'): echo 'selected'; endif; ?> value="liberado">Liberado</option>
                                        <option <?php if($liberado->liberacao === 'transitando'): echo 'selected'; endif; ?> value="transitando">Transitando</option>
                                        <option <?php if($liberado->liberacao === 'devolucao'): echo 'selected'; endif; ?> value="devolucao">Devolução</option>
                                    </select>
                                    <a target="_blank" href="<?= $router->route('Transportes.finalizar', ['id' => $liberado->id]) ?>">Finalizar Transporte</a>

                                    <?php if($liberado->motorista_name !== null && $liberado->motorista_name !== 'motorista'): ?> <a href="<?= $router->route('Motoristas.visualizar', ['token' => $liberado->token, 'id' => $liberado->id]) ?>" target="_blank">Confirmação de Motorista</a> <?php endif; ?>

                                    <a class="items-close <?= $liberado->id ?> duplicate" data-action="<?= $router->route('Transportes.duplicate', ['id' => $liberado->id]) ?>"  href="#">Duplicar</a>

                                    <a class="items-close <?= $liberado->id ?> delete" data-action="<?= $router->route('Transportes.delete') ?>" href="#">Deletar</a>
                                </div>

                            </td>
                            <td class="dados_td <?= $liberado->id ?>">

                                <a href="<?= $router->route('Motoristas.visualizar', ['token' => $liberado->token, 'id' => $liberado->id]) ?>">
                                    <i style="font-size: 1.4rem; color: #f96332; cursor: pointer" class="fas fa-clipboard-list"></i>
                                </a>
                            </td>
                            <td>

                                <span><?= strtoupper($liberado->liberacao) ?></span>
                                <br>
                                Data: <?= $liberado->data_liberacao ?>

                            </td>
                            <?php if($_GET['categoria'] === "finalizado"): ?>
                            <td>

                                <span> Data: <?= $liberado->data_finalizado ?></span>



                            </td>
                            <?php endif; ?>

                            <td style="text-align: center">
                                <a target="_blank" href="<?= $router->route('Cotacoes.visualizar', ['id'=> $liberado->cotacao_id]) ?>"><?= $liberado->cotacao_id ?> - R$ <?=$cotacao->valor_cotacao?></a>
                            </td>
                            <td style="max-width: 18rem; overflow-x: hidden">
                                <?= $liberado->empresa_name ?>
                            </td>
                            <td style="text-align: center">

                                <?php if($liberado->empresas()): ?>
                                <?php if($liberado->empresas()->spc === "sem restrição"): ?>
                                <span style="color: #00ff00; font-weight: bold"> OK </span>
                                <?php else: ?>
                                <span style="color: red; font-weight: bold"> Pendências </span>
                                <?php endif; else: ?>
                                    <span style="color: red; font-weight: bold"> Pendências </span>
                                <?php endif; ?>

                            </td>
                            <td class="status_td <?= $liberado->id ?>" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem">

                                    <input disabled="" style="color: <?php
                                    if(explode("; ", $liberado->status)[1] === "#E3AE26"):
                                        echo "black"; else: echo "white";
                                    endif; ?>; padding: .5rem; text-align: center; min-width: 18rem; background-color: <?= explode("; ", $liberado->status)[1] ?>" class="form-control status <?= $liberado->id ?>" type="text" name="status" value="<?= explode("; ", $liberado->status)[0] ?>">

                                <select style="width: 10rem" name="color" class="form-color <?= $liberado->id ?> active">
                                    <option style="background-color: #3CB043;" <?php if(explode("; ", $liberado->status)[1] === "#3CB043"): echo 'selected'; endif; ?> value="#3CB043"></option>
                                    <option style="background-color: #E3AE26;" <?php if(explode("; ", $liberado->status)[1] === "#E3AE26"): echo 'selected'; endif; ?> value="#E3AE26"></option>
                                    <option style="background-color: #6495ED;" <?php if(explode("; ", $liberado->status)[1] === "#6495ED"): echo 'selected'; endif; ?> value="#6495ED"></option>
                                    <option style="background-color: #D30000;" <?php if(explode("; ", $liberado->status)[1] === "#D30000"): echo 'selected'; endif; ?> value="#D30000"></option>
                                    <option style="background-color: #993399;" <?php if(explode("; ", $liberado->status)[1] === "#993399"): echo 'selected'; endif; ?> value="#993399"></option>
                                </select>

                            </td>

                            <?php if($_GET['categoria'] !== "liberado"): ?>
                                <td>
                                   <?= $liberado->motorista_name ?>
                                </td>
                            <?php endif; ?>

                            <!-- Valor Motorista + Desconto -->
                            <td>
                                <?php if($_GET['categoria'] !== 'liberado'):
                                    foreach ($motoristas as $motorista):
                                        if($motorista->name === $liberado->motorista_name):
                                            echo 'R$'.number_format($motorista->valor,2,",",".");
                                endif; endforeach; else: echo $liberado->valor_motorista; endif; ?>
                            </td>
                            <!-- Valor Motorista + Desconto -->

                            <!-- Forma de Pagamento -->
                            <?php if($_GET['categoria'] !== "liberado"): ?>
                            <td>
                                <?php foreach ($motoristas as $motorista):
                                    if($motorista->name === $liberado->motorista_name): ?>
                                <?= strtoupper($motorista->pagamento); ?>
                                        <?php endif; endforeach; ?>
                            </td>
                            <?php endif; ?>
                            <!-- Forma de Pagamento -->

                            <td>
                                <?= $liberado->origem ?>
                            </td>

                            <td>
                                <?= $liberado->destino ?>
                            </td>

                            <?php if($_GET['categoria'] === "liberado"): ?>
                                <td>
                                    <select data-action="<?= $router->route('Transportes.confirm', ['id' => $liberado->id]) ?>"
                                            data-id="<?= $liberado->id ?>"
                                            style="<?php if($liberado->confirmado === "1"): ?>
                                                    background-color: lightgreen;
                                            <?php else: ?>
                                                    background-color: indianred
                                            <?php endif; ?>" class="form-control confirmado" name="confirmado" id="confirmado">
                                        <option style="background-color: lightgreen" value="true" <?php if($liberado->confirmado === "1"): echo "selected"; endif; ?> >Sim</option>
                                        <option style="background-color: #d98181" value="false" <?php if($liberado->confirmado !== "1"): echo "selected"; endif; ?>>Não</option>
                                    </select>
                                </td>
                            <?php endif; ?>

                        </tr>
                                <?php break; endif; endforeach; endif; ?>
                        <?php endforeach; endif; ?>
                        </tbody>

                    </table><!-- table -->

                </div><!-- table div -->

            </div><!-- cardbody -->
            <div class="card-footer" style="display: flex; justify-content: center">
                <?php if($_GET['categoria'] === "finalizado"):
                echo $pager;
                endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->start("script") ?>
<script>

    $(function () {

        $(".confirmado").change(function (e) {

            var data = $(this).data();
            var value = $(this).val();

            $.post(data.action, {"id": data.id, "confirmado": value}, function (result) {
                alert(result);
                window.location.reload();
            }, "json");

        })

        //mudar cor do <select>
        $(".form-color").change(function (e) {

            $(this).css("background-color", $(this).val());

        })


        $("#categoria").on("change", function (e) {

            window.location.href = $(this).val();

        })

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

        $(".edit-button").click(function (e) {
            e.preventDefault();

            var button = this.classList[1];

            if(this.className === `edit-button ${button} cancel`) {
                $(`.status.${button}`).attr("disabled", "disabled");
            } else {
                $(`.status.${button}`).removeAttr("disabled");
            }

            $(`.form-color.${button}`).toggleClass("active");
            $(".edit-button").toggleClass("cancel");
            $(`.submit.${button}`).toggleClass("on");


        })

        $(".duplicate").click(function (e) {
            e.preventDefault();

            var id = this.classList[1];
            var data = $(this).data();

            $.post(data.action, {"id": id}, function (response) {

                alert(response);

                window.location.reload();
            }, 'json');
        })

        $(".submit").click(function (e) {
            e.preventDefault();

            var button = this.classList[2];
            var data = $(this).data();
            var status = $(`.status.${button}`).val();
            var color = $(`.form-color.${button}`).val();

            $(`.status.${button}`).attr("disabled", "disabled");
            $(`.form-color.${button}`).toggleClass("active");
            $(".edit-button").toggleClass("cancel");
            $(`.submit.${button}`).toggleClass("on");

            $.post(data.action, {"status": status, "color": color, "id": button}, function (response) {

                $(`.status.${button}`).css("background-color", color);

                alert(response);

            }, 'json')
        })

        $(".delete").click(function (e) {
            e.preventDefault();

            var id = this.classList[1]
            var data = $(this).data();

            $.post(data.action, {"id": id}, function (response) {

                alert(response);

            }, 'json')

            window.location.reload();
        })
        $(".liberar").change(function (e) {

            var id = this.classList[1];
            var data = $(this).data();
            var val = $(this).val();

            $.post(data.action, {"id": id, "liberacao": val}, function (response) {

                alert(response);

                window.location.reload();
            }, 'json')


        })
        $(".transitar").click(function (e) {
            e.preventDefault();

            var id = this.classList[1];
            var data = $(this).data();

            $.post(data.action, {"id": id, "liberacao": "liberado"}, function (response) {

                alert(response);

                window.location.reload();
            }, 'json')


        })

        $(".dados_td").on("click", function (e) {

            var id = this.classList[1];

                $(`.dados.${id}`).addClass("active");

        })

        $(".dados").on("mouseleave", function (e) {

            $(this).removeClass("active");

        })

        $("#add-button").click(function (e) {
            e.preventDefault();

            var id = $("#add-cotacoes").val();

            $(`.submit.${id}`).toggleClass("on");
            $(`.cadastrar-transportes.${id}`).toggleClass("active")
        })

        $(".liberacao").change(function (e) {

            var button = this.classList[2];

            if($(this).val() === "sim") {
                $(`#status-hidden-${button}`).prop("value", "Liberado")
                $(`#color-hidden-${button}`).prop("value", "#3CB043");
                $(".data_liberacao").prop("disabled", false)
            } else if($(this).val() === "nao") {
                $(`#status-hidden-${button}`).prop("value", "Aguardando Liberação")
                $(`#color-hidden-${button}`).prop("value", "#E3AE26");
                $(".data_liberacao").prop("disabled", true)
            }

        })

        $('.transporte-create').submit(function (e) {
            e.preventDefault();

            var data = $(this).data();
            var button = this.classList[1];

            $.post(data.action, $(this).serialize(), function (response) {

                alert(response);

                window.location.reload();
            }, 'json')

            $(`.cadastrar-transportes.${button}`).toggleClass("active");

        })

        $(".refresh").click(function (e) {
            e.preventDefault();

            var data = $(this).data();

            $.post(data.action, {"id": data.id}, function (response) {
                alert(response);
                window.location.reload();
            }, "json");
        })

    })

</script>
<?php $this->stop() ?>
