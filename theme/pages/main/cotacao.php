<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- css -->
    <link rel="stylesheet" href="<?= url('theme/scss/main/styles.css') ?>">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">

    <title>Petrotrans | <?= $title ?></title>

    <style>
        .btn-d{
            text-decoration: none;
        }
        .btn-d button{
            border: none;
            padding: 15px !important;
            cursor: pointer;
            width: 200px !important;
            height: auto !important;
            border-radius: 20px;
            font-size: 15px;
            color: #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transition: 1s;
            transition-delay: 0s;
        }
        .btn-1 button{
            background: linear-gradient(45deg, #a955ff, #ea51ff);
        }
        .btn-1 button:hover{
            background: linear-gradient(45deg, #ea51ff, #a955ff);
            transition: 1s;
        }
        .btn-2 button{
            background: linear-gradient(45deg, #56ccf2, #2f80ed);
            margin-top: 20px;
            display: block;
        }
    </style>
</head>

<body>

<header>
<!--    <nav>
        <ul>
            <li><a href="<?= $router->route('Main.table') ?>"><i class="fas fa-arrow-left"></i></a> </li>
            <li><a href="<?= $router->route('Cotacoes.edit', ['id' => $cotacao->id]) ?>"><i class="fas fa-pencil-ruler"></i></a></li>
            <li><a href="#" class="items-close <?= $cotacao->id ?> mail" data-action="<?= $router->route('Cotacoes.mail', ['id' => $cotacao->id, 'name' => $empresa->name]) ?>"><i class="fas fa-envelope-open-text"></i></a></li>
           
        </ul>
    </nav>
-->
</header>

<center>

 <?php if(isset($contatos)): foreach ($contatos as $contato): ?>
            <a href="#" class="items-close btn-d btn-1 <?= $cotacao->id ?> mail" data-action="<?= $router->route('Cotacoes.mail', ['id' => $cotacao->id, 'email' => $contato->email, 'name' => $empresa->name, 'origem' => $empresa->cidade_origem, 'destino' => $empresa->cidade_destino]) ?>"><button type="submit" name="submit" style="width: 80px; height:50px"><?=$contato->responsavel ?></button></a>
            <?php endforeach; ?>
     <a href="#" class="items-close btn-d btn-2 <?= $cotacao->id ?> all" data-action="<?= $router->route('Cotacoes.mail', ['id' => $cotacao->id]) ?>"><button type="submit" name="submit" style="width: 80px; height:50px">Todos</button></a>
    <?php
    endif;?>
<br><br>
</center>

<table class="tg" style=" font-family: Calibri, sans-serif; margin: auto; border-collapse: collapse; border-spacing: 0">
    <thead>
    <tr>
        <th class="tg-e5wj" style="font-size: 16px ;text-align: left ;overflow:hidden;padding:4px 10px;word-break:normal;border-bottom: 6px solid #3590ff;"><span style="font-weight:bold; color: #3590ff">EMPRESA</span><br><span style="font-weight:bold; color: #4169ff"><?= (new \Source\Models\EmpresasModel())->find("id = :id", "id={$cotacao->cliente_id}")->fetch()->name ?> <br> <?php if($cotacao->cliente_fob !== null): echo "<p style='font-size: .7rem; margin: 0'>FOB – ".$cotacao->cliente_fob."</p>"; endif;  ?> </span></th>
        <th class="tg-05am" style=" font-size:16px;font-weight:bold;text-align:left;vertical-align:top;overflow:hidden;padding:4px 10px;word-break:normal;color: #4169ff; border-bottom: 6px solid #3590ff;" colspan="2"><span style="color: #3590ff">DATA DA<br>COTACAO</span> <?= (new DateTime($cotacao->date))->format('d/m/Y') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nº <?= $cotacao->id ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;" class="tg-fa7u" width="175">ENDEREÇO: <?php if($empresa->rua !== null): echo $empresa->rua; else: echo "NÃO ESPECIFICADO"; endif ?>, <br>  <?php if($empresa->complemento !== null): echo "$empresa->complemento —  <br>"; endif; ?> <?= $empresa->cidade ?> —  <?= $empresa->estado ?>.</td>
        <td class="tg-fa7u" width="120px" style="font-size:14px;text-align:left;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal; border-left: 1px solid #3590ff; background-color: #bcdfeb;"><span style="font-weight:bold; color: #3590ff">Origem</span><br><span style="color: #4169ff; font-weight:normal"><?= $cotacao->cidade_origem ?>/<?= $cotacao->uf_origem ?> <?= $cotacao->origem_complemento ?></span><br></td>
        <td class="tg-fa7u" width="120px" style="font-size:14px;text-align:left;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;border-left: 1px solid #3590ff; border-right: 1px solid #3590ff; background-color: #bcdfeb;"><span style="font-weight:bold; color: #3590ff">Destino</span><br><span style="color: #4169ff; font-weight:normal"><?= $cotacao->cidade_destino ?>/<?= $cotacao->uf_destino ?> <?= $cotacao->destino_complemento ?></span><br></td>
    </tr>
    <tr>
        <td class="tg-fa7u" style="font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;text-align:left;vertical-align:top">CONTATO:
            <?php if(isset($contatos)): foreach ($contatos as $contato): ?>
                <?= $contato->responsavel.", "?>
            <?php endforeach; endif;?>
        </td>
        <td class="tg-fa7u" colspan="2" style="font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;text-align:left;vertical-align:top; border-left: 1px solid #3590ff; border-right: 1px solid #3590ff;" width="240"><span style="font-weight:bold; color: #4169ff">VEÍCULO:</span><br><span style="font-weight:normal; color: #4169ff"><?= $cotacao->veiculo ?></span><br></td>
    </tr>
    <tr>
        <td style="font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;" class="tg-fa7u" rowspan="2"><span style="font-weight:bold; color: #3590ff">VALOR DE NF</span><br><span style="font-weight:normal; color: #4169ff"><?= "R$".number_format($cotacao->valor_nota, 2, ',', '.') ?></span><br><span style="font-weight:bold; color: #3590ff">FORMA E PRAZO DE</span><br><span style="font-weight:bold; color: #3590ff">PAGAMENTO: </span><span style="font-weight:normal; color: #4169ff"><?= $cotacao->pagamento ?></span></td>
        <td class="tg-fa7u" colspan="2" style="font-size:14px;text-align:left;vertical-align:top; font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;background-color: #bcdfeb; border-left: 1px solid #3590ff; border-right: 1px solid #3590ff;"><span style="font-weight:bold; color: #3590ff">VOLUMES</span><br><span style="font-weight:normal"><?= $cotacao->volumes ?></span></td>
    </tr>
    <tr>
        <td class="tg-fa7u" colspan="2" style="font-size:14px;text-align:left;vertical-align:top; font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;border-left: 1px solid #3590ff; border-right: 1px solid #3590ff;"><span style="font-weight:bold; color: #3590ff">PESO TOTAL</span><br><span style="font-weight:normal; color: #4169ff"><?= $cotacao->peso ?></span></td>
    </tr>
    <tr>
        <td class="tg-fa7u" style="font-size:14px;text-align:left;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;color: red" rowspan="2"><br><span style="font-weight:bold; color: #3590ff">FRETE:</span><br><span style="font-weight:normal; color: red">CIF( <?php if($cotacao->opcao_frete === "cif"): echo "X"; endif; ?> )&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FOB( <?php if($cotacao->opcao_frete === "fob"): echo "X"; endif; ?> )</span><br><span style="font-weight:normal; color: red">INCLUSO: ICMS,<br> PEDÁGIOS </span><span style=" font-weight:normal">E </span>SEGURO (<?= $petrotrans->seguro ?>)</td>
        <td class="tg-fa7u" colspan="2" style="font-size:14px;text-align:left;vertical-align:top; font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;background-color: #bcdfeb; border-left: 1px solid #3590ff; border-right: 1px solid #3590ff;"><span style="font-weight:bold; color: #3590ff;">TIPO DE CARGA</span><br><span style="font-weight:normal; color: #4169ff">( <?php if($cotacao->tipo_carga === "dedicada"): echo "X"; endif; ?> )Fechada&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <?php if($cotacao->tipo_carga === "fracionada"): echo "X"; endif; ?> )Fracionada</span></td>
    </tr>
    <tr>
        <td class="tg-fa7u" width="240" colspan="2" style="font-size:14px;text-align:left;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;border-left: 1px solid #3590ff; color: #4169ff; border-right: 1px solid #3590ff;"><span style="font-weight:bold; color: #3590ff">DATA DA COLETA: </span><span style="font-weight:normal; color: #4169ff"><?php if($cotacao->data_coleta === "0000-00-00" || !$cotacao->data_coleta): echo "~~"; else: echo $cotacao->data_coleta; endif ?></span><br>PREVISÃO DE ENTREGA: <?= $cotacao->prazo_previsao ?>.<br>PRAZO MÁXIMO: <?= $cotacao->prazo_maximo ?>.</td>
    </tr>
    <tr>
        <td class="tg-zv4m" style="text-align:left;vertical-align:top; font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;border-bottom: 1px solid black"><img width="80" style="float: right" src="<?= $petrotrans->img ?>" alt="<?= $petrotrans->seguro ?>"></td>
        <td class="tg-dqrc" colspan="2" style="font-size:16px;text-align:right;vertical-align:top;font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;border: 1px solid black"><span style="font-weight:bold; color: red">VALOR DO FRETE</span><br><span style="font-weight:bold; color: #4169ff"><?= "R$".number_format($cotacao->valor_cotacao, 2, ',', '.') ?></span></td>
    </tr>
    <tr>
        <td style="font-size:14px;text-align:center;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;" class="tg-b420" colspan="3"><span style="font-weight:bold; color: #3590ff">OPCIONAIS</span></td>
    </tr>
    <tr>
        <td class="tg-aqz1" colspan="3" style="font-size:10px;text-align:center;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;color: #4169ff">Caro cliente, favor posicionar o vendedor se há necessidade de opcionais no carregamento.</td>
    </tr>
    <tr>
        <td class="tg-fa7u" colspan="3" style="font-size:14px;text-align:center;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;color: #4169ff"><span style="font-weight:bold">LONA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CINTAS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AÇOALHO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AJUDANTES</span></td>
    </tr>
    <tr>
        <td style="font-size:14px;text-align:center;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;" class="tg-fa7u" colspan="3">
<img style="margin-right: 4rem" class="opcionais" height="37" src="<?= url("theme/images/lonas.jpg")?>" alt="Ative Imagens no Outlook">
            &nbsp;&nbsp;<img  style="margin-right: 4rem" class="opcionais" height="37" width="37" src="<?= url("theme/images/cordas.jpg")?>" alt="Ative Imagens no Outlook">
            &nbsp;&nbsp;<img  style="margin-right: 4rem" class="opcionais" height="37" width="37" src="<?= url("theme/images/acoalho.jpg")?>" alt="Ative Imagens no Outlook">
            &nbsp;&nbsp;<img  class="opcionais" height="37" width="37" src="<?= url("theme/images/ajudantes.jpg")?>" alt="Ative Imagens no Outlook">
        </td>
    </tr>
    <tr>
        <td class="tg-fa7u" colspan="3" style="font-size:14px;text-align:center;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;color: #3590ff"> <h4>
( <?php if($cotacao->lona === "1"): echo "X"; endif ?> )
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <?php if($cotacao->cintas === "1"): echo "X"; endif ?> )
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <?php if($cotacao->acoalho === "ferro"): echo "X"; endif ?> ) Ferro
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(!$cotacao->ajudantes): echo "Nenhum"; else: echo $cotacao->ajudantes; endif; ?> <br> <br>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
( <?php if($cotacao->acoalho === "madeira"): echo "X"; endif ?> ) Madeira

            </h4>
        </td>
    </tr>
    </tbody>
