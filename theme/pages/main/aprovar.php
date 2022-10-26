<?php $this->layout("_theme", ["title" => $title]) ?>

<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Aprovar cadastro</h4>
            </div>
            <div class="card-body">

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
                        E-mail
                    </th>
                    <th>
                        Nível
                    </th>
                    <th>
                        Opções
                    </th>
                    </thead>
                    <tbody>

                    <?php if(!empty($users)): foreach ($users as $user): ?>

                            <tr>
                                <td><?= $user->id?></td>
                                <td><?= "{$user->first_name} {$user->last_name}"?></td>
                                <td><?= $user->email?></td>
                                <td><?= $user->role?></td>
                                <td>
                                    <form action="<?= $router->route('Main.aprovar') ?>" method="post" data-action="<?= $router->route('Main.aprovar') ?>" style="display: inline-block">
                                        <input type="hidden" name="type" value="approved">
                                        <input type="hidden" name="id" value="<?=  $user->id ?>">
                                        <button class="btn btn-success">Aprovar</button>
                                    </form>
                                    <form action="<?= $router->route('Main.aprovar') ?>" method="post" data-action="<?= $router->route('Main.aprovar') ?>" style="display: inline-block">
                                        <input type="hidden" name="type" value="repproved">
                                        <input type="hidden" name="id" value="<?=  $user->id ?>">
                                        <button class="btn btn-danger"> Reprovar</button>
                                    </form>
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
