<?php
$csv->addRow($headRow);
foreach ($contentsRows as $contentsRow) {
    //CSV出力用のforeach
    $csv->addField($contentsRow['Newstopic']['id']);
    $csv->addField($contentsRow['Newstopic']['title']);
    $csv->addField($contentsRow['Newstopic']['newstopic_image']);
    $csv->addField($contentsRow['Newstopic']['description']);
    $csv->addField($contentsRow['Newstopic']['link_url']);
    $csv->addField(Configure::read("LINK_CONF.flag.{$contentsRow['Newstopic']['blank_flag']}"));
    $csv->addField($contentsRow['Newstopic']['published']);
    $csv->addField(Configure::read("FLAG_CONF.flag.{$contentsRow['Newstopic']['flag']}"));
    $csv->endRow();
}
$csv->setFilename($filename);
echo $csv->render(true, 'sjis', 'UTF-8'); 