</table>


<textarea class="mail-message" name="email" id="email" cols="30" rows="10" style="display: none">

    <table class="tg" style=" font-family: Calibri, sans-serif; margin: auto; border-collapse: collapse; border-spacing: 0">
<thead>
    <tr>
        <th class="tg-e5wj" style="font-size: 16px ;text-align: left ;overflow:hidden;padding:4px 10px;word-break:normal;border-bottom: 6px solid #3590ff;"><span style="font-weight:bold; color: #3590ff">EMPRESA</span><br><span style="font-weight:bold; color: #4169ff"><?= (new \Source\Models\EmpresasModel())->find("id = :id", "id={$cotacao->cliente_id}")->fetch()->name ?> <br> <?php if($cotacao->cliente_fob !== null): echo "<p style='font-size: .7rem; margin: 0'>FOB – ".$cotacao->cliente_fob."</p>"; endif;  ?> </span></th>
        <th class="tg-05am" style=" font-size:16px;font-weight:bold;text-align:left;vertical-align:top;overflow:hidden;padding:4px 10px;word-break:normal;color: #4169ff; border-bottom: 6px solid #3590ff;" colspan="2"><span style="color: #3590ff">DATA DA<br>COTACAO</span> <?= (new DateTime($cotacao->date))->format('d/m/Y') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nº <?= $cotacao->id ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;" class="tg-fa7u" width="175">ENDEREÇO: <?php if($empresa->rua !== null): echo "<?= $empresa->rua ?>, <br> <?= $empresa->complemento ?> —  <br>"; endif; ?> <?= $empresa->cidade ?> —  <?= $empresa->estado ?>.</td>
        <td class="tg-fa7u" width="120px" style="font-size:14px;text-align:left;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal; border-left: 1px solid #3590ff; background-color: #bcdfeb;"><span style="font-weight:bold; color: #3590ff">Origem</span><br><span style="color: #4169ff; font-weight:normal"><?= $cotacao->cidade_origem ?>/<?= $cotacao->uf_origem ?> <?= $cotacao->origem_complemento ?></span><br></td>
        <td class="tg-fa7u" width="120px" style="font-size:14px;text-align:left;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;border-left: 1px solid #3590ff; border-right: 1px solid #3590ff; background-color: #bcdfeb;"><span style="font-weight:bold; color: #3590ff">Destino</span><br><span style="color: #4169ff; font-weight:normal"><?= $cotacao->cidade_destino ?>/<?= $cotacao->uf_destino ?> <?= $cotacao->destino_complemento ?></span><br></td>
    </tr>
    <tr>
        <td class="tg-fa7u" style="font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;text-align:left;vertical-align:top">CONTATO: <?php if(!$empresa->responsavel): echo "NÃO ESPECIFICADO"; else: echo $empresa->responsavel; endif; ?></td>
        <td class="tg-fa7u" colspan="2" style="font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;text-align:left;vertical-align:top; border-left: 1px solid #3590ff; border-right: 1px solid #3590ff;" width="240"><span style="font-weight:bold; color: #4169ff">VEÍCULO:</span><br><span style="font-weight:normal; color: #4169ff"><?= $cotacao->veiculo ?></span><br></td>
    </tr>
    <tr>
        <td style="font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;" class="tg-fa7u" rowspan="2"><span style="font-weight:bold; color: #3590ff">VALOR DE NF</span><br><span style="font-weight:normal; color: #4169ff"><?= "R$".number_format($cotacao->valor_nota, 2, ',', '.') ?></span><br><span style="font-weight:bold; color: #3590ff">FORMA E PRAZO DE</span><br><span style="font-weight:bold; color: #3590ff">PAGAMENTO: </span><span style="font-weight:normal; color: #4169ff"><?= $cotacao->pagamento ?></span></td>
        <td class="tg-fa7u" colspan="2" style="font-size:14px;text-align:left;vertical-align:top; font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;background-color: #bcdfeb; border-left: 1px solid #3590ff; border-right: 1px solid #3590ff;"><span style="font-weight:bold; color: #3590ff">VOLUMES</span><br><span style="font-weight:normal"><?= $cotacao->volumes ?></span></td>
    </tr>
    <tr>
        <td class="tg-fa7u" colspan="2" style="font-size:14px;text-align:left;vertical-align:top; font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;border-left: 1px solid #3590ff; border-right: 1px solid #3590ff;"><span style="font-weight:bold; color: #3590ff">PESO TOTAL</span><br><span style="font-weight:normal; color: #4169ff"><?= $cotacao->peso ?></span></td>
    </tr>
    <tr>
        <td class="tg-fa7u" style="font-size:14px;text-align:left;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;color: red" rowspan="2"><br><span style="font-weight:bold; color: #3590ff">FRETE:</span><br><span style="font-weight:normal; color: red">CIF( <?php if($cotacao->opcao_frete === "cif"): echo "X"; endif; ?> )&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FOB( <?php if($cotacao->opcao_frete === "fob"): echo "X"; endif; ?> )</span><br><span style="font-weight:normal; color: red">INCLUSO: ICMS,<br> PEDÁGIOS </span><span style=" font-weight:normal">E </span>SEGURO (<?= $petrotrans->seguro ?>)</td>
        <td class="tg-fa7u" colspan="2" style="font-size:14px;text-align:left;vertical-align:top; font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;background-color: #bcdfeb; border-left: 1px solid #3590ff; border-right: 1px solid #3590ff;"><span style="font-weight:bold; color: #3590ff;">TIPO DE CARGA</span><br><span style="font-weight:normal; color: #4169ff">( <?php if($cotacao->tipo_carga === "dedicada"): echo "X"; endif; ?> )Fechada&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <?php if($cotacao->tipo_carga === "fracionada"): echo "X"; endif; ?> )Fracionada</span></td>
    </tr>
    <tr>
        <td class="tg-fa7u" width="240" colspan="2" style="font-size:14px;text-align:left;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;border-left: 1px solid #3590ff; color: #4169ff; border-right: 1px solid #3590ff;"><span style="font-weight:bold; color: #3590ff">DATA DA COLETA: </span><span style="font-weight:normal; color: #4169ff"><?php if($cotacao->data_coleta === "0000-00-00" || !$cotacao->data_coleta): echo "~~"; else: echo $cotacao->data_coleta; endif ?></span><br>PREVISÃO DE ENTREGA: <?= $cotacao->prazo_previsao ?>.<br>PRAZO MÁXIMO: <?= $cotacao->prazo_maximo ?>.</td>
    </tr>
    <tr>
        <td class="tg-zv4m" style="text-align:left;vertical-align:top; font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;border-bottom: 1px solid black"><img width="40" style="float: right" src="<?= $petrotrans->img ?>" alt="<?= $petrotrans->seguro ?>"></td>
        <td class="tg-dqrc" colspan="2" style="font-size:16px;text-align:right;vertical-align:top;font-size:14px; overflow:hidden; padding:4px 10px; word-break:normal;border: 1px solid black"><span style="font-weight:bold; color: red">VALOR DO FRETE</span><br><span style="font-weight:bold; color: #4169ff"><?= "R$".number_format($cotacao->valor_cotacao, 2, ',', '.') ?></span></td>
    </tr>
    <tr>
        <td style="font-size:14px;text-align:center;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;" class="tg-b420" colspan="3"><span style="font-weight:bold; color: #3590ff">OPCIONAIS</span></td>
    </tr>
    <tr>
        <td class="tg-aqz1" colspan="3" style="font-size:10px;text-align:center;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;color: #4169ff">Caro cliente, favor posicionar o vendedor se há necessidade de opcionais no carregamento.</td>
    </tr>
    <tr>
        <td class="tg-fa7u" colspan="3" style="font-size:14px;text-align:center;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;color: #4169ff"><span style="font-weight:bold">LONA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CINTAS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AÇOALHO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AJUDANTES</span></td>
    </tr>
    <tr>
        <td style="font-size:14px;text-align:center;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;" class="tg-fa7u" colspan="3">
