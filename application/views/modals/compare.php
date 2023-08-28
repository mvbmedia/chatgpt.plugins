<div id="outlink">
    <div class="content">
        <div class="item">
            <?php
            if (!empty($result)) {
                foreach ($result as $key => $row) {
                    echo '<img src="/images/symbols/' . $row['symbol'] . '" data-subject="' . $row['ID'] . '" alt="symbol ' . $row['name'] . '" class="symbol" />';
                }
            }
            ?>
        </div>
        <div class="item">
            <a href="/compare/" class="btn btn-primary btn-medium btn-round">Naar vergelijker</a>
        </div>
    </div>
</div>