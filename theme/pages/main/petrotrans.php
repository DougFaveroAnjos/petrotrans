<?php $this->layout("_theme", ["title" => $title]) ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">Editar Petrotrans</h5>
                </div>
                <div class="card-body">
                    <form enctype="multipart/form-data" action="<?= $router->route('Empresas.edit'); ?>" method="post" data-action="<?= $router->route('Empresas.edit'); ?>">
                        <input type="hidden" name="id" value="<?= $empresa->id ?>">
                        <input type="hidden" name="userid" value="<?= $user->id ?>">
                        <div class="row">pdfapresentacao
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nome da Empresa</label>
                                    <input type="text" class="form-control" value="<?= $empresa->name ?>" name="empresa_name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Responsável</label>
                                    <input type="text" class="form-control" value="<?= $empresa->responsavel ?>" name="responsavel">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" value="<?= $empresa->email ?>" name="email">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <input type="text" name="uf" id="uf" class="form-control" value="<?= $empresa->estado ?>">
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <input type="text" name="city" id="city" class="form-control" value="<?= $empresa->cidade ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label>Rua</label>
                                    <input type="text" name="rua" id="rua" class="form-control" value="<?= $empresa->rua ?>">
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label>Complemento</label>
                                    <input type="text" name="complemento" id="complemento" class="form-control" value="<?= $empresa->complemento ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label>CNPJ</label>
                                    <input type="text" class="form-control" value="<?= $empresa->cnpj ?>" name="cnpj">
                                </div>
                            </div>
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label>Forma de Pagamento</label>
                                    <input type="text" class="form-control" value="<?= $empresa->pagamento ?>" name="pagamento">
                                </div>
                            </div>
                            <div class="col-md-4 pr-1">
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
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nome do Seguro</label>
                                    <input class="form-control" type="text" name="seguro" value="<?php if($empresa->seguro !== null): echo $empresa->seguro; else: echo 'Seguro da Empresa'; endif; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                    <label>Logo do Seguro</label>
                                    <input type="file" name="img" class="img form-control-file">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Assunto</label><br>
                                    <input class="form-control" type="text" name="assunto" value="<?php if($user->assunto !== null): echo $user->assunto; else: echo 'Assunto E-mail'; endif; ?>"> 
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Texto E-mail Apresentação</label><br>
                                    <textarea id="apresentacao" name="apresentacao"><?php if($user->apresentacao !== null): echo $user->apresentacao; else: echo 'E-mail Apresentação'; endif; ?></textarea>
                                    <!-- <input class="form-control" type="text" name="apresentacao" value="<?php if($user->apresentacao !== null): echo $empresa->apresentacao; else: echo 'E-mail Apresentação'; endif; ?>"> -->
                                </div>
                            </div>

                        </div>
                        <div class="row">
                           
                            <div class="col-md-4">
                                    <label>Arquivo Apresentacão</label><br>
                                    <input style="cursor: pointer" id='pdfapresentacao' accept="image/jpeg, image/jpg, image/png, application/pdf" type="file" name="pdfapresentacao">
                            </div>
                        
                        </div><br>
                        <div class="row">
                           <div class="col-md-4">
                                   <label>Assinatura E-mail</label>
                                   <input type="file" name="assinatura" class="img form-control-file">
                           </div>
                       </div>

                        <div class="row justify-content-end">
                            <div class="col-md-3">
                                <input type="hidden" class="form-control" name="petrotrans">
                                <input type="submit" class="form-control" name="submit" value="Editar Empresa" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="msg"></div>

<?php $this->start("script"); ?>

<?php $this->stop(); ?>