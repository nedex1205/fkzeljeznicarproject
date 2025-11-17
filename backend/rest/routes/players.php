<?php


Flight::route('GET /players', function () {
    $s = Flight::get('playerService');
    Flight::json($s->get_all());
});

Flight::route('GET /players/@id', function ($id) {
    $s = Flight::get('playerService');
    $row = $s->get_by_id($id);
    $row ? Flight::json($row) : Flight::json(['error' => 'not_found'], 404);
});

Flight::route('POST /players', function () {
    $s = Flight::get('playerService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $id = $s->add($data);
    Flight::json(['id' => $id], 201);
});

Flight::route('PUT /players/@id', function ($id) {
    $s = Flight::get('playerService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $ok = $s->update($id, $data);
    Flight::json(['ok' => (bool)$ok]);
});

Flight::route('PATCH /players/@id', function ($id) {
    $s = Flight::get('playerService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $ok = $s->update($id, $data);
    Flight::json(['ok' => (bool)$ok]);
});

Flight::route('DELETE /players/@id', function ($id) {
    $s = Flight::get('playerService');
    $ok = $s->delete($id);
    Flight::json(['ok' => (bool)$ok]);
});
