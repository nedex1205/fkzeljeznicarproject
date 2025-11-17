<?php


Flight::route('GET /matches', function () {
    $s = Flight::get('matchService');
    Flight::json($s->get_all());
});

Flight::route('GET /matches/@id', function ($id) {
    $s = Flight::get('matchService');
    $row = $s->get_by_id($id);
    $row ? Flight::json($row) : Flight::json(['error' => 'not_found'], 404);
});

Flight::route('POST /matches', function () {
    $s = Flight::get('matchService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $id = $s->add($data);
    Flight::json(['id' => $id], 201);
});

Flight::route('PUT /matches/@id', function ($id) {
    $s = Flight::get('matchService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $ok = $s->update($id, $data);
    Flight::json(['ok' => (bool)$ok]);
});

Flight::route('PATCH /matches/@id', function ($id) {
    $s = Flight::get('matchService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $ok = $s->update($id, $data);
    Flight::json(['ok' => (bool)$ok]);
});

Flight::route('DELETE /matches/@id', function ($id) {
    $s = Flight::get('matchService');
    $ok = $s->delete($id);
    Flight::json(['ok' => (bool)$ok]);
});
