<?php
/**
 * 404.php v2.0 — OVC Design System
 */
header('HTTP/1.1 404 Not Found');
$siteId    = 'main';
$pageTitle = '404 — Оптимизировано ИИ | chernetchenko.pro';
include 'header.php';
?>
<meta name="description" content="Страница не найдена. Видимо, её удалил ИИ в рамках штатной оптимизации.">
<link rel="canonical" href="https://chernetchenko.pro/404">

<style>
.page-404 {
  min-height: calc(100vh - 120px);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px 20px;
  text-align: center;
  position: relative;
  overflow: hidden;
}
/* Фоновый текст */
.page-404::before {
  content: "404";
  position: absolute;
  font-family: var(--font-code);
  font-size: clamp(12rem, 30vw, 20rem);
  font-weight: 700;
  color: rgba(255,255,255,.018);
  pointer-events: none;
  user-select: none;
  line-height: 1;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  z-index: 0;
}
/* Сканирующая линия */
.page-404::after {
  content: "";
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--c-ai), transparent);
  opacity: .4;
  animation: scan404 5s linear infinite;
  pointer-events: none;
  z-index: 1;
}
@keyframes scan404 { 0%{top:0} 100%{top:100%} }

.err-inner { position: relative; z-index: 2; max-width: 540px; }

.err-code {
  font-family: var(--font-code);
  font-size: clamp(4rem, 12vw, 7rem);
  font-weight: 700;
  color: var(--c-ai);
  line-height: 1;
  margin-bottom: 16px;
  text-shadow: 0 0 40px rgba(176,42,42,.3);
}
.err-sub {
  font-family: var(--font-code);
  font-size: .82rem;
  color: var(--c-ai);
  text-transform: uppercase;
  letter-spacing: .1em;
  font-weight: 700;
  margin-bottom: 20px;
}
.err-title {
  font-family: var(--font-code);
  font-size: clamp(1.1rem, 3vw, 1.5rem);
  font-weight: 700;
  color: var(--text-main);
  margin-bottom: 12px;
  line-height: 1.2;
}
.err-desc {
  color: var(--text-muted);
  font-size: .95rem;
  line-height: 1.7;
  margin-bottom: 32px;
}
.err-desc .code-inline {
  font-family: var(--font-code);
  color: var(--c-ai);
  background: rgba(176,42,42,.1);
  padding: 2px 8px;
  border-radius: 4px;
  border: 1px solid rgba(176,42,42,.25);
  font-size: .88em;
}
.err-status {
  margin-top: 40px;
  font-family: var(--font-code);
  font-size: .72rem;
  color: var(--text-dim);
  letter-spacing: .12em;
  text-transform: uppercase;
  opacity: .5;
}
</style>

<div class="page-404">
  <div class="err-inner">
    <div class="err-code">404</div>
    <div class="err-sub">
      <span class="cli-only">bash: cd: /page: </span>
      <span class="mgmt-only">Страница не найдена</span>
      <span class="cli-only">No such file or directory</span>
    </div>
    <h1 class="err-title">Система оптимизирована</h1>
    <p class="err-desc">
      Страница не найдена. Видимо, её удалил ИИ в рамках
      <span class="code-inline">штатной_оптимизации</span>.<br><br>
      В цифровом будущем WeAreFired лишние сущности и битые ссылки не предусмотрены.
    </p>
    <div class="btn-group" style="justify-content:center">
      <a href="/" class="btn btn-action c-main">
        <span class="cli-only">~/ </span>
        <span class="mgmt-only">→ </span>На главную
      </a>
      <a href="javascript:history.back()" class="btn btn-nav c-main">
        <span class="cli-only">&lt;_ </span>Назад
      </a>
    </div>
    <div class="err-status">STATUS: LOGICAL_ERROR_CLEANUP_COMPLETE</div>
  </div>
</div>

<?php include 'footer.php'; ?>
