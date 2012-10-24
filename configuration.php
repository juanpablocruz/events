<?php
session_start();
if (!isset($_SESSION['CLASE'])) header("location:index.php");
header('Content-Type: text/html; charset=UTF-8');
echo "<p>Notificaciones</p>";
echo "<p>Privacidad</p>";
echo "<p>Contraseña</p>";
echo "<div ><p>Tema</p>
<select>
  <option>Clasico</option>
  <option>Default</option>
  <option>Gatitos</option>
</select></div>";
?>