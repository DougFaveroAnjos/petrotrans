<?php $this->layout("_theme", ["title" => $title, "dir" => $dir]); ?>

<a class="back-button" href="<?= $router->route('Login.login') ?>">
    <i class="fas fa-arrow-left"></i>
</a>

<h1>Criar Conta</h1>

<form class="register-form" method="post" data-action="<?= $router->route('Register.create') ?>">

    <label>
        <input name="first_name" type="text" placeholder="Primeiro Nome" required>
    </label>
    <label>
        <input name="last_name" type="text" placeholder="Sobrenome" required>
    </label>
    <label>
        <input name="email" type="email" placeholder="Email" required>
    </label>
    <label>
        <input name="password" type="password" placeholder="Senha" required>
    </label>
    <label>
        <select name="role" id="role">
            <option value="Fiscal">Fiscal</option>
            <option value="Financeiro">Financeiro</option>
            <option value="Operacional">Operacional</option>
            <option value="Comercial">Comercial</option>
        </select>
    </label>

    <input class='register' type="submit" value="Cadastrar">

</form>

<div class="msg"></div>

<?php $this->start('scripts'); ?>
<script>
    $(function() {
        $(".register-form").submit(function (e) {
            e.preventDefault();
            var data = $(this).data();

            $.post(data.action, $(".register-form").serialize(), function (data) {

                console.log(data["message"]);

                $(".msg").addClass("on");
                $(".msg").html(data["message"]);
            }, "json");
        })
    })
</script>
<?php $this->stop(); ?>
