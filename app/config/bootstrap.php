<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */
Configure::write('MAIL_CONF', array(
    'admin' => array(
        'to' => 'fumiya.def.mathmatica@gmail.com',
        'from' => 'fumiya.def.mathmatica@gmail.com',
        'return' => 'fumiya.def.mathmatica@gmail.com',
        'subject' => '大塚「珍しいもん」Store | お問い合わせフォームよりお問い合わせがありました。',
        'template' => 'thanks_mail_admin',
   ),
   'custmor' => array(
        'to' => '',
        'from' => 'fumiya.def.mathmatica@gmail.com',
        'return' => 'fumiya.def.mathmatica@gmail.com',
        'subject' => '大塚「珍しいもん」Store | この度はお問い合わせ頂きまして誠にありがとうございます。',
        'template' => 'thanks_mail_customer', 
   ),
));


Configure::write('CONTACT_CONF', array(
    'title' => array(
        '1' => 'このブログに関すること全般',
        '2' => 'ストアに関すること全般',
        '3' => 'Webサイトの機能に関して',
        '4' => 'ライターさん募集に関して',
        '5' => 'ストアへの出店をしてみたい',
        '6' => '広告掲載に関して',
        '7' => 'その他',
   ),
));

Configure::write('ENQUETE_CONF', array(
    'enquete1' => array(
        '' => '',
        '1' => '10代',
        '2' => '20代',
        '3' => '30代',
        '4' => '40代',
        '5' => '50代',
        '6' => '60代',
        '7' => '70代以上',
   ),
    'enquete2' => array(
        '' => '',
        '1' => '農業・林業・漁業・鉱業',
        '2' => '建設業',
        '3' => '製造業',
        '4' => '電気・ガス・熱供給・水道業',
        '5' => '運輸・通信業',
        '6' => '卸売・小売業, 飲食店',
        '7' => '金融・保険業',
        '8' => '不動産業',
        '9' => 'サービス業',
        '10' => 'その他',
   ),
));

Configure::write('UPLOAD_TMP_PATH_CONF', array(
    'path' => array(
        '1' => 'tmp_special',
        '2' => 'tmp_banner',
        '3' => 'tmp_slide',
   ),
));

Configure::write('UPLOAD_PATH_CONF', array(
    'path' => array(
        '1' => 'special',
        '2' => 'banner',
        '3' => 'slide',
   ),
));

Configure::write('FLAG_CONF', array(
    'flag' => array(
        '0' => '公開中',
        '1' => '非公開',
        /* '2' => '日時を指定して公開', */
   ),
));

Configure::write('LINK_CONF', array(
    'flag' => array(
        '0' => '外部リンク',
        '1' => '内部リンク',
   ),
));
