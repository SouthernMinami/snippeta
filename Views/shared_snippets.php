<?php

namespace Views;

use DateTime;

define('SNIPPETS_PER_PAGE', 5);
define('PAGE_COUNT', ceil(count($snippets) / SNIPPETS_PER_PAGE));

$currPage = isset($_GET['page']) ? $_GET['page'] : 1;
$indexOfLastSnippet = $currPage * SNIPPETS_PER_PAGE;
$indexOfFirstSnippet = $indexOfLastSnippet - SNIPPETS_PER_PAGE;

$currSnippets = array_slice($snippets, $indexOfFirstSnippet, $indexOfLastSnippet);

$now = new DateTime();
?>

<div class="table">
    <div class="table-head">
        <div class="th">Snippet Title</div>
        <div class="th">Language</div>
        <div class="th">Creation Date</div>
        <div class="th">Expiration Date</div>
    </div>
    <div class="table-body">
        <?php foreach ($currSnippets as $snippet): ?>
            <div class="tr">
                <div class="td"><a href="/snippet/<?= $snippet['path'] ?>"><?= $snippet['title'] ?></a></div>
                <div class="td"><?= $snippet['language'] ?></div>
                <div class="td"><?= $snippet['created_at'] ?></div>
                <div class="td"><?= $snippet['expiration_date'] ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="pagination">
    <?php foreach (range(1, PAGE_COUNT) as $page):
        $isCurrPage = $page == $currPage;
        $bgColor = $isCurrPage ? 'bg-gray' : 'bg-skyblue';

        echo '<a href="?page=' . $page . '" class="pagination-btn ' . $bgColor . ' ' . '">' . $page . '</a>';
    endforeach; ?>
</div>