<!DOCTYPE html>

<?php
if (isset($_GET['info'])) {
    echo phpinfo();
    die();
}

$blocchi = [
    [
        'title' => "Esempi del prof Longhin",
        'path' => "",
        'class' => 'teoria'
    ]
];

$dir = false;
if (isset($_GET['dir'])) {
    $dir = $_GET['dir'];
    $blocchi = [
        [
            'title' => "Cartella " . $dir,
            'path' => $dir,
            'class' => 'dir'
        ]
    ];
}

$title = $blocchi[0]['title'];

require 'direxplorer.php';

?>
<html lang="it">

    <?php require 'head.php'; ?>

    <body class="bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <?php foreach ($blocchi as $blocco) { ?>
                    <div class="col-12 col-md-8 blocco-link <?= $blocco['class'] ?> shadow-sm p-3 mb-5 bg-body rounded">
                        <h1><?= $blocco['title'] ?></h1>
                        <ul class="list-group">
                            <?php foreach (getDirContents(__DIR__ . $blocco['path'], $blocco['path'], $blocco['path']) as $label => $entry) { ?>
                                <li class="list-group-item">
                                    <div>
                                        <a href="<?= $entry['url'] ?>" class="text-decoration-none text-primary">
                                            <?php if ($entry['is_recursive']) { ?>
                                                <i class="bi bi-folder-fill"></i>
                                            <?php } else { ?>
                                                <i class="bi bi-file-earmark-code-fill"></i>
                                            <?php } ?>
                                            <?= $label ?>
                                        </a>
                                        <?php if (!$entry['is_recursive']) { ?>
                                            <a href="code.php?dir=<?= urlencode($entry['url']) ?>"
                                                class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-eye"></i> Visualizza codice
                                            </a>
                                        <?php } ?>

                                    </div>
                                </li>
                            <?php } ?>
                            <?php if ($dir) { ?>
                                <li class="list-group-item">
                                    <div>
                                        <a href="index.php?" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-eye"></i> Torna alla Home
                                        </a>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>

        <footer class="footer mt-5">
            <div class="container">
                <h5>Credits</h5>
                <p>Creato da Samuele Longhin</p>
                <a href="?info=1" class="btn btn-outline-info">Visualizza PhpInfo</a>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>