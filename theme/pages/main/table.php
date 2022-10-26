<?php $this->layout("_theme", ["title" => $title]) ?>

<style> .cancel { display: none } </style>

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
                                    <label>Data de Coleta</label>

                                    <div class="radios">

                                        <input value="sim" class="form-check-radio coleta <?= $cotacao->id ?>" type="radio" name="coleta-option">
                                        <label style="margin-right: 1rem">Sim</label>
                                        <input checked value="nao" class="form-check-radio coleta <?= $cotacao->id ?>" type="radio" name="coleta-option">
                                        <label>Não</label>

                                    </div>

                                    <input disabled type="datetime-local" class="form-control data_coleta <?= $cotacao->id ?>" placeholder="Data de Coleta" name="data_liberacao" required>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <input type="text" name="status" id="status-hidden-<?= $cotacao->id ?>" list="status">
                                    <datalist id="status">
                                        <option>Liberado</option>
                                        <option>Aguardando Cliente</option>
                                    </datalist>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cor do Status</label>
                                    <select style="width: 10rem" id="color-hidden-<?= $cotacao->id ?>" name="color" class="form-color <?= $cotacao->id ?>">
                                        <option style="background-color: #3CB043; color: white" value="#3CB043">Verde</option>
                                        <option style="background-color: #E3AE26; color: white" value="#E3AE26">Laranja</option>
                                        <option style="background-color: #6495ED; color: white" value="#6495ED">Azul</option>
                                        <option style="background-color: #D30000; color: white" value="#D30000">Vermelho</option>
                                        <option style="background-color: #993399; color: white" value="#993399">Roxo</option>
                                    </select>
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

<?php endforeach; endif; ?>

<div class="row">

    <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cotações</h4>

                    <a href="<?= $router->route('Cotacoes.create') ?>"> <h6>Criar Cotação</h6> </a>
                </div>
                <div class="card-body">

                    <form method="get" class="filter-form">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="vendedor" value="vendedor">
                            <label class="form-check-label" for="inlineRadio1">Vendedor</label>
                        </div>
   <!--
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="updater" value="updater">
                            <label class="form-check-label" for="inlineRadio8">Usuário</label>
                        </div>
-->

                     <div class="form-check form-check-inline">
                         <input class="form-check-input" type="radio" name="type" id="id" value="id">
                         <label class="form-check-label" for="inlineRadio7">Num.</label>
                     </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="data" value="data">
                            <label class="form-check-label" for="inlineRadio2">Data</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="cliente" value="cliente">
                            <label class="form-check-label" for="inlineRadio3">Cliente</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="valor" value="valor">
                            <label class="form-check-label" for="inlineRadio4">Valor</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="km" value="km">
                            <label class="form-check-label" for="inlineRadio5">KM</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="tipo" value="tipo">
                            <label class="form-check-label" for="inlineRadio6">Tipo de Carga</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="status" value="status">
                            <label class="form-check-label" for="inlineRadio6">Status</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="origem" value="origem">
                            <label class="form-check-label" for="inlineRadio6">Origem</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="destino" value="destino">
                            <label class="form-check-label" for="inlineRadio6">Destino</label>
                        </div>
                        <div class="form-group form-check-inline">
                            <select class="form-control" name="frete" id="frete">
                                <option value="">Todos</option>
                                <option value="cif">CIF</option>
                                <option value="fob">FOB</option>
                            </select>
                        </div>
                        <div class="form-group form-check-inline" style="margin-bottom: .5rem">
                            <button type="submit" class="btn btn-primary" name="submit" value="Filtrar">Filtrar</button>
                        </div>

                        <div class="filter-group vendedor">

                            <select disabled style="max-width: 15rem" class="filter-group form-control vendedor" name="vendedor" id="usuario">
                                <option value="todos">Todos</option>

                                <?php foreach ($users as $user): ?>

                                    <option value="<?= $user->first_name ?>"><?= $user->first_name ?></option>

                                <?php endforeach; ?>
                            </select>
                        </div>
<!--
                        <div class="filter-group updater">

                            <select disabled style="max-width: 15rem" class="filter-group form-control updater" name="updater" id="updater">
                                <option value="todos">Todos</option>

                                <?php foreach ($users as $user): ?>

                                    <option value="<?= $user->first_name ?>"><?= $user->first_name ?></option>

                                <?php endforeach; ?>
                            </select>
                        </div>
