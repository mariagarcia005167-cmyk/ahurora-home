<?php
session_start();

// Acción: borrar
if (isset($_POST['accion']) && $_POST['accion'] === 'borrar') {
  unset($_SESSION['carrito']);
  unset($_SESSION['pedido_demo']);
  header("Location: carrito.php");
  exit;
}

// Acción: enviar (demo)
$mensaje = null;

if (isset($_POST['accion']) && $_POST['accion'] === 'enviar') {
  $items = $_SESSION['carrito'] ?? [];

  if (empty($items)) {
    $mensaje = "Tu carrito está vacío.";
  } else {
    // Capturar datos del cliente (demo)
    $nombre   = trim($_POST['nombre'] ?? '');
    $direccion= trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    if ($nombre === '' || $direccion === '' || $telefono === '') {
      $mensaje = "Por favor completa nombre, dirección y teléfono.";
    } else {
      // (Validación simple)
      if (!preg_match('/^[0-9+ ]+$/', $telefono)) {
        $mensaje = "El teléfono solo debe contener números, + o espacios.";
      } else {
        // Guardar pedido demo en sesión
        $_SESSION['pedido_demo'] = [
          'fecha' => date('Y-m-d H:i:s'),
          'cliente' => [
            'nombre' => $nombre,
            'direccion' => $direccion,
            'telefono' => $telefono,
          ],
          'items' => $items,
        ];

        $mensaje = "Compra registrada (demo). ¡Gracias, pronto te contactaremos!";

        // Opcional: vaciar carrito después de enviar
        // unset($_SESSION['carrito']);
        // Para evitar que se repita el envío con los mismos items:
        unset($_SESSION['carrito']);
      }
    }
  }
}

$items = $_SESSION['carrito'] ?? [];

// Total
$total = 0;
foreach ($items as $item) {
  $total += $item['precio'];
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Carrito</title>
</head>
<body style="font-family: Arial, sans-serif; background: beige; margin: 20px;">

  <h1>Tu Carrito</h1>

  <?php if (!empty($mensaje)): ?>
    <p style="background:#e7ffe7; padding:10px; border:1px solid #7bd67b;">
      <?= htmlspecialchars($mensaje) ?>
    </p>
  <?php endif; ?>

  <?php if (empty($items)): ?>
    <p>Tu carrito está vacío.</p>
    <p><a href="login.html">Volver al catálogo</a></p>
  <?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 900px;">
      <thead>
        <tr style="background:#f2f2f2;">
          <th>Producto</th>
          <th>Precio (Q.)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['producto']) ?></td>
            <td><?= number_format((float)$item['precio'], 2, '.', ',') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h2>Total: Q. <?= number_format((float)$total, 2, '.', ',') ?></h2>

    <!-- Formulario de datos del cliente + botones -->
    <form method="POST" action="carrito.php" style="margin-top: 15px;">
      <input type="hidden" name="accion" value="enviar">

      <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top: 10px;">
        <label style="display:flex; flex-direction:column; min-width:220px;">
          Nombre
          <input
            name="nombre"
            required
            style="padding:8px; border-radius:6px; border:1px solid #ccc;"
            type="text"
            placeholder="Tu nombre"
          >
        </label>

        <label style="display:flex; flex-direction:column; min-width:260px; flex:1;">
          Dirección
          <input
            name="direccion"
            required
            style="padding:8px; border-radius:6px; border:1px solid #ccc;"
            type="text"
            placeholder="Calle, zona, municipio"
          >
        </label>

         <label style="display:flex; flex-direction:column; min-width:260px; flex:1;">
          tarjeta de credito/debito
          <input
            name="direccion"
            required
            style="padding:8px; border-radius:6px; border:1px solid #ccc;"
            type="text"
            placeholder="0000 0000 0000 0000"
          >
        </label>

        <label style="display:flex; flex-direction:column; min-width:180px;">
          Teléfono
          <input
            name="telefono"
            required
            style="padding:8px; border-radius:6px; border:1px solid #ccc;"
            type="text"
            placeholder="0000 0000"
            pattern="[0-9+ ]+"
          >
        </label>
      </div>

      <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top: 15px;">
        <button type="submit"
          style="padding:10px 16px; border:2px solid #000; border-radius:8px; font-weight:bold; background:#dfffd7;">
          Finalizar
        </button>

        <button type="submit" formaction="carrito.php" formmethod="POST" name="accion" value="borrar"
          style="padding:10px 16px; border:2px solid #000; border-radius:8px; font-weight:bold; background:#ffd7d7;">
          Borrar carrito
        </button>
      </div>
    </form>

    <p style="margin-top: 15px;">
      <a href="login.html">Seguir comprando</a>
    </p>
  <?php endif; ?>

</body>
</html>