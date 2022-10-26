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
    <?php
        $userLogado = (new \Source\Models\Users())->findById($_SESSION['id']);
        $userL = (!empty($userLogado->empresa) ? false : true);
    ?>
</head>

<body>

<header>
    <nav>
        <ul>
            <li><a href="<?= $router->route('Main.transportes', ['categoria' => 'liberado']) ?>"><i class="fas fa-arrow-left"></i></a> </li>

            <?php if($_GET['token'] !== "000"): ?>

                <div class="accept" style="display: flex; gap: 3rem">
                    <li><a class="accept-refuse" href="#" data-action="<?= $router->route('Motoristas.accept') ?>"><i class="fas fa-times"></i></a> </li>
                    <li><a class="accept-accept" href="#" data-action="<?= $router->route('Motoristas.accept') ?>"><i class="fas fa-check"></i></a> </li>
                </div>

            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>

    <div class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h3>TRANSPORTE:
                            <?= (!$userL) ? explode(";", $transporte->status)[0] : null; ?></h3>
                    </div>
                    <div class="card-body">
			<?php
			if($empresa->importante != NULL){
				?>
					 <div class="row mb-2">
				            <div class="col-md-12">
				                <b><font color="red"><h3>IMPORTANTE (cliente):</h3></font></b>
				                <p><font color="red"><?= $empresa->importante ?></font></p>
				            </div>
				        </div>
				<?php
			}
			?>
                        <div class="row mb-1">
                            <div class="col-md-10">
                                <b>Descrição da Cotação: </b>

                                <p><?= $cotacao->observacao ?></p>
                            </div>
                        </div>


                        <div class="row mb-1">
                            <div class="col-md-3">
                                <b>Data de Liberação:</b>
                                <span><?= $transporte->data_liberacao ?></span>
                            </div>

                            <div class="col-md-3">
                                <b>Empresa:</b>
                                <span><?= $transporte->empresa_name ?></span>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-md-4">
                                <b>Origem:</b>
                                <span><?= $transporte->origem ?></span>
                            </div>

                            <div class="col-md-4">
                                <b>Destino:</b>
                                <span><?= $transporte->destino ?></span>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-md-4">
                                <b>Carroceria:</b>
                                <span><?= $transporte->carroceria ?></span>
                            </div>

                            <div class="col-md-3">
                                <b>Tipo de Carga:</b>
                                <span><?= $transporte->tipo_carga ?></span>
                            </div>

                            <div class="col-md-3">
                                <b>Peso:</b>
                                <span><?= $transporte->peso ?></span>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-md-4">
                                <b>Data da Coleta:</b>
                                <span><?= $transporte->data_coleta ?></span>
                            </div>

                            <div class="col-md-4">
                                <b>Data de Finalização</b>
                                <span><?= $transporte->data_finalizado ?></span>
                            </div>
                        </div>

                       
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <b>Anexos da Cotação:</b>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12" style="display: flex">
                                <?php foreach ($anexos  as $anexo):
                                    if(pathinfo($anexo)['basename']):
                                        ?>

                                        <div style="display: flex; flex-direction: column; max-width: 150px; margin: 0" class='documento'>
                                            <img class='documentimg' width="130" height="130" src="<?= $anexo ?>" alt="<?= pathinfo($anexo)['basename'] ?>" />
                                            <span><?= pathinfo($anexo)['basename'] ?></span>
                                        </div>

                                    <?php endif; endforeach; ?>
                            </div>
                        </div>

                        <?php if($transporte->liberacao === "finalizado"): ?>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <b>Data de Finalização: <?= $transporte->data_finalizado ?></b>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <b>Anexos Finalizado</b>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12" style="display: flex; gap: 2rem">
                                    <?php foreach ($comprovantes as $comprovante):
                                        if(pathinfo($comprovante)['basename']): ?>

                                            <div style="display: flex; flex-direction: column; max-width: 150px; margin: 0" class='documento'>
                                                <span><?= pathinfo($comprovante)['basename'] ?></span>

                                                <div class="links" style="display: flex; gap: 1rem">
                                                    <a target="_blank" href="<?= $comprovante ?>">Abrir</a>
                                                    <a download="<?= pathinfo($comprovante)['basename'] ?>" href="<?= $comprovante ?>">Download</a>
                                                </div>

                                            </div>


                                        <?php endif; endforeach; ?>
                                </div>
                            </div>

                        <?php endif ?>
                    </div>
                </div>


                <?php if($_GET['token'] !== '000'): ?>

                    <div class="card">
                        <div class="card-header">
                            <h3>MOTORISTA:</h3>
                        </div>

                        <div class="card-body">
                            <div class="row mb-1">
                                <div class="col-md-3">
                                    <b>Nome:</b>
                                    <span><?= $motorista->name ?></span>
                                </div>

                                <div class="col-md-3">
                                    <b>CPF:</b>
                                    <span><?= $motorista->cpf ?></span>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-md-3">
                                    <b>Placa do Veículo:</b>
                                    <span><?= $motorista->placa_veiculo ?></span>
                                </div>

                                <div class="col-md-3">
                                    <b>Placa do Reboque:</b>
                                    <span><?= $motorista->placa_reboque ?></span>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <?php if($userL): ?>
                                <div class="col-md-3">
                                    <b>Valor Combinado:</b>
                                    <span>R$ <?= $motorista->valor ?></span>
                                </div>

                                <div class="col-md-3">
                                    <b>Forma de Pagamento:</b>
                                    <span><?php if($motorista->pagamento !== "vista"): echo strtoupper($motorista->pagamento); else: echo "A VISTA"; endif; ?></span>
                                </div>

                                <div class="col-md-6">
                                    <b><?php if($motorista->pagamento !== "vista"): echo strtoupper($motorista->pagamento).":"; else: echo "A VISTA:"; endif; ?></b>
                                    <span><?php switch ($motorista->pagamento):

                                            case "adiamento":

                                                echo str_replace("<br>", "", $motorista->porcentagem);

                                                break;

                                            case "adiantamento":

                                                echo str_replace("<br>", "", $motorista->porcentagem);

                                                break;

                                            case "vista":

                                                echo "Pagar na ".strtoupper($motorista->vista);

                                                break;

                                            case "cheque":

                                                echo $motorista->cheque;

                                                break;

                                        endswitch; ?> </span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <b>Anexos do Motorista:</b>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-12" style="display: flex">
                                    <?php foreach ($documentos as $documento):
                                        if(pathinfo($documento)['basename']): ?>

                                        <div style="display: flex; flex-direction: column; max-width: 150px; margin: 0" class='documento'>
                                            <img class='documentimg' width="130" height="130" src="<?= $documento ?>" alt="<?= pathinfo($documento)['basename'] ?>" />
                                            <span><?= pathinfo($documento)['basename'] ?></span>
                                        </div>

                                    
                                    <?php endif; endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>DADOS BANCÁRIOS:</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mb-1">
                                <div class="col-md-4">
                                    <b>Nome da Conta:</b>
                                    <span><?= $motorista->nome_banco ?></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-3">
                                    <b>PIX:</b>
                                    <span><?= $motorista->pix ?></span>
                                </div>
                                <div class="col-md-3">
                                    <b>Agência:</b>
                                    <span><?= $motorista->agencia ?></span>
                                </div>
                                <div class="col-md-3">
                                    <b>Conta:</b>
                                    <span><?= $motorista->conta ?></span>
                                </div>
                                <div class="col-md-3">
                                    <b>Banco:</b>
                                    <span><?= $motorista->banco ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <h5 style="color: red"> ! IMPORTANTE ! </h5>
                            <p> Antes de confirmar, contatar responsável para compartilhamento de localização a partir do GOOGLE MAPS.</p>
                        </div>
                    </div>

                <?php endif; ?>


            </div>

        </div>
    </div>

</main>

<script src="<?= url('theme/js/jquery-3.6.0.min.js') ?>"></script>
<script>

    $(function () {

        $(".accept-accept").click(function (e) {
            e.preventDefault();

            var data = $(this).data();

            $.post(data.action, {'id': <?= $motorista->id ?>, 'accept': true}, function (response) {

                alert(response);
                window.location.href = "<?= $router->route('Main.transportes', ['categoria' => 'liberado']) ?>";
            }, 'json')
        })

        $(".accept-refuse").click(function (e) {
            e.preventDefault();

            var data = $(this).data();

            $.post(data.action, {'id': <?= $motorista->id ?>, 'accept': false}, function (response) {

                alert(response);
                window.location.href = "<?= $router->route('Main.transportes', ['categoria' => 'liberado']) ?>";
            }, 'json')
        })

    })

</script>

</body>
</html>