-->
                        <div class="filter-group id" style="display: flex">
                            <input style="max-width: 10rem; margin-right: 2rem" class="filter-group form-control id" type="text" name="id" placeholder="Num.: " disabled>
                        </div>

                        <div class="filter-group data">
                            <input style="max-width: 15rem" class="filter-group form-control data" type="date" name="data" placeholder="Data da Cotação" disabled>
                        </div>
                        <div class="filter-group cliente">
                            <input style="max-width: 15rem" class="filter-group form-control cliente" type="text" name="cliente" placeholder="Nome do Cliente" disabled>
                        </div>
                        <div class="filter-group valor" style="display: flex">
                            <input style="max-width: 10rem; margin-right: 2rem" class="filter-group form-control valor-filter" type="number" name="min-valor" placeholder="Maior que:" disabled required>
                            <input style="max-width: 10rem;" class="filter-group form-control valor-filter" type="number" name="max-valor" placeholder="Menor que:" disabled required>

                        </div>
                        <div class="filter-group km" style="display: flex">
                            <input style="max-width: 10rem; margin-right: 2rem" class="filter-group form-control km-filter" type="text" name="min-km" placeholder="Maior que:" disabled required>
                            <input style="max-width: 10rem" class="filter-group form-control km-filter" type="text" name="max-km" placeholder="Menor que:" disabled required>
                        </div>
                        <div class="filter-group tipo">
                            <select class="filter-group form-control tipo-filter" style="max-width: 10rem" name="tipo" id="tipo" disabled>
                                <option value="dedicada">Dedicada</option>
                                <option value="fracionada">Fracionada</option>
                            </select>
                        </div>
                        <div class="filter-group status">
                            <select class="filter-group form-control status-filter" style="max-width: 10rem" name="status" id="status" disabled>
                                <option value="Fechado">Fechado</option>
                                <option value="Não Fechou">Não Fechado</option>
                                <option value="Aguardando Cliente">Aguardando Cliente</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="filter-group origem" style="display: flex">
                            <input style="max-width: 10rem; margin-right: 2rem" class="filter-group form-control origem-filter" type="text" name="origem" placeholder="Origem" disabled>
                        </div>
                        <div class="filter-group destino" style="display: flex">
                            <input style="max-width: 10rem" class="filter-group form-control destino-filter" type="text" name="destino" placeholder="Destino" disabled>
                        </div>

                    </form>

                    <div class="table-responsive">
                        <table class="table" style= "white-space: nowrap;">
                            <thead class="text-primary" style="font-size: .6rem; gap: 1rem">
                            <th>
                                Editar
                            </th>

                            <?php
                                if($userE):
                            ?>
                            <th>
                                Vendedor
                            </th>
                            <?php
                                endif;
                            ?>
                            <!--
                            <th>
                                Usuário
                            </th>
                            -->
                            <th>
                                Nª
                            </th>
                            <th>
                                Data
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                Cliente
                            </th>
                            <th>
                                Valor
                            </th>

                            <?php
                                if($userE):
                            ?>
                            <th>
                                Valor do Motorista
                            </th>
                            <?php
                                endif;
                            ?>
                            <th>
                                Orígem x Destino
                            </th>
                            <th>
                                Veículo
                            </th>
                            <th>
                                Km
                            </th>
                            <th>
                                UF
                            </th>
                            <th>
                                CNPJ Remetente
                            </th>
                            <th>
                                CNPJ destinatario
                            </th>
                            <?php
                                if($userE):
                            ?>
                            <th>
                                OBS
                            </th>
                            <?php
                                endif;
                            ?>
                            </thead>
                            <tbody>

                            <?php if(!empty($cotacoes)): foreach ($cotacoes as $cotacao): ?>

                            <form method="post" class="main-form <?= $cotacao->id ?>" data-action="<?= $router->route('Cotacoes.update'); ?>">
                                <tr>
                                    <td style="display: flex;  gap: 2rem; align-items: center">
                                        <a href="#" class="items-open <?= $cotacao->id ?>"> <i class="fas fa-bars"></i> </a>
                                        <div class="items <?= $cotacao->id ?>">
                                            <a class="items-close <?= $cotacao->id ?>" href="#"><i class="fas fa-times"></i></a>

                                            <?php
                                                if($userE):
                                            ?>
                                            <a href="<?= $router->route('Cotacoes.edit', ['id'=>$cotacao->id]) ?>">Editar</a>
                                            <?php
                                                endif;
                                            ?>
                                            <div class="edit-div" style="display: flex; justify-content: space-around; align-items: center; min-width: 40%">
                                                <a href="#" class="edit-button <?= $cotacao->id ?>" >Alterar Status</a>
                                                <input type="submit" name="submit" class="form-control submit <?= $cotacao->id ?> " value="Confirmar" style="border: 2px solid #f96332; font-weight: bold;">
                                            </div>

                                            <?php
                                                if($userE):
                                            ?>
                                            <a class="items-close <?= $cotacao->id ?> ddd" href="#" data-action="<?= $router->route('Cotacoes.delete', ['id' => $cotacao->id]) ?>">Deletar</a>
                                            <a class="items-close <?= $cotacao->id ?> ddd" href="#" data-action="<?= $router->route('Cotacoes.duplicate', ['id' => $cotacao->id]) ?>">Duplicar</a>
                                            <?php
                                                endif;
                                            ?>


                                        </div>

                                        <?php
                                            if($userE):
                                        ?>
                                        <div style="width: 1rem; height: 1rem; border-radius: 100%; border: 1px inset black; background-color: <?php if($cotacao->enviado === "1"): echo 'greenyellow;'; else: echo 'gray;'; endif; ?>"></div>
                                        <?php
                                            endif;
                                        ?>
                                    </td>
                                    <?php
                                        if($userE):
                                    ?>
                                        <td>
                                            <?= $cotacao->vendedor ?>
                                        </td>
                                    <?php
                                        endif;
                                    ?>
                                    <!--
                                    <td>
                                        <?= $cotacao->updater ?>
                                    </td>
                                    -->
                                    <td>
                                        <a target="_blank" href="<?= $router->route('Cotacoes.visualizar', ['id'=> $cotacao->id]) ?>"><?= $cotacao->id ?></a>
                                        <input type="hidden" value="<?= $cotacao->id ?>" name="cotacao">
                                        <input type="hidden" value="<?= $_SESSION['id'] ?>" name="updater">
                                    </td>
                                    <td>
                                        <?= $cotacao->date ?>
                                    </td>
                                    <td style="min-width: 14rem">
                                        <select style="border: 1px solid #f96332; color: black; font-weight: bold" data-pagamento="<?= $cotacao->pagamento ?>" class="form-control status <?= $cotacao->id; ?> <?php
                                        foreach ($empresas as $empresa) :
                                            if($empresa->id === $cotacao->cliente_id) :
                                                echo $empresa->spc;
                                            endif;
                                        endforeach;
                                        ?>" name="status" id="<?php

                                        foreach ($transportes as $transporte):

                                            if($transporte->cotacao_id === $cotacao->id):

                                                echo "sim";
                                            else:
                                                echo "nao";

                                            endif;

                                        endforeach;
                                        ?>" disabled="">
                                            <option style="background-color: dodgerblue;" value="Fechado" <?php if($cotacao->status == "Fechado"): echo "selected"; endif; ?>>Fechado</option>
                                            <option style="background-color: red;" value="Nao Fechou" <?php if($cotacao->status == "Nao Fechou"): echo "selected"; endif; ?>>Não Fechou</option>
                                            <option style="background-color: yellow;" value="Aguardando Cliente" <?php if($cotacao->status == "Aguardando Cliente"): echo "selected"; endif; ?>>Aguardando Cliente</option>
                                            <option style="background-color: orange;" value="Cancelado" <?php if($cotacao->status == "Cancelado"): echo "selected"; endif; ?>>Cancelado</option>
                                            <option style="background-color: blue;color: #fff;" value="Aguardando Transportadora" <?php if($cotacao->status == "Aguardando Transportadora"): echo "selected"; endif; ?>>Aguardando Transportadora</option>

                                        </select>

                                    </td>
                                    <td>
                                        <?php foreach($empresas as $empresa):

                                            if($empresa->id === $cotacao->cliente_id):

                                                ?>
                                                <a href="<?= url("empresa/edit/{$empresa->id}")?>"><?= $empresa->name ?></a>
                                                <?php


                                            endif;

                                         endforeach;?>

                                        <?php if(isset($cotacao->cliente_fob)):?>
                                            <b style="color: #f96332; cursor: pointer; margin-left: 1rem" class="fob <?= $cotacao->id ?>">FOB</b>
                                            <b style="color: #f96332;" class="empresa-fob <?= $cotacao->id ?>">-> <?= $cotacao->cliente_fob ?></b>
                                        <?php endif; ?>

                                    </td>
                                    <td>
                                        R$ <?= number_format($cotacao->valor_cotacao,2,",",".") ?>
                                    </td>

                                    <?php
                                        if($userE):
                                    ?>
                                    <td>
                                        Entre: R$ <?= number_format($cotacao->valor_motorista_min,2,",",".") ?> E: R$ <?= number_format($cotacao->valor_motorista_max,2,",",".") ?>
                                    </td>
                                    <?php
                                        endif;
                                    ?>
                                    <td>
                                        <?= $cotacao->cidade_origem."/".$cotacao->uf_origem." X ".$cotacao->cidade_destino."/".$cotacao->uf_destino ?>
                                    </td>
                                    <td>
                                        <?= $cotacao->veiculo ?>
                                    </td>
                                    <td>
                                        <?= $cotacao->km ?>
                                    </td>
                                    <td>
                                        <?= $cotacao->uf_origem."x".$cotacao->uf_destino ?>
                                    </td>
                                    <td>
                                        <?= $cotacao->cidade_origem."/".$cotacao->uf_origem ?>
                                    </td>
                                    <td>
                                        <?= $cotacao->cidade_destino."/".$cotacao->uf_destino ?>
                                    </td>

                                    <?php
                                        if($userE):
                                    ?>
                                    <td>
                                        <?= $cotacao->observacao ?>
                                    </td>
                                    <?php
                                        endif;
                                    ?>
                                </tr>
                            </form>

                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer" style="display: flex; flex-direction: column; align-items: center">

                    <?php
                    if(!array_key_exists("type", $_GET)):
                    echo $pager;
                    endif;
                    ?>

                    <?php
                    if(array_key_exists("message", $_SESSION)):
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    endif;
                    ?>
                    <div class="msg"></div>
                </div>
            </div>
    </div>

