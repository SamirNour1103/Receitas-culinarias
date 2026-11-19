<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/funcoes.php';
$url = BASE_URL;
?>


<footer class="footer">
    <p>Receitas Abdallah &copy; <?php echo date('Y'); ?> - Todos os direitos reservados</p>
  </footer>
  <script src="<?php echo $url; ?>js/js.js"></script>
</body>
</html>