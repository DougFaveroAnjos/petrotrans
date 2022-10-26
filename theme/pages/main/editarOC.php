<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Editar Ordem de Coleta</h5>
            </div> <!-- cardheader -->

            <div class="card-body">

                <form method="post" action="<?= $router->route('Coletas.edit', ['id' => $coleta->id]) ?>">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Motorista</label>
                            <select class="form-control" name="motorista" id="motorista">
                                <?php foreach ($motoristas as $motorista):
                                    if($motorista->name === $coleta->motorista): ?>
                                        <option value="<?= $motorista->name ?>" selected><?= $motorista->name ?></option>
                                    <?php else: ?>
                                        <option value="<?= $motorista->name ?>"><?= $motorista->name ?></option>
                                <?php endif; endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Data de Coleta</label>
                                <input value="<?= $transporte->data_liberacao ?>" value="Data de Coleta (transporte)" class="form-control" name="coleta" type="datetime-local">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Valor do Motorista</label>
                                <input value="<?= $coleta->valor ?>" class="form-control" name="valor" type="number" step="0.01">
                             
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Endereço de Coleta</label>
                                <input value="<?= $coleta->endereco_coleta ?>" value="Endereço para Coleta" type="text" class="form-control" name="endereco_coleta" id="endereco_coleta">
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Endereço de Entrega</label>
                                <input value="<?= $coleta->endereco_entrega ?>" value="Endereço para Entrega" type="text" class="form-control" name="endereco_entrega" id="endereco_entrega">
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
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Emails para Envio (Separar com "; ")</label>
                                <textarea class="form-control" value="email@exemplo.com; email2@exemplo.com"
                                          name="emails" id="emails" cols="30" rows="10"><?= $coleta->emails ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telefones para Envio (Separar com "; ")</label>
                                <textarea class="form-control" value="01999999999; 01888888888"
                                          name="telefones" id="telefones" cols="30" rows="10"><?= $coleta->telefones ?></textarea>
                            </div>
                        </div>
                    </div><!-- dados -->

                    <div class="row">
                        <div class="col-md-12">
                            <label>Descrição da O.C</label>
                            <textarea value="Descrição para a Ordem de Coleta" class="form-control"
                                      name="obs_oc" id="obs_oc" cols="30" rows="10"><?= $coleta->obs ?></textarea>
                        </div>
                    </div><!-- descrição oc --><br>
                    
                     <div class="row">
                            <div class="col-md-4">
                                <h5>Forma de Pagamento</h5>
                            </div>

                   
                        </div><!-- DADOS BANCARIOS -->
                        
                        <br>
                    
                    <div class="row justify-content-between">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Valor Acordado Motorista</label>
                                <input required type="number" class="form-control" name="valor" id="valor" value="<?= $coleta->valor ?>">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                
                                <div class="descontos">
                                    <input value="Desconto" type="text" class="form-control desconto" name="desconto" id="desconto" value="<?= $coleta->desconto ?>">
                                    <input value="Motivo" type="text" class="form-control desconto" name="motivo_desconto" id="motivo" value="<?= $coleta->motivo_desconto ?>">
                                </div>
                                
                                

                                
                            </div>
                        </div>
                        
                       

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Forma de Pagamento</label>
                                <select name="pagamento" id="pagamento" class="form-control">
                                    <option <?php if($coleta->pagamento === "adiamento" || $coleta->pagamento === "adiantamento"): echo "selected"; endif; ?> value="adiantamento">
                                        Adiantamento
                                    </option>
                                    <option <?php if($coleta->pagamento === "vista"): echo "selected"; endif; ?> value="vista">
                                        A Vista
                                    </option>
                                    <option <?php if($coleta->pagamento === "cheque"): echo "selected"; endif; ?> value="cheque">
                                        Cheque
                                    </option>
                                </select>
                            </div>
                        </div>

                    </div>
                    
                     <div class="row justify-content-end">
                            <div class="col-md-2 porcentagem">
                                <div class="form-group">
                                    <label>Porcentagem do Adiantamento:</label>
                                    <input disabled value="%" type="text" name="porcento" id="porcentagem" class="form-control porcentagem motorista-input">
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
                                    <input disabled type="text" name="cheque" id="cheque" class="form-control cheque" value="Quantidade de Dias">
                                </div>
                            </div>
                        </div><!-- Quarta Linha -->

                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea name="obs" class="form-control motorista-input" id="obs" value="Observação do Motorista"></textarea>
                                </div>
                            </div>
                        </div><!-- OBS -->



                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <button type="submit" class="btn form-control" name="submit" value="Editar Ordem de Coleta" style="margin-top: 10px;">Editar O.C</button>
                        </div>
                    </div><!-- submit button -->
                </form>

            </div><!-- cardbody -->
        </div>
    </div>

</div>

<?php $this->start("script") ?>
<script>

    $(function () {

        $(".desconto").prop("disabled", false);
        $(".descontos").addClass("active");
        

        $("#desconto").on("blur", function (e) {

            var valor = $("#valor").val()

            $("#valor").val(valor - $(this).val())

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
