<?php $this->layout("_theme", ["title" => $title]) ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <iframe src="https://www.qualp.com.br/" style="min-height: 110vh; min-width: 100%; outline: none; border: none"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <?php if($contatos): ?>
                    <br>
                    <h5 class="title"> Contatos </h5><br>
                        
                <?php endif;?>        
                        
                <?php if($contatos): foreach ($contatos as $contato): ?>

                    <b>Nome: </b><?=$contato->responsavel ?> <b>  Cargo/Departamento: </b> <?=$contato->cargo ?><b>  Celular: </b> <?=$contato->contato ?><b> E-mail: </b><?=$contato->email ?>  
                    <a href="#" class="items-close <?= $cotacao->id ?> mail" data-action="<?= $router->route('Cotacoes.mail', ['id' => $cotacao->id, 'email' => $contato->email, 'name' => $empresa->name, 'origem' => $cotacao->cidade_origem, 'destino' => $cotacao->cidade_destino]) ?>"><button>Enviar Cotação</button></a><br><br>
                    
                <?php endforeach; endif;?>        
                <br><br>

                <div class="card-header">
                    <h5 class="title">Editar Cotação</h5>
                </div>
                <div class="card-body">
                    <form class="main-form" enctype="multipart/form-data" action="<?= $router->route('Cotacoes.update'); ?>" method="post" data-action="<?= $router->route('Cotacoes.update'); ?>">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <p><label>Haverá devolução?</label></p>
                                    <label><input checked type="radio" class="form-control" name="devolucao" value="n" required <?= ($cotacao->devolucao == '' ? "checkead": "") ?>><span>Não</span></label>
                                    <label><input type="radio" class="form-control" name="devolucao" value="container" required <?= ($cotacao->devolucao == 'container' ? "checkead": "") ?>><span>Container</span></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Vendedor</label>
                                    <select class="form-control" name="vendedor" id="usuario">
                                        <option value="não selecionado">Selecione Vendedor</option>

                                        <?php foreach ($users as $user): ?>

                                            <option <?php if($user->first_name === $cotacao->vendedor): echo 'selected'; endif; ?> value="<?= $user->first_name ?>"><?= $user->first_name ?></option>

                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>ANEXOS</label>

                                    <div class="files">

                                        <label style="cursor: pointer" class="file-input"> + </label>
                                        <input style="cursor: pointer" id='arquivo' accept="image/jpeg, image/jpg, image/png, application/pdf" type="file" name="anexos[]" multiple="multiple">

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
                            <div class="col-md-12">
                                <h6>Anexos</h6>

                                <div style="display: flex; gap: 1rem" class="anexos">
                                    <?php if($anexos): foreach ($anexos as $anexo):?>

                                        <div style="display: flex; flex-direction: column" class='documento'>
                                            <img class='documentimg' width="130" height="130" src="<?= $anexo ?>" alt="<?= pathinfo($anexo)['basename'] ?>" />
                                            <span><?= pathinfo($anexo)['basename'] ?></span>
                                        </div>

                                    <?php endforeach; endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Empresa</label> <a target="_blank" href="<?= $router->route('Empresas.criarEmpresa') ?>" style="margin-left: 1rem"><i style="color: green" class="fas fa-plus"></i></a>

                                    <a id="editar-empresa" target="_blank" href="#" style="color: grey; margin-left: 2rem"><i class="fas fa-pen"></i></a>

                                    <div class="typeahead__container">
                                        <input data-action="<?= $router->route('Empresas.search') ?>" value="<?= $cotacao->cliente ?>" type="text" class="form-control empresas" name="empresa" autocomplete="off">
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kilometragem</label>
                                    <input data-action="<?= $router->route('Cotacoes.analise') ?>" type="number" class="form-control kilometer-input" value="<?= $cotacao->km ?>" name="km">
                                </div>
                                <input type="hidden" name="raio" class="raio-form">
                                <input type="hidden" name="status" class="status-main">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Opção do Frete</label>
                                    <select name="frete" class="form-control frete">
                                        <option value="cif" <?php if($cotacao->opcao_frete === "cif"): echo "selected"; endif; ?>>CIF</option>
                                        <option value="fob" <?php if($cotacao->opcao_frete === "fob"): echo "selected"; endif; ?>>FOB</option>
                                    </select>

                                    <div class="typeahead__container">
                                        <input <?php if($cotacao->opcao_frete !== "fob"): echo "disabled"; endif; ?> type="text" value="<?php if(!$cotacao->cliente_fob): echo 'Empresa FOB'; else: echo $cotacao->cliente_fob; endif; ?>" name="cliente_fob" autocomplete="off" class="form-control cliente_fob <?php if($cotacao->opcao_frete === 'fob'): echo 'active'; endif; ?> empresas" data-action="<?= $router->route('Empresas.search') ?>">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cidade Origem</label>
                                    <input type="text" name="city-origem" id="cityorigem" class="form-control" value="<?= $cotacao->cidade_origem ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estado Origem</label>
                                    <input type="text" name="uf-origem" id="uforigem" class="form-control" value="<?= $cotacao->uf_origem ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="origem" class="form-control" value="<?php if(!$cotacao->origem_complemento): echo 'Sem Outras Origens'; else: echo $cotacao->origem_complemento; endif ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cidade Destino</label>
                                    <input type="text" name="city-destino" id="cityorigem" class="form-control" value="<?= $cotacao->cidade_destino ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estado Destino</label>
                                    <input type="text" name="uf-destino" id="uforigem" class="form-control" value="<?= $cotacao->uf_destino ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="destino" class="form-control" value="<?php if(!$cotacao->destino_complemento): echo 'Sem Outros Destinos'; else: echo $cotacao->destino_complemento; endif ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Veículo</label>
                                    <input type="text" class="form-control" value="<?= $cotacao->veiculo ?>" name="veiculo">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Volumes</label>
                                    <input type="text" class="form-control" value="<?= $cotacao->volumes ?>" name="volumes">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Peso</label>
                                    <input type="text" class="form-control" value="<?= $cotacao->peso ?>" name="peso">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tipo de Carga</label>
                                    <select data-action="<?= $router->route('Cotacoes.analise') ?>" name="tipo" class="form-control tipo-input">
                                        <option value="dedicada" <?php if($cotacao->tipo_carga === "dedicada"): echo "selected"; endif; ?>>Dedicada</option>
                                        <option value="fracionada" <?php if($cotacao->tipo_carga === "fracionada"): echo "selected"; endif; ?>>Fracionada</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data de Coleta</label>
                                    <div class="coleta-div" style="display: flex;">
                                        <input type="checkbox" name="coleta" class="form-control coleta" style="margin: .5rem; max-width: 1rem">
                                        <input type="text" class="form-control data-coleta" value="Data" name="data_coleta" style="width: 85%" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Previsão de Entrega</label>
                                    <input type="text" class="form-control" name="previsao">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Prazo Maximo de Entrega</label>
                                    <input type="text" class="form-control" name="prazo">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor da Nota</label>
                                    <input type="text" class="form-control nota" value="<?= $cotacao->valor_nota ?>" name="nota">
                                    <div class="seguros" style="display: flex; margin-top: 1rem; gap: 4rem">
                                        <span><b class="seguro1">RCTR-C: R$ 0</b></span>
                                        <span><b class="seguro2">RCF-DC 2: R$ 0</b></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor da Cotação</label>
                                    <input type="text" class="form-control" value="<?= $cotacao->valor_cotacao ?>" name="valor">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor do Motorista</label>
                                    <div class="valores-motoristas" style="display: flex; gap: 1rem">
                                        <input class="form-control" placeholder="Entre: <?= $cotacao->valor_motorista_min ?>" type="text" name="valor_motorista_min">
                                        <input class="form-control" placeholder="A: <?= $cotacao->valor_motorista_max ?>" type="text" name="valor_motorista_max">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-md-4 px-1">
                                <div class="form-group">
                                    <label>Forma de Pagamento</label> <a href="#" class="editar-pagamento" style="margin-left: 1rem">Editar</a>
                                    <input type="text" class="form-control pagamento" value="<?= (new \Source\Models\EmpresasModel())->find('id = :id', 'id='.$cotacao->cliente_id)->fetch()->pagamento ?>" name="pagamento" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descrição da Cotação</label>
                                    <input type="hidden" value="<?= $_SESSION['id'] ?>" name="updater">
                                    <input type="hidden" value="<?= $cotacao->id ?>" name="cotacao">
                                    <input type="hidden" value="Editar" name="editar">
                                    <textarea style="max-height: 160px" name="obs" rows="8" cols="80" class="form-control" value="<?= $cotacao->observacao ?>"><?= $cotacao->observacao ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-md-3">
                                <input type="hidden" name="edit_cotacao" value=" ">
                                <input type="submit" class="form-control" name="submit" value="Editar Cotação" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h4>Analise de Cotações</h4>
                </div>
                <form class="filter-form form-group" method="post" data-action="<?= $router->route('Cotacoes.analise') ?>">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <input class="raio-filter form-control" type="number" value="Raio (km):" data-action="<?= $router->route('Cotacoes.analise') ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="status-analise form-control" name="status" id="status">
                                <option value="todos">Todos</option>
                                <option value="Fechado">Fechado</option>
                                <option value="Nao Fechou">Não Fechou</option>
                                <option value="Aguardando Cliente">Aguardando Cliente</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="submit" value="Filtrar" name="submit" class="btn btn-primary">
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table" style="white-space: nowrap">
                        <thead>
                        <th>Nº</th>
                        <th>Status</th>
                        <th>Preço</th>
                        <th>Tipo de Carga</th>
                        <th>KM</th>
                        <th>Veículo</th>
                        <th>Origem x Destino</th>
                        <th>Peso</th>
                        <th>Observação</th>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="msg"></div>

