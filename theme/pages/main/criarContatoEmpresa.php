<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Cadastrar Contato</h5>
            </div>
            <div class="card-body">
                <form action="<?= $router->route('ContatoEmpresa.new') ?>" method="post" data-action="<?= $router->route('ContatoEmpresa.new') ?>">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" class="form-control" id="nome" placeholder="Insira o nome do Responsável" name="nome">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> Cargo </label>
                                <input class="form-control" id="cargo" name="cargo" placeholder="Contato a ser registrado" type="text">
                            </div>
                        </div>
                    </div>

              

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" id="email" placeholder="Insira o email do contato" name="email" required>
                            </div>
                        </div>
                        
                         <div class="col-md-4">
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="text" class="form-control" id="celular" placeholder="Insira o email do contato" name="celular" required>
                            </div>
                        </div>

                      
                                <input type="text" class="form-control" id="cnpjempresa" placeholder="CNPJ" name="cnpjempresa" value"<?= $cnpj ?>" required>
                         

                       
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

        $("#cnpj").on("keyup", function (e) {
            e.preventDefault()
            var value = this.value;

            if(value.length >= 14) {
                var data = $(this).data();

                $.post(data.action, {'cnpj': value}, function (result) {

                    console.log(result);

                    $("#email").prop("value", result.email);
                    $("#name").prop("value", result.nome);
                    $("#uf").prop("value", result.uf);
                    $("#city").prop("value", result.municipio)

                }, "json")
            }
        })
    })

</script>
<?php $this->stop() ?>
