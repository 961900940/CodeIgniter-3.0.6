<?php echo validation_errors(); ?>						<!--用于显示表单验证的错误信息。-->

<?php echo form_open('Index/create'); ?>				<!--表单辅助函数 提供的，用于生成 form 元素-->

    <label for="title">Title</label>
    <input type="input" name="title" /><br />

    <label for="text">Text</label>
    <textarea name="text"></textarea><br />

    <input type="submit" name="submit" value="Create news item" />

</form>