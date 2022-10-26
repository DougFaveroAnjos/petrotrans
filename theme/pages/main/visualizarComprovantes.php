<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- css -->
    <link rel="stylesheet" href="<?= url('theme/scss/main/styles.css') ?>">
    <link rel="stylesheet" href="<?= url('theme/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">

    <title>Petrotrans | <?= $title ?></title>

</head>

<body>
<style>
    .confirmation {
        display: none;
        position: fixed;
        padding: 3rem;

        width: 20%;
        height: 20%;

        left: 40%;
        top: 40%;

        background-color: #fafafa;
        border: 5px solid orangered;
    }

    .confirmation.active {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        justify-content: center;
        align-items: center;
    }

</style>
<div class="confirmation">
    <h5>Tem certeza?</h5>

    <a href="#" data-action="<?= $router->route('Financeiro.delete', ['id' => $fiscal->id]) ?>" class="accept" style="color: green">Sim</a>

    <a href="#" class="refuse" style="color: red">Não</a>
</div>


<header>
    <nav>
        <ul>
            <li><a href="<?= $router->route('Main.financeiro') ?>"><i class="fas fa-arrow-left"></i></a> </li>

            <li class="deletar-comprovantes">
                <a href="#" id="delete" ><i class="fas fa-times"></i></a>
            </li>
        </ul>
    </nav>
</header>

<main style="display: flex; justify-content: center; align-items: center; flex-direction: column; gap: 2rem">

    <?php foreach ($comprovantes as $comprovante): ?>
        <div class="item" style="display: flex; gap: 2rem">
            <h5><?= pathinfo($comprovante)['filename'] ?></h5>
            <a target="_blank" href="<?= $comprovante ?>">Abrir</a>
            <a download="<?= pathinfo($comprovante)['basename'] ?>" href="<?= $comprovante ?>">Download</a>
        </div>
    <?php endforeach; ?>


    <div class="descricao" style="text-align: center">
        <h5>Descrição dos Comprovantes:</h5>
        <p><?= $fiscal->comprovantes_descricao ?></p>
    </div>
</main>

<footer style="display: flex; gap: 1rem; margin-top: 5rem; padding: 2rem; border-top: 3px solid black">

    <h4>Histórico:</h4>
    <?php foreach (explode("; ", $fiscal->comprovantes_historico) as $item): ?>

        <div class="doc" style="max-width: 15rem; overflow-x: hidden;">
            <a download="<?= pathinfo($item)['basename'] ?>" href="<?= $item ?>">Download</a>
            <label><?= pathinfo($item)['basename'] ?></label>
        </div>

    <?php endforeach; ?>
</footer>

<script src="<?= url('theme/js/jquery-3.6.0.min.js') ?>"></script>
<script>
    $(function () {

        $(".deletar-comprovantes").click(function (e) {
            e.preventDefault();

            $(".confirmation").addClass("active");

        })

        $(".refuse").click(function (e) {
            e.preventDefault();

            $(".confirmation").removeClass("active");
        })

        $(".accept").click(function (e) {
            e.preventDefault();

            var data = $(this).data();
            $(".confirmation").removeClass("active");

            $.post(data.action, function (result) {
                alert(result);
                window.location.href = "<?= $router->route("Main.financeiro") ?>";
            }, "json")
        })

    })
</script>
</body>
