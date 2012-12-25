<?php echo $this->Form->create('Contact', array('type' => 'post', 'action' => 'confirm')); ?>
<div>
    <p><?php echo $form_description; ?></p>
    <p>お問い合わせ内容</p>
    <table cellpadding="0" cellspacing="0" id="contactForm">
        <tr>
            <th>お名前&nbsp;<span class="required">※</span></th>
            <td>
                <?php echo $this->Form->text('name',array('class' => 'formArea')); ?>
                <?php echo $this->Form->error('name'); ?>
            </td>
        </tr>
        <tr>
            <th>フリガナ&nbsp;<span class="required">※</span></th>
            <td>
                <?php echo $this->Form->text('kana',array('class' => 'formArea')); ?>
                <?php echo $this->Form->error('kana'); ?>
            </td>
        </tr>
        <tr>
            <th>メールアドレス&nbsp;<span class="required">※</span></th>
            <td>
                <?php echo $this->Form->text('mail',array('class' => 'formArea')); ?>
                <?php echo $this->Form->error('mail'); ?>
            </td>
        </tr>
        <tr>
            <th>メールアドレス（確認用）&nbsp;<span class="required">※</span></th>
            <td>
                <?php echo $this->Form->text('mail_conf',array('class' => 'formArea')); ?>
                <?php echo $this->Form->error('mail_conf'); ?>
            </td>
        </tr>
        <tr>
            <th>お問い合わせ内容&nbsp;<span class="required">※</span></th>
            <td>
                <?php
                    echo $this->Form->input('purpose', array(
                        'div' => false, 
                        'type' => 'select', 
                        'empty' => '--お問い合わせ内容を選択--', 
                        'options' => Configure::read('CONTACT_CONF.title'),
                        'label' => false
                        ));
                ?>
                <br />
                <?php echo $this->Form->text('purpose_etc',array('class' => 'formArea')); ?>
                <?php echo $this->Form->error('purpose_etc'); ?>
            </td>
        </tr>
        <tr>
            <th>本文&nbsp;<span class="required">※</span></th>
            <td>
                <?php
                    echo $this->Form->textarea('text',array(
                        'class'=>'formAreaText',
                        'rows' => 5,
                        'cols' => 40
                    ));
                ?>
                <?php echo $this->Form->error('text'); ?>
            </td>
        </tr>
    </table>

    <p>アンケート（もしお時間があればご回答ください）</p>
    <table cellpadding="0" cellspacing="0" id="contactForm">
        <tr>
            <th>（Q1）あなたのご年齢を選択して下さい</th>
        </tr>
        <tr>
            <td>
                <?php
                    echo $this->Form->input('enquete1', array(
                        'div' => false, 
                        'type' => 'select', 
                        'empty' => '--あなたの年齢を選択--', 
                        'options' => Configure::read('ENQUETE_CONF.enquete1'),
                        'label' => false
                        ));
                ?>
            </td>
        </tr>
        <tr>
            <th>（Q2）あなたの職業の業種を選択して下さい</th>
        </tr>
        <tr>
            <td>
                <?php
                    echo $this->Form->input('enquete2', array(
                        'div' => false, 
                        'type' => 'select', 
                        'empty' => '--あなたの業種を選択--', 
                        'options' => Configure::read('ENQUETE_CONF.enquete2'),
                        'label' => false
                        ));
                ?>
            </td>
        </tr>
        <tr>
            <th>（Q3）現在のストアでご興味のある商品はありますか？</th>
        </tr>
        <tr>
            <td>
                <?php echo $this->Form->text('enquete3',array('class' => 'formArea')); ?>
            </td>
        </tr>
        <tr>
            <th>（Q4）あなたがよく利用しているオンラインショップは何ですか？</th>
        </tr>
        <tr>
            <td>
                <?php echo $this->Form->text('enquete4',array('class' => 'formArea')); ?>
            </td>
        </tr>
        <tr>
            <th>（Q5）Q4のオンラインショップを利用する理由があればお答え下さい。</th>
        </tr>
        <tr>
            <td>
                <?php
                    echo $this->Form->textarea('enquete5',array(
                        'class'=>'formAreaText',
                        'rows' => 5,
                        'cols' => 40
                    ));
                ?>
            </td>
        </tr>
    </table>
    <div class="sendbn">
        <?php echo $this->Form->submit('送信内容を確認する', array('div' => 'false')); ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>

