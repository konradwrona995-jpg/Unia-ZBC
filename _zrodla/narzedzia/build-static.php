<?php
// Eksport strony do statycznego HTML (np. pod GitHub Pages).
// Użycie: php narzedzia/build-static.php <folder-docelowy>
// Panel admina i formularze PHP nie są eksportowane – zostają tylko strony publiczne.
$src = dirname(__DIR__);
$out = rtrim($argv[1] ?? '', '/\\');
if ($out === '') exit("Podaj folder docelowy.\n");
if (!is_dir($out)) mkdir($out, 0775, true);

require $src . '/inc/db.php';
require_once $src . '/inc/liga.php';
liga_dane(true); // świeża tabela z 90minut (gdy nie odpowiada – ostatnia kopia)

function render(string $src, string $strona, array $param = []): string
{
    $cmd = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg("$src/narzedzia/render.php") . ' ' . escapeshellarg($strona);
    foreach ($param as $k => $v) $cmd .= ' ' . escapeshellarg("$k=$v");
    $html = shell_exec($cmd);
    if (!$html || stripos($html, 'Fatal error') !== false) {
        fwrite(STDERR, "Błąd renderowania $strona\n$html\n");
        exit(1);
    }
    return $html;
}

// Linki *.php -> *.html, aktualnosc.php?id=N -> aktualnosc-N.html
function linki(string $html): string
{
    $html = preg_replace('/aktualnosc\.php\?id=(\d+)/', 'aktualnosc-$1.html', $html);
    return preg_replace('/\b(index|rozgrywki|klub|aktualnosci)\.php\b/', '$1.html', $html);
}

$strony = ['index.php' => 'index.html', 'rozgrywki.php' => 'rozgrywki.html', 'klub.php' => 'klub.html', 'aktualnosci.php' => 'aktualnosci.html'];
foreach ($strony as $php => $html) {
    file_put_contents("$out/$html", linki(render($src, $php)));
    echo "  $html\n";
}
foreach (news_lista() as $n) {
    file_put_contents("$out/aktualnosc-{$n['id']}.html", linki(render($src, 'aktualnosc.php', ['id' => $n['id']])));
    echo "  aktualnosc-{$n['id']}.html\n";
}

// Zasoby: CSS, JS, obrazki i zdjęcia z panelu
function kopiuj(string $z, string $do): void
{
    if (!is_dir($z)) return;
    if (!is_dir($do)) mkdir($do, 0775, true);
    foreach (scandir($z) as $f) {
        if ($f === '.' || $f === '..' || $f === '.htaccess') continue;
        is_dir("$z/$f") ? kopiuj("$z/$f", "$do/$f") : copy("$z/$f", "$do/$f");
    }
}
kopiuj("$src/assets", "$out/assets");
kopiuj("$src/uploads", "$out/uploads");
echo "Gotowe: $out\n";
