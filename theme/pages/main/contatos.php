<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row" style="position: relative">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Contatos</h4>
                <a href="<?= $router->route('Contato.criarContato') ?>"> <h6>Criar Contato</h6> </a>
            </div>
            <div class="card-body">

                <form method="get" class="filter-form">

                    <div class="form-check-inline filter-group form-group usuario">
                        <select class="form-control" name="usuario" id="usuario">
                            <option value="<?= $_SESSION['name'] ?>"><?= $_SESSION['name'] ?></option>
                            <option value="todos">Todos</option>
                            
                            <?php foreach ($users as $user): ?>

                                <option value="<?= $user->first_name ?>"><?= $user->first_name ?></option>

                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-check-inline filter-group form-group nome">
                        <input style="max-width: 15rem" class="filter-group form-control nome" type="text" name="nome" placeholder="Nome da Empresa">
                    </div>

                    <div class="form-check-inline filter-group form-group date">
                        <label style="margin-right: .5rem">Entre:</label>
                        <input class="filter-group form-control date" style="max-width: 15rem; margin-right: 1rem" type="date" name="data_min">
                        <label style="margin-right: .5rem"> A: </label>
                        <input class="filter-group form-control date" style="max-width: 15rem" type="date" name="data_max">
                    </div>

                    <div class="form-check-inline filter-group form-group status">
                        <select style="max-width: 15rem" class="filter-group form-control status" name="status">
                            <option value="todos">Todos</option>
                            <option value="cliente">Cliente</option>
                            <option value="não">Não Cliente</option>
                            <option value="provável">Provável Cliente</option>
                            <option value="improvável">Improvavel Cliente</option>
                        </select>
                    </div>

                    <div class="form-group form-check-inline" style="margin-bottom: .5rem; margin-left: 1rem">
                        <button type="submit" class="btn btn-primary" name="submit" value="Filtrar">Filtrar</button>
                    </div>


                </form>

                <div class="table-responsive">
                    <table class="table" style= "white-space: nowrap;">
                        <thead class="text-primary" style="font-size: .8rem; gap: 1rem; color: black!important;">
                        <th>
                            #
                        </th>
                        <th>
                            <i class="far fa-comment-alt"></i>
                        </th>
                        <th>
                            Data
                        </th>
                        <th>
                            Empresa
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Responsável
                        </th>
                        <th>
                            Contato
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Cidade
                        </th>
                        <th>
                            Estado
                        </th>
                        <th>
                            OBS
                        </th>
                        </thead>
                    <tbody>

                    <?php if(!empty($contatos)): foreach ($contatos as $contato
                    ): ?>

                        <tr>
                            <td>
                                <a href="#" class="items-open <?= $contato->id ?>"> <i class="fas fa-bars"></i> </a>
                               <div class="items <?= $contato->id ?>">
                               <a href="#" class="items-close <?= $contato->id ?> mail" data-action="<?= $router->route('Coletas.mail', ['emailapresentacao' => $contato->email, 'nomecontato' => $contato->responsavel]) ?>">Enviar Apresentação</a>
                                
                                    <a href="<?= $router->route('Contato.editContato', ['id'=> $contato->id]) ?>" class="edit-button <?= $contato->id ?>">Editar</a>
                                    <a class="items-close <?= $contato->id ?> delete" data-action="<?= $router->route('Contato.delete', ['id'=> $contato->id]) ?>" href="#">Deletar</a>
                                </div>
                                <input class="form-control id <?= $contato->id ?>" type="hidden" name="id" value="<?= $contato->id ?>" disabled>
                            </td>
                            <td style="display: flex;">
                                <a href="#" class="comentarios-open <?= $contato->id ?>" data-render="<?= $router->route('ComentariosContatos.render') ?>"> <i class="fas fa-comment-alt"></i> </a>
                                <div class="comentarios <?= $contato->id ?>">
                                    <div class="comentarios-section <?= $contato->id ?>" style="position: relative; height: 80%">

                                    </div>

                                    <div class="escrever-comentario <?= $contato->id ?>" style="display: flex; gap: 1rem">
                                        <input placeholder="Escreva seu comentario" type="text" name="comentario" class="form-control comentario-input <?= $contato->id ?>" style="border: 1px solid #f96332;">
                                        <button style="font-size: .7rem; padding: .6rem .6rem" class="btn btn-primary comentario-send <?= $contato->id ?>" data-render="<?= $router->route('ComentariosContatos.render') ?>" data-action="<?= $router->route('ComentariosContatos.new') ?>">Comentar</button>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?= (new DateTime($contato->date))->format('d/m/Y') ?>
                            </td>
                            <td>
                                <?= $contato->empresa ?>
                            </td>
                            <td class="form-control" style="border: 1px solid #f96332; font-weight: bold; <?php
                            switch ($contato->status):

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
                                <?= strtoupper($contato->status) ?>
                            </td>
                            <td>
                                <?= $contato->responsavel ?>
                            </td>
                            <td>
                                <?= $contato->contato ?>
                            </td>
                            <td>
                                <?= $contato->email ?>
                            </td>
                            <td>
                                <?= $contato->cidade ?>
                            </td>
                            <td>
                                <?= $contato->estado ?>
                            </td>
                            <td>
                                <?= $contato->obs ?>
                            </td>

                        </tr>

                    <?php endforeach; endif; ?>
                    </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer" style="display: flex; justify-content: center">
                <?php
                if(!array_key_exists("status", $_GET)):
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
                alert(result);
            }, "json")

            setInterval(function () {
                window.location.reload()
            }, 500)
        })

        $(".comentarios-open").on("mouseenter", function (e) {
            e.preventDefault();

            var data = $(this).data();
            var id = this.classList[1];

            $.post(data.render, {"id": id}, function (response) {

                $(`.comentarios-section.${id}`).html(response);

            }, "json")

            $(`.comentarios.${id}`).toggleClass("active");

        })

        $(".comentarios").on("mouseleave", function (e) {
            let id = this.classList[1];

            $(`.comentarios.${id}`).toggleClass("active");
        })
        $(".comentario-send").click(function (e) {
            e.preventDefault();

            var data = $(this).data();
            var id = this.classList[3];
            var comentario = $(`.comentario-input.${id}`).val();

            $.post(data.action, {"contato_id": id, "comentario": comentario}, function (response) {

                alert(response);
            }, "json")

            $.post(data.render, {"id": id}, function (response) {

                $(`.comentarios-section.${id}`).html(response);

            }, "json")
        })

        $(".mail").click(function (e) {
            e.preventDefault();

            var data = $(this).data();
            var id = this.classList[1];

            $.post(data.action, {'emailapresentacao': id}, function (result) {
                alert(result);
                window.location.reload();
            }, 'json');
        })

    })
</script>
<?php $this->stop(); ?>
