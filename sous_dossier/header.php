<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $metaDescription ?? "" ?>">
    <!-- ?=$metaDescription ?? "" ?>   => raccourci de echo  -->
    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/style.css">

    <link rel="stylesheet" href="/css/responsive.css">
    <!-- <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css"> -->
    <title><?php echo $pageTitre ?? '' ?></title>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="/"><img src="/sous_dossier/img/LogoPHP2024.png" alt="">
                    </a></li>
                <?= $nav ?>
            </ul>
        </nav>
    </header>
</body>

</html>