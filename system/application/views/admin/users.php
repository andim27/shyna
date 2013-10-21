<?php require_once('header.php'); ?>
<script type="text/javascript" src="<?=base_url()?>js/js_ajax/ajax_admin.js"></script>
<script type="text/javascript">set_admin_base_url('<?=base_url()?>', 'users');</script>
<div class="priceBlock" style="float:left;margin:20px 0 5px 20px;width:1060px;">
	<div class="heaLabelBlock">
		<h2 style="display:inline">Список пользователей:</h2>
        <a style="margin-left:20px;" href="javascript:add_new_user();">Добавить нового:</a>
        <div id="id_new_user" style="display:none">
        <div style="display:inline" >
            <label>Имя:</label><input id="id_new_user_name" name="new_user_name" type="text" onblur="javascript:setNewUserPsw();" size=12>&nbsp;
            <label>Пароль:</label><input  id="id_new_user_psw"  name="new_user_psw" type="text" size=12 >&nbsp;
            <label>email:</label><input id="id_new_user_email" name="new_user_email" type="text" value="<?= $this->config->item('admin_email'); ?>">&nbsp;
            <input type="button" name="save_new" value="Сохранить" onclick="javascript:admin_add_new_user();">
            <input type="button" name="save_new" value="Отмена" onclick="javascript:cancel_add_new_user();">
        </div>
        </div>
	</div>
	<div class="priceBox">
		<table class="headerTab" cellpadding="2" cellspacing="0">
			<thead>
				<tr>
					<th>#</th>
					<th style="width:150px;">Логин</th>
					<th style="width:100px;">E-mail</th>
					<th style="width:100px;">Дата регистрации</th>
					<th style="width:100px;">IP</th>
					<th style="width:110px;">Активен до</th>
					<th style="width:110px;">Город</th>
					<th style="width:110px;">Телефон</th>
					<th style="width:110px;">Компания</th>
					<th style="width:110px;">ФИО</th>
					<th style="width:110px;">Группа</th>
					<th style="width:110px;">Управление</th>
				</tr>
			</thead>
			<tbody id="priceBoxBody">
			<?php
				if(isset($users) && !empty($users)) {
					$user_str = '';
					foreach ($users as $uindex=>$user) {
                        $img_del="<img title='Удалить' src='".base_url()."images/icons/delete.png' onclick='admin_user_del(".$user->user_id.");'></img>";
                        $uindex++;
                        if ($user->active == -1) {
                            $img_block="<img id='id_img_block_".$user->user_id."' style='display:none' title='Блокировать'  src='".base_url()."images/icons/stop.png' onclick='admin_user_block(".$user->user_id.");'></img>";;
                            $img_unblock="<img id='id_img_unblock_".$user->user_id."' style='display:inline' title='Разблокировать'  src='".base_url()."images/icons/unstop.png' onclick='admin_user_unblock(".$user->user_id.");'></img>";
                            $user_str .= '<tr style="background:#FFCCFF" id="id_tr_'.$user->user_id.'">';
                        } else {
                            $img_block="<img id='id_img_block_".$user->user_id."' title='Блокировать'  src='".base_url()."images/icons/stop.png' onclick='admin_user_block(".$user->user_id.");'></img>";;
                            $img_unblock="<img id='id_img_unblock_".$user->user_id."' style='display:none' title='Разблокировать'  src='".base_url()."images/icons/unstop.png' onclick='admin_user_unblock(".$user->user_id.");'></img>";;
                            $user_str .= '<tr id="id_tr_'.$user->user_id.'">';
                        }
						$user_str .= '<td>'.$uindex.'</td>';
						$user_str .= '<td><a href="'.base_url().'admin/users/profile/'.$user->user_id.'">'.$user->login.'</a></td>';
						$user_str .= '<td>'.$user->email.'</td>';
						$user_str .= '<td>'.date("d/m/Y", strtotime($user->registration_date)).'</td>';
						$user_str .= '<td>'.$user->registration_ip.'</td>';						
						$user_str .= '<td>'.$user->active_end_date.'</td>';
						$user_str .= '<td>'.$user->city.'</td>';
						$user_str .= '<td>'.$user->tel.'</td>';
						$user_str .= '<td>'.$user->company.'</td>';
						$user_str .= '<td>'.$user->fio.'</td>';
						$user_str .= '<td>'.$user->group_name.'</td>';
                        //if ($user->active == -1) {
                        //   	$user_str .= '<td>'.$img_del.$img_unblock.'</td>';
                        //} else {
                        //   	$user_str .= '<td>'.$img_del.$img_block.'</td>';
                        //}
                        $user_str .= '<td>'.$img_del.$img_block.$img_unblock.'</td>';
						$user_str .= '</tr>';
					}
					echo $user_str;
				}
			?>
			</tbody>
		</table>
	</div>
</div>
<?php require_once("footer.php"); ?>