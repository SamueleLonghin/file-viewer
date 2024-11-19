<?php
function getDirContents($dir, $bpath, $cpath = '', &$results = [])
{
    if (is_dir($dir)) {
        $files = scandir($dir);
        // Controllo se voglio ignorare la cartella 
        // (per ignorare la cartella basta creare all'interno un file chiamato '.explorer-exclude')
        if (in_array('.explorer-exclude', $files)) {
            return $results;
        }
        // Controllo se voglio che la cartella abbia una pagina tutta sua, 
        // ovvero non elenco tutti i file qui ma all'interno di un'altro menù 
        // (per farlo basta creare all'interno un file chiamato '.explorer-recoursive')
        if (in_array('.explorer-recoursive', $files) && $bpath != $cpath) {
            $results[$cpath] = ['url' => "/index.php?dir=$cpath", 'is_recursive' => true];
            return $results;
        }
        // Comincio ad analizzare i file della cartella
        // $cpath .= DIRECTORY_SEPARATOR;
        foreach ($files as $value) {

            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            $isDir = is_dir($path);
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $isGoodFile = in_array($ext, ['html', 'php']);
            $fileName = pathinfo($path, PATHINFO_FILENAME);
            // Escludo la cartella banale .
            // Escludo la cartella padre ..
            // Escludo qualunque file nascosto (inizia per .)
            // Escludo i file posizionati alla root (file di progetto)
            if ($value[0] != '.' && !($isGoodFile && $cpath == '')) {
                // Se il file è un file (non cartella) ed è un file html o php lo aggiungo alla lista
                if (!$isDir) {
                    if ($isGoodFile)
                        // Se si chiama index non aggiungo il nome ma utilizzo la cartella
                        if ($fileName == 'index')
                            $results[$cpath] = ['url' => $cpath, 'is_recursive' => false];
                        else
                            $results[$cpath. DIRECTORY_SEPARATOR . $value] = ['url' => $cpath . DIRECTORY_SEPARATOR . $value, 'is_recursive' => false];
                } else {
                    // Se il file è una cartella esploro la cartella per trovare altri file
                    getDirContents($path, $bpath, $cpath . DIRECTORY_SEPARATOR . $value, $results);
                }
            }
        }
    }

    return $results;
}
