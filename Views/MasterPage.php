<!-- MASTER PAGE -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><!-- Ttitulo del sitio web --></title>
  <link rel="stylesheet" href="/bienesraicesMVC/public/build/css/app.css">
</head>
<body>
  <!-- Header -->
  <header class="header <?php echo $inicio ? "inicio" : "";?>">
    <!-- Codigo de header aqui:  -->
  </header>
  
  <!-- Mostrar contenido especifico de cada página -->
  <?php echo $contenido; ?>

  <!-- Footer -->
  <footer class="footer seccion">
    <!-- Vódigo de Footer aqui: --> 
  </footer>
  <script src="/bienesraicesMVC/public/build/js/bundle.min.js"></script>
</body>
</html>
