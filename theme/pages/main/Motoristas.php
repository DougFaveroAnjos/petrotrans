<?php $this->layout("_theme", ["title" => $title, "motoristas" => $motoristas]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Motoristas</h4>

                <a href="<?= $router->route('Motoristas.cadastrar') ?>"><h6>Cadastrar Motorista</h6></a>
            </div><!-- card header -->

            <div class="card-body">

                <form method="get" class="filter-form">

                    <div class="form-check-inline filter-group form-group nome">
                        <input style="max-width: 15rem" class="filter-group form-control nome" type="text" name="nome" placeholder="Nome do Motorista">
                    </div>

                    <div class="form-check-inline filter-group form-group cpf">
                        <input type="text" name="cpf" class="form-control" placeholder="CPF">
                    </div>

                    <div class="form-check-inline filter-group form-group placa">
                        <input type="text" name="veiculo" class="form-control" placeholder="Placa do Veículo">
                    </div>

                    <div class="form-group form-check-inline" style="margin-bottom: .5rem; margin-left: 1rem">
                        <button type="submit" class="btn btn-primary" name="submit" value="Filtrar">Filtrar</button>
                    </div>


                </form>

                <div class="table-responsive" style="max-height: 80vh; overflow-y: auto; position: static">

                    <table class="table" style="white-space: nowrap; position: static">

                        <thead style="font-size: .8rem; gap: 1rem">
                        <th>
                            #
                        </th>
                        <th>
                            Nome
                        </th>
                        <th>
                            CPF
                        </th>
                        <th>
                            Placa do Veículo
                        </th>
                        <th>
                            Placa de Reboque
                        </th>
                        </thead>


                        <tbody>

                        <?php if(!empty($motoristas)): foreach ($motoristas as $motorista): ?>

                        <tr>
                            <td style="display: flex;  gap: 2rem; align-items: center">
                                <a href="#" class="items-open <?= $motorista->id ?>"> <i class="fas fa-bars"></i> </a>

                                <div class="items <?= $motorista->id ?>">
                                    <a class="items-close <?= $motorista->id ?>" href="#"><i class="fas fa-times"></i></a>

                                    <a href="<?= $router->route('Motoristas.editMotorista', ['id' => $motorista->id]) ?>" target="_blank">Editar</a>

                                    <a class="items-close <?= $motorista->id ?> delete" data-action="<?= $router->route('Motoristas.delete') ?>" href="#">Deletar</a>
                                </div>

                            </td>

                            <td><?= $motorista->name ?></td>
                            <td><?= formata_cpf_cnpj($motorista->cpf) ?></td>
                            <td><?= $motorista->placa_veiculo ?></td>
                            <td><?= $motorista->placa_reboque ?></td>
                        </tr>

                        <?php endforeach; endif; ?>
                        </tbody>

                    </table><!-- table -->

                </div><!-- table div -->

            </div><!-- cardbody -->
            <div class="card-footer" style="display: flex; justify-content: center">
                <?= $pager; ?>
            </div>
        </div>
    </div>
</div>

<?php
function formata_cpf_cnpj($cpf_cnpj){
    /*
        Pega qualquer CPF e CNPJ e formata

        CPF: 000.000.000-00
        CNPJ: 00.000.000/0000-00
    */

    ## Retirando tudo que não for número.
    $cpf_cnpj = preg_replace("/[^0-9]/", "", $cpf_cnpj);
    $tipo_dado = NULL;
    if(strlen($cpf_cnpj)==11){
        $tipo_dado = "cpf";
    }
    if(strlen($cpf_cnpj)==14){
        $tipo_dado = "cnpj";
    }
    switch($tipo_dado){
        default:
            $cpf_cnpj_formatado = "Não foi possível definir tipo de dado";
        break;

        case "cpf":
            $bloco_1 = substr($cpf_cnpj,0,3);
            $bloco_2 = substr($cpf_cnpj,3,3);
            $bloco_3 = substr($cpf_cnpj,6,3);
            $dig_verificador = substr($cpf_cnpj,-2);
            $cpf_cnpj_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."-".$dig_verificador;
        break;

        case "cnpj":
            $bloco_1 = substr($cpf_cnpj,0,2);
            $bloco_2 = substr($cpf_cnpj,2,3);
            $bloco_3 = substr($cpf_cnpj,5,3);
            $bloco_4 = substr($cpf_cnpj,8,4);
            $digito_verificador = substr($cpf_cnpj,-2);
            $cpf_cnpj_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."/".$bloco_4."-".$digito_verificador;
        break;
    }
    return $cpf_cnpj_formatado;
}
?>

<?php $this->start("script"); ?>
<script>
    $(function () {

        $(".items-open").click(function (e) {
            e.preventDefault();

            let id = this.classList[1];

            this.classList.toggle("active");
            $(`.items.${id}`).toggleClass("active");

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

                location.reload();
            }, "json")


        })
    })
</script>
<?php $this->stop(); ?>
