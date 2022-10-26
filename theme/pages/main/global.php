<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- css -->
    <link rel="stylesheet" href="<?= url('theme/scss/main/styles.css') ?>">
    <link rel="stylesheet" href="<?= url('theme/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">

    <title>Petrotrans | <?= $title ?></title>

</head>

<body>

<style>
    .edit-global.active {
        display: none;
    }

    .accept-global {
        display: none;
    }

    .accept-global.active {
        display: block;
    }
</style>

<header style="margin-bottom: 0">
    <div class="row" style="background-color: #383838;">

        <div class="col-md-1" style="display: flex; align-items: center; justify-content: end">
            <a style="text-align: center; color: #00f300; font-size: 2rem" href="<?= $router->route('Main.transportes', ['categoria' => 'liberado']) ?>"><i class="fas fa-arrow-left"></i></a>
        </div>

        <div class="col-md-10">
            <h1 style="text-align: center; color: #00f300; padding: 2rem">Controle Transportes - PETROTRANS</h1>
        </div>

    </div>

    <form method="get" style="background-color: #383838; color: white">

        <div class="row justify-content-center" style="padding: 1rem">
            <div class="col-md-2">
                <label>(Data de Coleta) Entre</label>
                <input class="form-control" type="date" name="coleta_min">
            </div>

            <div class="col-md-2">
                <label>(Data de Coleta) E</label>
                <input class="form-control" type="date" name="coleta_max">
            </div>

            <div class="col-md-2">
                <label>CTe</label>
                <input class="form-control" type="number" name="cte">
            </div>

            <div class="col-md-2">
                <label>Empresa</label>
                <input class="form-control" type="text" name="empresa">
            </div>

            <div class="col-md-2">
                <label>Motorista</label>
                <input class="form-control" type="text" name="motorista">
            </div>

        </div>

        <div class="row justify-content-center" style="padding: 1rem">
            <div class="col-md-2">
                <label>Origem</label>
                <input class="form-control" type="text" name="origem">
            </div>

            <div class="col-md-2">
                <label>Destino</label>
                <input class="form-control" type="text" name="destino">
            </div>

            <div class="col-md-2" style="display: flex; align-items: end">
                <input class="form-control" type="submit" name="submit" value="Filtrar">
            </div>
        </div>

    </form>
</header>

