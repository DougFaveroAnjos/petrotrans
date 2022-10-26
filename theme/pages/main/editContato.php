<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Editar Contato</h5>
            </div>
            <div class="card-body">
                <form action="<?= $router->route('Contato.edit') ?>" method="post" data-action="<?= $router->route('Contato.edit') ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Empresa</label> <a target="_blank" href="<?= $router->route('Empresas.criarEmpresa') ?>" style="color: green; margin-left: 1rem"><i class="fas fa-plus"></i></a>
                                <div class="typeahead__container">
                                    <input id="name" value="<?= $contato->empresa ?>" data-action="<?= $router->route('Empresas.search') ?>" type="text" class="form-control empresas" name="empresa" autocomplete="off">
                                </div>
                                <input type="hidden" name="id" value="<?= $contato->id ?>">
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label> CNPJ </label>
                                <input type="text" class="form-control" id="cnpj" value="<?= $contato->cnpj ?>" name="cnpj" data-action="<?= $router->route('Empresas.consulta') ?>">
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" name="city" id="city" class="form-control" value="<?= $contato->cidade ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estado</label>
                                <input type="text" name="uf" id="uf" class="form-control" value="<?= $contato->estado ?>">
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control status" name="status" id="status">
                                    <option value="provável cliente" <?php if($contato->status === "provável cliente"): echo "selected"; endif ?>>Provável Cliente</option>
                                    <option value="cliente"<?php if($contato->status === "cliente"): echo "selected"; endif ?>>Cliente</option>
                                    <option value="improvável cliente"<?php if($contato->status === "improvável cliente"): echo "selected"; endif ?>>Improvável Cliente</option>
                                    <option value="não cliente"<?php if($contato->status === "não cliente"): echo "selected"; endif ?>>Não Cliente</option>
                                </select>
                            </div>

                        </div>

                    </div>
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descrição da Contato</label>
                                <textarea name="obs" rows="4" cols="80" class="form-control" value="<?= $contato->obs ?>"></textarea>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                     <div class="col-md-3">
                            <div class="form-group">
                                <label>Contato</label>
                                <input type="text" class="form-control" value="<?= $contato->responsavel ?>" name="responsavel">
                            </div>
                        </div>
                          <div class="col-md-3">
                            <div class="form-group">
                                <label>Cargo</label>
                                <input type="text" class="form-control" value="<?= $contato->cargo ?>" name="cargo">
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                                <label> Telefone </label>
                                <input class="form-control" name="contato" value="<?= $contato->contato ?>" type="text">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" id="email" value="<?= $contato->email ?>" name="email">
                            </div>
                        </div>
                       
                       
                       

                       

                       
                    </div>

                  
                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <input type="submit" class="form-control" name="submit" value="Editar Contato" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
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

            $("form").submit(function (e) {
                e.preventDefault();

                var data = $(this).data();

                $.post(data.action, $(this).serialize(), function (response) {

                    alert(response);
                    window.location.href = "<?= $router->route("Main.contatos") ?>"
                }, "json");
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
