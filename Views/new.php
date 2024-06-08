<?php

namespace Views;

?>

<div class="title-container">
    <h1 class="page-title">New Snippet</h1>
    <p class="page-description">Share your code snippets with the world, and learn other snippets!</p>
</div>
<div class="content">
    <?php include __DIR__ . './Views/component/editor.php'; ?>
    <?php include __DIR__ . './Views/component/form.php'; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.41.0/min/vs/loader.min.js"></script>
<script src="../public/js/app_new.js"></script>