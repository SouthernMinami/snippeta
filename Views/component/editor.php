<?php
$languages = ["Javascript", "Python", "PHP", "Ruby", "Java", "C", "C#", "C++", "Swift", "Go", "Scala", "Kotlin", "Typescript", "Rust", "Shell", "SQL", "Plaintext"];

?>

<div class="editor-container">
    <div class="editor-header">
        <div class="editor-header-left">
            <select id="language-list" name="language">
                <?php foreach ($languages as $language): ?>
                    <option class="language-item" value="<?= $language ?>"><?= $language ?></option>
                <?php endforeach; ?>
            </select>

        </div>
        <div class="editor-header-right">
            <button class="btn btn-secondary" id="copy-btn">Copy</button>
            <button class="btn btn-primary" id="download-btn">Download .js file</button>
        </div>
    </div>
    <div class="editor-body">
        <div id="editor" class="editor"></div>
    </div>
</div>