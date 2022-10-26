<?php $this->layout("_theme", ["title" => $title]) ?>

<style>

    .mass,
    .normal {
        display: none;
    }

    .mass.active,
    .normal.active {
        display: flex;
    }

</style>

<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="row justify-content-center">
                <div class="col-md-auto">
                    <button type="button" id="normal" class="btn btn-primary">Envio Normal</button>
                </div>

                <div class="col-md-auto">
                    <button type="button" id="mass" class="btn btn-primary">Envio em Massa</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"> <h4>Envio Normal</h4> </div>

            <div class="card-body">
                <form id="email-form" enctype="multipart/form-data" method="post" data-action="<?= $router->route('Mailer.send') ?>" action="<?= $router->route('Mailer.send') ?>">
                    <input type="hidden" name="type" value="<?= $data['type'] ?>">
                    <input type="hidden" id="destiny" name="destiny" value="normal">
                    <input type="hidden" id="id" name="id" value="<?= $data['id'] ?>">

                    <div class="row justify-content-center normal active">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="emails"> EMAILS </label>
                                <input
                                        placeholder="email@provedor.com; email2@provedor.com (Separar com '; ')"
                                        id="emails" type="text" name="emails" class="form-control normal active"
                                        style="border: 1px solid #f96332"

                                        <?php if($data['type'] !== 'Petrotrans'): ?>
                                        value="<?= $mail['email'] ?>"
                                        <?php endif; ?>

                                >
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center mass">
                        <div class="col-md-5">
                            <div class="form-group">
                                <select name="contatos" id="contatos" class="form-control mass">
                                    <option value="none" selected> Tipo de Contato </option>

                                    <option value="não cliente">Não Clientes</option>
                                    <option value="provável cliente">Provaveis Clientes</option>
                                    <option value="cliente">Clientes</option>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="assunto"> ASSUNTO </label>
                                <input
                                        placeholder="Assunto do Email"
                                        id="assunto" type="text" name="assunto" class="form-control"
                                        style="border: 1px solid #f96332"

                                    <?php if($data['type'] !== 'Petrotrans'): ?>
                                        value="<?= $mail['assunto'] ?>"
                                    <?php endif; ?>
                                >
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="corpo">CORPO</label>
                                <textarea
                                        style="border: 1px solid #f96332; border-radius: 20px; max-height: 20rem"
                                        class="form-control" name="corpo" id="corpo" cols="30" rows="100"
                                        placeholder="Corpo do Email"
                                ><?php if($data['type'] !== 'Petrotrans'): echo $mail['msg']; endif; ?></textarea>
                            </div>
                        </div>
                    </div>



                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <input id="submit" type="submit" class="form-control" name="submit" value="Enviar" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<?php $this->start('script'); ?>
    <script>
        $(function () {

            $("#normal").click(function (e) {

                $(".mass").removeClass("active");
                $(".mass").prop("disabled", true);

                $(".normal").addClass("active");
                $(".normal").prop("disabled", false);

                $("#destiny").val("normal");

            })

            $("#mass").click(function (e) {

                $(".mass").addClass("active");
                $(".mass").prop("disabled", false);

                $(".normal").removeClass("active");
                $(".normal").prop("disabled", true);

                $("#destiny").val("mass");

            })
        })
    </script>
<?php $this->stop(); ?>