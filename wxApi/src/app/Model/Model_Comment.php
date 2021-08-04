<?php

namespace App\Model;

error_reporting(E_ALL ^ E_NOTICE);
class Model_Comment extends \PhalApi\Model\NotORMModel
{
	protected function getTableName($_var_0)
	{
		return 'mac_comment';
	}
	public function addNewComment($_var_1, $_var_2, $_var_3, $_var_4, $_var_5)
	{
		$_var_6 = array('comment_rid' => $_var_2, 'user_id' => $_var_1, 'comment_pid' => $_var_3, 'comment_name' => $_var_5, 'comment_time' => time(), 'comment_content' => $_var_4);
		$_var_7 = $this->getORM()->insert($_var_6);
		return $_var_7;
	}
	public function deleteUserComment($_var_8, $_var_9)
	{
		return $this->getORM()->where('comment_id', $_var_9)->where('user_id', $_var_8)->delete($_var_9);
	}
	public function getComment($_var_10, $_var_11, $_var_12)
	{
		return $this->getORM()->select('comment_id,comment_pid,comment_rid,user_id,comment_name,comment_time,comment_content,comment_up,comment_down,comment_report')->where('comment_rid', $_var_10)->where('comment_pid', 0)->order('comment_id DESC')->limit(($_var_11 - 1) * $_var_11, $_var_12)->fetchAll();
	}
	public function getCommentById($_var_13)
	{
		return $this->getORM()->select('comment_id,comment_pid,comment_rid,user_id,comment_name,comment_time,comment_content,comment_up,comment_down,comment_report')->where('comment_pid', $_var_13)->order('comment_id DESC')->fetchAll();
	}
	public function diggComment($_var_14)
	{
		$_var_15 = $this->getORM()->select('comment_up')->where('comment_id', $_var_14)->fetchOne();
		$_var_16 = $_var_15['comment_up'];
		$_var_17 = $_var_16 + 1;
		$_var_18 = array('comment_up' => $_var_17);
		return $this->getORM()->where('comment_id', $_var_14)->update($_var_18);
	}
	public function canceldiggComment($_var_19)
	{
		$_var_20 = $this->getORM()->select('comment_up')->where('comment_id', $_var_19)->fetchOne();
		$_var_21 = $_var_20['comment_up'];
		if ($_var_21 <= 0) {
			return '2';
		}
		$_var_22 = $_var_21 - 1;
		$_var_23 = array('comment_up' => $_var_22);
		return $this->getORM()->where('comment_id', $_var_19)->update($_var_23);
	}
}