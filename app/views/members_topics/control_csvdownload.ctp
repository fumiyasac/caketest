<?php
$csv->addRow($headRow);
foreach ($contentsRows as $contentsRow) {
    //CSV出力用のforeach
    $csv->addField($contentsRow['MembersTopic']['id']);
    $csv->addField($contentsRow['MembersTopic']['title']);
    $csv->addField($contentsRow['MembersTopic']['kcpy']);
    $csv->addField($contentsRow['MembersTopic']['description']);
    $csv->addField($contentsRow['MembersTopic']['member_topic_image']);
    $csv->addField($contentsRow['MembersTopic']['published']);
    $csv->addField(Configure::read("FLAG_CONF.flag.{$contentsRow['MembersTopic']['flag']}"));
    $csv->endRow();
}
$csv->setFilename($filename);
echo $csv->render(true, 'sjis', 'UTF-8'); 
