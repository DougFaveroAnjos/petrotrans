<?php $this->layout("_theme", ["title" => $title]) ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">Editar Empresa</h5>
                </div>
                <div class="card-body">
                    <form enctype="multipart/form-data" action="<?= $router->route('Empresas.edit'); ?>" method="post" data-action="<?= $router->route('Empresas.edit'); ?>">
                        <input type="hidden" name="id" value="<?= $empresa->id ?>">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Vendedor</label>
                                    <select class="form-control" name="vendedor_id" id="vendedor_id">
                                        <option value="não selecionado">Selecione Vendedor</option>

                                        <?php foreach ($users as $user): ?>

                                            <option <?php if($user->id == $empresa->vendedor_id): echo 'selected'; endif; ?> value="<?= $user->id ?>"><?= $user->first_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        <div class="col-md-4">
                                <div class="form-group">
                                    <label>CNPJ</label>
                                    <input type="text" class="form-control" value="<?= $empresa->cnpj ?>" name="cnpj" id="cnpj" data-action="<?= $router->route('Empresas.consulta') ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nome da Empresa</label>
                                    <input id="name" type="text" class="form-control" value="<?= $empresa->name ?>" name="empresa_name">
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>Responsável</label>
                                    <input type="text" class="form-control" value="<?= $empresa->responsavel ?>" name="responsavel">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label> Telefone </label>
                                    <input class="form-control" name="contato" value="<?= $empresa->contato ?>" type="text">
                                </div>
                            </div> -->
                        </div>
                        
                  
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <input type="text" name="uf" id="uf" class="form-control" value="<?= $empresa->estado ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <input type="text" name="city" id="city" class="form-control" value="<?= $empresa->cidade ?>">
                                </div>
                            </div>

                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input id="email" type="text" class="form-control" value="<?= $empresa->email ?>" name="email">
                                </div>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Rua</label>
                                    <input type="text" name="rua" id="rua" class="form-control" value="<?= $empresa->rua ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Complemento</label>
                                    <input type="text" name="complemento" id="complemento" class="form-control" value="<?= $empresa->complemento ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Forma de Pagamento</label>
                                    <input type="text" class="form-control" value="<?= $empresa->pagamento ?>" name="pagamento">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>SPC</label>
                                    <select name="spc" class="form-control spc" id="spc">
                                        <option value="sem restrição" <?php if($empresa->spc === "sem restrição"): echo "selected"; endif ?>>Sem Restrição</option>
                                        <option value="com restrição" <?php if($empresa->spc === "com restrição"): echo "selected"; endif ?>>Com Restrição</option>
                                        <option value="a verificar" <?php if($empresa->spc === "a verificar"): echo "selected"; endif ?>>A Verificar</option>
                                    </select>
                                </div>
                            </div>
                             
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control status" name="status" id="status">
                                        <option value="cliente" <?php if($empresa->status === "cliente"): echo "selected"; endif ?>>Cliente</option>
                                        <option value="não cliente" <?php if($empresa->status === "não cliente"): echo "selected"; endif ?>>Não Cliente</option>
                                        <option value="provável cliente" <?php if($empresa->status === "provável cliente"): echo "selected"; endif ?>>Provável Cliente</option>
                                        <option value="improvável cliente" <?php if($empresa->status === "improvável cliente"): echo "selected"; endif ?>>Improvável Cliente</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                <label>Informações Importantes</label>
                            <input type="text" value="<?= $empresa->importante ?>" name="importante" class="form-control" id="importante" cols="30" rows="10">
                                </div>
                            </div>
                        </div>  
                        
                       
                        <h4>Novo Contato</h4>

                      
                        <div class="row">
                          <div class="col-md-3">
                             <div class="form-group">
                            
                             <label>Contato</label>
                                 <div class="contato-origem">
                                     <input type="text" name="contato-origemnovo" id="contatoorigemnovo" class="form-control contatoorigem">
                                 </div>
                             </div>
                         </div>
                         <div class="col-md-2">
                             <div class="form-group">
                             <label>Cargo</label>
                                 <div class="cargo-origem">
                                     <input type="text" style="width: 200px"name="cargo-origemnovo" id="cargoorigemnovo" class="form-control cargoorigemnovo">
                                 </div>
                             </div>
                         </div>
                          <div class="col-md-3">
                             <div class="form-group">
                             <label>E-mail</label>
                                 <div class="email-origemnovo">
                                     <input type="text" name="email-origemnovo" id="emailorigemnovo" class="form-control emailorigemnovo">
                                 </div>
                             </div>
                         </div>
                         <div class="col-md-2">
                             <div class="form-group">
                             <label>Telefone</label>
                                 <div class="telefone-origemnovo">
                                     <input type="text"  style="width: 160px"name="telefone-origemnovo" id="telefoneorigemnovo" class="form-control telefoneorigemnovo">
                                 </div>
                             </div>
                         </div>
                        
                       <br>
                      
                        </div>
                        <?php if(isset($contatos)): ?>
                            <h6>____________________________________________________________________________</h6><br>
                         <h4>Contatos Cadastrados</h4>
                         <?php endif;?>
                         <div class="row">
                   
                    <?php if($contatos): foreach ($contatos as $key => $contato): ?>
                        <?php if(count($contatos) >= 0): ?>
                         <div class="col-md-3">
                            <div class="form-group">
                            <?php if($key == 0): ?>
                              
                                <?php endif;?>

                                <div class="contato-origem<?=$key?>">
                                    <input type="text" name="contato-origem<?=$key?>" id="contatoorigem<?=$key?>" class="form-control contatoorigem<?=$key?>" value="<?=$contatos[$key]->responsavel?>" >
                                </div>
                            </div>
                        </div>
                         <input type="hidden" name="contato_id<?=$key?>" id="contato_id<?=$key?>" class="form-control" value="<?=$contatos[$key]->id?>">
                        <div class="col-md-2">
                            <div class="form-group">
                            <?php if($key == 0): ?>
                              
                                <?php endif;?>
                                <div class="cargo-origem">
                                    <input type="text" style="width: 200px" name="cargo-origem<?=$key?>" id="cargoorigem<?=$key?>" class="form-control cargoorigem<?=$key?>" value="<?=$contatos[$key]->cargo?>">
                                </div>
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="form-group">
                            <?php if($key == 0): ?>
                               
                                <?php endif;?>
                                <div class="email-origem<?=$key?>">
                                    <input type="text" name="email-origem<?=$key?>" id="emailorigem<?=$key?>" class="form-control emailorigem<?=$key?>" value="<?=$contatos[$key]->email?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                            <?php if($key == 0): ?>
                               
                                <?php endif;?>
                                <div class="telefone-origem<?=$key?>">
                                    <input type="text" style="width: 160px" name="telefone-origem<?=$key?>" id="telefoneorigem<?=$key?>" class="form-control telefoneorigem<?=$key?>" value="<?=$contatos[$key]->contato?>">
                                    
                                </div>
                               
                            </div>
            
                            
                        </div>
                        <div class="col-md-1">
                        <div class="form-group">
                            <?php if($key == 0): ?>
                               
                                <?php endif;?>
                                <div class="telefone-origem<?=$key?>">
                                <a href="#" class="items-close <?= $contatos[$key]->id ?> mail" data-action="<?= $router->route('Contato.delete', ['id'=>$contatos[$key]->id]) ?>"><img src="https://findicons.com/files/icons/1262/amora/128/delete.png" style="width: 30px"></a>
                    
                                </div>
                             
                            </div>
                            </div>
                       
                          <?php endif;?>
                        
                                        <?php endforeach; endif;?>

                       
                    
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-md-3">
                                <input type="submit" class="form-control" name="submit" value="Editar Empresa" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
             
                </form>
            </div>
        </div>
    </div>
