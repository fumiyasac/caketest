<?php
$csv->addRow($headRow);
foreach ($contentsRows as $contentsRow) {
    //CSV出力用のforeach
    $csv->addField($contentsRow['Contact']['id']);
    $csv->addField($contentsRow['Contact']['name']);
    $csv->addField($contentsRow['Contact']['kana']);
    $csv->addField($contentsRow['Contact']['mail']);
    $csv->addField(Configure::read("CONTACT_CONF.title.{$contentsRow['Contact']['purpose']}"));
    $csv->addField($contentsRow['Contact']['purpose_etc']);
    $csv->addField($contentsRow['Contact']['text']);
    $csv->addField(Configure::read("ENQUETE_CONF.enquete1.{$contentsRow['Contact']['enquete1']}"));
    $csv->addField(Configure::read("ENQUETE_CONF.enquete2.{$contentsRow['Contact']['enquete2']}"));
    $csv->addField($contentsRow['Contact']['enquete3']);
    $csv->addField($contentsRow['Contact']['enquete4']);
    $csv->addField($contentsRow['Contact']['enquete5']);
    $csv->endRow();  
}
$csv->setFilename($filename);
echo $csv->render(true, 'sjis', 'UTF-8'); 
