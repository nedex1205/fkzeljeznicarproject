<?php
declare(strict_types=1);

class BaseRoute {


    public static function registerCrud(string $basePath, string $serviceKey): void {


        Flight::route("GET {$basePath}", function() use ($serviceKey) {
            $service = Flight::get($serviceKey);
            Flight::json($service->get_all());
        });


        Flight::route("GET {$basePath}/@id", function($id) use ($serviceKey) {
            $service = Flight::get($serviceKey);
            $row = $service->get_by_id((int)$id);
            if ($row) {
                Flight::json($row);
            } else {
                Flight::json(['error' => 'not_found'], 404);
            }
        });

        Flight::route("POST {$basePath}", function() use ($serviceKey) {
            $service = Flight::get($serviceKey);
            $data = json_decode(file_get_contents('php://input'), true) ?? [];
            $id = $service->add($data);
            Flight::json(['id' => $id], 201);
        });


        Flight::route("PUT {$basePath}/@id", function($id) use ($serviceKey) {
            $service = Flight::get($serviceKey);
            $data = json_decode(file_get_contents('php://input'), true) ?? [];
            $ok = $service->update((int)$id, $data);
            Flight::json(['ok' => (bool)$ok]);
        });


        Flight::route("PATCH {$basePath}/@id", function($id) use ($serviceKey) {
            $service = Flight::get($serviceKey);
            $data = json_decode(file_get_contents('php://input'), true) ?? [];
            $ok = $service->update((int)$id, $data);
            Flight::json(['ok' => (bool)$ok]);
        });


        Flight::route("DELETE {$basePath}/@id", function($id) use ($serviceKey) {
            $service = Flight::get($serviceKey);
            $ok = $service->delete((int)$id);
            Flight::json(['ok' => (bool)$ok]);
        });
    }
}
