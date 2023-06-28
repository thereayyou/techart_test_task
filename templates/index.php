<?require_once(__DIR__ . '/header.php');?>

<?if($arrayResult['banner']) {?>
    <div class="prenews_banner" style="/images/<?=$arrayResult['banner']['image']?>">
        <div class="container">
            <div class="banner_title_and_description">
                <h1 class="banner_title"><?=$arrayResult['banner']['title']?></h1>
                <div class="banner_description">
                    <?=$arrayResult['banner']['announce']?>
                </div>
            </div>
        </div>
    </div>
<?}?>

<?if($arrayResult['items']) {?>
    <div class="container">
        <h1 class="news_title">Новости</h1>
        <div class="news_items">
            <?foreach($arrayResult['items'] as $item){?>
                <div class="news_item">
                    <div class="news_date"><?=date('d.m.Y', strtotime($item['date']))?></div>
                    <h2 class="news_item_title"><?=$item['title']?></h2>
                    <div class="news_description">
                        <?=$item['announce']?>
                    </div>
                    <a class="news_button" href="?id=<?=$item['id']?>">
                        <div>Подробнее</div>
                        <img src="/images/Arrow 1.svg" alt="arrow">
                    </a>
                </div>
            <?}?>
        </div>
    </div>
    <?if($arrayResult['pagesCount'] > 1){?>
        <?$getPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;?>
        <div class="container">
            <div class="pagination_items">
                <?if($getPage > 1) {?>
                    <a href="?page=<?=($getPage - 1)?>">
                        <span class="pagination_item prew"></span>
                    </a>
                <?}?>
                <?for ($i=1; $i <= $arrayResult['pagesCount']; $i++) {
                    if($getPage == $i) {?>
                        <div class="pagination_item active"><?=$i?></div>
                    <?} else {?>
                        <a class="pagination_item" href="?page=<?=$i?>"><?=$i?></a>
                    <?}?>
                <?}?>
                <?if($getPage < $arrayResult['pagesCount']) {?>
                    <a href="?page=<?=($getPage + 1)?>">
                        <span class="pagination_item next"></span>
                    </a>
                <?}?>
            </div>
        </div>
    <?}?>
<?}?>
<?require_once(__DIR__ . '/footer.php');?>