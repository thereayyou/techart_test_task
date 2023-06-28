<?require_once(__DIR__ . '/header.php');?>

<?if($arrayResult) {?>
    <div class="container">
    <div class="detail_sections">
        <a class="first_section" href="/">Главная</a>
        <div>/</div>
        <div class="second_section"><?=$arrayResult['title']?></div>
    </div>
    <h1 class="detail_main_title"><?=$arrayResult['title']?></h1>
    <div class="detail_content_container">
        <div class="first_column">
            <div class="detail_date"><?=date('d.m.Y', strtotime($arrayResult['date']))?></div>
            <h2 class="detail_title"><?=$arrayResult['title']?></h2>
            <div class="detail_description_items">
                <?=$arrayResult['content']?>
            </div>
            <div onClick="history.back()" class="detail_button">
                <img src="/images/Arrow 2.svg" alt="backArrow" />
                <div>Назад к новостям</div>
            </div>
        </div>
        <div class="second_column">
            <img src="/images/<?=$arrayResult['image']?>" alt="detailImage" />
        </div>
    </div>
</div>
    <?}?>
<?require_once(__DIR__ . '/footer.php');?>