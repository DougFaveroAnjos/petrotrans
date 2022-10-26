<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Cadastrar Motorista</h5>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" action="<?= $router->route('Motoristas.new') ?>" method="post" data-action="<?= $router->route('Motoristas.new') ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>BUONNY</label>
                                <select class="form-control" name="buonny" id="buonny">
                                    <option value="nao consultado">NÃO CONSULTADO</option>
                                    <option value="consultado">CONSULTADO</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>DOCUMENTOS</label>

                                <div class="files">

                                    <label style="cursor: pointer" class="file-input"> + </label>
                                    <input style="cursor: pointer" id='arquivo' accept="image/jpeg, image/jpg, image/png, application/pdf" type="file" name="documentos[]" multiple="multiple">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>IMAGENS</label>

                                <div style="display: flex; gap: 2rem; margin-top: 1rem; margin-bottom: 1rem" class="images">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nome do Motorista</label>
                                <input required placeholder="Nome do Motorista" type="text" name="name" class="form-control" id="name">
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
                                <input oninput="mascara(this)" type="text" name="cpf" id="cpf" class="form-control" placeholder="CPF do Motorista">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Telefone</label>
                                <input type="tel" name="telefone" id="telefone" class="form-control" placeholder="Telefone do Motorista">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Placa do Veículo</label>
                                <input type="text" name="placa_veiculo" id="placa_veiculo" class="form-control" placeholder="Placa do Veículo">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Placa do Reboque</label>
                                <input type="text" class="form-control" id="placa_reboque" placeholder="Placa do Reboque" name="placa_reboque">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Carroceria</label>
                                <input type="text" class="form-control" id="carroceria" placeholder="Carroceria" name="carroceria">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Modelo / Ano</label>
                                <input type="text" class="form-control" id="modelo" placeholder="Modelo / Ano" name="modelo">
                            </div>
                        </div>
                    </div>


                    <!--<div class="row justify-content-between">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Valor Acordado</label>
                                <input required type="text" class="form-control" placeholder="Valor Acordado" name="valor" id="valor">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Desconto</label>
                                <input type="checkbox" name="desconto_check" class="form-control" id="desconto_check">

                                <div class="descontos">
                                    <input disabled placeholder="Desconto" type="text" class="form-control desconto" name="desconto" id="desconto">
                                    <input disabled placeholder="Motivo" type="text" class="form-control desconto" name="motivo_desconto" id="motivo">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Forma de Pagamento</label>
                                <select name="pagamento" class="form-control" id="pagamento">
                                    <option value="forma">Forma de Pagamento</option>
                                    <option class="porcentagem-option" value="adiantamento">Adiantamento</option>
                                    <option value="vista">A Vista</option>
                                    <option value="cheque">Cheque</option>
                                </select>
                            </div>
                        </div>

                    </div>-->

                    <div class="row justify-content-end">
                        <div class="col-md-2 porcentagem">
                            <div class="form-group">
                                <label>Porcentagem do Adiantamento:</label>
                                <input disabled placeholder="%" type="text" name="porcento" id="porcentagem" class="form-control porcentagem">
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
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="obs" class="form-control" id="obs" placeholder="Observação do Motorista"></textarea>
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
                                <input type="checkbox" class="form-control" name="dono" id="dono">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" class="form-control" placeholder="Nome Condizente a Conta" name="nome_banco" id="nome_banco">
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
                                <input type="text" class="form-control" placeholder="PIX" name="pix" id="pix">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Agência</label>
                                <input type="text" class="form-control" placeholder="Agência" name="agencia" id="agencia">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Conta</label>
                                <input type="text" class="form-control" placeholder="Conta" name="conta" id="conta">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Banco</label>
                                <input type="text" class="form-control" placeholder="Banco" name="banco" id="banco">
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-3" style="display: flex; align-items: center; justify-content: right">
                            <span style="font-size: .8rem; color: red; opacity: .7">*VERIFIQUE O BUONNY*</span>
                        </div>

                        <div class="col-md-3">
                            <input disabled id="submit" type="submit" class="form-control" name="submit" value="Cadastrar Motorista" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
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

            var valor = $("#valor").val()

            $("#valor").val(valor - $(this).val())

        })

        $("#dono").click(function (e) {

            var nome = $("#name").val();
            var cpf = $("#cpf").val();

            if(this.checked) {
                $("#nome_banco").val(nome);
                $("#cpf_banco").val(cpf);
            } else {
                $("#nome_banco").val("");
                $("#cpf_banco").val("");
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

        $("#buonny").change(function () {
            if($(this).val() === "consultado") {
                $("#submit").prop("disabled", false);
            } else {
                $("#submit").prop("disabled", true);
            }
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

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
<script type="text/javascript">$("#telefone").mask("(00) 0000-00009");</script>
<?php $this->stop() ?>
