<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Cadastrar Empresa</h5>
            </div>
            <div class="card-body">
                <form action="<?= $router->route('Empresas.new') ?>" method="post" data-action="<?= $router->route('Empresas.new') ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Vendedor</label>
                                <select class="form-control" name="vendedor_id" id="vendedor_id">
                                    <option value="">Selecione Vendedor</option>

                                    <?php foreach ($users as $user): ?>

                                        <option value="<?= $user->id ?>"><?= $user->first_name ?></option>

                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nome da Empresa</label>
                                <input type="text" class="form-control" id="name" placeholder="Insira o nome aqui" name="empresa_name" required>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label>Cidade</label>
                                <input required type="text" name="city" id="city" class="form-control" placeholder="Cidade qual se localiza a empresa">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estado</label>
                                <input required type="text" name="uf" id="uf" class="form-control" placeholder="Estado no qual se localiza a empresa (Sigla UF)">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control status" name="status" id="status">
                                    <option value="provável cliente" selected>Provável Cliente</option>
                                    <option value="cliente">Cliente</option>
                                    <option value="improvável cliente">Improvável Cliente</option>
                                    <option value="não cliente">Não Cliente</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr style="background-color: rgba(0, 0, 0, .2)">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rua</label>
                                <input type="text" name="rua" id="rua" class="form-control" placeholder="Rua na qual se localiza a empresa">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Complemento</label>
                                <input type="text" name="complemento" id="complemento" class="form-control" placeholder="Número, Apto, etc">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>CNPJ</label>
                                <input type="text" class="form-control" id="cnpj" placeholder="CNPJ" name="cnpj" data-action="<?= $router->route('Empresas.consulta') ?>">
                                <!-- <input type="text" class="form-control" id="cnpj" placeholder="CNPJ da empresa" name="cnpj" data-action="<?= $router->route('Empresas.consulta') ?>"> -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Forma de Pagamento</label>
                                <input type="text" class="form-control" placeholder="Escolha a Forma de Pagamento da Empresa" name="pagamento">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>SPC</label>
                                <select name="spc" class="form-control spc" id="spc">
                                    <option value="a verificar" selected>A Verificar</option>
                                    <option value="sem restrição">Sem Restrição</option>
                                    <option value="com restrição">Com Restrição</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Informações Importantes</label>
                            <textarea placeholder="Avisos importantes para o cliente." name="importante" class="form-control" id="importante" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    
                     <h4>Contatos</h4>
                     
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contato</label> <a href="#" style="color: green; margin-left: 1rem" class="remove-origem"><i class="fas fa-minus"></i></a> <a href="#" style="color: green; margin-left: 1rem" class="add-origem"><i class="fas fa-plus"></i></a>

                                <div class="contato-origem">
                                    <input type="text" name="contato-origem" id="contatoorigem" class="form-control contatoorigem" placeholder="Contato">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cargo</label>
                                <div class="cargo-origem">
                                    <input type="text" name="cargo-origem" id="cargoorigem" class="form-control cargoorigem" placeholder="Cargo">
                                </div>
                            </div>

                          

                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email</label>
                                <div class="email-origem">
                                    <input type="text" name="email-origem" id="emailorigem" class="form-control emailorigem" placeholder="E-mail">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Telefone</label>
                                <div class="telefone-origem">
                                    <input type="text" name="telefone-origem" id="telefoneorigem" class="form-control telefoneorigem" placeholder="Telefone">
                                </div>
                            </div>

                          

                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <input type="submit" class="form-control" name="submit" value="Cadastrar Empresa" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                        </div>
                    </div>
                    
                   
                </form>
            </div>
        </div>
    </div>
</div>



<div class="msg"></div>

<?php $this->start("script"); ?>
<script>
    $(function () {

        $("[data-action]").submit(function (e) {
            e.preventDefault();

            var data = $(this).data();

            $.post(data.action, $(this).serialize(), function(response) {
                alert(response);

               window.location.href = "<?= $router->route('Main.empresas') ?>"
            }, "json");
        })

        $("#cnpj").on("keyup", function (e) {
            e.preventDefault()
            var value = this.value;
            value = value.replace('.', ''); 
            value = value.replace('/', ''); 

            if(value.length >= 14) {
                var data = $(this).data();

                $.post(data.action, {'cnpj': value}, function (result) {

                    console.log(result);

                    $("#email").prop("value", result.email);
                    $("#name").prop("value", result.nome);
                    $("#uf").prop("value", result.uf);
                    $("#city").prop("value", result.municipio)
                    $("#complemento").prop("value", result.numero);
                    $("#rua").prop("value", `${result.logradouro} - ${result.bairro} - ${result.cep}`);

                }, "json")
            }
        })

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
        })

    })
    
    $(".add-origem").click(function (e) {
        e.preventDefault();

        var contato = $("#contatoorigem").clone();
        var cargo = $("#cargoorigem").clone();
        var email = $("#emailorigem").clone();
        var telefone = $("#telefoneorigem").clone();

        contato.prop('name', `contato-origem ${$(".contatoorigem").length}`)
        cargo.prop('name', `cargo-origem ${$(".cargoorigem").length}`)
        contato.prop('class', `form-control contatoorigem ${$(".contatoorigem").length}`)
        cargo.prop('class', `form-control cargoorigem ${$(".cargoorigem").length}`)
        
        email.prop('name', `email-origem ${$(".emailorigem").length}`)
        telefone.prop('name', `telefone-origem ${$(".telefoneorigem").length}`)
        email.prop('class', `form-control emailorigem ${$(".emailorigem").length}`)
        telefone.prop('class', `form-control telefoneorigem ${$(".telefoneorigem").length}`)

        contato.appendTo($(".contato-origem"));
        cargo.appendTo($(".cargo-origem"))
        email.appendTo($(".email-origem"));
        telefone.appendTo($(".telefone-origem"))
    })
       
    $(".remove-origem").click(function (e) {
        e.preventDefault();

        var length = $(".contatoorigem").length - 1;

        $(`.contatoorigem.${length}`).remove();
        $(`.cargoorigem.${length}`).remove();
            $(`.emailorigem.${length}`).remove();
        $(`.telefoneorigem.${length}`).remove();
    })
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
<script type="text/javascript">$("#contato-origem").mask("(00) 0000-00009");</script>
<script type="text/javascript">$("#contatoorigem0").mask("(00) 0000-00009");</script>
<script type="text/javascript">$("#contatoorigem1").mask("(00) 0000-00009");</script>
<script type="text/javascript">$("#contatoorigem2").mask("(00) 0000-00009");</script>
<script type="text/javascript">$("#contatoorigem3").mask("(00) 0000-00009");</script>
<script type="text/javascript">$("#contatoorigem4").mask("(00) 0000-00009");</script>

<?php $this->stop() ?>
