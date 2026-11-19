<?php
require dirname(__DIR__) . '/vendor/autoload.php';

function failTest(string $message): never
{
    fwrite(STDERR, $message . PHP_EOL);
    exit(1);
}

$socket = stream_socket_server('tcp://127.0.0.1:0', $errno, $error);
if (!$socket) failTest("Could not reserve test port: $error");
$address = stream_socket_get_name($socket, false);
fclose($socket);
$port = (int)substr(strrchr($address, ':'), 1);
$server = proc_open(
    [PHP_BINARY, '-S', "127.0.0.1:$port", __DIR__ . '/mock-api-router.php'],
    [0 => ['pipe', 'r'], 1 => ['file', '/dev/null', 'a'], 2 => ['file', '/dev/null', 'a']],
    $pipes,
    dirname(__DIR__)
);
if (!is_resource($server)) failTest('Could not start local mock API');
fclose($pipes[0]);

try {
    $ready = false;
    for ($attempt = 0; $attempt < 100; $attempt++) {
        $probe = @fsockopen('127.0.0.1', $port, $errno, $error, 0.1);
        if ($probe) { fclose($probe); $ready = true; break; }
        usleep(50000);
    }
    if (!$ready) failTest('Local mock API did not start');

    $client = new Senviok\Senviok('svk_test_key', ['base_url' => "http://127.0.0.1:$port"]);
    $uploaded = $client->emails->uploadAttachment('invoice.pdf', 'pdf', 'application/pdf');
    if (($uploaded['id'] ?? null) !== 'att_test_1') failTest('Upload response did not return the attachment ID');
    $sent = $client->emails->send([
        'from' => 'billing@example.com', 'to' => 'customer@example.com',
        'subject' => 'Invoice', 'attachmentIds' => [$uploaded['id']],
    ]);
    if (($sent['id'] ?? null) !== 'msg_test_1') failTest('Send did not complete with the uploaded attachment ID');
    echo "PHP upload and send attachment flow passed\n";
} finally {
    proc_terminate($server);
    proc_close($server);
}
