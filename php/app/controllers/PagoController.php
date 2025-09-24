<?php
require_once '../app/models/Pago.php';
require_once '../config/database.php';

class PagoController {
    public function index() {
        global $pdo;
        $pagos = Pago::all($pdo);
        require '../app/views/pago/index.php';
    }

    public function show($id) {
        global $pdo;
        $pago = Pago::find($pdo, $id);
        require '../app/views/pago/show.php';
    }

    public function create() {
        require '../app/views/pago/create.php';
    }

    public function store($data) {
        global $pdo;
        Pago::create($pdo, $data);
        header('Location: /public/index.php?r=pago');
    }

    public function edit($id) {
        global $pdo;
        $pago = Pago::find($pdo, $id);
        require '../app/views/pago/edit.php';
    }

    public function update($id, $data) {
        global $pdo;
        Pago::update($pdo, $id, $data);
        header('Location: /public/index.php?r=pago');
    }

    public function destroy($id) {
        global $pdo;
        Pago::delete($pdo, $id);
        header('Location: /public/index.php?r=pago');
    }
}
