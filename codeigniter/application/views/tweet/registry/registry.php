<h2>ユーザー登録</h2>

<?php echo validation_errors();?>

<?php echo form_open('tweet/registry')?>
    ユーザー名
    <input type = "text" name = "name" value = "<?php echo set_value('name');?>"/><br/>
    メールアドレス
    <input type = "text" name = "mailaddress" value = "<?php echo set_value('mailaddress');?>"/><br/>
    パスワード
    <input type = "password" name = "password" value = "<?php echo set_value('password');?>"/><br/>
    パスワード(確認)
    <input type = "password" name = "passconfirm" value = "<?php echo set_value('passconfirm');?>"/><br/>

    <input type = "submit" name = "submit" value = "登録する"/>
</form>
</br>
パスワードは6文字以上の英数字