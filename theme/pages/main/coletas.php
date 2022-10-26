<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Ordens de Coleta</h4>
            </div><!-- card header -->

            <div class="card-body">

                <form method="get" class="filter-form">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input class="form-control" type="text" name="motorista" placeholder="Nome do Motorista">
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" name="oc" id="oc">

                                    <option class="form-control" value="none">Nº OC</option>

                                    <?php foreach ($coletas as $coleta): ?>

                                        <option value="<?= $coleta->coleta_id ?>"><?= $coleta->coleta_id ?></option>

                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input class="form-control" type="text" name="placa" placeholder="Placa do Veículo / Reboque">
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
                            Data O.C
                        </th>
                        <th>
                            Enviado
                        </th>
                        <th>
                            Nº O.C
                        </th>
                        <th>
                            Empresa
                        </th>
                        <th>
                            Motorista
                        </th>
                        <th>
                            Placa do Veículo
                        </th>
                        <th>
                            Placa do Reboque
                        </th>
                        </thead>


                        <tbody>

                        <?php if(!empty($coletas)): foreach ($coletas as $coleta): ?>

                            <tr>
                                <td style="display: flex;  gap: 2rem; align-items: center;">
                                    <a href="#" class="items-open <?= $coleta->id ?>" style="color: #27356f;"> <i class="fas fa-bars"></i> </a>

                                    <div class="items <?= $coleta->id ?>" style=" border: solid 2px #008DD5; color: #008DD5; border-radius: 10px;">
                                        <a class="items-close <?= $coleta->id ?>" href="#"><i class="fas fa-times"></i></a>

                                        <a class="items-close <?= $coleta->id ?>" href="<?= $router->route('Coletas.visualizar', ['id' => $coleta->id]) ?>" target="_blank">Visualizar PDF</a>

                                        <a class="items-close <?= $coleta->id ?>" href="<?= $router->route('Coletas.download', ['id' => $coleta->id]) ?>">Baixar PDF</a>
                                        <a href="#" class="items-whatsapp <?= $coleta->id ?> whatsapp" data-id="<?= $coleta->id ?>"  data-action="<?= $router->route('Coletas.phone', ['id' => $coleta->id]) ?>">Enviar Mensagem</a>

                                        <a href="<?= $router->route('Coletas.editarOC', ['id' => $coleta->id]) ?>" target="_blank"> Editar O.C </a>

                                        <a class="items-close <?= $coleta->id ?> delete" data-action="<?= $router->route('Coletas.delete') ?>" href="#">Deletar</a>
                                    </div>

                                </td>

                                <td><?= $coleta->date ?></td>
                                <td style="display: flex; justify-content: center"><div style="width: 1rem; height: 1rem; border-radius: 100%; border: 1px inset black;
                                 background-color: <?php if($coleta->email === null): echo 'gray'; else: echo 'lightgreen'; endif; ?>;"></div></td>
                                <td><?= $coleta->coleta_id ?></td>
                                <td><?= ((new \Source\Models\TransportesModel())->find("id = :n", "n={$coleta->transporte}")->fetch()->empresa_name ?? "<p style='font-weight: bold;'>CANCELADO</p>")?></td>
                                <td><?= $coleta->motorista ?></td>
                                <td><?= (new \Source\Models\MotoristasModel())->find("name = :n", "n={$coleta->motorista}")->fetch()->placa_veiculo ?></td>
                                <td><?= (new \Source\Models\MotoristasModel())->find("name = :n", "n={$coleta->motorista}")->fetch()->placa_reboque ?></td>
                            </tr>
                        <?php endforeach; endif; ?>
                        </tbody>

                    </table><!-- table -->

                </div><!-- table div -->

            </div><!-- cardbody -->
            <div class="card-footer" style="display: flex; justify-content: center">
                <?= $pager; ?>
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
            let id = this.classList[1];

            $.post(data.action, {"id": id}, function (result) {
                alert(result);
                window.location.reload();
            }, "json")
        })

        $(".mail").click(function (e) {
            e.preventDefault();

            var data = $(this).data();
            var id = this.classList[1];

            $.post(data.action, {'id': id}, function (result) {
                alert(result);
                window.location.reload();
            }, 'json');
        })

        $(".whatsapp").click(function (e) {
            e.preventDefault();

            var data = $(this).data();
            var id = this.classList[1];

            $(`.cadastrar-transportes`).toggleClass("active");

            $.post(data.action, {'id': id}, function (result) {
                $(`.cadastrar-transportes #checkboxphone`).html(result.msg);
            }, 'json');
        });

//        $(".transporte-create").click(function (e) {
//            e.preventDefault();
//
//            var data = $(this).data();
//            var id = this.classList[1];
//
//            $.post(data.action, {"a": data}, function (result) {
//                console.log(result);
////                window.location.reload();
//            }, 'json');
//        });

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
                <h5 class="title">Enviar mensagem no whatsapp</h5>
            </div>
            <div class="card-body">
                <form class="transporte-create ajax_on" action="<?= url("coleta/phone/send")?>" method="post" data-action="<?= url("coleta/phone/send")?>">
                    <?php
//                        $wpp = (new \Source\Models\Whatsapp());
//                        $wpp->status();
//                        if(empty($wpp->callback()->connected) ||  $wpp->callback()->connected == false):
//                            ?>
<!--                            <div style="display: none">--><?php //var_dump($wpp->callback())?><!--</div>-->
<!--                            --><?php
//                            echo "<p>O Whatsapp não esta conectado, favor aponte o seu celular para o QRcode abaixo</p>";
//                            $wpp->neWqrCode();
//                            if(!empty($wpp->callback()->qr)){
//                                echo "<img style='margin: 20px auto; display: block' width='300px' height='300px' src={$wpp->callback()->qr}>";
//                            }
//                            echo "<p style='margin: 20px auto; display: block; text-align: center'><strong>Recarregue a página depois de conectar o whatsapp ao sistema</strong></p>";
//                        else:
                    ?>
                    <div id="checkboxphone"></div>
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <button type="submit" class=" btn form-control" name="submit" value="Enviar mensagem" style="margin-top:10px;">Enviar Mensagem</button>
                        </div>
                    </div>
                    <?php
//                        endif;
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
