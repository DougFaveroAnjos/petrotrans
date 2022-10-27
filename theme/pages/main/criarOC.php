<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <form
                enctype="multipart/form-data"
                method="post"
                action="<?= $router->route('Coletas.new') ?>"
                class="oc-form"
        >

            <input type="hidden" name="transporte_id" value="<?= $transporte->id ?>">

        <div class="card">

            <div class="card-header">
                <h5 class="title"> Cadastrar O.C </h5>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-md-3"> <h6> O Motorista ja existe? </h6> </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="radio" class="motorista-radio" name="motorista" value="true">
                            <label style="margin-left: .5rem" for="motorista"> Sim </label>
                        </div>
                    </div><!-- Motorista Existe -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="radio" class="motorista-radio" name="motorista" value="false" checked>
                            <label style="margin-left: .5rem" for="motorista"> Não </label>
                        </div>
                    </div> <!-- Motorista Não Existe -->
                </div>

                <div class="row motorista-false active">
                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>BUONNY</label>
                                    <select class="form-control motorista-input" name="buonny" id="buonny">
                                        <option value="nao consultado">NÃO CONSULTADO</option>
                                        <option value="consultado">CONSULTADO</option>
                                    </select>
                                </div>
                            </div>
                        </div> <!-- BUONNY-->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>DOCUMENTOS</label>

                                    <button class="files" id="addFiles">

                                        <label style="cursor: pointer; color: #008DD5;" class="file-input"> + </label>
                                        <input style="cursor: pointer" class="motorista-input" id='arquivo' accept="image/jpeg, image/jpg, image/png, application/pdf" type="file" name="documentos[]" multiple="multiple">

                                    </button>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>IMAGENS</label>

                                    <div style="display: flex; gap: 2rem; margin-top: 1rem; margin-bottom: 1rem" class="images">

                                    </div>
                                </div>
                            </div>
                        </div> <!-- IMAGENS -->

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nome do Motorista</label>
                                    <input placeholder="Nome do Motorista" type="text" name="name" class="form-control motorista-input" id="name">

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
                                    <input type="text" name="cpf" id="cpf" class="form-control motorista-input" placeholder="CPF do Motorista">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefone</label>
                                    <input type="tel" name="telefone" id="telefone" class="form-control motorista-input" placeholder="Telefone do Motorista">
                                </div>
                            </div>
                        </div> <!-- Primeira Linha -->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Placa do Veículo</label>
                                    <input type="text" name="placa_veiculo" id="placa_veiculo" class="form-control motorista-input" placeholder="Placa do Veículo">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Placa do Reboque</label>
                                    <input type="text" class="form-control motorista-input" id="placa_reboque" placeholder="Placa do Reboque" name="placa_reboque">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Carroceria</label>
                                    <input type="text" class="form-control motorista-input" id="carroceria" placeholder="Carroceria" name="carroceria">
                                     <br>
                                    
                                    <b><?= $transporte->carroceria ?> | Peso: <?= $transporte->peso ?></b>
                                    <br>
                                    
                                    
                                    <br>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Modelo / Ano</label>
                                    <input type="text" class="form-control" id="modelo" placeholder="Modelo / Ano" name="modelo">
                                </div>
                            </div>
                        </div><!-- Segunda Linha -->

                        <div class="row justify-content-between">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor Acordado</label>
                                    <input type="text" class="form-control motorista-input" placeholder="Valor Acordado" name="valor" id="valor">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Desconto</label>
                                    <input type="checkbox" class="form-control motorista-input" id="desconto_check" name="desconto_check">

                                    <div class="descontos">
                                        <input disabled placeholder="Desconto %" type="text" class="form-control desconto motorista-input" name="desconto" id="desconto">
                                        <input disabled placeholder="Motivo" type="text" class="form-control desconto motorista-input" name="motivo_desconto" id="motivo">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Forma de Pagamento</label>
                                    <select name="pagamento" class="form-control motorista-input" id="pagamento">
                                        <option value="forma">Forma de Pagamento</option>
                                        <option class="porcentagem-option" value="adiantamento">Adiantamento</option>
                                        <option value="vista">A Vista</option>
                                        <option value="cheque">Cheque</option>
                                    </select>
                                </div>
                            </div> <!-- Pagamento -->

                        </div><!-- Terceira Linha -->

                        <div class="row justify-content-end">
                            <div class="col-md-2 porcentagem">
                                <div class="form-group">
                                    <label>Porcentagem do Adiantamento:</label>
                                    <input disabled placeholder="%" type="text" name="porcento" id="porcentagem" class="form-control porcentagem motorista-input">
                                </div>
                            </div>

                            <div class="col-md-2 porcentagem">
                                <div class="form-group">
                                    <span id="adiamento">Adiantamento: </span>
                                </div>
                            </div>
                            <div class="col-md-2 porcentagem">
                                <div class="form-group">
                                    <span id="saldo">Saldo: </span>
                                </div>
                            </div>
                            <input type="hidden" class="porcentagem" id="porcentagem_valor" name="adiantamento">

                            <div class="col-md-3 vista">
                                <div class="form-group">
                                    <select disabled class="form-control vista" name="vista" id="vista">
                                        <option value="coleta">Na Coleta</option>
                                        <option value="entrega">Na Entrega</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 cheque">
                                <div class="form-group">
                                    <label>Quantos dias</label>
                                    <input disabled type="text" name="cheque" id="cheque" class="form-control cheque" placeholder="Quantidade de Dias">
                                </div>
                            </div>
                        </div><!-- Quarta Linha -->

                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea name="obs" class="form-control motorista-input" id="obs" placeholder="Observação do Motorista"></textarea>
                                </div>
                            </div>
                        </div><!-- OBS -->

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
                        </div><!-- DADOS BANCARIOS -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nome</label>
                                    <input type="text" class="form-control motorista-input" placeholder="Nome Condizente a Conta" name="nome_banco" id="nome_banco">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>CPF</label>
                                    <input type="text" id="cpf_banco" class="form-control" placeholder="Cpf Condizente a Conta" name="cpf_banco">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>PIX</label>
                                    <input type="text" class="form-control motorista-input" placeholder="PIX" name="pix" id="pix">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Agência</label>
                                    <input type="text" class="form-control motorista-input" placeholder="Agência" name="agencia" id="agencia">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Conta</label>
                                    <input type="text" class="form-control motorista-input" placeholder="Conta" name="conta" id="conta">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Banco</label>
                                    <input type="text" class="form-control motorista-input" placeholder="Banco" name="banco" id="banco">
                                </div>
                            </div>
                        </div>

                    </div>
                </div> <!-- Motorista false -->


                <div class="row motorista-true">
                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">

                                    <label> Selecione o Motorista </label>
                                    <select disabled class="form-control" name="motoristas" id="motoristas">

                                        <?php if($motoristas): foreach ($motoristas as $motorista): ?>

                                            <option value="<?= $motorista->name ?>"><?= $motorista->name ?></option>

                                        <?php endforeach; endif; ?>

                                    </select>
                                    
                                </div>
                            </div>
                        </div>

                    </div>
                </div> <!-- Motorista true -->

            </div>

        </div><!-- MOTORISTA -->

            <div class="card">
                <div class="card-header">
                    <h5 class="title"> DADOS DA O.C </h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Data de Coleta</label>
                                <input placeholder="Data de Coleta (transporte)" class="form-control" name="coleta" type="datetime-local">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Endereço de Coleta</label>
                                <input placeholder="Endereço para Coleta" type="text" class="form-control" name="endereco_coleta" id="endereco_coleta">
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Endereço de Entrega</label>
                                <input placeholder="Endereço para Entrega" type="text" class="form-control" name="endereco_entrega" id="endereco_entrega">
                            </div>
                        </div>
                    </div><!-- endereços -->
                    
                    <?php if($contatos): ?>
                    <br>
                    <h5 class="title"> Contatos </h5><br>
                    
                       <?php endif;?>
                    
                    
                    	<?php if($contatos): foreach ($contatos as $contato): ?>

                                            <b>Nome: </b><?=$contato->nome ?> <b>  Cargo/Departamento: </b> <?=$contato->cargo ?><b>  Celular: </b> <?=$contato->cargo ?><b> E-mail: </b><?=$contato->email ?>
                                            
                                            <br><br>

                                        <?php endforeach; endif;?>
                    	
                    
                    <br><br>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Emails para Envio (Separar com "; ")</label>
                                <textarea class="form-control" placeholder="email@exemplo.com; email2@exemplo.com" name="emails" id="emails" cols="30" rows="10"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telefones para Envio (Separar com "; ")</label>
                                <textarea class="form-control" placeholder="01999999999; 01888888888" name="telefones" id="telefones" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div><!-- dados -->

                    <div class="row">
                        <div class="col-md-12">
                            <label>Descrição da O.C</label>
                            <textarea placeholder="Descrição para a Ordem de Coleta" class="form-control" name="obs_oc" id="obs_oc" cols="30" rows="10"></textarea>
                        </div>
                    </div><!-- descrição oc -->

                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <button disabled id="submit" type="submit" class="btn form-control" name="submit" value="Cadastrar O.C" style="margin-top:10px">Cadastrar O.C</button>
                        </div>
                    </div>
                </div>
            </div> <!-- OC -->
        </form>
    </div>