<img  class="opcionais" height="37" src="https://www.petrotrans.com.br/sistema/theme/images/lonas.jpg" alt="Ative Imagens no Outlook">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  class="opcionais" height="37" width="37" src="https://www.petrotrans.com.br/sistema/theme/images/cordas.jpg" alt="Ative Imagens no Outlook">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  class="opcionais" height="37" width="37" src="https://www.petrotrans.com.br/sistema/theme/images/acoalho.jpg" alt="Ative Imagens no Outlook">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  class="opcionais" height="37" width="37" src="https://www.petrotrans.com.br/sistema/theme/images/ajudantes.jpg" alt="Ative Imagens no Outlook">
        </td>
    </tr>
    <tr>
        <td class="tg-fa7u" colspan="3" style="font-size:14px;text-align:center;vertical-align:top; overflow:hidden; padding:4px 10px; word-break:normal;color: #3590ff"> <h4>
( <?php if($cotacao->lona === "1"): echo "X"; endif ?> )
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <?php if($cotacao->cintas === "1"): echo "X"; endif ?> )
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <?php if($cotacao->acoalho === "ferro"): echo "X"; endif ?> ) Ferro
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(!$cotacao->ajudantes): echo "Nenhum"; else: echo $cotacao->ajudantes; endif; ?> <br> <br>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
( <?php if($cotacao->acoalho === "madeira"): echo "X"; endif ?> ) Madeira

            </h4>
        </td>
    </tr>
    </tbody>
