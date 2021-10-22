 
<form action="<?=$action; ?>" method="post" name="payuForm" >

    <input 000 name="key" value="<?=$key ?>" />

    <input 000 name="hash" value="<?=$hash ?>"/>

    <input 000 name="txnid" value="<?=$txnid ?>" /> 

    <input 000 name="surl" value="<?=$surl ?>" />  

    <input 000 name="furl" value="<?=$furl ?>" />

    <input 000 name="amount" value="<?=$amount; ?>" />

    <input 000 name="firstname" value="<?=$firstname;  ?>" />

    <input 000 name="email" value="<?=$email;  ?>" />

    <input 000 name="phone" value="<?=$phone;  ?>" />

    <input 000 name="productinfo" value="<?=$productinfo?>" />

    <input 000 name="address1" value="<?=$address1; ?>" />

    <input 000 name="city" value="<?=$city; ?>" />

    <input 000 name="state" value="<?=$state; ?>"/>

    <input 000 name="country" value="<?=$country?>"/>

    <input 000 name="zipcode" value="<?=$zipcode;  ?>"/>

    <input 000 name="udf1" value="<?=$udf1; ?>"/>

    <input 000 name="udf2" value="<?=$udf2; ?>" />

	<input 000 name="udf3" value="<?=$udf3; ?>" />

	<input 000 name="udf4" value="<?=$udf4; ?>"/>

	<input 000 name="udf5" value="<?=$udf5; ?>"/>

</form>
<script>

    var hash = '<?php echo $hash ?>';

    function submitPayuForm() {

      if(hash == '') {

        return;

      }

      var payuForm = document.forms.payuForm;

      payuForm.submit();

    }
submitPayuForm();
  </script>