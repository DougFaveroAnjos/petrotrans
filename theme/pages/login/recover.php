<?php $this->layout("_theme", ["title" => $title, "dir" => $dir]); ?>

<a class="back-button" href="<?= $router->route('Login.login') ?>">
    <i class="fas fa-arrow-left"></i>
</a>

<h1>Esqueci a Senha</h1>

<form method="post" action="" data-action="<?= $router->route('Auth.recover') ?>">
    <label>
        <input name="email" type="email" placeholder="Email" required>
    </label>

    <input type="submit" value="Mandar Email">
</form>

<div class="msg"></div>

<?php $this->start("scripts") ?>
<script>
    $(function () {

        $("form").submit(function (e) {
            e.preventDefault()
            
            var data = $(this).data();
            
            $.post(data.action, $(this).serialize(), function (response) {
                $(".msg").addClass("on");
                $(".msg").html(response.message);
            }, "json")
        })

    })
</script>
<?php $this->stop() ?>
