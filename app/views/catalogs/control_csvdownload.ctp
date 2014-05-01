<?php
$csv->addRow($headRow);
foreach ($contentsRows as $contentsRow) {
    //CSV出力用のforeach
    $csv->addField($contentsRow['Catalog']['id']);
    $csv->addField($contentsRow['Catalog']['title']);
    $csv->addField($contentsRow['Catalog']['kcpy']);
    $csv->addField($contentsRow['Catalog']['template']);
    $csv->addField($contentsRow['Catalog']['catalog_image']);
    $csv->addField($contentsRow['Catalog']['description']);
    $csv->addField($contentsRow['Catalog']['published']);
    $csv->addField(Configure::read("FLAG_CONF.flag.{$contentsRow['Catalog']['flag']}"));
    $csv->endRow();
}
$csv->setFilename($filename);
echo $csv->render(true, 'sjis', 'UTF-8'); 
