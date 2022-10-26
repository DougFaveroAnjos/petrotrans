<?php $this->layout("_theme", ["title" => $title]) ?>

<style>

    .mass,
    .normal,
    .ddd,
    .input-data{
        display: none;
    }

    .mass.active,
    .normal.active,
    .ddd.active,
    .input-data.active{
        display: flex;
    }

</style>

<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="row justify-content-center">
                <div class="col-md-auto">
                    <button type="button" id="normal" class="btn btn-primary" style="margin-top:10px">Envio Normal</button>
                </div>

                <div class="col-md-auto">
                    <button type="button" id="mass" class="btn btn-primary" style="margin-top:10px">Envio em Massa</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"> <h4>Envio Normal</h4> </div>

            <div class="card-body">
                <form id="email-form" enctype="multipart/form-data" method="post" data-action="<?= $router->route('Mailer.send') ?>" action="<?= $router->route('Main.whatsappSend') ?>">
                    <div class="row justify-content-center normal active">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="emails"> Whatsapps </label>
                                <input
                                        placeholder="99999999999; 99999999999 (Separar com '; ')"
                                        id="whatsapps" type="text" name="whatsapps" class="form-control normal active"
                                        style="border: 1px solid #f96332"
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
                                    <option value="motorista">Motorista</option>
                                    <option value="all">Todos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center input-data">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="de">De: </label>
                                        <input style="border: 1px solid #f96332; border-radius: 20px; max-height: 20rem" placeholder="11" type="date" name="de"  value="<?= date("Y-m-d", strtotime("-1 day")) ?>" class="form-control input-data active" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ate">Até: </label>
                                        <input style="border: 1px solid #f96332; border-radius: 20px; max-height: 20rem" placeholder="11" value="<?= date("Y-m-d") ?>" type="date" name="ate" class="form-control input-data active" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center ddd">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="emails"> DDD do motista </label>
                                <input style="border: 1px solid #f96332; border-radius: 20px; max-height: 20rem" placeholder="11" type="number" maxlength="2" max="99" name="ddd" class="form-control input-data active">
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="corpo">CORPO</label>
                                <textarea
                                        style="border: 1px solid #f96332; border-radius: 20px; max-height: 20rem"
                                        class="form-control" name="conteudo" cols="30" rows="100"
                                        placeholder="Corpo do whatsapp" required
                                ></textarea>
                            </div>
                        </div>
                    </div>



                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <button id="submit" type="submit" class="btn form-control" name="submit" value="Enviar" style="margin-top:10px">Enviar Mensagem</button>
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

                $(".ddd").removeClass("active");
                $(".ddd").prop("disabled", true);

                $(".input-data").removeClass("active");
                $(".input-data").prop("disabled", true);

                $("#destiny").val("normal");

            })

            $("#mass").click(function (e) {

                $(".mass").addClass("active");
                $(".mass").prop("disabled", false);

                $(".normal").removeClass("active");
                $(".normal").prop("disabled", true);

                $("#destiny").val("mass");

            })

            $("#contatos").change(function(){
                // Aqui você tem o value selecionado assim que o usuário muda o option
                var id = $(this).val();
                if(id == 'motorista'){
                    $(".ddd").addClass("active");
                    $(".ddd").prop("disabled", true);
                }else{
                    $(".ddd").removeClass("active");
                    $(".ddd").prop("disabled", true);

                    $(".input-data").addClass("active");
                    $(".input-data").prop("disabled", false);
                }

            });
        })
    </script>
<?php $this->stop(); ?>