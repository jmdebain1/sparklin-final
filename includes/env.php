<?php
/**
 * .env loader — reads key=value pairs into $_ENV
 */
function loadEnv(string $path = __DIR__ . '/../.env'): void {
    if (!file_exists($path)) {
        // Pas de fichier .env (ex. build Netlify) : les variables sont déjà
        // exportées dans l'environnement du process (dashboard Netlify).
        // getenv() sans argument fonctionne quel que soit variables_order.
        foreach (getenv() as $key => $value) {
            $_ENV[$key] = $value;
        }
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, '=') === false) continue;
        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);
        if (preg_match('/^(["\'])(.*)\\1$/', $value, $m)) {
            $value = $m[2];
        }
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}