</div>

<?php $this->start("script") ?>

<script>
    $(function () {

        $(".motorista-radio").click(function () {

            if($(this).val() === "true") {
                $("#submit").prop("disabled", false);

                $(".motorista-true").addClass("active");
                $("#motoristas").prop("disabled", false);
                //---------------------------------------//
                $(".motorista-false").removeClass("active");
                $(".motorista-input").prop("disabled", true);

            } else if($(this).val() === "false") {

                if($("#buonny").val() === "nao consultado") {
                    $("#submit").prop("disabled", false);
                }

                $(".motorista-true").removeClass("active");
                $("#motoristas").prop("disabled", true);
                //--------------------------------------//
                $(".motorista-false").addClass("active");
                $(".motorista-input").prop("disabled", false);

            }

        })


        $("#desconto_check").click(function (e) {

            if(this.checked) {
                $(".desconto").prop("disabled", false);
                $(".descontos").addClass("active");
            } else {
                $(".desconto").prop("disabled", true);
                $(".descontos").removeClass("active");
            }

        })

        $("#desconto").on("blur", function (e) {

            var valor = $("#valor").val();

            $("#valor").val(valor - $(this).val())

        })

        $("#dono").click(function (e) {

            var nome = $("#name").val();

            if(this.checked) {
                $("#nome_banco").val(nome);
            } else {
                $("#nome_banco").val("");
            }

        })


        $("#buonny").change(function () {
            if($(this).val() === "consultado") {
                $("#submit").prop("disabled", false);
            } else {
                $("#submit").prop("disabled", true);
            }
        })

        $("#arquivo").change(function(e) {
            var files = $(this)[0].files;

            $.each(files, function (index, file) {

                var fileReader = new FileReader();

                fileReader.onload = function (e) {
                    var div = `<div style="display: flex; flex-direction: column" class='documento'><img class='documentimg' width="130" height="130" src="${fileReader.result}" alt="${file.name}" /> <span>${file.name}</span> </div>`

                    $(".images").append(div);
                }

                fileReader.readAsDataURL(file);
            })

        })


        $("#porcentagem").on("keyup", function (e) {

            var val = parseFloat($("#valor").val());
            var porcentagem = parseFloat($(this).val());

            var adiamento = (val/100)*porcentagem ;
            var saldo = val - adiamento ;

            var adiamentoFormatado = adiamento.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            var saldoFormatado = saldo.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

            $("#adiamento").html(`Adiantamento: <br> ${adiamentoFormatado}`);
            $("#saldo").html(`Saldo: <br> ${saldoFormatado}`);
            $("#porcentagem_valor").prop("value", `${porcentagem}/${100-porcentagem} -- Adiantamento: ${adiamentoFormatado} - Saldo: ${saldoFormatado}`)

        })
        $("#valor").on("keyup", function (e) {

            var val = parseFloat($(this).val());
            var porcentagem = parseFloat($("#porcentagem").val());

            var adiamento = (val/100)*porcentagem ;
            var saldo = val - adiamento ;

            var adiamentoFormatado = adiamento.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            var saldoFormatado = saldo.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

            $("#adiamento").html(`Adiantamento: <br> ${adiamentoFormatado}`);
            $("#saldo").html(`Saldo: <br> ${saldoFormatado}`);
            $("#porcentagem_valor").prop("value", `${porcentagem}/${100-porcentagem} -- Adiantamento: ${adiamentoFormatado} - Saldo: ${saldoFormatado}`)


        })

        $("#pagamento").change(function (e) {

            switch ($(this).val()) {

                case "adiantamento":

                    $(".porcentagem").prop("disabled", false);
                    $(".porcentagem").toggleClass("active")

                    $(".vista").prop("disabled", true);
                    $(".vista").removeClass("active")
                    $(".cheque").prop("disabled", true);
                    $(".cheque").removeClass("active")

                    break

                case "vista":

                    $(".vista").prop("disabled", false);
                    $(".vista").toggleClass("active")

                    $(".porcentagem").prop("disabled", true);
                    $(".porcentagem").removeClass("active")
                    $(".cheque").prop("disabled", true);
                    $(".cheque").removeClass("active")

                    break

                case "cheque":

                    $(".cheque").prop("disabled", false);
                    $(".cheque").toggleClass("active")

                    $(".vista").prop("disabled", true);
                    $(".vista").removeClass("active")
                    $(".porcentagem").prop("disabled", true);
                    $(".porcentagem").removeClass("active")

                    break

                case "forma":

                    $(".vista").prop("disabled", true);
                    $(".vista").removeClass("active")
                    $(".porcentagem").prop("disabled", true);
                    $(".porcentagem").removeClass("active")
                    $(".cheque").prop("disabled", true);
                    $(".cheque").removeClass("active")

                    break


            }

        })
    })
</script>

<?php $this->stop() ?>
