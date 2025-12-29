<?php

Flight::route('GET /products', function () {
    $s = Flight::get('productService');
    Flight::json($s->get_all());
});


Flight::route('GET /products/@id', function ($id) {
    $s = Flight::get('productService');
    $row = $s->get_by_id($id);
    $row ? Flight::json($row) : Flight::json(['error' => 'not_found'], 404);
});


Flight::route('POST /products', function () {
    $s = Flight::get('productService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    try {
        $id = $s->add($data);
        Flight::json(['id' => $id], 201);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

Flight::route('PUT /products/@id', function ($id) {
    $s = Flight::get('productService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    try {
        $ok = $s->update($id, $data);
        Flight::json(['ok' => (bool)$ok]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

Flight::route('PATCH /products/@id', function ($id) {
    $s = Flight::get('productService');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    try {
        $ok = $s->update($id, $data);
        Flight::json(['ok' => (bool)$ok]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});



Flight::route('DELETE /products/@id', function ($id) {
    $s = Flight::get('productService');
    $ok = $s->delete($id);
    Flight::json(['ok' => (bool)$ok]);
});
