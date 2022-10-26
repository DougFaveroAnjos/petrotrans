<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="<?= url('theme/images/logo.svg') ?>">

    <!-- css -->
    <link rel="stylesheet" href="<?= url("theme/scss/".$dir."/styles.css") ?>">

    <title><?= $title ?></title>


    <style>
        /* From uiverse.io by @adamgiebl */
    .btn {
        font-family: inherit;
        font-size: 15px;
        background: royalblue;
        color: white;
        padding: 0.7em 1em;
        padding-left: 0.9em;
        display: flex;
        align-items: center;
        border: none;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.2s;
        width:100%;
        margin-top: 10px;
        margin-bottom: -10px
    }

    .btn span {
        display: block;
        margin-left: 0.3em;
        transition: all 0.3s ease-in-out;
    }

    .btn svg {
        display: block;
        transform-origin: center center;
        transition: transform 0.3s ease-in-out;
    }

    .btn:hover .svg-wrapper {
        animation: fly-1 0.6s ease-in-out infinite alternate;
    }

    .btn:hover svg {
        transform: translateX(1.2em) rotate(45deg) scale(1.1);
    }

    .btn:hover span {
        transform: translateX(9em);
    }

    .btn:active {
        transform: scale(0.95);
    }

    @keyframes fly-1 {
    from {
        transform: translateY(0.1em);
    }

    to {
        transform: translateY(-0.1em);
    }
    }

</style>
</head>
<body>

<video autoplay loop muted>
    <source src="https://www.petrotrans.com.br/video/PETROTRANS%20-%20INSTITUCIONAL.mp4" type="video/mp4">
</video>

<main>
<?= $this->section("content"); ?>
</main>

<script src="https://kit.fontawesome.com/560a2dac7b.js" crossorigin="anonymous"></script>
<script src="<?= url('theme/js/jquery-3.6.0.min.js') ?>"></script>
<?= $this->section('scripts'); ?>
</body>
</html>