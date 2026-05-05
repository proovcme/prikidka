<?php 
  $pageTitle = "404 - Ограничение найдено"; 
  include 'header.php'; 
?>
<div class="container" style="text-align: center; padding: 100px 20px;">
    <h1 style="color: var(--red); font-size: 80px; margin: 0;">404</h1>
    <h2>Система нашла ограничение</h2>
    <p>Похоже, вы забрели туда, где задач еще не создано.</p>
    <a href="/calc" class="btn btn-dark">Вернуться к планированию</a>
</div>
<?php include 'footer.php'; ?>