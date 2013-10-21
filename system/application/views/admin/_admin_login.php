<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
	<title><?=lang('admin.title')?></title>
	<?php include('includes.php'); ?>
<script type="text/javascript">

Ext.onReady(function(){
  
  var form = new Ext.FormPanel({
        xtype: 'form', frame:true,
        width: 300, height: 150,
        padding: '15',
        id: 'login-form', url: '', standardSubmit : true,
        title: "<?=lang('admin.login')?>",
        buttonAlign: 'center',
        renderTo:'container',
        items: [{
                xtype:'textfield', fieldLabel: '<?=lang('admin.login.login')?>', name: 'login',
                width: 150,
				enableKeyEvents: true,
				listeners: {
					specialkey: function(field, el){
					if (el.getKey() == Ext.EventObject.ENTER)
					Ext.getCmp('loginButton').fireEvent('click')
					}
				}
				
            },{
                xtype:'textfield', fieldLabel: '<?=lang('admin.login.password')?>', name: 'passwd',
                width: 150, inputType: 'password',
				enableKeyEvents: true,
				listeners: {
				specialkey: function(field, el){
					if (el.getKey() == Ext.EventObject.ENTER)
					Ext.getCmp('loginButton').fireEvent('click')
				}
				}
				
        }],
		
        buttons: [{
            text: '<?=lang('admin.login.submit')?>',
			id: 'loginButton',
			formBind: true,
			listeners: {
				click: function(){
				Ext.getCmp('login-form').getForm().submit();
				}
			}
        }]
		
    });
    form.render();
});

</script>    
</head>
<table width="100%">
    <tr>
        <td>&nbsp;</td>
        <td align="center" style='padding-top: 200px;'><div id="container"></div></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td align="center" style='padding-top: 10px;'><span class="error"><?=$error?></span></td>
        <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>
