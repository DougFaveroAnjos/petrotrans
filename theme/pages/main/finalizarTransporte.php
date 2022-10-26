<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <h4>Finalizar Transporte</h4>
            </div>

            <form enctype="multipart/form-data" action="<?= $router->route('Transportes.liberacao') ?>" method="post">
                <input type="hidden" name="liberacao" value="finalizado">
                <input type="hidden" name="id" value="<?= $transporte->id ?>">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Data de Finalização</label>
                            <input required type="date" name="data_finalizado" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row"><div class="col-md-12"><label>Anexar Arquivos</label></div></div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <input id="arquivo" accept="image/jpeg, image/jpg, image/png, application/pdf" type="file" name="anexos[]" multiple="multiple">
                    </div>
                </div>

                <div class="row"><div class="col-md-12"><h5>Anexos:</h5></div></div>
                <div class="row" id="lista">
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-3">
                        <input id="submit" type="submit" class="form-control" name="submit" value="Finalizar" style="border: 2px solid #f96332; font-weight: bold; padding: .8rem 1rem;margin-top: 2rem">
                    </div>
                </div>
            </div>

            </form>
        </div>

    </div>
</div>

<?php $this->start("script") ?>

<script>
    $(function () {

        $("#arquivo").change(function(e) {
            var files = $(this)[0].files;

            $.each(files, function (index, file) {

                var fileReader = new FileReader();

                fileReader.onload = function (e) {
                    var div = `<div class="col-md-2"> <span>${file.name}</span> </div>  `

                    $("#lista").append(div);
                }

                fileReader.readAsDataURL(file);
            })

        })

    })
</script>

<?php $this->stop() ?>
