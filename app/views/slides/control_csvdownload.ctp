<?php
$csv->addRow($headRow);
foreach ($contentsRows as $contentsRow) {
    //CSV出力用のforeach
    $csv->addField($contentsRow['Slide']['id']);
    $csv->addField($contentsRow['Slide']['title']);
    $csv->addField($contentsRow['Slide']['description']);
    $csv->addField($contentsRow['Slide']['slide_image']);
    $csv->addField($contentsRow['Slide']['link_url']);
    $csv->addField(Configure::read("LINK_CONF.flag.{$contentsRow['Slide']['blank_flag']}"));
    $csv->addField($contentsRow['Slide']['published']);
    $csv->addField(Configure::read("FLAG_CONF.flag.{$contentsRow['Slide']['flag']}"));
    $csv->endRow();
}
$csv->setFilename($filename);
echo $csv->render(true, 'sjis', 'UTF-8'); 
