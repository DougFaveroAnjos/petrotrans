
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

<header>
    <nav>
        <ul>
            <li><a href="<?= $router->route('Main.transportes', ['categoria' => 'liberado']) ?>"><i class="fas fa-arrow-left"></i></a> </li>

            <div class="accept" style="display: flex; gap: 3rem">
                <li><a class="accept-refuse" href="<?= $router->route('Main.transportes') ?>"><i class="fas fa-times"></i></a> </li>
                <li><a class="accept-accept" href="#" data-action="<?= $router->route('Transportes.editDimensoes') ?>"><i class="fas fa-check"></i></a> </li>
            </div>
        </ul>
    </nav>
</header>

<main>

    <div class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h3>TRANSPORTE:</h3>
                    </div>
                    <div class="card-body">

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
                                    <b>Motorista:</b>
                                    <span><b>Motorista:</b> <?php if($transporte->motorista_name !== null): echo $transporte->motorista_name; else: echo "Motorista Não Registrado."; endif; ?></span>
                                </div>

                                <div class="col-md-4">
                                    <b>Veículo:</b>
                                    <span><?= $cotacao->veiculo ?></span>
                                </div>

                            </div>

                            <div class="row mb-1">

                                <div class="col-md-3">
                                    <b>Tipo de Carga:</b>
                                    <span><?= strtoupper($transporte->tipo_carga) ?></span>
                                </div>

                                <div class="col-md-3">
                                    <b>Peso:</b>
                                    <span><?= $transporte->peso ?></span>
                                </div>

                                <div class="col-md-3">
                                    <b>Volumes:</b>
                                    <span><?= $cotacao->volumes ?></span>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-md-4">
                                    <b>Data da Coleta:</b>
                                    <span><?= $transporte->data_coleta ?></span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <b>Descrição da Cotação:</b>
                                    <span><?= $cotacao->observacao ?></span>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-md-4">
                                    <b><label>Dimensões dos Volumes:</label></b>
                                    <br>

                                    <div class="input-group">
                                        <input class="form-control" type="text" name="dimensoes" id="dimensoes" value="Dimensões: <?= $transporte->dimensoes ?>">
                                    </div>

                                </div>
                            </div>

                        <div class="row mb-1">
                            <div class="col-md-4">
                                <b><label>Endereço de Coleta:</label></b>
                                <br>

                                <div class="input-group">
                                    <input class="form-control" type="text" name="coleta" id="coleta" value="Endereco de Coleta: <?= $transporte->endereco_coleta ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <b><label>Endereço de Entrega:</label></b>
                                <br>

                                <div class="input-group">
                                    <input class="form-control" type="text" name="entrega" id="entrega" value="Endereco de Entrega: <?= $transporte->endereco_entrega ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-md-4">
                                <b><label>Emails da O.C:  (Separar com "; ")</label></b>
                                <br>

                                <div class="input-group">
                                    <input class="form-control" type="text" name="emails" id="emails" value="Emails da O.C: <?= $transporte->emails ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <b><label>Telefones da O.C:  (Separar com "; ")</label></b>
                                <br>

                                <div class="input-group">
                                    <input class="form-control" type="text" name="telefones" id="telefones" value="Telefones da O.C: <?= $transporte->telefones ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
            var dimensoes = $("#dimensoes").val();
            var enderecocoleta = $("#coleta").val();
            var enderecoentrega = $("#entrega").val();
            var emails = $("#emails").val();
            var telefones = $("#telefones").val();

            $.post(data.action, {
                'id': <?= $transporte->id ?>,
                'dimensoes': dimensoes,
                'enderecocoleta': enderecocoleta,
                'enderecoentrega': enderecoentrega,
                'emails': emails,
                'telefones': telefones
            }, function (response) {

                alert(response);
                window.location.href = "<?= $router->route('Main.transportes', ['categoria' => 'liberado']) ?>";
            }, 'json')
        })

    })

</script>

</body>
</html>