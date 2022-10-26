<?php $this->layout("_theme", ["title" => $title, "liberados" => $liberados]) ?>

<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Empresas</h4>
                <a href="<?= $router->route('Empresas.criarEmpresa') ?>"> <h6>Criar Empresa</h6> </a>
            </div>
            <div class="card-body">

                <form method="get" class="filter-form">

                    <div class="form-check-inline filter-group form-group nome">
                        <input style="max-width: 15rem" class="filter-group form-control nome" type="text" name="nome" placeholder="Nome da Empresa">
                    </div>
                    <div class="form-check-inline filter-group form-group status">
                        <select style="max-width: 15rem" class="filter-group form-control status" name="status">
                            <option value="todos">Todos</option>
                            <option value="cliente">Cliente</option>
                            <option value="não cliente">Não Cliente</option>
                            <option value="provável cliente">Provável Cliente</option>
                            <option value="improvável cliente">Improvavel Cliente</option>
                        </select>
                    </div>

                    <div class="form-group form-check-inline" style="margin-bottom: .5rem">
                        <button type="submit" class="btn btn-primary" name="submit" value="Filtrar">Filtrar</button>
                    </div>


                </form>

                <div class="table-responsive">
                    <table class="table" style= "white-space: nowrap;">
                            <thead class="text-primary" style="font-size: .6rem; gap: 1rem">
                    <th>
                        #
                    </th>
                    <th>
                        Nome
                    </th>
                    <th>
                        Status
                    </th>
                    <th style="text-align: center">
                        SPC
                    </th>
                    <th>
                        Responsável
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Contato
                    </th>
                    <th>
                        Estado
                    </th>
                    <th>
                        Cidade
                    </th>
                    <th>
                        Rua
                    </th>
                    <th>
                        CNPJ
                    </th>
                    <th>
                        Forma de Pagamento
                    </th>
                    </thead>
                    <tbody>

                    <?php if(!empty($empresas)): foreach ($empresas as $empresa): ?>

                            <tr>
                                <td>
                                    <a href="#" class="items-open <?= $empresa->id ?>"> <i class="fas fa-bars"></i> </a>
                                    <div class="items <?= $empresa->id ?>">
                                        <a class="items-close <?= $empresa->id ?>" href="#"><i class="fas fa-times"></i></a>
                                        <a href="<?= $router->route('Empresas.editEmpresa', ['id'=> $empresa->id]) ?>" class="edit-button <?= $empresa->id ?>">Editar</a>
                                        <a class="items-close <?= $empresa->id ?> delete" data-action="<?= $router->route('Empresas.delete', ['id'=> $empresa->id]) ?>" href="#">Deletar</a>
                                    </div>
                                    <input class="form-control id <?= $empresa->id ?>" type="hidden" name="id" value="<?= $empresa->id ?>" disabled>
                                </td>
                                <td>
                                    <?= $empresa->name ?>
                                </td>
                                <td class="form-control" style="border: 1px solid #f96332; font-weight: bold; padding: .5rem 1rem; <?php
                                switch ($empresa->status):

                                    case "cliente":
                                        echo "background-color: dodgerblue;";
                                        break;

                                    case "não cliente":
                                        echo "background-color: yellow;";
                                        break;

                                    case "provável cliente":
                                        echo "background-color: lightgreen;";
                                        break;

                                    case "improvável cliente":
                                        echo "background-color: orange;";
                                        break;

                                 endswitch;
                                 ?>">
                                    <?= strtoupper($empresa->status) ?>
                                </td>
                                <td style="font-weight: bold; text-align: center">

                                    <?php switch ($empresa->spc):

                                        case "sem restrição":
                                            echo "<span style='color: green'>OK</span>";
                                            break;

                                        case "com restrição":
                                            echo "<span style='color: red'>PENDÊNCIA</span>";
                                            break;

                                        case "a verificar":
                                            echo "<span style='color: orange'>A VERIFICAR</span>";
                                            break;

                                    endswitch;?>
                                </td>
                                <td>
                                    <?= $empresa->responsavel ?>
                                </td>
                                <td>
                                    <?= $empresa->email ?>
                                </td>
                                <td>
                                    <?= $empresa->contato ?>
                                </td>
                                <td style="min-width: 10rem">
                                    <?= $empresa->estado ?>
                                </td>
                                <td style="min-width: 10rem">
                                   <?= $empresa->cidade ?>
                                </td>
                                <td style="min-width: 15rem">
                                    <?= $empresa->rua ?>
                                </td>
                                <td>
                                    <?= $empresa->cnpj ?>
                                </td>
                                <td>
                                    <?= $empresa->pagamento ?>
                                </td>
                            </tr>

                    <?php endforeach; endif; ?>
                    </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer" style="display: flex; justify-content: center">
                <?php
                if(!array_key_exists("nome", $_GET)):
                    echo $pager;
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

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

            var id = this.classList[1];
            var data = $(this).data();

            $.post(data.action, {'id': id}, function (result) {
            }, "json")

            setInterval(function () {
                window.location.reload()
            }, 500)
        })
    })
</script>
<?php $this->stop(); ?>
