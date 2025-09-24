<?php
require_once '../app/models/Reserva.php';
require_once '../config/database.php';

class ReservaController {
    public function index() {
        global $pdo;
        $reservas = Reserva::all($pdo);
        require '../app/views/reserva/index.php';
    }

    public function show($id) {
        global $pdo;
        $reserva = Reserva::find($pdo, $id);
        require '../app/views/reserva/show.php';
    }

    public function create() {
        require '../app/views/reserva/create.php';
    }

    public function store($data) {
        global $pdo;
        Reserva::create($pdo, $data);
        header('Location: /public/index.php?r=reserva');
    }

    public function edit($id) {
        global $pdo;
        $reserva = Reserva::find($pdo, $id);
        require '../app/views/reserva/edit.php';
    }

    public function update($id, $data) {
        global $pdo;
        Reserva::update($pdo, $id, $data);
        header('Location: /public/index.php?r=reserva');
    }

    public function destroy($id) {
        global $pdo;
        Reserva::delete($pdo, $id);
        header('Location: /public/index.php?r=reserva');
    }
}
