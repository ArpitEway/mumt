<div class="row">
    <?php foreach($users as $user) { ?>

            <div class="row mt-3 pl-10">
                <div class="col-md-3">
                    <a  class="text-dark users"  data-user = "<?=$user['email']; ?>" data-pass = "<?= $user['password']; ?>")>
                    <div class="card card-custom bgi-no-repeat gutter-b card-stretch master-admin-block">
                        <div class="card-body">
                        <?php echo $user['name']; ?>
                        </div>
                    </div>
                    </a>
                </div>
            </div>

    <?php } ?>
</div>
<script>

    $(document).on("click", ".users", function() {
    var username = $(this).attr('data-user');
    var password = $(this).attr('data-pass');
    var data = {
        username: username,
        password: password
    };
    var target = $(this).attr("data-target");
    var url = BASE_URL + "admin/Admins/check_login";
    var response = call_ajax(data, url);
    if(response) {
        console.log(response);
        console.log(response.data);
        var link = BASE_URL + "admin/"+response.data.account_type;
        window.open(link, '_blank').focus();
    } 
});
</script>