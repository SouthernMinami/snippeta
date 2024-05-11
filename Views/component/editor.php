<?php
$languages = ["Javascript", "Python", "PHP", "Ruby", "java", "C", "C#", "C++", "Swift", "Go", "Scala", "Kotlin", "Typescript", "Rust", "Shell", "MySQL", "Plaintext"];

?>

<div class="editor-container">
    <div class="editor-header">
        <div class="editor-header-left">
            <select name="language">
                <? foreach ($languages as $language): ?>
                    <option value="<?= $language ?>"><?= $language ?></option>
                <? endforeach; ?>
            </select>

        </div>
        <div class="editor-header-right">
            <button class="btn btn-secondary" id="copyBtn">Copy</button>
            <button class="btn btn-primary" id="downloadBtn">Download .js file</button>
        </div>
    </div>
    <div class="editor-body">
        <div id="editor" class="editor"></div>
    </div>
</div>