<main>

    <table class="table table-responsive table-bordered" style="max-height: 80vh; overflow-x: auto; white-space: nowrap">

        <thead style="background-color: #383838; color: #ffb700;">
        <tr style="font-size: .8rem">
            <th scope="col">#</th>
            <th scope="col">COLETA</th>
            <th scope="col">ENTREGA</th>
            <th style="text-align: center" scope="col">STATUS</th>
            <th scope="col">EMPRESA</th>
            <th scope="col">SPC</th>
            <th scope="col">VALOR DO FRETE</th>
            <th scope="col">ORIGEM</th>
            <th scope="col">DESTINO</th>
            <th scope="col">ESPECIFICAÇÕES</th>
            <th scope="col">VALOR MOTORISTA</th>
            <th scope="col">PRAZO</th>
            <th scope="col">FORMA DE PGTO</th>
            <th scope="col">NOME MOTORISTA</th>
            <th scope="col">FORMA PGTO EMPRESA</th>
        </tr>
        </thead>

        <tbody>
        <?php if($transportes): foreach ($transportes as $transporte): ?>

            <form class="global-form <?= $transporte->id ?>" method="post" data-action="<?= $router->route('Transportes.editGlobal', ['id' => $transporte->id]) ?>">

                <input type="hidden" name="id" value="<?= $transporte->id ?>">

            <tr>

                <td>
                    <a class="edit-global <?= $transporte->id ?>" href="#"><i class="fas fa-pencil-alt"></i></a>
                    <input name="submit" type="submit" class="accept-global <?= $transporte->id ?>" value="EDITAR">
                </td>
                <td style="font-weight: bold; max-width: 15rem; overflow-x: hidden"><?php if($transporte->data_liberacao === "Não Especificado"): ?>
                        <input disabled style="border: none; font-weight: bold; color: black; max-width: 12rem" class="coleta <?= $transporte->id ?>" type="text" name="coleta" placeholder="<?= $transporte->data_liberacao ?>" value="Não Especificado" onfocus="(this.type='datetime-local')">
                        <?php else: ?>
                        <input disabled style="border: none; font-weight: bold; color: black; max-width: 12rem" class="coleta <?= $transporte->id ?>" type="hidden" name="coleta" placeholder="<?= $transporte->data_liberacao ?>" value="<?= $transporte->data_liberacao ?>">
                        <a target="_blank" href="<?= $router->route('Motoristas.visualizar', ['token' => $transporte->token, 'id' => $transporte->id]) ?>"><?= $transporte->data_liberacao ?></a>
                <?php endif ?>
                </td>
                <td><input disabled style="border: none; font-weight: bold; color: black; max-width: 12rem" class="liberacao <?= $transporte->id ?>" type="text" name="liberacao" placeholder="<?= $transporte->data_entrega ?>" value="<?= $transporte->data_entrega ?>" onfocus="(this.type='date')"></td>
                <td style="min-width: 20rem; color: white; text-align: center; background-color: <?= explode("; ", $transporte->status)[1] ?>">  <?= explode("; ", $transporte->status)[0] ?>  </td>
                <td style="max-width: 15rem; overflow-x: hidden">  <?= $transporte->empresa_name ?>  </td>
                <td>  <?php
                    if((new \Source\Models\EmpresasModel())->find("name = :name", "name={$transporte->empresa_name}")->fetch()):
                    echo (new \Source\Models\EmpresasModel())->find("name = :name", "name={$transporte->empresa_name}")->fetch()->spc;
                    endif;
                    ?>
                </td>
                <td> R$<input disabled
                            name="valor"
                            style="border: none; color: black; max-width: 12rem"
                            class="valor <?= $transporte->id ?>"
                            value="<?= (new \Source\Models\CotacoesModel())->find("id = :id", "id={$transporte->cotacao_id}")->fetch()->valor_cotacao ?>"
                            type="text"
                            onfocus="(this.type='number'); (this.value='<?= (new \Source\Models\CotacoesModel())->find("id = :id", "id={$transporte->cotacao_id}")->fetch()->valor_cotacao ?>')"
                    >
                </td>
                <td><input disabled style="border: none" class="origem <?= $transporte->id ?>" type="text" name="origem" value="<?= $transporte->origem ?>"></td>
                <td><input disabled style="border: none" class="destino <?= $transporte->id ?>" type="text" name="destino" value="<?= $transporte->destino ?>"></td>
                <td>
                    <?= $transporte->peso ?> -
                    <?= (new \Source\Models\CotacoesModel())->find("id = :id", "id={$transporte->cotacao_id}")->fetch()->volumes ?>
                </td>
                <td>  <?php
                    if($transporte->motorista_name):
                        echo 'R$'.(new \Source\Models\MotoristasModel())->find("name = :name", "name={$transporte->motorista_name}")->fetch()->valor;
                    else: echo $transporte->valor_motorista; endif;
                    ?>  </td>

                <td>  <?php
                    if($transporte->motorista_name):
                       if((new \Source\Models\MotoristasModel())->find("name = :name", "name={$transporte->motorista_name}")->fetch()->porcentagem):
                        echo (new \Source\Models\MotoristasModel())->find("name = :name", "name={$transporte->motorista_name}")->fetch()->porcentagem;
                       elseif((new \Source\Models\MotoristasModel())->find("name = :name", "name={$transporte->motorista_name}")->fetch()->vista):
                       echo (new \Source\Models\MotoristasModel())->find("name = :name", "name={$transporte->motorista_name}")->fetch()->vista;
                       elseif((new \Source\Models\MotoristasModel())->find("name = :name", "name={$transporte->motorista_name}")->fetch()->cheque):
                    echo (new \Source\Models\MotoristasModel())->find("name = :name", "name={$transporte->motorista_name}")->fetch()->cheque;
                      endif; endif;
                    ?>  </td>
                <td>  <?php if($transporte->motorista_name): echo (new \Source\Models\MotoristasModel())->find("name = :name", "name={$transporte->motorista_name}")->fetch()->pagamento; endif; ?>  </td>
                <td><a href="<?= $router->route('Motoristas.visualizar', ['token' => $transporte->token, 'id' => $transporte->id]) ?>"><?= $transporte->motorista_name ?></a> </td>
                <td>  <?php
                    if((new \Source\Models\EmpresasModel())->find("name = :name", "name={$transporte->empresa_name}")->fetch()):
                    echo (new \Source\Models\EmpresasModel())->find("name = :name", "name={$transporte->empresa_name}")->fetch()->pagamento;
                    endif;
                    ?>  </td>

            </tr>

            </form>

        <?php endforeach; endif; ?>
        </tbody>

    </table>
</main>

<script src="<?= url('theme/js/jquery-3.6.0.min.js') ?>"></script>
<script>

    $(function () {

        $(".edit-global").click(function (e) {
            e.preventDefault();

            var id = this.classList[1];

            $(this).addClass("active");
            $(`.accept-global.${id}`).addClass("active");

            $(`input.${id}`).prop("disabled", false);
        })

        $(".global-form").submit(function (e) {
            e.preventDefault();

            var id = this.classList[1];
            var data = $(this).data();

            $(`.accept-global.${id}`).removeClass("active");
            $(`.edit-global.${id}`).removeClass("active");

            $.post(data.action, $(this).serialize(), function (result) {
                alert(result);
                $(`input.${id}`).prop("disabled", true);
                window.location.reload();
            }, "json");



        })

    })
    
</script>
</body>
