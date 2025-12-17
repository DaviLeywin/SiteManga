<?php
require_once __DIR__ . '\api\banco\class.Banco.php';

echo json_encode(Banco::IniciarBanco());