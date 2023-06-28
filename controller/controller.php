<?
require_once(__DIR__ . '/../class/class.php');

$DB = new DB();

if(isset($_GET['id'])) {
    $arrayResult = $DB->GetById('news', (int)$_GET['id']);
    include_once(__DIR__ . '/../templates/detail.php');
} else {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $arrayResult['items'] = $DB->GetList('news', [], ['id', 'date', 'title', 'announce'], ['date' => 'desc'], (($page - 1) * 4), 4);
    $arrayResult['pagesCount'] = ceil($DB->Count('news') / 4); 
    $arrayResult['banner'] = $DB->GetList('news', [], ['image', 'title', 'announce'], ['date' => 'desc'], 0, 1)[0];
    include_once(__DIR__ . '/../templates/index.php');
}