<?php
// Test encoding
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Encoding Test</title></head>
<body>
<h1>Test Encoding</h1>
<p>Direct: Diagnóstico Especializado - começar - Inteligência</p>
<p>PHP default_charset: <?= ini_get('default_charset') ?></p>
<p>mb_internal_encoding: <?= function_exists('mb_internal_encoding') ? mb_internal_encoding() : 'N/A' ?></p>
<p>htmlspecialchars: <?= htmlspecialchars('Diagnóstico começar', ENT_QUOTES, 'UTF-8') ?></p>
<hr>
<p>Hex of "ç": <?= bin2hex('ç') ?></p>
<p>If hex is "c3a7" = correct UTF-8. If "e7" = Latin-1.</p>
</body>
</html>
