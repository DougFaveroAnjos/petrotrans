<?php $this->layout("_theme", ["title" => $title, "dir" => $dir]); ?>

<?php
    if(!empty($cliente['type'])):
?>
    <h1>Área do cliente</h1>
<?php
    else:
?>
      <h1>Entrar</h1>
<?php
    endif;
?>
<form class="login-form" method="post" action="<?= $router->route('Auth.auth') ?>" data-action="<?= $router->route('Auth.auth') ?>">

    <label>
        <input name="type" type="hidden"  value="<?= (!empty($cliente['type']) ? $cliente['type'] : null)?>" required>
        <?php
            if(!empty($cliente['type'])):
        ?>
                <input name="email" type="text" placeholder="CNPJ" required>
        <?php
            else:
        ?>
            <input name="email" type="email" placeholder="Email" required>
        <?php
            endif;
        ?>
    </label>
    <label>
        <input name="password" type="password" placeholder="Senha" required>
    </label>

    <?php
        if(empty($cliente['type'])):
    ?>
        <span style="margin-bottom: 1rem">Não tem uma conta? <a href="<?= $router->route('Login.register') ?>">Crie uma!</a></span>
        <span><a href="<?= $router->route('Login.recover') ?>">Esqueci minha senha</a></span>
    <?php
        endif;
    ?>


    <button class="btn connect" type="submit" value="Conectar">
    <div class="svg-wrapper-1">
        <div class="svg-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
            <path fill="none" d="M0 0h24v24H0z"></path>
            <path fill="currentColor" d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"></path>
        </svg>
        </div>
    </div>
    <span>Entrar</span>
    </button>

</form>

<?php
if(array_key_exists("errormsg", $_COOKIE)):
echo $_COOKIE["errormsg"];
endif;
?>

<?php $this->start('scripts'); ?>

<?php $this->stop(); ?>
