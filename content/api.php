<?php
//
// API JSON/P
//

// Simulate API slow respond
//sleep(3);

header('X-Robots-Tag: nosnippet');

if (!$results) {
    http_response_code(404);
    header('Content-Type: text/plain');
    exit('404 Not Found');
}

$callback      = isset($_GET['callback']) ? $_GET['callback'] : null;
$issetcallback = !empty($callback) ? true : false;

header('Content-Type: application/' . ($issetcallback ?  'javascript' : 'json') . '; charset=utf-8');

$array = array(
    'pagetitle' => $pagetitle,
    'content' => "<h1 dir=auto>$title</h1>\n$content\n"
);

// UTF8 decoded JSON
// Add option "JSON_PRETTY_PRINT" in case you care more readability than to save some bits
$data = json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
if (version_compare(PHP_VERSION, '5.4', '<')) {
    $data = preg_replace('/\\\\u([a-f0-9]{4})/e', "iconv('UCS-4LE', 'UTF-8', pack('V',  hexdec('U$1')))", json_encode($array));
    $data = str_replace('\\/', '/', $data);
}

echo $issetcallback ? $callback . '(' . $data . ')' : $data;