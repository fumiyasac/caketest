<?php
$csv->addRow($headRow);
foreach ($contentsRows as $contentsRow) {
    //CSV出力用のforeach
    $csv->addField($contentsRow['CatalogsComment']['id']);
    $csv->addField($contentsRow['CatalogsComment']['catalog_id']);
    $csv->addField($contentsRow['CatalogsComment']['username']);
    $csv->addField($contentsRow['CatalogsComment']['text']);
    $csv->addField($contentsRow['CatalogsComment']['published']);
    $csv->addField(Configure::read("FLAG_CONF.flag.{$contentsRow['CatalogsComment']['flag']}"));
    $csv->endRow();
}
$csv->setFilename($filename);
echo $csv->render(true, 'sjis', 'UTF-8'); 
