<?php

Flight::route('GET /orders', function () {
    $s = Flight::get('orderService');
    Flight::json($s->get_all());
});


Flight::route('GET /orders/@id', function ($id) {
    $s = Flight::get('orderService');
    $row = $s->get_by_id($id);
    $row ? Flight::json($row) : Flight::json(['error' => 'not_found'], 404);
});


Flight::route('GET /orders/user/@uid', function ($uid) {
    $s = Flight::get('orderService');
    Flight::json($s->getByUserId($uid));
});

Flight::route('POST /orders', function () {
    $s = Flight::get('orderService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    if (!isset($data['userId'])) {
        Flight::json(['error' => 'userId_required'], 400);
        return;
    }

    $id = $s->add([
        'user_id' => (int)$data['userId'],
        'status'  => $data['status'] ?? 'NEW',
        'total'   => 0
    ]);

    Flight::json(['id' => $id], 201);
});


Flight::route('POST /orders/@id/items', function ($id) {
    $s = Flight::get('orderService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $ok = $s->add_item($id, (int)($data['productId'] ?? 0), (int)($data['qty'] ?? 1));
    Flight::json(['ok' => (bool)$ok]);
});


Flight::route('POST /orders/@id/finalize', function ($id) {
    $s = Flight::get('orderService');
    $ok = $s->finalize($id);
    Flight::json(['ok' => (bool)$ok]);
});

Flight::route('PATCH /orders/@id/status', function ($id) {
    $s = Flight::get('orderService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    if (!isset($data['status'])) {
        Flight::json(['error' => 'status_required'], 400);
        return;
    }
    $ok = $s->update_status($id, (string)$data['status']);
    Flight::json(['ok' => (bool)$ok]);
});


Flight::route('DELETE /orders/@id', function ($id) {
    $s = Flight::get('orderService');
    $ok = $s->delete($id);
    Flight::json(['ok' => (bool)$ok]);
});
