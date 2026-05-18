<?php
/**
 * admin.php — Мини-админка TOC
 * Создание и редактирование статей для toc.chernetchenko.pro
 */

require_once __DIR__ . '/config.php'; // тот же config что и на main

session_start();

if (isset($_GET['logout'])) { session_destroy(); header('Location: admin.php'); exit; }

if (isset($_POST['password'])) {
    if (hash_equals(ADMIN_PASSWORD, $_POST['password'])) {
        $_SESSION['toc_admin'] = true;
        header('Location: admin.php');
        exit;
    }
    $err = 'Неверный пароль';
}

if (empty($_SESSION['toc_admin'])): ?>
<!DOCTYPE html>
<html lang="ru"><head><meta charset="UTF-8"><title>TOC · Вход</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'JetBrains Mono',monospace;background:#1a1612;display:flex;align-items:center;justify-content:center;min-height:100vh}
.box{background:#2a2218;border:1px solid #b8860b;border-radius:8px;padding:40px;width:340px}
h1{color:#b8860b;font-size:1.2rem;margin-bottom:24px;text-align:center}
label{display:block;color:rgba(255,255,255,.5);font-size:.7rem;text-transform:uppercase;margin-bottom:6px}
input{width:100%;padding:10px;background:#1a1612;border:1px solid #b8860b;border-radius:4px;color:#fff;font-family:inherit;font-size:.9rem;outline:none}
button{width:100%;margin-top:12px;padding:10px;background:#b8860b;color:#fff;border:none;border-radius:4px;font-family:inherit;font-weight:700;cursor:pointer;font-size:.9rem}
.err{color:#ef4444;font-size:.75rem;margin-top:8px;text-align:center}
</style></head><body>
<div class="box">
    <h1>TOC · Господин Оформитель</h1>
    <form method="post">
        <label>Пароль</label>
        <input type="password" name="password" autofocus required>
        <?php if (isset($err)): ?><div class="err"><?= htmlspecialchars($err) ?></div><?php endif; ?>
        <button type="submit">Войти →</button>
    </form>
</div>
</body></html>
<?php exit; endif;

// === АВТОРИЗОВАН ===

$contentDir = __DIR__ . '/content';
if (!is_dir($contentDir)) mkdir($contentDir, 0755, true);

// CSRF
if (empty($_SESSION['toc_csrf'])) $_SESSION['toc_csrf'] = bin2hex(random_bytes(32));
$csrf = $_SESSION['toc_csrf'];

$msg = ''; $msgErr = false;
$isEditing = false;
$fields = ['title'=>'','section'=>'estimates','description'=>'','tags'=>'','content'=>'','draft'=>false,'slug'=>''];

function yaml_esc(string $v): string { return '"' . str_replace('"', '\"', $v) . '"'; }

function parseMd(string $path): ?array {
    if (!is_file($path)) return null;
    $raw = file_get_contents($path);
    if (!preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)/s', $raw, $m)) return ['meta'=>[],'body'=>$raw];
    $meta = [];
    foreach (explode("\n", $m[1]) as $line) {
        if (!str_contains($line, ':')) continue;
        [$k, $v] = explode(':', $line, 2);
        $k = trim(strtolower($k)); $v = trim($v);
        if (preg_match('/^\[(.*)]\$/', $v, $a)) $meta[$k] = array_map(fn($i)=>trim(trim($i),'"\''), explode(',', $a[1]));
        elseif ($v === 'true') $meta[$k] = true;
        elseif ($v === 'false') $meta[$k] = false;
        else $meta[$k] = trim($v, '"\'');
    }
    return ['meta'=>$meta,'body'=>$m[2]];
}

// Превью (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'preview') {
    header('Content-Type: text/html; charset=UTF-8');
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
        echo (new \Parsedown())->setSafeMode(true)->text($_POST['content'] ?? '');
    } else {
        echo nl2br(htmlspecialchars($_POST['content'] ?? ''));
    }
    exit;
}

// Сохранение статьи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $slug    = preg_replace('/[^a-z0-9\-_]/i', '', trim($_POST['slug'] ?? ''));
    $title   = trim($_POST['title'] ?? '');
    $section = in_array($_POST['section']??'', ['estimates','management','training'], true) ? $_POST['section'] : 'estimates';
    $desc    = trim($_POST['description'] ?? '');
    $tags    = trim($_POST['tags'] ?? '');
    $content = $_POST['content'] ?? '';
    $isDraft = isset($_POST['draft']) ? 'true' : 'false';
    $date    = date('Y-m-d');

    $old = parseMd("$contentDir/$slug.md");
    if ($old && !empty($old['meta']['date'])) $date = $old['meta']['date'];

    $yaml = "title: " . yaml_esc($title) . "\nslug: " . yaml_esc($slug)
          . "\nsite: \"toc\"\nsection: " . yaml_esc($section)
          . "\ndate: " . yaml_esc($date)
          . "\ntags: [" . implode(', ', array_map('yaml_esc', array_filter(array_map('trim', explode(',', $tags))))) . "]"
          . "\ndraft: $isDraft\ndescription: " . yaml_esc($desc) . "\n";

    if (@file_put_contents("$contentDir/$slug.md", "---\n$yaml---\n\n$content", LOCK_EX) !== false) {
        $msg = '✅ Статья сохранена';
        $fields = ['title'=>'','section'=>'estimates','description'=>'','tags'=>'','content'=>'','draft'=>false,'slug'=>''];
        $isEditing = false;
    } else {
        $msg = '❌ Ошибка записи — проверь права на content/'; $msgErr = true;
    }
}

// Удаление
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    if (hash_equals($csrf, $_POST['csrf_token'] ?? '')) {
        $ds = preg_replace('/[^a-z0-9\-_]/i', '', $_POST['delete']);
        $dp = "$contentDir/$ds.md";
        if (is_file($dp)) rename($dp, "$dp.bak");
        $msg = '🗑 Статья удалена (сохранена как .bak)';
    }
}

// Редактирование
if (isset($_GET['edit'])) {
    $editSlug = preg_replace('/[^a-z0-9\-_]/i', '', $_GET['edit']);
    $art = parseMd("$contentDir/$editSlug.md");
    if ($art) {
        $isEditing = true;
        $fields = [
            'title'       => $art['meta']['title'] ?? '',
            'section'     => $art['meta']['section'] ?? 'estimates',
            'description' => $art['meta']['description'] ?? '',
            'tags'        => implode(', ', (array)($art['meta']['tags'] ?? [])),
            'content'     => $art['body'] ?? '',
            'draft'       => !empty($art['meta']['draft']),
            'slug'        => $art['meta']['slug'] ?? $editSlug,
        ];
    }
}

// Список статей
$articles = [];
foreach (glob("$contentDir/*.md") ?: [] as $f) {
    $p = parseMd($f);
    if (!$p) continue;
    $articles[] = [
        'slug'  => $p['meta']['slug']  ?? pathinfo($f, PATHINFO_FILENAME),
        'title' => $p['meta']['title'] ?? 'Без заголовка',
        'section' => $p['meta']['section'] ?? '-',
        'draft' => !empty($p['meta']['draft']),
        'date'  => $p['meta']['date'] ?? '',
    ];
}
usort($articles, fn($a,$b) => strtotime($b['date']) <=> strtotime($a['date']));

// Транслитерация (JS)
$translit = "const map={а:'a',б:'b',в:'v',г:'g',д:'d',е:'e',ё:'yo',ж:'zh',з:'z',и:'i',й:'y',к:'k',л:'l',м:'m',н:'n',о:'o',п:'p',р:'r',с:'s',т:'t',у:'u',ф:'f',х:'kh',ц:'ts',ч:'ch',ш:'sh',щ:'shch',ъ:'',ы:'y',ь:'',э:'e',ю:'yu',я:'ya'};";
?>
<!DOCTYPE html>
<html lang="ru"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>TOC · Редактор</title>
<link rel="stylesheet" href="/style.css">
<style>
.adm{max-width:1100px;margin:0 auto;padding:24px 20px}
.adm-hd{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
.adm-hd h1{font-family:'JetBrains Mono',monospace;font-size:1.2rem;color:var(--accent)}
.adm-grid{display:grid;grid-template-columns:300px 1fr;gap:20px;margin-bottom:24px}
.adm-panel{background:#fff;border:1.5px solid var(--border);border-radius:8px;padding:20px}
.adm-panel h3{font-size:.8rem;text-transform:uppercase;letter-spacing:.05em;color:var(--ink3);margin-bottom:14px;font-family:'JetBrains Mono',monospace}
.form-row{margin-bottom:12px}
.form-row label{display:block;font-size:.72rem;text-transform:uppercase;color:var(--ink3);margin-bottom:4px;font-weight:600}
input[type=text],textarea,select{width:100%;padding:8px 10px;border:1.5px solid var(--border);border-radius:4px;font-family:inherit;font-size:.85rem;color:var(--ink);background:#faf7f2;outline:none;transition:border-color .2s}
input:focus,textarea:focus,select:focus{border-color:var(--accent)}
textarea{min-height:320px;font-family:'JetBrains Mono',monospace;font-size:.8rem;resize:vertical}
.btn{display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border-radius:4px;font-size:.8rem;font-weight:700;cursor:pointer;border:none;transition:all .15s;font-family:'JetBrains Mono',monospace;text-transform:uppercase;letter-spacing:.03em;text-decoration:none}
.btn-gold{background:var(--accent);color:#fff}.btn-gold:hover{opacity:.88}
.btn-ghost{background:transparent;color:var(--ink3);border:1.5px solid var(--border)}.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.btn-red{background:#ef4444;color:#fff}
.btn-sm{padding:4px 10px;font-size:.72rem}
.btn-row{display:flex;gap:8px;margin-top:8px;flex-wrap:wrap}
.alert{padding:10px 14px;border-radius:4px;margin-bottom:16px;font-size:.85rem;font-weight:500}
.alert-ok{background:#dcfce7;border:1px solid #86efac;color:#166534}
.alert-err{background:#fee2e2;border:1px solid #fca5a5;color:#991b1b}
.preview-pane{padding:14px;border:1.5px solid var(--border);background:#faf7f2;min-height:320px;border-radius:4px;font-size:.9rem;line-height:1.7;overflow-y:auto}
table{width:100%;border-collapse:collapse;font-size:.83rem}
th{background:#f0ede7;padding:8px 12px;text-align:left;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:var(--ink3)}
td{padding:8px 12px;border-top:1px solid var(--border);vertical-align:middle}
.bdg{display:inline-block;padding:2px 7px;border-radius:3px;font-size:.68rem;font-weight:700;text-transform:uppercase}
.bdg-gold{background:rgba(184,134,11,.15);color:#b8860b}
.bdg-draft{background:#fef3c7;color:#92400e}
.bdg-pub{background:#dcfce7;color:#166534}
@media(max-width:800px){.adm-grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<?php
$pageTitle = 'TOC · Редактор';
$siteId = 'toc';
// Подключаем только navbar без лишнего
?>
<div style="background:#1a1612;padding:8px 20px;display:flex;gap:16px;align-items:center;font-family:'JetBrains Mono',monospace;font-size:11px">
    <a href="/" style="color:#b8860b;text-decoration:none;font-weight:700">TOC</a>
    <a href="https://chernetchenko.pro/admin/" style="color:rgba(255,255,255,.5);text-decoration:none">← Главная панель</a>
    <span style="color:rgba(255,255,255,.2)">|</span>
    <span style="color:rgba(255,255,255,.5)">Редактор статей</span>
    <a href="?logout=1" style="margin-left:auto;color:rgba(255,255,255,.3);text-decoration:none">Выйти</a>
</div>

<div class="adm">
    <div class="adm-hd">
        <h1>✍️ <?= $isEditing ? 'Редактировать статью' : 'Новая статья' ?></h1>
        <?php if ($isEditing): ?><a href="admin.php" class="btn btn-ghost btn-sm">+ Новая</a><?php endif; ?>
    </div>

    <?php if ($msg): ?>
    <div class="alert <?= $msgErr?'alert-err':'alert-ok' ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="save" value="1">
        <div class="adm-grid">
            <div class="adm-panel">
                <h3>Мета-информация</h3>
                <div class="form-row">
                    <label>Раздел</label>
                    <select name="section">
                        <option value="estimates"  <?= ($fields['section']==='estimates')?'selected':'' ?>>Оценки и сметы</option>
                        <option value="management" <?= ($fields['section']==='management')?'selected':'' ?>>Управление</option>
                        <option value="training"   <?= ($fields['section']==='training')?'selected':'' ?>>Обучение</option>
                    </select>
                </div>
                <div class="form-row">
                    <label>Заголовок *</label>
                    <input type="text" name="title" id="inp-title" value="<?= htmlspecialchars($fields['title']) ?>" required placeholder="Заголовок статьи">
                </div>
                <div class="form-row">
                    <label>Slug *</label>
                    <input type="text" name="slug" id="inp-slug" value="<?= htmlspecialchars($fields['slug']) ?>" pattern="[a-z0-9\-_]+" required <?= $isEditing?'readonly':'' ?>>
                </div>
                <div class="form-row">
                    <label>SEO-описание</label>
                    <input type="text" name="description" value="<?= htmlspecialchars($fields['description']) ?>">
                </div>
                <div class="form-row">
                    <label>Теги (через запятую)</label>
                    <input type="text" name="tags" value="<?= htmlspecialchars($fields['tags']) ?>">
                </div>
                <div class="form-row">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;text-transform:none">
                        <input type="checkbox" name="draft" style="width:auto" <?= !empty($fields['draft'])?'checked':'' ?>>
                        Черновик
                    </label>
                </div>
                <button type="submit" class="btn btn-gold" style="width:100%">
                    <?= $isEditing ? '💾 Сохранить' : '✨ Завизировать' ?>
                </button>
            </div>

            <div class="adm-panel">
                <h3>Контент</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div>
                        <label style="display:block;font-size:.72rem;text-transform:uppercase;color:var(--ink3);margin-bottom:4px">Markdown</label>
                        <textarea name="content" id="inp-md"><?= htmlspecialchars($fields['content']) ?></textarea>
                    </div>
                    <div>
                        <label style="display:block;font-size:.72rem;text-transform:uppercase;color:var(--ink3);margin-bottom:4px">Превью</label>
                        <div class="preview-pane" id="preview">Нажми «Обновить»</div>
                    </div>
                </div>
                <div class="btn-row">
                    <button type="button" class="btn btn-ghost btn-sm" onclick="updatePreview()">👁 Обновить превью</button>
                </div>
            </div>
        </div>
    </form>

    <div class="adm-panel">
        <h3>Все статьи TOC (<?= count($articles) ?>)</h3>
        <table>
            <thead><tr><th>Заголовок</th><th>Раздел</th><th>Дата</th><th>Статус</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($articles as $art): ?>
            <tr>
                <td><a href="?edit=<?= htmlspecialchars($art['slug']) ?>" style="color:var(--accent);font-weight:600"><?= htmlspecialchars($art['title']) ?></a></td>
                <td><span class="bdg bdg-gold"><?= htmlspecialchars($art['section']) ?></span></td>
                <td style="color:var(--ink3);font-size:.78rem"><?= htmlspecialchars($art['date']) ?></td>
                <td><?= $art['draft'] ? '<span class="bdg bdg-draft">Черновик</span>' : '<span class="bdg bdg-pub">Опубл.</span>' ?></td>
                <td>
                    <a href="?edit=<?= htmlspecialchars($art['slug']) ?>" class="btn btn-ghost btn-sm">✏️</a>
                    <form method="post" style="display:inline" onsubmit="return confirm('Удалить?')">
                        <input type="hidden" name="delete" value="<?= htmlspecialchars($art['slug']) ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                        <button type="submit" class="btn btn-red btn-sm">🗑</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
<?= $translit ?>
const ti = document.getElementById('inp-title');
const si = document.getElementById('inp-slug');
if (ti && si && !<?= $isEditing?'true':'false' ?>) {
    ti.addEventListener('input', function() {
        si.value = this.value.toLowerCase().split('').map(c=>map[c]!==undefined?map[c]:(c.match(/[a-z0-9-]/)?c:'-')).join('').replace(/-+/g,'-').replace(/^-|-$/g,'');
    });
}
function updatePreview() {
    const fd = new FormData();
    fd.append('action','preview');
    fd.append('content', document.getElementById('inp-md').value);
    fetch('', {method:'POST',body:fd}).then(r=>r.text()).then(h=>document.getElementById('preview').innerHTML=h);
}
document.getElementById('inp-md').addEventListener('input', function() {
    clearTimeout(this._t);
    this._t = setTimeout(updatePreview, 800);
});
</script>
</body></html>
