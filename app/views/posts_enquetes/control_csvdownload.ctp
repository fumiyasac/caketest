<?php
$csv->addRow($headRow);
foreach ($contentsRows as $contentsRow) {
    //CSV出力用のforeach
    $csv->addField($contentsRow['PostsEnquete']['id']);
    $csv->addField($contentsRow['PostsEnquete']['post_id']);
    $csv->addField($contentsRow['PostsEnquete']['username']);
    $csv->addField($contentsRow['PostsEnquete']['enquete_question1']);
    $csv->addField($contentsRow['PostsEnquete']['enquete_question2']);
    $csv->addField($contentsRow['PostsEnquete']['enquete_question3']);
    $csv->addField($contentsRow['PostsEnquete']['enquete_question4']);
    $csv->addField($contentsRow['PostsEnquete']['enquete_question5']);
    $csv->addField(Configure::read("POST_PARTS_CONF.type.{$contentsRow['PostsEnquete']['enquete_type1']}"));
    $csv->addField(Configure::read("POST_PARTS_CONF.type.{$contentsRow['PostsEnquete']['enquete_type2']}"));
    $csv->addField(Configure::read("POST_PARTS_CONF.type.{$contentsRow['PostsEnquete']['enquete_type3']}"));
    $csv->addField(Configure::read("POST_PARTS_CONF.type.{$contentsRow['PostsEnquete']['enquete_type4']}"));
    $csv->addField(Configure::read("POST_PARTS_CONF.type.{$contentsRow['PostsEnquete']['enquete_type5']}"));
    $csv->addField($contentsRow['PostsEnquete']['enquete_answer1']);
    $csv->addField($contentsRow['PostsEnquete']['enquete_answer2']);
    $csv->addField($contentsRow['PostsEnquete']['enquete_answer3']);
    $csv->addField($contentsRow['PostsEnquete']['enquete_answer4']);
    $csv->addField($contentsRow['PostsEnquete']['enquete_answer5']);
    $csv->endRow();
}
$csv->setFilename($filename);
echo $csv->render(true, 'sjis', 'UTF-8'); 