</div>-->
               
                
                <br>
               <!-- <div class="table-responsive">
                    <table class="table" style="white-space: nowrap">
                        <thead>
                        <th>Nome</th>
                        <th>Celular</th>
                        <th>E-mail</th>
                        <th>Cargo</th>
                        <th>Operações</th>
                       
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>-->
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
                console.log($(this).serialize())

                $.post(data.action, $(this).serialize(), function(response) {

                    alert(response);

                    window.location.href = "<?= $router->route('Main.empresas') ?>"
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
                        $("#complemento").prop("value", result.numero);
                        $("#rua").prop("value", `${result.logradouro} - ${result.bairro} - ${result.cep}`);

                    }, "json")
                }
            })
        })
        
        $("#cnpjempresa").keydown(function(){
    try {
        $("#cnpjempresa").unmask();
    } catch (e) {}

    var tamanho = $("#cnpjempresa").val().length;

    if(tamanho < 11){
        $("#cnpjempresa").mask("999.999.999-99");
    } else {
        $("#cnpjempresa").mask("99.999.999/9999-99");
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
        $(".items-close").click(function (e) {

        let id = this.classList[1];

        $(`.items-open.${id}`).toggleClass("active");
        $(`.items.${id}`).toggleClass("active");
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
    </script>
<?php $this->stop(); ?>
