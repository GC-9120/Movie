<?php

namespace App\Api;

class JX extends \PhalApi\Api
{
	public function getRules()
	{
		return array("getJX" => array("url" => array("name" => "url", "type" => "string", "min" => 1, "default" => "", "desc" => "url"), "type" => array("name" => "type", "type" => "string", "min" => 1, "default" => "", "desc" => "type"), "mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须")), "VipJX" => array("url" => array("name" => "url", "type" => "string", "min" => 1, "default" => "", "desc" => "url"), "from" => array("name" => "from", "type" => "string", "default" => "", "desc" => "来源"), "mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须")));
	}
	public function getJX()
	{
		$url = $this->url;
		$type = $this->type;
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get(md5($url));
		if (empty($data)) {
			if ($type == "acfun") {
				$playUrl = $this->acfun($url);
				$cache->set(md5($url), $playUrl, 600);
				return array("url" => $playUrl);
			} elseif ($type == "douban") {
				$playUrl = $this->douban($url);
				$cache->set(md5($url), $playUrl, 600);
				return array("url" => $playUrl);
			} elseif ($type == "bilibili") {
				$playUrl = $this->bilibili($url);
				$cache->set(md5($url), $playUrl, 600);
				return array("url" => $playUrl);
			}
		} else {
			return array("url" => $data);
		}
	}
	private function acfun($url)
	{
		$curl = new \PhalApi\CUrl();
		$html = $curl->get($url, 3000);
		$html = $this->getSubstr($html, "window.pageInfo = window.videoInfo = ", ";");
		$vod = json_decode($html, true);
		$vod = $vod["currentVideoInfo"]["ksPlayJson"];
		$vod = json_decode($vod, true);
		$vod = $vod["adaptationSet"]["representation"];
		$vod = array_pop($vod);
		return $vod["url"];
	}
	private function douban($url)
	{
		$curl = new \PhalApi\CUrl();
		$html = $curl->get($url, 3000);
		$html = $this->getSubstr($html, "\"embedUrl\": \"", "\",");
		return $html;
	}
	private function bilibili($url)
	{
		$curl = new \PhalApi\CUrl();
		$curl->setHeader(array("Content-Type" => "application/x-www-form-urlencoded", "user-agent" => "Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1"));
		$html = $curl->get($url, 3000);
		$html = $this->getSubstr($html, "readyVideoUrl: '", "',");
		if (substr($html, 0, 4) != "http") {
			$html = "https:" . $html;
		}
		return $html;
	}
	public function VipJX()
	{
		$url = $this->url;
		$from = $this->from;
		$url = base64_decode($url);
		if ($from != "qq" || $from != "mgtv" || $from != "qiyi" || $from != "youku" || $from != "iqiyi") {
			array("code" => 200, "url" => $url, "form" => $from, "msg" => "不在解析范围,直连返回");
		}
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get(md5($url));
		if (empty($data)) {
			$m3u8 = $this->m3u8($url);
			if ($m3u8["code"] == 200) {
				$cache->set(md5($url), $m3u8, 300);
				return $m3u8;
			} else {
				$fufei = $this->pzapi($url);
				if ($fufei["code"] == 200) {
					$cache->set(md5($url), $fufei, 300);
				}
				return $fufei;
			}
		} else {
			return $data;
		}
	}
	private function fufei($url)
	{
		$curl = new \PhalApi\CUrl();
		$API = "https://api.kuluni.cn/analysis/json/?uid=13&my=adhmnoptDKMTVYZ069&url=" . $url;
		$html = $curl->get($API, 3000);
		if (!$html) {
			return array("code" => 400, "msg" => "加载失败，请切换播放源！");
		}
		$html = json_decode($html, true);
		if ($html["code"] == 200) {
			return array("code" => 200, "url" => $html["url"], "form" => "fufei", "msg" => "fufei，即将播放！");
		} else {
			return array("code" => 400, "msg" => "加载失败，请切换播放源！");
		}
	}
	private function m3u8($url)
	{
		$curl = new \PhalApi\CUrl();
		$html = $curl->get("http://m3u8.tv.janan.net/json.php?url=" . $url, 3000);
		if (!$html) {
			return array("code" => 400, "msg" => "加载失败，请切换播放源！");
		}
		$html = json_decode($html, true);
		if ($html["code"] == 200) {
			return array("code" => 200, "url" => $html["url"], "form" => "m3u8", "msg" => "m3u8，即将播放！");
		} else {
			return array("code" => 400, "msg" => "加载失败，请切换播放源！");
		}
	}
	private function wkjx($url)
	{
		$curl = new \PhalApi\CUrl();
		$html = $curl->get("https://www.wkjx.me/api/?=" . $url, 3000);
		if (!$html) {
			return array("code" => 400, "msg" => "加载失败，请切换播放源！");
		}
		$html = $this->getSubstr($html, "var vkey = '", "';");
		$param = array("vkey" => $html);
		$rs = $curl->post("https://www.wkjx.me/api/api.php", $param, 3000);
		if (!$rs) {
			return array("code" => 400, "msg" => "加载失败，请切换播放源！");
		}
		$JSON = json_decode($rs, true);
		if ($JSON["ckflv"] == 200 && $JSON["url"] !== "") {
			return array("code" => 200, "url" => $JSON["url"], "form" => "wkjx", "msg" => "wkjx加载成功，即将播放！");
		} else {
			return array("code" => 400, "msg" => "加载失败，请切换播放源！");
		}
	}
	private function pzapi($url)
	{
		$curl = new \PhalApi\CUrl();
		$html = $curl->get("http://pzapi.top/jx.php?id=5&url=" . $url, 3000);
		if (!$html) {
			return array("code" => 400, "msg" => "加载失败，请切换播放源！");
		}
		$html = json_decode($html, true);
		if ($html["code"] == 200) {
			return array("code" => 200, "url" => $html["url"], "form" => "pzapi", "msg" => "pzapi加载成功，即将播放！");
		} else {
			return array("code" => 400, "msg" => "加载失败，请切换播放源！");
		}
	}
	private function htv009($url)
	{
		$curl = new \PhalApi\CUrl();
		$html = $curl->get("https://user.htv009.com/json?url=" . $url, 3000);
		if (!$html) {
			return array("code" => 400, "msg" => "加载失败，请切换播放源！");
		}
		$html = json_decode($html, true);
		if ($html["code"] == 200) {
			return array("code" => 200, "url" => $html["url"], "form" => "htv009", "msg" => "htv009加载成功，即将播放！");
		} else {
			return array("code" => 400, "msg" => "加载失败，请切换播放源！");
		}
	}
	private function m1907($url)
	{
		$curl = new \PhalApi\CUrl();
		$a = "BTK7A7O6KKMTE8LSHTCG";
		$cid = "399051116KKMTE8";
		$d = number_format(microtime(true), 3, "", "");
		$b = md5($cid . $d);
		$geturl = "https://z1.m1907.cn/app/json?jx=" . $url . "&a=" . $a . "&d=" . $d . "&b=" . $b;
		$html = $curl->get($geturl, 3000);
		if (!$html) {
			return array("code" => 400, "msg" => "加载失败，请切换播放源！");
		}
		$html = json_decode($html, true);
		if ($html["code"] == 200) {
			return array("code" => 200, "url" => $html["url"], "form" => "htv009", "msg" => "htv009加载成功，即将播放！");
		} else {
			return array("code" => 400, "msg" => "加载失败，请切换播放源！");
		}
	}
	private function dashi($url)
	{
		$curl = new \PhalApi\CUrl();
		$html = $curl->get("https://user.htv009.com/json?url=" . $url, 3000);
		$html = json_decode($html, true);
		$playUrl = array();
		if ($html["code"] == 200) {
			$playUrl["code"] = 200;
			$playUrl["url"] = $html["url"];
			return $playUrl;
		} else {
			$playUrl["code"] = 400;
			return $playUrl;
		}
	}
	private function getSubstr($str, $leftStr, $rightStr)
	{
		$left = strpos($str, $leftStr);
		$right = strpos($str, $rightStr, $left);
		if ($left < 0 || $right < $left) {
			return "";
		}
		return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
	}
}