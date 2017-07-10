<form action="/kushimoto/php/delete.php" onSubmit="return disp();" name="delete" method="post">
<input type="hidden" id="people_id" name="people_id" />
<input type="submit" name="buttom"  value="削除" />
<script type="text/javascript">

function disp(){
    return window.confirm('削除してもよろしいですか？');
}
</script>
