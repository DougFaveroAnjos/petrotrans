<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Cadastrar Contato</h5>
            </div>
            <div class="card-body">
                <form action="<?= $router->route('Contato.new') ?>" method="post" data-action="<?= $router->route('Contato.new') ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Empresa</label> <a target="_blank" href="<?= $router->route('Empresas.criarEmpresa') ?>" style="color: green; margin-left: 1rem"><i class="fas fa-plus"></i></a>
                                <div class="typeahead__container">
                                    <input placeholder="Empresa" data-fill="<?= $router->route('Contato.search') ?>" data-action="<?= $router->route('Empresas.search') ?>" type="text" class="form-control empresas" name="empresa" autocomplete="off" id="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> CNPJ </label>
                                <input type="text" class="form-control" id="cnpj" placeholder="CNPJ" name="cnpj" data-action="<?= $router->route('Empresas.consulta') ?>">
                            </div>
                        </div>
                       
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" name="city" id="city" class="form-control" placeholder="Cidade qual se localiza o contato" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estado</label>
                                <input type="text" name="uf" id="uf" class="form-control" placeholder="Estado no qual se localiza o contato (Sigla UF)" required>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control status" name="status" id="status">
                                    <option value="provável cliente">Provável Cliente</option>
                                    <option value="cliente">Cliente</option>
                                    <option value="improvável cliente">Improvável Cliente</option>
                                    <option value="não cliente">Não Cliente</option>
                                </select>
                            </div>

                        </div>
                         <div class="col-md-12">
                            <div class="form-group">
                                <label>Descrição da Contato</label>
                                <textarea name="obs" rows="4" cols="80" class="form-control" placeholder="Descrição"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-md-3">
                            <div class="form-group">
                                <label>Contato</label>
                                <input type="text" class="form-control" id="responsavel" placeholder="Insira o nome do Contato" name="responsavel">
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                <label>Cargo</label>
                                <input type="text" class="form-control" id="cargo" placeholder="Insira o cargo do Contato" name="responsavel">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> Telefone </label>
                                <input class="form-control" id="contato" name="contato" placeholder="Insira o telefone do Contato" type="text">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" id="email" placeholder="Insira o email do contato" name="email">
                            </div>
                        </div>
                       
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <input type="submit" class="form-control" name="submit" value="Criar Contato" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="msg"></div>

<?php $this->start("script") ?>
<script>

    $(function () {

        var empresas = [];

        $('.empresas').on("keyup", function () {
            var data = $('.empresas').data();

            $.post(data.action, $("form").serialize(), function (success) {

                empresas = success

            }, "json")

            $.typeahead({
                input: '.empresas',
                order: "desc",
                source: {
                    data: empresas[0]
                },
                callback: {
                    onInit: function (node) {
                        console.log('Typeahead Initiated on ' + node.selector);
                    }
                }
            });
        })

        $(".empresas").on("blur", function () {
            var data = $(this).data();

            $.post(data.fill, $("form").serialize(), function (response) {

                if(response.existe === true) {
                    $("#responsavel").prop("value", response.responsavel)
                    $("#contato").prop("value", response.contato)
                    $("#city").prop("value", response.city)
                    $("#uf").prop("value", response.uf)
                    $("#email").prop("value", response.email)
                    $("#status").prop("value", response.status)
                }

            }, "json")

        })

        $("form").submit(function (e) {
            e.preventDefault();

            var data = $(this).data();

            $.post(data.action, $(this).serialize(), function (response) {

                console.log(response);
                $(".msg").html(response);
                window.location.href = "<?= $router->route("Main.contatos") ?>"
            })
        })

        $("[data-action]").submit(function (e) {
            e.preventDefault();

            var data = $(this).data();

            $.post(data.action, $(this).serialize(), function(response) {
                alert(response);

            //   window.location.href = "<?= $router->route('Main.empresas') ?>"
               window.location.href = "<?= $router->route('Main.contatos') ?>"
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
<script type="text/javascript">$("#contato").mask("(00) 0000-00009");</script>
<?php $this->stop() ?>
