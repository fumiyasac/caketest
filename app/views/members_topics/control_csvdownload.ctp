<?php
$csv->addRow($headRow);
foreach ($contentsRows as $contentsRow) {
    //CSV出力用のforeach
    $csv->addField($contentsRow['Special']['id']);
    $csv->addField($contentsRow['Special']['title']);
    $csv->addField($contentsRow['Special']['kcpy']);
    $csv->addField($contentsRow['Special']['image_main']);
    $csv->addField($contentsRow['Special']['description_main']);
    $csv->addField($contentsRow['Special']['title_sub1']);
    $csv->addField($contentsRow['Special']['image_sub1']);
    $csv->addField($contentsRow['Special']['description_sub1']);
    $csv->addField($contentsRow['Special']['title_sub2']);
    $csv->addField($contentsRow['Special']['image_sub2']);
    $csv->addField($contentsRow['Special']['description_sub2']);
    $csv->addField($contentsRow['Special']['title_sub3']);
    $csv->addField($contentsRow['Special']['image_sub3']);
    $csv->addField($contentsRow['Special']['description_sub3']);
    $csv->addField($contentsRow['Special']['other_description']);
    $csv->addField($contentsRow['Special']['published']);
    $csv->addField(Configure::read("FLAG_CONF.flag.{$contentsRow['Special']['flag']}"));
    $csv->endRow();
}
$csv->setFilename($filename);
echo $csv->render(true, 'sjis', 'UTF-8'); 
