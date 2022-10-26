<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Configurações</h5>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" action="<?= $router->route('Motoristas.edit', ['id' => $motorista->id]) ?>" method="post" data-action="<?= $router->route('Motoristas.new') ?>">

                    <input type="hidden" name="id" value="<?= $motorista->id ?>">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>BUONNY</label>
                                <select class="form-control" name="buonny" id="buonny">
                                    <option value="nao consultado">NÃO CONSULTADO</option>
                                    <option selected value="consultado">CONSULTADO</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6>Anexos</h6>

                            <div style="display: flex; gap: 1rem" class="anexos">

                                <?php if($documentos): foreach ($documentos as $documento):?>

                                    <div style="display: flex; flex-direction: column" class='documento'>
                                        <img class='documentimg' width="130" height="130" src="<?= $documento ?>" alt="<?= pathinfo($documento)['basename'] ?>" />
                                        <span><?= pathinfo($documento)['basename'] ?></span>
                                    </div>

                                <?php endforeach; endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nome do Motorista</label>
                                <input value="<?= $motorista->name ?>" value="Nome do Motorista" type="text" name="name" class="form-control" id="name">
                                <?php if(isset($_COOKIE['aviso'])): ?>

                                    <b style="color: red; font-size: 10px">
                                        <?= $_COOKIE['aviso'] ?>
                                    </b>

                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>CPF</label>
                                <input oninput="mascara(this)" value="<?= $motorista->cpf ?>" type="text" name="cpf" id="cpf" class="form-control" value="CPF do Motorista">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Telefone</label>
                                <input value="<?= $motorista->telefone ?>" type="tel" name="telefone" id="telefone" class="form-control" value="Telefone do Motorista">
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Placa do Veículo</label>
                                <input value="<?= $motorista->placa_veiculo ?>" type="text" name="placa_veiculo" id="placa_veiculo" class="form-control" value="Placa do Veículo">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Placa do Reboque</label>
                                <input value="<?= $motorista->placa_reboque ?>" type="text" class="form-control" id="placa_reboque" value="Placa do Reboque" name="placa_reboque">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Carroceria</label>
                                <input value="<?= $motorista->carroceria ?>" type="text" class="form-control" id="carroceria" value="Carroceria" name="carroceria">
                                <span id="carroceria_preview"></span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Modelo / Ano</label>
                                <input value="<?= $motorista->modelo ?>" type="text" class="form-control" id="modelo" value="Modelo / Ano" name="modelo">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!--<div class="col-md-4">
                            <div class="form-group">
                                <input value="<?= $motorista->valor ?>" type="number" name="valor" class="form-control" id="valor" value="Valor">
                            </div>
                        </div>-->

                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="pagamento" id="pagamento" class="form-control">
                                    <option <?php if($motorista->pagamento === "adiamento" || $motorista->pagamento === "adiantamento"): echo "selected"; endif; ?> value="adiantamento">
                                        Adiantamento
                                    </option>
                                    <option <?php if($motorista->pagamento === "vista"): echo "selected"; endif; ?> value="vista">
                                        A Vista
                                    </option>
                                    <option <?php if($motorista->pagamento === "cheque"): echo "selected"; endif; ?> value="cheque">
                                        Cheque
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <input <?php

                                switch ($motorista->pagamento):

                                    case "adiamento":
                                        echo "value='{$motorista->porcentagem}'";
                                        break;

                                    case "adiantamento":
                                        echo "value='{$motorista->porcentagem}'";
                                        break;

                                    case "vista":
                                        echo "value='{$motorista->vista}'";
                                        break;

                                    case "cheque":
                                        echo "value='{$motorista->cheque}'";
                                        break;
                                endswitch;
                                ?> value="Texto do Pagamento" type="text" name="texto_pagamento" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="obs" class="form-control" id="obs" value="Observação do Motorista"><?= $motorista->obs ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <h5>Dados Bancários</h5>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>O Motorista é o dono da conta?</label>
                                <input type="checkbox" class="form-control motorista-input" name="dono" id="dono">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nome</label>
                                <input value="<?= $motorista->nome_banco ?>" type="text" class="form-control" value="Nome Condizente a Conta" name="nome_banco" id="nome_banco">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>CPF</label>
                                <input value="<?= $motorista->cpf_banco ?>" type="text" id="cpf_banco" class="form-control" value="Cpf Condizente a Conta" name="cpf_banco">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>PIX</label>
                                <input value="<?= $motorista->pix ?>" type="text" class="form-control" value="PIX" name="pix" id="pix">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Agência</label>
                                <input value="<?= $motorista->agencia ?>" type="text" class="form-control" value="Agência" name="agencia" id="agencia">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Conta</label>
                                <input value="<?= $motorista->conta ?>" type="text" class="form-control" value="Conta" name="conta" id="conta">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Banco</label>
                                <input value="<?= $motorista->banco ?>" type="text" class="form-control" value="Banco" name="banco" id="banco">
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <input type="submit" class="form-control" name="submit" value="Editar Motorista" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->start("script") ?>
<script>

    $(function () {

        $("#dono").click(function (e) {

            var nome = $("#name").val();

            if(this.checked) {
                $("#nome_banco").val(nome);
            } else {
                $("#nome_banco").val("");
            }

        })

    })
    
    function mascara(i){
   
   var v = i.value;
   
   if(isNaN(v[v.length-1])){ // impede entrar outro caractere que não seja número
      i.value = v.substring(0, v.length-1);
      return;
   }
   
   i.setAttribute("maxlength", "14");
   if (v.length == 3 || v.length == 7) i.value += ".";
   if (v.length == 11) i.value += "-";

}

$("#cnpj").keydown(function(){
    try {
        $("#cnpj").unmask();
    } catch (e) {}

    var tamanho = $("#cnpj").val().length;

    if(tamanho < 11){
        $("#cnpj").mask("999.999.999-99");
    } else {
        $("#cnpj").mask("99.999.999/9999-99");
    }

    // ajustando foco
    var elem = this;
    setTimeout(function(){
        // mudo a posição do seletor
        elem.selectionStart = elem.selectionEnd = 10000;
    }, 0);
    // reaplico o valor para mudar o foco
    var currentValue = $(this).val();
    $(this).val('');
    $(this).val(currentValue);
});

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
<script type="text/javascript">$("#telefone").mask("(00) 0000-00009");</script>
<?php $this->stop() ?>

