<?php $this->layout("_theme", ["title" => $title]) ?>

<style>

    .spanB{
        display: block;
        padding: 8px 15px;
        width: 100%;
        text-transform: uppercase;
        text-align: center;
        border-radius: 10px;
        margin: 5px 0;
    }
    .span1{
        color: #fff;
        background: linear-gradient(to right, #72fe6c 0%, #3afd03 100%);
    }
    .span2{
        color: #fff;
        background: linear-gradient(to right, #83b1ff 0%, #066ffe 100%);
    }
    .span3{
        color: #fff;
        background: linear-gradient(to right, #5b73fe 0%, #0d02fe 100%);
    }
    .span4{
        color: #fff;
        background: #000000;
    }
    .span5{
        color: #fff;
        background: #c5cd01;
    }
    .span6{
        color: #fff;
        background: #fe0000;
    }
    .span6{
        color: #fff;
        background: #fe0000;
    }
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
<div class="row" style="position: relative">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header justify-content-start">
                <h4>Transportes</h4>
                <div class="row">
                    <div class="col-md-auto">

                    </div>
                </div>
            </div>

            <div class="card-body">


                <form method="get" class="filter-form">
                    <input type="hidden" name="categoria" value="<?= $_GET['categoria'] ?>">

                    <div class="row align-items-center">

                        <div class="col-md-3">
                            <div class="form-group">
                                <input placeholder="Nome do motorista" type="text" name="motorista" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input placeholder="CTe" type="number" name="cte" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input placeholder="MDFe" type="text" name="mdfe" class="form-control">
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
                                <input type="submit" class="btn btn-primary" name="submit" value="Filtrar">
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
                                Coleta
                            </th>
                            <th>Entrega</th>
                            <th>Status</th>
                            <th>
                                Contação
                            </th>
                            <th>
                                Origem
                            </th>
                            <th style="text-align: center">
                                Destino
                            </th>
                            <th>
                                Boleto
                            </th>
                            <th>
                                CTe
                            </th>
                            <th>
                                Motorista
                            </th>
                            <th>
                                CPF
                            </th>
                            <th>
                                Placa
                            </th>
                            <th>
                                Placa Reboque
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
                                        Data: <?= $fiscal->transportes()->data_liberacao ?>
                                    </td>
                                    <td>
                                        <?= $fiscal->transportes()->data_entrega ?>
                                    </td>

                                    <td>
                                        <?php
                                            if($fiscal->transportes()->liberacao == "liberado"){
                                        ?>
                                                <span class="spanB span1">LIBERADO</span>
                                        <?php
                                            }elseif ($fiscal->transportes()->liberacao == "transitando"){
                                        ?>
                                                <span class="spanB span2">TRANSITANDO</span>
                                        <?php
                                            }elseif ($fiscal->transportes()->liberacao == "finalizado"){

                                        ?>
                                                <span class="spanB span3">FINALIZADO</span>
                                        <?php
                                            }
                                        ?>

                                    </td>

                                    <td style="text-align: center">
                                        <a target="_blank" href="<?= $router->route('Cotacoes.visualizar', ['id'=> $fiscal->cotacao]) ?>"><?= $fiscal->cotacao ?></a>
                                    </td>

                                    <td><?= $fiscal->transportes()->origem ?></td>

                                    <td><?= $fiscal->transportes()->destino ?></td>

                                    <td>
                                        <?php if(!empty($fiscal->boletos)): ?>
                                            <?php
                                                $vencimentos = explode(";", $fiscal->boletos_datas);
                                                $a = null;
                                                $b = null;
                                                foreach ($vencimentos as $item){
                                                    if(strtotime($item) < strtotime(date("Y-m-d"))){
                                                        $a .= date("d/m/Y", strtotime($item))." ";
                                                    }else{
                                                        $b .= date("d/m/Y",  strtotime($item))." ";
                                                    }
                                                }

                                                if($a){
                                        ?>
                                                    <span class="spanB span6">VENCIDO - <?= $a ?></span>
                                        <?php
                                                }

                                                if($b){
                                        ?>
                                                    <span class="spanB span5">A VENCER - <?= $b ?></span>
                                        <?php
                                                }
                                            ?>
                                        <?php else: ?>
                                            <span class="spanB span4">NÂO GERADO</span>
                                        <?php endif; ?>
<!--                                        <span class="spanB span1">PAGO</span>-->
                                    </td>

                                    <td style="text-align: center">
                                        <?php if(!$fiscal->cte): ?>
                                            <a href="<?= $router->route('Fiscal.addD', ['id' => $fiscal->id]) ?>">  <i class="far fa-plus-square"></i>  </a>
                                        <?php else: ?>
                                            <a target="_blank" href="<?= url($fiscal->pdf_cte) ?>"><?= $fiscal->cte ?></a>
                                        <?php endif; ?>
                                    </td>

                                    <td> <?= $fiscal->motorista ?> </td>
                                    <td> <?= $fiscal->motoristas()->cpf ?> </td>

                                    <td><?= $fiscal->motoristas()->placa_veiculo ?></td>

                                    <td><?= $fiscal->motoristas()->placa_reboque ?></td>
                                </tr>

                            <?php endforeach; endif; ?>
<!--                            <tr>-->
<!--                                <td></td>-->
<!--                                <td></td>-->
<!--                                <td>Aguardando</td>-->
<!--                                <td></td>-->
<!--                                <td>-->
<!--                                    <span class="spanB span1">LIBERADO</span>-->
<!--                                    <span class="spanB span2">TRANSITANDO</span>-->
<!--                                    <span class="spanB span3">FINALIZADO</span>-->
<!--                                </td>-->
<!--                                <td>9842 - R$ 2.000,00</td>-->
<!--                                <td></td>-->
<!--                                <td></td>-->
<!--                                <td>-->
<!--                                    <span class="spanB span4">NÂO GERADO</span>-->
<!--                                    <span class="spanB span5">A VENCER - 17/08/2022</span>-->
<!--                                    <span class="spanB span6">VENCIDO - 16/08/2022</span>-->
<!--                                    <span class="spanB span1">PAGO</span>-->
<!--                                </td>-->
<!--                            </tr>-->
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
