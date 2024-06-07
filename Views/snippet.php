<?php

namespace Views;

?>

<script type="text/javascript">
    var snippet = <?php
    // renderer->getContent()内のextract()関数で展開された変数を取得
    echo json_encode($snippet); ?>;
</script>

<div class="title-container">
    <h1 class="page-title">Snippet</h1>
</div>
<div class="snippet-info">
    <h3>Title: <?php echo htmlspecialchars($snippet['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
        <p>Language: <?php echo htmlspecialchars($snippet['language'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Expiration Date: <?php echo htmlspecialchars($snippet['expiration_date'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Created At: <?php echo htmlspecialchars($snippet['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
</div>
<div class="content">
    <?php include './Views/component/editor.php'; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.41.0/min/vs/loader.min.js"></script>
<script src="../public/js/app_snippet.js"></script>