<?php $this->start("script"); ?>
    <script>
        $(function () {

            var empresas = [];
            var id = [];
            var pag = [];

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

            $(".frete").change(function (e) {
                if($(this).val() === "fob") {
                    $(".cliente_fob").prop("disabled", false);
                    $(".cliente_fob").toggleClass("active");
                } else {
                    $(".cliente_fob").prop("disabled", true);
                    $(".cliente_fob").toggleClass("active");
                }
            })

            $('.empresas').on("keyup", function () {
                var data = $('.empresas').data();

                $.post(data.action, $(".main-form").serialize(), function (success) {
                    empresas = success;

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

            $(".empresas").on("blur", function (e) {

                var data = $('.empresas').data();

                $.post(data.action, $(".main-form").serialize(), function (success) {

                    empresas = success
                    pag = empresas[1]
                    id = empresas[2]

                }, "json")

                $(".pagamento").prop("value", pag[0])

                if(id.length === 1) {

                    $("#editar-empresa").prop("href", `https://www.petrotrans.com.br/sistema/empresa/edit/${id}`)
                    $("#editar-empresa").css({"color": "#32CD32", "text-shadow": "2px 2px 2px rgba(206,89,55,0.4)"});

                } else {
                    $("#editar-empresa").prop("href", `#`)
                    $("#editar-empresa").css("color", "grey");
                }
            })

            $(".cliente_fob").on("keyup", function () {
                var data = $('.cliente_fob').data();

                $.post(data.action, $(".main-form").serialize(), function (success) {

                    empresas = success

                }, "json")

                $.typeahead({
                    input: '.cliente_fob',
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

            var acoalho = $('.acoalho-options');
            var ajudantes = $('.ajudantes-options');
            var coleta = $('.data-coleta');


            $("input[type=checkbox]").click(function (e) {

                switch (this.name) {
                    case "acoalho":

                        acoalho.toggleClass("active")

                        if(this.checked === true) {
                            acoalho.prop("disabled", false)
                        } else {
                            acoalho.prop("disabled", true)
                        }

                        break

                    case "ajudantes":

                        ajudantes.toggleClass("active")

                        if(this.checked === true) {
                            ajudantes.prop("disabled", false)
                        } else {
                            ajudantes.prop("disabled", true)
                        }

                        break

                    case "coleta":

                        coleta.toggleClass("active");

                        if(this.checked === true) {
                            coleta.prop("disabled", false);
                        } else {
                            coleta.prop("disabled", true)
                        }

                        break
                }

            })

            $(".editar-pagamento").click(function (e) {
                e.preventDefault();

                $(".pagamento").prop("disabled", false);
            })

            $(".filter-form").submit(function (e) {
                e.preventDefault();

                $(".raio-form").prop("value", $(".raio-filter").val());
                $(".status-main").prop("value", $(".status-analise").val());

                var data = $(this).data();

                $.post(data.action, $(".main-form").serialize(), function (response) {
                    $("tbody").html(response);
                }, "text")
            })

            /*
            $(".main-form").submit(function (e) {
                e.preventDefault();

                var data = $(this).data();
                console.log($(this).serialize())

                $.post(data.action, $(this).serialize(), function(response) {
                    $(".msg").addClass("on");
                    $(".msg").html(response.message);

                    $(".pagamento").prop("disabled", true);

                    window.location.href = "<?= $router->route('Main.table') ?>"
                }, "json");
            })
            */

            $(".kilometer-input").on("keyup", function (e) {
                var data = $(this).data();

                $.post(data.action, $("form").serialize(), function (response) {
                    $("tbody").html(response);
                }, "text")
            })

            $(".tipo-input").change(function (e) {
                var data = $(this).data();

                $.post(data.action, $("form").serialize(), function (response) {
                    $("tbody").html(response);
                }, "text")
            })

            $(".nota").on("keyup", function (e) {

                let value = this.value;
                let value1 = ((value/100) * 0.02);
                let value2 = ((value/100) * 0.018);

                $(".seguro1").html(`RCTR-C: ${value1.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"})}`)
                $(".seguro2").html(`RCF-DC: ${value2.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"})}`)

            })
            
            $(".items-close").click(function (e) {

            let id = this.classList[1];

            $(`.items-open.${id}`).toggleClass("active");
            $(`.items.${id}`).toggleClass("active");
        })
        $(".delete").click(function (e) {
            e.preventDefault();

            var data = $(this).data();
            let id = this.classList[1];

            $.post(data.action, {"id": id}, function (result) {
                alert(result);
                window.location.reload();
            }, "json")
        })

        $(".mail").click(function (e) {
            e.preventDefault();

            var data = $(this).data();
            var id = this.classList[1];

            $.post(data.action, {'id': id}, function (result) {
                alert(result);
                window.location.reload();
            }, 'json');
        })

        })
    </script>
<?php $this->stop(); ?>
