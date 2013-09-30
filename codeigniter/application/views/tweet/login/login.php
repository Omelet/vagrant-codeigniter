<h2>ログイン</h2>
<?php echo validation_errors();?>

<?php echo form_open('tweet/login')?>
    メールアドレス
    <input type = "text" name = "mailaddress" value = "<?php echo set_value('mailaddress');?>"/><br/>
    パスワード
    <input type = "password" name = "password" value = "<?php echo set_value('password');?>"/><br/>

    <input type = "submit" name = "submit" value = "ログインする"/>
</form>
<h4> 新規登録は<a href="registry">こちら</a></h4>