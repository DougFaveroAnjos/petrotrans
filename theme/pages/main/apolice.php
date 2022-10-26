<?php $this->layout("_theme", ["title" => $title]) ?>
<style>
    .assinatura{
        width: 100%;
        height: 200px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title"><?= $title ?></h5>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" action="<?= $router->route('Main.apolice'); ?>" method="post" data-action="<?= $router->route('Main.apolice'); ?>">
                    <input type="hidden" name="tipo" value="create">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>E-mails</label>
                                <div class="contato-origem">
                                    <input type="text" name="emails" class="form-control" placeholder="exemplo@exemplo.com.br; exemplo@exemplo.com.br">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Assunto</label>
                                <div class="contato-origem">
                                    <input type="text" name="assunto" class="form-control" placeholder="Assunto">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>E-mail</label>
                                <textarea name="signature"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <h6> Anexos </h6>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="row">

                                    <input name="anexos" class="anexos" type="hidden" value="<?= implode(';', $mail['anexos']) ?>">

                                    <?php
                                    if($mail['anexos']):
                                        foreach ($mail['anexos'] as $key => $anexo):
                                            ?>


                                            <div class="col-md-6 box-<?= $key ?>">

                                                <b><?= pathinfo($anexo)['filename'] ?>.pdf</b><br>

                                                <a class="deletar" data-id="<?= $key ?>" data-path="<?= $anexo ?>" href="#">Excluir</a>
                                                <a target="_blank" href="<?= url($anexo) ?>">Abrir</a>

                                            </div>


                                        <?php endforeach; endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <input type="submit" class="form-control" name="submit" value="Enviar e-mail" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="msg"></div>

<?php $this->start('script'); ?>
<script>
    $(function () {
        $(".deletar").click(function (e) {
            id = $(this).attr("data-id");// ID
            url = $(this).attr("data-path");//Path arquivo
            anexos = $(".anexos").val();
            $(".anexos").val(anexos.replace(url, ""));
            $(".box-"+id).hide();
        })
    })
</script>
<?php $this->stop(); ?>