</table>

</textarea>

<script src="<?= url('theme/js/jquery-3.6.0.min.js') ?>"></script>
<script>
    $(function () {

        $(".mail").click(function (e) {
            e.preventDefault();

            let data = $(this).data();
            let email = '<?= $empresa->email ?>';
            let cliente_fob = '<?= $cotacao->cliente_fob ?>';
            let name = '<?= $empresa->responsavel ?>';
            let id = '<?= $cotacao->id ?>';
            let price = '<?= "R$".number_format($cotacao->valor_cotacao, 2, ',', '.') ?>';
            let destino = '<?= $cotacao->cidade_origem ?>/<?= $cotacao->uf_origem ?> X <?= $cotacao->cidade_destino ?>/<?= $cotacao->uf_destino ?>'

            $.post(data.action, {'all': false, 'msg': $(".mail-message").val(), 'email': email, 'name': name, 'id': id, 'price': price, 'destino': destino, 'cliente_fob': cliente_fob }, function (result) {
                alert(result);
            }, "json");
        })


        $(".all").click(function (e) {
            e.preventDefault();

            let data = $(this).data();
            let id = '<?= $cotacao->id ?>';

            $.post(data.action, {'all': true, 'id': id}, function (result) {
                alert(result);
            }, "json");
        })
    })
</script>
</body>
</html>
