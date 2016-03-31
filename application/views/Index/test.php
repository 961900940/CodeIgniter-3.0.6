<?php foreach ($news as $news_item): ?>
    <h3><?php echo $news_item['name']; ?> : <?php echo $news_item['title']; ?></h3>
    <div class="main">
        <?php echo $news_item['remark']; ?>
    </div>
    <p><a href="<?php echo site_url('index/'.$news_item['type']); ?>">View article</a></p>

<?php endforeach; ?>