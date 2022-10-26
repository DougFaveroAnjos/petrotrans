<?php $this->layout("_theme", ["title" => $title]) ?>

<?php
    if(empty($userE->empresa)):
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <iframe src="https://www.qualp.com.br/" style="min-height: 110vh; min-width: 100%; outline: none; border: none"></iframe>
            </div>
        </div>
    </div>
</div>
<?php
    endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Criar Cotação</h5>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" class="main-form" action="<?= $router->route('Cotacoes.new'); ?>" method="post" data-action="<?= $router->route('Cotacoes.new'); ?>">
<!--
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Vendedor</label>
                                <select class="form-control" name="vendedor" id="usuario">
                                    <option value="não selecionado">Selecione Vendedor</option>

                                    <?php foreach ($users as $user): ?>

                                        <option value="<?= $user->first_name ?>"><?= $user->first_name ?></option>

                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
!-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p><label>Haverá devolução?</label></p>
                                <label><input checked type="radio" class="form-control" name="devolucao" value="n" required><span>Não</span></label>
                                <label><input type="radio" class="form-control" name="devolucao" value="container" required><span>Container</span></label>
                            </div>
                        </div>
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
                        <?php
                            if(empty($userE->empresa)):
                        ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Empresa</label> <a target="_blank" href="<?= $router->route('Empresas.criarEmpresa') ?>" style="color: green; margin-left: 1rem"><i style="color: green" class="fas fa-plus"></i></a>

                                <a id="editar-empresa" target="_blank" href="#" style="color: grey; margin-left: 2rem"><i class="fas fa-pen"></i></a>

                                <div class="typeahead__container">
                                    <input placeholder="Empresa" data-action="<?= $router->route('Empresas.search') ?>" type="text" class="form-control empresas" name="empresa" autocomplete="off" required>
                                </div>

                            </div>
                        </div>
                        <?php
                            endif;
                        ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kilometragem</label>
                                <input data-action="<?= $router->route('Cotacoes.analise') ?>" type="number" class="form-control kilometer-input" placeholder="Km" name="km" required>
                            </div>
                            <input type="hidden" name="raio" class="raio-form">
                            <input type="hidden" name="status" class="status-main">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Opção do Frete</label>
                                <select name="frete" class="form-control frete" required>
                                    <option value="cif">CIF</option>
                                    <option value="fob">FOB</option>
                                </select>

                                <div class="typeahead__container">
                                    <input disabled type="text" placeholder="Empresa FOB" name="cliente_fob" class="form-control cliente_fob" data-action="<?= $router->route('Empresas.search') ?>" autocomplete="off">
                                </div>

                                <div class="add-fob" style="text-align: center">
                                    <span>Empresa não Registrada. <a target="_blank" href="<?= $router->route('Empresas.criarEmpresa') ?>" style="color: green; margin-left: 1rem"><i class="fas fa-plus"></i></a> </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cidade Origem</label> <a href="#" style="color: green; margin-left: 1rem" class="remove-origem"><i class="fas fa-minus"></i></a> <a href="#" style="color: green; margin-left: 1rem" class="add-origem"><i class="fas fa-plus"></i></a>

                                <div class="city-origem">
                                    <input type="text" name="city-origem" id="cityorigem" class="form-control cityorigem" placeholder="Cidade" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado Origem</label>
                                <div class="uf-origem">
                                    <input type="text" name="uf-origem" id="uforigem" class="form-control uforigem" placeholder="Estado" required>
                                </div>
                            </div>

                            <input type="hidden" name="origem" class="origem">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cidade Destino</label> <a href="#" style="color: green; margin-left: 1rem" class="remove-destino"><i class="fas fa-minus"></i></a> <a href="#" style="color: green; margin-left: 1rem" class="add-destino"><i class="fas fa-plus"></i></a>

                                <div class="city-destino">
                                    <input type="text" name="city-destino" id="citydestino" class="form-control citydestino" placeholder="Cidade" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado Destino</label>

                                <div class="uf-destino">
                                    <input type="text" name="uf-destino" id="ufdestino" class="form-control ufdestino" placeholder="Estado" required>
                                </div>
                            </div>

                            <input type="hidden" name="destino" class="destino">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Veículo</label>
                                <input type="text" class="form-control" placeholder="Veículo" name="veiculo" required>
                                <input type="text" class="form-control" placeholder="Carroceria (opcional)" name="carroceria" style="margin-top: 6px">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Volumes</label>
                                <input type="text" class="form-control" placeholder="Volumes" name="volumes" required>
                                <input type="text" class="form-control" placeholder="Tipo de Mercadoria" name="tipo_mercadoria" style="margin-top: 6px">

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Peso</label>
                                <input type="text" class="form-control" placeholder="Peso" name="peso" required>
                            </div>
                        </div>

                        <?php
                            if(empty($userE->empresa)):
                        ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo de Carga</label>
                                <select data-action="<?= $router->route('Cotacoes.analise') ?>" name="tipo" class="form-control tipo-input" required>
                                    <option value="dedicada">Dedicada</option>
                                    <option value="fracionada">Fracionada</option>
                                </select>
                            </div>
                        </div>
                        <?php
                            endif;
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data de Coleta</label>
                                <div class="coleta-div" style="display: flex;">
                                    <input type="checkbox" name="coleta" class="form-control coleta" style="margin: .5rem; max-width: 1rem">
                                    <input type="text" class="form-control data-coleta" placeholder="Data" name="data_coleta" style="width: 85%" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Previsão de Entrega</label>
                                <input type="text" class="form-control previsao time" placeholder="Previsão" name="previsao" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Prazo Maximo de Entrega</label>
                                <input type="text" class="form-control prazo time" placeholder="Prazo" name="prazo" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Cobrança</label>
                                <div class="coleta-div" style="display: flex;">
                                    <label><input type="radio" class="form-control" name="cobranca" value="S" checked>Sim</label>
                                    <label><input type="radio" class="form-control" name="cobranca" value="N">Não</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Dias (cobrança)</label>
                                <input type="number" class="form-control" name="cobranca_days" value="5">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data (cobrança)</label>
                                <input type="date" class="form-control" name="cobranca_date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Valor da Nota</label>
                                <input type="number" step="0.01" class="form-control nota" placeholder="Valor" name="nota" required>
                                <div class="seguros" style="display: flex; margin-top: 1rem; gap: 4rem">
                                   <span><b class="seguro1">RCTR-C: R$ 0</b></span>
                                    <span><b class="seguro2">RCF-DC 2: R$ 0</b></span>
                                </div>
                            </div>
                        </div>
                        <?php
                            if(empty($userE->empresa)):
                        ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Valor da Cotação</label>
                                <input step="0.01" type="number" class="form-control" placeholder="Valor" name="valor" required>
                            </div>
                        </div>
                        <?php
                            endif;
                        ?>

                        <?php
                            if(empty($userE->empresa)):
                        ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Valor do Motorista</label>
                                <div class="valores-motoristas" style="display: flex; gap: 1rem">
                                    <input class="form-control" step="0.01" placeholder="Entre" type="number" name="valor_motorista_min">
                                    <input class="form-control" step="0.01" placeholder="A" type="number" name="valor_motorista_max">
                                </div>
                            </div>
                        </div>
                        <?php
                            endif;
                        ?>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Forma de Pagamento</label> <a href="#" class="editar-pagamento" style="margin-left: 1rem">Editar</a>
                                <input type="text" class="form-control pagamento" name="pagamento" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descrição da Cotação</label>
                                <textarea style="max-height: 160px" name="obs" rows="8" cols="80" class="form-control" placeholder="Descrição"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2" style="text-align: center">
                            <label> Lona </label>
                            <input type="checkbox" name="lona" class="form-control">
                        </div>
                        <div class="col-md-2" style="text-align: center">
                            <label> Cintas </label>
                            <input type="checkbox" name="cintas" class="form-control">
                        </div>
                        <div class="col-md-2" style="text-align: center">
                            <label> Açoalho </label>
                            <input type="checkbox" name="acoalho" class="form-control">
                            <select disabled="disabled" class="acoalho-options form-control" name="acoalho-options">
                                <option value="ferro">Ferro</option>
                                <option value="madeira">Madeira</option>
                            </select>
                        </div>
                        <div class="col-md-2" style="text-align: center">
                            <label> Ajudantes </label>
                            <input type="checkbox" name="ajudantes" class="form-control">
                            <label class="ajudantes-options">Coleta</label>
                            <input disabled="disabled" type="number" value="0" name="coleta" class="ajudantes-options form-control">
                            <label class="ajudantes-options">Destino</label>
                            <input disabled="disabled" type="number" value="0" name="destino" class="ajudantes-options form-control">
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <input type="submit" class="form-control submit-button" name="submit" value="Criar Cotação" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
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
                       <input class="raio-filter form-control" type="number" placeholder="Raio (km):" data-action="<?= $router->route('Cotacoes.analise') ?>">
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
        var pag = [];
        var id = [];
        var acoalho = $('.acoalho-options');
        var ajudantes = $('.ajudantes-options');
        var coleta = $('.data-coleta');

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

        $(".cliente_fob").on("keyup", function () {
            var data = $(this).data();

            $.post(data.action, {"empresa": $(this).val()}, function (success) {

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

        $(".cliente_fob").on("blur", function (e) {

            var data = $(this).data();

            $.post(data.action, {"empresa": $(this).val()}, function (success) {

                empresas = success

            }, "json")


            if(empresas[0][0] === "Nenhuma Empresa Encontrada") {
                $(".add-fob").addClass("active");
            } else {
                $(".add-fob").removeClass("active");
            }
        })

        $(".empresas").on("blur", function (e) {

            var data = $('.empresas').data();
            $(".submit-button").prop("disabled", false);

            $.post(data.action, $(".main-form").serialize(), function (success) {

                empresas = success
                pag = empresas[1]
                id = empresas[2]

            }, "json")

            $(".pagamento").prop("placeholder", pag[0])

            if(empresas[0][0] === "Nenhuma Empresa Encontrada") {
                alert("Nenhuma Empresa Encontrada")
                $(".submit-button").prop("disabled", true);
            }

            if(id.length === 1) {

                $("#editar-empresa").prop("href", `https://www.petrotrans.com.br/empresa/edit/${id}`)
                $("#editar-empresa").css({"color": "#32CD32", "text-shadow": "2px 2px 2px rgba(206,89,55,0.4)"});

            } else {
                $("#editar-empresa").prop("href", `#`)
                $("#editar-empresa").css("color", "grey");
            }
        })

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

        $(".submit-button").on("mouseover", function (e) {
            e.preventDefault();

            var origemval = "";
            var destinoval = "";

            $(".cityorigem").each(function (index, value) {

                var text = ` + ${value.value}/${$(`.uforigem.${index}`).val()}`;

                if(index !== 0) {
                    origemval = origemval + text;
                }

            })

            $(".citydestino").each(function (index, value) {

                var text = ` + ${value.value}/${$(`.ufdestino.${index}`).val()}`;

                if(index !== 0) {
                    destinoval = destinoval + text;
                }

            })

            $(".origem").prop("value", origemval);
            $(".destino").prop("value", destinoval);
        })

        $(".kilometer-input").on("keyup", function (e) {
            var data = $(this).data();

            $.post(data.action, $(".main-form").serialize(), function (response) {
                $("tbody").html(response);
            }, "text")
        })

        $(".tipo-input").change(function (e) {
            var data = $(this).data();

            $.post(data.action, $(".main-form").serialize(), function (response) {
                $("tbody").html(response);
            }, "text")
        })

        $(".nota").on("keyup", function (e) {

            let value = this.value;
            let value1 = ((value/100) * 0.025);
            let value2 = ((value/100) * 0.018);

            $(".seguro1").html(`RCTR-C: ${value1.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"})}`)
            $(".seguro2").html(`RCF-DC: ${value2.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"})}`)

        })

        $(".time").on("keyup", function (e) {

            var value = this.value;

            if(value === "24h" || value === "48h" || value === "72h") {
                $(this).prop("value", `${value} - após coleta`);
            }
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

        $(".add-origem").click(function (e) {
            e.preventDefault();

            var city = $("#cityorigem").clone();
            var uf = $("#uforigem").clone();

            city.prop('name', `city-origem ${$(".cityorigem").length}`)
            uf.prop('name', `uf-origem ${$(".uforigem").length}`)
            city.prop('class', `form-control cityorigem ${$(".cityorigem").length}`)
            uf.prop('class', `form-control uforigem ${$(".uforigem").length}`)

            city.appendTo($(".city-origem"));
            uf.appendTo($(".uf-origem"))
        })
        $(".add-destino").click(function (e) {
            e.preventDefault();

            var city = $("#citydestino").clone();
            var uf = $("#ufdestino").clone();

            city.prop('name', `city-destino ${$(".citydestino").length}`)
            uf.prop('name', `uf-destino ${$(".ufdestino").length}`)
            city.prop('class', `form-control citydestino ${$(".citydestino").length}`)
            uf.prop('class', `form-control ufdestino ${$(".ufdestino").length}`)

            city.appendTo($(".city-destino"));
            uf.appendTo($(".uf-destino"))
        })
        $(".remove-origem").click(function (e) {
            e.preventDefault();

            var length = $(".cityorigem").length - 1;

            $(`.cityorigem.${length}`).remove();
            $(`.uforigem.${length}`).remove();
        })
        $(".remove-destino").click(function (e) {
            e.preventDefault();

            var length = $(".citydestino").length - 1;

            $(`.citydestino.${length}`).remove();
            $(`.ufdestino.${length}`).remove();
        })
    })
</script>
<?php $this->stop(); ?>