</div>

<?php $this->start("script"); ?>

<script>
$(function () {

    const filterdiv = $(".filter-group");

    $(".fob").click(function (e) {

        var id = this.classList[1];

        $(`.empresa-fob.${id}`).toggleClass("active");

    })

    $(".items-open").click(function (e) {
        e.preventDefault();

        let id = this.classList[1];

        this.classList.toggle("active");
        $(`.items.${id}`).toggleClass("active");

    })
    $(".items-close").click(function (e) {
        e.preventDefault();

        let id = this.classList[1];

        $(`.items-open.${id}`).toggleClass("active");
        $(`.items.${id}`).toggleClass("active");
    })
    $("input[type=radio]").click(function (e) {

        switch (this.id) {
            case "vendedor":
                filterdiv.removeClass("active");
                filterdiv.prop("disabled", true);
                $(".vendedor").addClass("active");
                $(".vendedor").prop("disabled", false);
                break

//            case "updater":
//                filterdiv.removeClass("active");
//                filterdiv.prop("disabled", true);
//                $(".updater").addClass("active");
//                $(".updater").prop("disabled", false);
//                break

            case "id":
                filterdiv.removeClass("active");
                filterdiv.prop("disabled", true);
                $(".id").addClass("active");
                $(".id").prop("disabled", false);
                break

            case "data":
                filterdiv.removeClass("active");
                filterdiv.prop("disabled", true);
                $(".data").addClass("active");
                $(".data").prop("disabled", false);
                break

            case "cliente":
                filterdiv.removeClass("active");
                filterdiv.prop("disabled", true);
                $(".cliente").addClass("active");
                $(".cliente").prop("disabled", false);
                break

            case "valor":
                filterdiv.removeClass("active");
                filterdiv.prop("disabled", true);
                $(".valor-filter").addClass("active");
                $(".valor-filter").prop("disabled", false);
                break

            case "km":
                filterdiv.removeClass("active");
                filterdiv.prop("disabled", true);
                $(".km-filter").addClass("active");
                $(".km-filter").prop("disabled", false);
                break

            case "tipo":
                filterdiv.removeClass("active");
                filterdiv.prop("disabled", true);
                $(".tipo-filter").addClass("active");
                $(".tipo-filter").prop("disabled", false);
                break

            case "status":
                filterdiv.removeClass("active");
                filterdiv.prop("disabled", true);
                $(".status-filter").addClass("active");
                $(".status-filter").prop("disabled", false);
                break

            case "origem":
                filterdiv.removeClass("active");
                filterdiv.prop("disabled", true);
                $(".origem-filter").addClass("active");
                $(".origem-filter").prop("disabled", false);
                break

            case "destino":
                filterdiv.removeClass("active");
                filterdiv.prop("disabled", true);
                $(".destino-filter").addClass("active");
                $(".destino-filter").prop("disabled", false);
                break
        }

    })
     $(".status").map(function (e) {
         let thisClass = `${this.classList[1]}.${this.classList[2]}`;

         $(`.${thisClass}`).css("background-color", $(`.${thisClass} option:selected`).css("background-color"))
     })

    var button;

    $(".edit-button").click(function (e) {
        e.preventDefault();

        button = this.classList[1];

        if(this.className === `edit-button ${button} cancel`) {
            $(`.status.${button}`).attr("disabled", "disabled");
        } else {
            $(`.status.${button}`).removeAttr("disabled");
        }

        $(".edit-button").toggleClass("cancel");
        $(`.submit.${button}`).toggleClass("on");
    })

    $(".main-form").submit(function (e) {
        e.preventDefault();

        var data = $(this).data();

        $.post(data.action, $(this).serialize(), function (response) {
            $(".msg").addClass("on");
            $(".msg").html(response.message);

            $(`.status.${button}`).attr("disabled", "disabled");
            $(".edit-button").toggleClass("cancel");
            $(`.submit.${button}`).toggleClass("on");

        }, "json");
    })

    $(".status").change(function (e) {

        var pagamento = ($(this).data()).pagamento;
        var spc = this.classList[3];
        var id = this.classList[2];

        switch (this.value) {
            case "Aguardando Cliente":
                if(this.id === "sim") {
                    $(`.status.${button}`).attr("disabled", "disabled");
                    $(".edit-button").toggleClass("cancel");
                    $(`.submit.${button}`).toggleClass("on");
                    $(`.status.${button}`).val("Nao Fechou");
                    alert("Transporte vinculado, por gentileza excluir o transporte");
                    return;
                }

                $(this).css("background-color", "yellow");
                break
            case "Fechado":

                if(pagamento !== "a vista") {
                    if(spc === "a") {
                        var resultado = confirm("O Frete é à Vista ?");
                        if (!resultado == true) {
                            $(`.status.${button}`).attr("disabled", "disabled");
                            $(".edit-button").toggleClass("cancel");
                            $(`.submit.${button}`).toggleClass("on");
                            $(`.status.${button}`).val("Nao Fechou");

                            alert("SPC A VERIFICAR");
                            return;
                        }
                    }
                }

                $(`.submit.${button}`).toggleClass("on");
                $(`.cadastrar-transportes.${button}`).toggleClass("active")

                $(this).css("background-color", "dodgerblue");
                break
            case "Nao Fechou":
                if(this.id === "sim") {
                    $(`.status.${button}`).attr("disabled", "disabled");
                    $(".edit-button").toggleClass("cancel");
                    $(`.submit.${button}`).toggleClass("on");
                    $(`.status.${button}`).val("Nao Fechou");
                    alert("Transporte vinculado, por gentileza excluir o transporte");
                    return;
                }

                $(this).css("background-color", "red");
                break
            case "Cancelado":
                if(this.id === "sim") {
                    $(`.status.${button}`).attr("disabled", "disabled");
                    $(".edit-button").toggleClass("cancel");
                    $(`.submit.${button}`).toggleClass("on");
                    $(`.status.${button}`).val("Nao Fechou");
                    alert("Transporte vinculado, por gentileza excluir o transporte");
                    return;
                }

                $(this).css("background-color", "orange");
                break
        }

    })

    $(".ddd").click(function (e) {
        e.preventDefault();

        var id = this.classList[1];
        var data = $(this).data();
        console.log(id)

        $.post(data.action, {'id': id}, function (result) {
        }, "json")

            window.location.reload()
    })
})
</script>

<script>
    $(function () {

        $(".form-color").change(function (e) {

            $(this).css("background-color", $(this).val());

        })

        $(".coleta").change(function (e) {

            var button = this.classList[2];

            if($(this).val() === "sim") {
                $(".data_coleta").prop("disabled", false)
            } else if($(this).val() === "nao") {
                $(".data_coleta").prop("disabled", true)
            }

        })


        $('.transporte-create').submit(function (e) {
            e.preventDefault();

            var data = $(this).data();
            var button = this.classList[1];

            var mainform = $(`.main-form.${button}`).data();

            $.post(mainform.action, $(`.main-form.${button}`).serialize(), function (response) {
                $(".msg").addClass("on");
                $(".msg").html(response.message);

                $(`.status.${button}`).attr("disabled", "disabled");
                $(".edit-button").toggleClass("cancel");

            }, "json");

            $.post(data.action, $(this).serialize(), function (response) {

                alert(response);

                window.location.href = "<?= $router->route('Main.transportes', ['categoria' => 'liberado']) ?>"
            }, 'json')

            $(`.cadastrar-transportes.${button}`).toggleClass("active");

        })

    })
</script>

<?php $this->stop(); ?>
