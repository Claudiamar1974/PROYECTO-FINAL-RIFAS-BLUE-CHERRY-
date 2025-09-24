<?php
require_once '../app/models/Rifa.php';
require_once '../config/database.php';

class RifaController {
    public function index() {
        global $pdo;
        $rifas = Rifa::all($pdo);
        require '../app/views/rifa/index.php';
    }

    public function show($id) {
        global $pdo;
        $rifa = Rifa::find($pdo, $id);
        require '../app/views/rifa/show.php';
    }

    public function create() {
        require '../app/views/rifa/create.php';
    }

    public function store($data) {
        global $pdo;
        Rifa::create($pdo, $data);
        header('Location: /public/index.php?r=rifa');
    }

    public function edit($id) {
        global $pdo;
        $rifa = Rifa::find($pdo, $id);
        require '../app/views/rifa/edit.php';
    }

    public function update($id, $data) {
        global $pdo;
        Rifa::update($pdo, $id, $data);
        header('Location: /public/index.php?r=rifa');
    }

    public function destroy($id) {
        global $pdo;
        Rifa::delete($pdo, $id);
        header('Location: /public/index.php?r=rifa');
    }
}
