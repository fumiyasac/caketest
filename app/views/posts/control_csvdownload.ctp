<?php
$csv->addRow($headRow);
foreach ($contentsRows as $contentsRow) {
    //CSV出力用のforeach
    $csv->addField($contentsRow['Post']['id']);
    $csv->addField($contentsRow['Post']['title']);
    $csv->addField($contentsRow['Post']['post_image']);
    $csv->addField($contentsRow['Post']['description']);
    $csv->addField($contentsRow['Post']['start_date']);
    $csv->addField($contentsRow['Post']['end_date']);
    $csv->addField(Configure::read("FLAG_CONF.flag.{$contentsRow['Post']['flag']}"));
    $csv->endRow();
}
$csv->setFilename($filename);
echo $csv->render(true, 'sjis', 'UTF-8'); 
