<?php
session_start();

// Recibimos desde login.html
$producto = isset($_POST['producto']) ? trim($_POST['producto']) : '';
$precioRaw = isset($_POST['precio']) ? $_POST['precio'] : null;

// Validación simple
$precio = null;
if ($precioRaw !== null && $precioRaw !== '') {
  // Por si viene "1,044" o cosas raras, lo normalizamos
  // En tu código casi siempre viene como número: 1044, 1575, etc.
  $precio = (float) str_replace(',', '', $precioRaw);
}

if ($producto === '' || $precio === null) {
  // Si algo viene mal, no agregamos
  header("Location: carrito.php");
  exit;
}

// Inicializa carrito
if (!isset($_SESSION['carrito'])) {
  $_SESSION['carrito'] = [];
}

// Agregamos el producto
$_SESSION['carrito'][] = [
  'producto' => $producto,
  'precio'   => $precio
];

header("Location: carrito.php");
exit;