<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>
</head>
<body>
<style>

    body {
        background-image: url("theme/images/logo-background.png");
        background-repeat: no-repeat;
        background-position: right bottom;
    }

    b {
        font-size: 13px;
    }

    span {
        font-size: 12px;
    }

    .inline {
        display: inline-block;
    }

    .logo-petrotrans {
        width: 100px;
    }

    hr {
        margin: 0;
        padding: 0;
    }

    .items {
        margin-top: 0;
    }


</style>


<table style="margin-bottom: 32px">
    <tbody>
    <tr>
        <td><img class="logo-petrotrans" src="theme/images/logo.png" alt="logo"></td>
        <td style="padding: 10px">
                <b>PETROTRANS TRANSPORTES E LOGISTICA EIRELI</b>
            <br>
                <span>
                     CNPJ: 37.298.359/0001-05 <br>
                     AV. INTEGRAÇÃO, 1028 <br>
                     BAIRRO ALTO <br>
                     CURITIBA / PR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CEP: 82820-070 <br>
                     TELEFONE: (41) 3367-9436 <br>
                </span>
        </td>
        <td>
            <b style="font-weight: bolder; font-size: 24px">Ordem de Coleta</b>
            <p style="font-weight: bolder; font-size: 16px; margin: 0; padding: 0">
                Nº <?= $cotacao->id ?>
            </p>
            <p style="margin: 0; padding: 0"> Emissão: <?= date('d/m/Y'); ?>
            </p>
            <p style="margin: 0; padding: 0"> Previsão de Entrega: <?= $cotacao->prazo_previsao ?>
            </p>
            <p style="margin: 0; padding: 0">
                Referencia:
            </p>
        </td>
    </tr>
    </tbody>
</table>

<b style="font-size: 16px">Solicitante</b>
<table style="border: 1px solid black; width: 100%; margin-bottom: 8px">
    <tbody>
    <tr style="width: 100%">
        <td style="width: 100%">
            <b>CNPJ / NOME</b> <br>
            <?= $empresa->cnpj ?> - <?= $empresa->name ?>
        </td>
    </tr>
    </tbody>
</table>

<b style="font-size: 16px">Local da coleta</b>
<table style="border: 1px solid black; width: 100%; margin-bottom: 8px">
    <tbody>
    <tr style="width: 100%">
        <td style="width: 100%">
            <?= mb_strtoupper($coleta->endereco_coleta) ?> <br>
        </td>
    </tr>
    </tbody>
</table>

<b style="font-size: 16px">Local da entrega</b>
<table style="border: 1px solid black; width: 100%; margin-bottom: 8px">
    <tbody>
    <tr style="width: 100%">
        <td style="width: 100%">
            <?= mb_strtoupper($coleta->endereco_entrega) ?> <br>
        </td>
    </tr>
    </tbody>
</table>

<b style="font-size: 16px">Dados para contato</b>
<table style="border: 1px solid black; width: 100%; margin-bottom: 8px">
    <tbody>
    <tr>
        <td>
            <b>NOME</b> <br>
            <?= mb_strtoupper($motorista->name) ?>
        </td>
    </tr>
    </tbody>
</table>

<b style="font-size: 16px">Composição da Mercadoria</b>
<table style="margin-bottom: 16px; border: 1px solid black; width: 100%; text-align: center">
    <thead style="border-bottom: 1px solid black; background-color: #cbcbcb">
    <tr>
        <th style="border-right: 1px solid black">
            Descrição / Espécie
        </th>
        <th style="border-right: 1px solid black">
            Volumes
        </th>
        <th>
            Peso
        </th>
    </tr>
    </thead>

    <tbody>
    <tr style="border-bottom: 1px solid black;">
        <td style="border-right: 1px solid black; height: 80px; border-bottom: 1px solid black"><?= $motorista->obs ?></td>
        <td style="border-right: 1px solid black; border-bottom: 1px solid black"><?php
            if(array_key_exists(1, explode("--", $cotacao->volumes))):
                echo explode("--", $cotacao->volumes)[1];
            endif;
            ?></td>
        <td style="border-bottom: 1px solid black"></td>
    </tr>
    <tr>
        <td style="border-right: 1px solid black"><b>Totais Declarados</b></td>
        <td style="border-right: 1px solid black">
            <b>Volumes</b>
            <br>
            <?= explode("--", $cotacao->volumes)[0] ?>
        </td>
        <td>
            <b> Peso </b>
            <br>
            <?= $cotacao->peso ?>
        </td>
    </tr>
    </tbody>
</table>

<!--<b style="font-size: 16px">Demais Dados</b>
<table style="border: 1px solid black; width: 100%; margin-bottom: 16px">
    <tbody>
    <tr>
        <td><b>Descrição: <br> </b><?= $cotacao->observacao ?></td>
    </tr>
    </tbody>
</table>-->

<b style="font-size: 16px">Dados do motorista</b>
<table style="margin-bottom: 16px; width: 100%; text-align: center; border: 1px solid black">
    <tbody>
    <tr>
        <td style="border-right: 1px solid black">
            <b>Nome</b>
            <br>
            <?= mb_strtoupper($motorista->name) ?>
        </td>
        <td>
            <b>CPF</b>
            <br>
            <?= $motorista->cpf ?>
        </td>
    </tr>

    </tbody>
</table>

<b style="font-size: 16px"> Dados do Veículo </b>
<table style="margin-bottom: 4px; border: 1px solid black; width: 100%; text-align: center">
    <tbody>
    <tr>
        <td style="border-right: 1px solid black">
            <b>Placa do Veículo</b>
            <br>
            <?= $motorista->placa_veiculo ?>
        </td>
        <td style="border-right: 1px solid black">
            <b>Placa Reboque</b>
            <br>
            <?= $motorista->placa_reboque ?>
        </td>
        <td>
            <b>Carroceria</b>
            <br>
            <?= $motorista->carroceria ?>
        </td>
    </tr>
    </tbody>
</table>

<b style="font-size: 16px"> Observações </b>
<table style="border: 1px solid black; margin-bottom: 50px; width: 100%">
    <tbody>
    <tr>
        <td style="height: 70px"><?= $coleta->obs ?></td>
    </tr>
    </tbody>
</table>

<img style="width: 140px" src="theme/images/seguro/2022/07/logoporto.png" alt="Porto Seguro"> <br>
<b>Transporte assegurado pela Porto Seguros – Uma das 30 maiores seguradoras do mundo. </b>

</body>
</html>
