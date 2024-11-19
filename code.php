<?php
if (!isset($_GET['dir'])) {
    echo "Nessun file o cartella specificato.";
    exit;
}

$dir = $_GET['dir'];
$file = false;
$code = "";
$filepath = null;

// Mappatura delle estensioni di file ai linguaggi per Prism.js
$languageMap = [
    'php' => 'php',
    'html' => 'html',
    'css' => 'css',
    'js' => 'javascript',
    'json' => 'json',
    'xml' => 'markup',
    'py' => 'python',
    'java' => 'java',
    'c' => 'c',
    'cpp' => 'cpp',
    'cs' => 'csharp',
    'rb' => 'ruby',
    'go' => 'go',
    'sh' => 'bash'
];
$languageClass = 'text';

// Se è specificata una cartella, otteniamo i contenuti solo a livello base e visualizziamo il file index.html o index.php se presente
$dirContents = [];

$dirPath = realpath(__DIR__ . DIRECTORY_SEPARATOR . $dir);

// var_dump($dirPath);


if (is_file($dirPath)) {
    $dirPath = dirname($dirPath);
    $file = basename($dir);
    $dir = dirname($dir);
}
if (!$dirPath || strpos($dirPath, __DIR__) !== 0 || !is_dir($dirPath) ) {
    echo "Cartella non valida o non trovata.";
    exit;
}

$files = scandir($dirPath);
foreach ($files as $value) {
    if ($value[0] != '.') {
        $itemPath = $dirPath . DIRECTORY_SEPARATOR . $value;
        $isDir = is_dir($itemPath);
        $dirContents[] = [
            'label' => $value,
            'is_recursive' => $isDir
        ];
    }
}

// Se non è specificato un file ma c'è un index.html o index.php, lo visualizziamo di default
if (!$file) {
    if (in_array('index.html', $files)) {
        $file = 'index.html';
    } elseif (in_array('index.php', $files)) {
        $file = 'index.php';
    }
}

if ($file) {
    $filepath = realpath(__DIR__ . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $file);
    $extension = pathinfo($filepath, PATHINFO_EXTENSION);
    if (array_key_exists($extension, $languageMap)) {
        $languageClass = $languageMap[$extension];
    }
    $code = htmlspecialchars(file_get_contents($filepath));
}


$title = "Visualizza Codice: " . htmlspecialchars($file ?? $dir);
?>
<!DOCTYPE html>
<html lang="it">

    <?php require 'head.php' ?>

    <body class="bg-light">
        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-12 col-sm-3 col-md-2 sidebar bg-white shadow-sm p-3 mb-5 rounded">
                    <h5><?= htmlspecialchars($dir) ?></h5>
                    <ul class="list-group">
                        <?php foreach ($dirContents as $entry) { ?>
                            <li class="list-group-item">
                                <a href="code.php?dir=<?= urlencode($dir . DIRECTORY_SEPARATOR . $entry['label']) ?>"
                                    class="text-decoration-none text-primary">
                                    <?php if ($entry['is_recursive']) { ?>
                                        <i class="bi bi-folder-fill"></i>
                                    <?php } else { ?>
                                        <i class="bi bi-file-earmark-code-fill"></i>
                                    <?php } ?>
                                    <?= $entry['label'] ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <div class="mt-2 btn-group-vertical w-100">

                        <a href="index.php?dir=<?= $dir ?>"
                            class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center  gap-1">
                            <i class="bi bi-house"></i> Torna alla pagina principale</a>
                        <?php if ($file) { ?>
                            <a href="<?= htmlspecialchars($dir . DIRECTORY_SEPARATOR . $file) ?>" target="_blank"
                                class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-start gap-1">
                                <i class="bi bi-window"></i> Apri file attuale</a>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-12 col-sm-9 col-md-10">
                    <div class="card shadow-sm">
                        <div
                            class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Visualizzazione del codice:
                                <?= htmlspecialchars($file ?? 'Nessun file selezionato') ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if ($file) { ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <pre><code class="language-<?= $languageClass ?>"><?= $code ?></code></pre>
                                    </div>
                                    <div class="col-md-6">
                                        <iframe src="<?= htmlspecialchars($dir . DIRECTORY_SEPARATOR . $file) ?>"
                                            class="w-100" style="height: 500px; border: none;"></iframe>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <p class="text-muted">Seleziona un file per visualizzare il codice e l'anteprima.</p>
                            <?php } ?>
                        </div>
                        <div class="card-footer text-center">
                            <a href="index.php" class="btn btn-secondary">Torna alla home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>