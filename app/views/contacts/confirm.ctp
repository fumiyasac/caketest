<div>
    <p><?php echo $form_description; ?></p>
    <p>お問い合わせ内容</p>
    <?php echo $this->Form->create('Contact'); ?>
    <table cellpadding="0" cellspacing="0" id="contactForm">
        <tr>
            <th>お名前&nbsp;<span class="required">※</span></th>
            <td>
                <?php echo h($this->Form->value('name')); ?>
            </td>
        </tr>
        <tr>
            <th>フリガナ&nbsp;<span class="required">※</span></th>
            <td>
                <?php echo h($this->Form->value('kana')); ?>
            </td>
        </tr>
        <tr>
            <th>メールアドレス&nbsp;<span class="required">※</span></th>
            <td>
                <?php echo h($this->Form->value('mail')); ?>
            </td>
        </tr>
        <tr>
            <th>メールアドレス（確認用）&nbsp;<span class="required">※</span></th>
            <td>
                <?php echo h($this->Form->value('mail_conf')); ?>
            </td>
        </tr>
        <tr>
            <th>お問い合わせ内容&nbsp;<span class="required">※</span></th>
            <td>
                <?php echo h(Configure::read("CONTACT_CONF.title.{$this->Form->value('purpose')}")); ?>
                <br />
                <?php echo $this->Form->value('purpose_etc'); ?>
            </td>
        </tr>
        <tr>
            <th>本文&nbsp;<span class="required">※</span></th>
            <td><?php echo nl2br(h($this->Form->value('text'))); ?></td>
        </tr>
    </table>

    <p>アンケート（もしお時間があればご回答ください）</p>
    <table cellpadding="0" cellspacing="0" id="contactForm">
        <tr>
            <th>（Q1）あなたのご年齢を選択して下さい</th>
        </tr>
        <tr>
            <td>
                <?php echo h(Configure::read("ENQUETE_CONF.enquete1.{$this->Form->value('enquete1')}")); ?>
            </td>
        </tr>
        <tr>
            <th>（Q2）あなたの職業の業種を選択して下さい</th>
        </tr>
        <tr>
            <td>
                <?php echo h(Configure::read("ENQUETE_CONF.enquete2.{$this->Form->value('enquete2')}")); ?>
            </td>
        </tr>
        <tr>
            <th>（Q3）現在のストアでご興味のある商品はありますか？</th>
        </tr>
        <tr>
            <td>
                <?php echo $this->Form->value('enquete3'); ?>
            </td>
        </tr>
        <tr>
            <th>（Q4）あなたがよく利用しているオンラインショップは何ですか？</th>
        </tr>
        <tr>
            <td>
                <?php echo $this->Form->value('enquete4'); ?>
            </td>
        </tr>
        <tr>
            <th>（Q5）Q4のオンラインショップを利用する理由があればお答え下さい。</th>
        </tr>
        <tr>
            <td>
                <?php echo nl2br(h($this->Form->value('enquete5'))); ?>
            </td>
        </tr>
    </table>
    <?php echo $this->Form->end(); ?>
    <div class="sendbn">
        <?php echo $this->Form->create('Contact', array('type' => 'post', 'action' => 'index')); ?>
        <?php echo $this->Formhidden->hiddenVars(); ?>
        <?php echo $this->Form->submit('入力画面に戻る', array('div' => 'false')); ?>
        <?php echo $this->Form->end(); ?>
        &nbsp;
        <?php echo $this->Form->create('Contact', array('type' => 'post', 'action' => 'complete')); ?>
        <?php echo $this->Formhidden->hiddenVars(); ?>
        <?php echo $this->Form->submit('この内容で送信する', array('div' => 'false')); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

