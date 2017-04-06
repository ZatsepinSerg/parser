<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 21.03.2017
 * Time: 17:48
 */

require_once 'DB/DataBase.php';

$html = file_get_contents('http://tcpulse.zpao.com/news/');

preg_match_all( '/<span class=[\'|\"]blurb[\'|\"]>.*?<\/span>/' ,$html  ,$discript);
preg_match_all( '/<span class=[\'|\"]date-author[\'|\"]>.*?<\/span>/' ,$html  ,$date);
preg_match_all( '/<a class=[\'|\"]headline[\'|\"] href=\"(.+?)\">(.*?)<\/a>/' ,$html  ,$title);


$hrefs=$title[1];
foreach ($hrefs AS $href){
    $item= file_get_contents('http://tcpulse.zpao.com'.$href);

    //preg_match( '/   <span class=[\'|\"]article-body[\'|\"]>.*?[<a[^>].*<\/a>|div[^>]]/' ,$item ,$bodyns[]);
    preg_match_all( '/<span class=[\'|\"]article-body[\'|\"]>.*/' ,$item ,$bodyn[]);

}

foreach ($bodyn AS $body){
    foreach ( $body AS $bod){
        $bo[]=strip_tags($bod[0],'<a><br><p>');
        $count=count($bo);
    }
}

$titles=$title[2];

foreach ($titles AS $title){
    $titlei[]= $title;
}
$art[]=$titlei;

    for ($i=0;$i<=$count-1;$i++){

    preg_match('/\D{3}\s\d{2},\s\d{4}\s\d{1,2}:\d{2}\s\D{2}/is', $date[0][$i], $times);
    preg_match('/ \|\sby\s([A-Za-z0-9-].+\Z)/is', $date[0][$i], $author);
    preg_match('/\A[A-Za-z0-9-].+\Z/', $art[0][$i], $headline);

        $body=$bo[$i];
    $today = strtotime($times[0]);
    $times = date("Y-m-d H:i", $today);


        $headline = addslashes($headline[0]);

        $body=addslashes($bo[$i]);
        $body=strip_tags($body);
        $author= addslashes($author[1]);
        $author=strip_tags($author);

        $id=authors_search($author);
        $news=news_search($headline);

    if((!($news) && !($id))){
        authors_insert($author);
        $id=authors_search($author);
        insert($headline,$body,$times,$id);
    }elseif((!$news) && $id){
        insert($headline,$body,$times,$id);
    }
    elseif($news  && $id){
            echo "Уже есть такая запись<br>";
        }
    }