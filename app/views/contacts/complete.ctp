<div>
    <p><?php echo $form_description; ?></p>
    <div class="sendbn">
        <?php
        if($complete_link == 0){
            echo $this->Form->create('Contact', array('type' => 'post', 'url' => '../archives/index'));
            echo $this->Form->submit('TOP画面に戻る', array('div' => 'false'));
            echo $this->Form->end();
        }else{
            echo $this->Form->create('Contact', array('type' => 'post', 'action' => 'index'));
            echo $this->Form->submit('入力画面に戻る', array('div' => 'false'));
            echo $this->Form->end();                                
        } 
        ?>
    </div>
</div>