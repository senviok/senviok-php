<?php
header('Content-Type: application/json');
if (($_SERVER['HTTP_AUTHORIZATION'] ?? '') !== 'Bearer svk_test_key') {
    http_response_code(401);
    echo json_encode(['Message' => 'Unauthorized']);
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/v1/emails/attachments') {
    $file = $_FILES['file'] ?? null;
    if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || file_get_contents($file['tmp_name']) !== 'pdf') {
        http_response_code(400);
        echo json_encode(['Message' => 'Invalid multipart file']);
        return;
    }
    echo json_encode([
        'id' => 'att_test_1', 'filename' => $file['name'],
        'contentType' => $file['type'], 'sizeBytes' => $file['size'],
        'bucket' => 'test', 'key' => 'test/att_test_1',
    ]);
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/v1/emails') {
    $payload = json_decode(file_get_contents('php://input'), true);
    if (($payload['attachmentIds'] ?? []) !== ['att_test_1']) {
        http_response_code(400);
        echo json_encode(['Message' => 'Attachment ID missing']);
        return;
    }
    echo json_encode(['id' => 'msg_test_1']);
    return;
}

http_response_code(404);
echo json_encode(['Message' => 'Not found']);
