<?php


Flight::route('GET /users', function () {
    $s = Flight::get('userService');
    Flight::json($s->get_all());
});

Flight::route('GET /users/@id', function ($id) {
    $s = Flight::get('userService');
    $row = $s->get_by_id($id);
    $row ? Flight::json($row) : Flight::json(['error' => 'not_found'], 404);
});

Flight::route('POST /users', function () {
    $s = Flight::get('userService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $id = $s->add($data);
    Flight::json(['id' => $id], 201);
});

Flight::route('PUT /users/@id', function ($id) {
    $s = Flight::get('userService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $ok = $s->update($id, $data);
    Flight::json(['ok' => (bool)$ok]);
});

Flight::route('PATCH /users/@id', function ($id) {
    $s = Flight::get('userService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $ok = $s->update($id, $data);
    Flight::json(['ok' => (bool)$ok]);
});

Flight::route('DELETE /users/@id', function ($id) {
    $s = Flight::get('userService');
    $ok = $s->delete($id);
    Flight::json(['ok' => (bool)$ok]);
});
