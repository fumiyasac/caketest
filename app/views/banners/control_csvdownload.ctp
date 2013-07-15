<?php
$csv->addRow($headRow);
foreach ($contentsRows as $contentsRow) {
    //CSV出力用のforeach
    $csv->addField($contentsRow['Banner']['id']);
    $csv->addField($contentsRow['Banner']['title']);
    $csv->addField($contentsRow['Banner']['description']);
    $csv->addField($contentsRow['Banner']['banner_image']);
    $csv->addField($contentsRow['Banner']['link_url']);
    $csv->addField(Configure::read("LINK_CONF.flag.{$contentsRow['Banner']['blank_flag']}"));
    $csv->addField($contentsRow['Banner']['published']);
    $csv->addField(Configure::read("FLAG_CONF.flag.{$contentsRow['Banner']['flag']}"));
    $csv->endRow();
}
$csv->setFilename($filename);
echo $csv->render(true, 'sjis', 'UTF-8'); 
