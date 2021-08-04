<?php

namespace app\admin\controller;

class Wxapi extends Base
{
	public $_pre;
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$list = config("wxapi");
		$res = model("Admin")->checkLogin();
		$admin_name = $res["info"]["admin_name"];
		$admin_id = $res["info"]["admin_id"];
		$this->assign("title", "小程序配置");
		if ($admin_id !== 1) {
			$data = array();
			foreach ($list as $k => $v) {
				if ($v["admin"] == $admin_name) {
					array_push($data, $v);
				}
			}
			$this->assign("list", $data);
			return $this->fetch("admin@wxapi/agent");
		} else {
			$this->assign("list", $list);
			return $this->fetch("admin@wxapi/index");
		}
	}
	public function info()
	{
		$param = input();
		$list = config("wxapi");
		if (Request()->isPost()) {
			$param["weeks"] = join(",", $param["weeks"]);
			$param["hours"] = join(",", $param["hours"]);
			if (!empty($param["index"]["sy_type"]["type_id"])) {
				$sy_type = $param["index"]["sy_type"];
				unset($param["index"]["sy_type"]);
				foreach ($sy_type["type_id"] as $k => $v) {
					if (empty($v) || empty($sy_type["type_name"][$k])) {
						continue;
					}
					$param["index"]["sy_type"][] = array("type_id" => $sy_type["type_id"][$k], "type_name" => $sy_type["type_name"][$k]);
				}
			}
			if (!empty($param["wode"]["more"]["list"]["img"])) {
				$more = $param["wode"]["more"]["list"];
				unset($param["wode"]["more"]["list"]);
				foreach ($more["img"] as $k => $v) {
					if (empty($v) || empty($more["appid"][$k])) {
						continue;
					}
					$param["wode"]["more"]["list"][] = array("img" => $more["img"][$k], "appid" => $more["appid"][$k], "name" => $more["name"][$k]);
				}
			}
			if (!empty($param["play"]["danmuList"]["text"])) {
				$more = $param["play"]["danmuList"];
				unset($param["play"]["danmuList"]);
				foreach ($more["text"] as $k => $v) {
					if (empty($v) || empty($more["time"][$k])) {
						continue;
					}
					$param["play"]["danmuList"][] = array("text" => $more["text"][$k], "color" => $more["color"][$k], "time" => \intval($more["time"][$k]));
				}
			}
			if (!empty($param["play"]["interval"])) {
				$param["play"]["interval"] = $param["play"]["interval"] * 3600;
			} else {
				$param["play"]["interval"] = 0;
			}
			$list[$param["name"]] = $param;
			$res = mac_arr2file(APP_PATH . "extra/wxapi.php", $list);
			if ($res === false) {
				return $this->error("保存配置文件失败，请重试!");
			}
			return $this->success("保存成功!");
		}
		$info = $list[$param["id"]];
		$this->assign("info", $info);
		$this->assign("title", "信息管理");
		return $this->fetch("admin@wxapi/info");
	}
	public function infoQQ()
	{
		$param = input();
		$list = config("wxapi");
		if (Request()->isPost()) {
			$param["weeks"] = join(",", $param["weeks"]);
			$param["hours"] = join(",", $param["hours"]);
			$list[$param["name"]] = $param;
			$res = mac_arr2file(APP_PATH . "extra/wxapi.php", $list);
			if ($res === false) {
				return $this->error("保存配置文件失败，请重试!");
			}
			return $this->success("保存成功!");
		}
		$info = $list[$param["id"]];
		$this->assign("info", $info);
		$this->assign("title", "信息管理");
		return $this->fetch("admin@wxapi/infoQQ");
	}
	public function del()
	{
		$param = input();
		$list = config("wxapi");
		unset($list[$param["ids"]]);
		$res = mac_arr2file(APP_PATH . "extra/wxapi.php", $list);
		if ($res === false) {
			return $this->error("删除失败，请重试!");
		}
		return $this->success("删除成功!");
	}
	public function field()
	{
		$param = input();
		$ids = $param["ids"];
		$col = $param["col"];
		$val = $param["val"];
		if (!empty($ids) && in_array($col, array("status"))) {
			$list = config("wxapi");
			$ids = explode(",", $ids);
			foreach ($list as $k => &$v) {
				if (in_array($k, $ids)) {
					$v[$col] = $val;
				}
			}
			$res = mac_arr2file(APP_PATH . "extra/wxapi.php", $list);
			if ($res === false) {
				return $this->error("保存失败，请重试!");
			}
			return $this->success("保存成功!");
		}
		return $this->error("参数错误");
	}
}