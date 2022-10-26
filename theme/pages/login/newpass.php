<?php $this->layout("_theme", ["title" => $title, "dir" => $dir]); ?>

<h1>Redefinir Senha</h1>

<form method="post" action="" data-action="<?= $router->route('Register.newpass') ?>">

    <label>
        <input class="pass" name="password" type="password" placeholder="Nova Senha">
    </label>

    <label>
        <input class="repeat-pass" type="password" placeholder="Repita a Nova Senha">
    </label>

    <input type="hidden" name="token" value="<?= $token ?>">

    <input type="submit" value="Alterar Senha">
</form>

<div class="msg"></div>

<?php $this->start("scripts") ?>
<script>
    $(function () {

        $("form").submit(function (e) {
            e.preventDefault();

            var data = $(this).data();
            msg = $(".msg");

            if($(".pass").val() !== $(".repeat-pass").val()) {
                msg.toggleClass("on");
                msg.html("<div class='message error'>As Senhas não Coincidem</div>")
            } else {
                $.post(data.action, $(this).serialize(), function (response) {
                    alert(response);
                    window.location.href = "<?= $router->route("Login.login") ?>";
                }, "json")
            }


        })

    })
</script>
<?php $this->stop() ?>
