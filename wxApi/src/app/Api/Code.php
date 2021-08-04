<?php

namespace App\Api;

class Code extends \PhalApi\Api
{
	public function getRules()
	{
		return array("wxCode" => array("iMark" => array("name" => "iMark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须"), "path" => array("name" => "path", "type" => "string", "min" => 1, "default" => "", "desc" => "path")), "qqJX" => array("mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须")), "poster" => array("app" => array("name" => "app", "type" => "string", "min" => 1, "default" => "", "desc" => "app，参数必须"), "path" => array("name" => "path", "type" => "string", "min" => 1, "default" => "", "desc" => "路径，参数必须"), "iMark" => array("name" => "iMark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须"), "text" => array("name" => "text", "type" => "string", "require" => true, "default" => "", "desc" => "内容，参数必须")), "wxPoster" => array("app" => array("name" => "app", "type" => "string", "min" => 1, "default" => "", "desc" => "app，参数必须"), "path" => array("name" => "path", "type" => "string", "min" => 1, "default" => "", "desc" => "路径，参数必须"), "iMark" => array("name" => "iMark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须"), "text" => array("name" => "text", "type" => "string", "require" => true, "default" => "", "desc" => "内容，参数必须")), "upload" => array("filePath" => array("name" => "filePath", "type" => "string", "min" => 1, "default" => "", "desc" => "path")), "qiniu" => array("filePath" => array("name" => "filePath", "type" => "string", "min" => 1, "default" => "", "desc" => "path")));
	}
	public function poster()
	{
		$iMark = $this->iMark;
		$path = $this->path;
		$text = $this->text;
		$app = $this->app;
		$posterPath = $iMark . "poster" . md5($app . $iMark . $path);
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get($posterPath);
		if (!empty($data) && file_exists($_SERVER["DOCUMENT_ROOT"] . "/appImg/poster/" . $posterPath . ".jpg")) {
			$data["MSG"] = "缓存";
			return $data;
		} else {
			$text = json_decode($text, true);
			if (empty($text["vod_pic"])) {
				return array("imgUrl" => false, "MSG" => "海报图片为空");
			}
			$fileConfig = \PhalApi\DI()->uniapp[$iMark]["site"]["file"];
			if ($app == "QQ") {
				$codeData = $this->qqCode($iMark, $path);
				if ($codeData["code"] != 200) {
					$codeData["imgUrl"] = false;
					return $codeData;
				}
			} else {
				$wxMark = \PhalApi\DI()->uniapp[$iMark]["site"]["share"]["wxName"];
				$codeData = $this->wxCode($wxMark, $path);
				if ($codeData["code"] != 200) {
					$codeData["imgUrl"] = false;
					return $codeData;
				}
			}
			$imgData = $this->posterImg($posterPath, $codeData["codeUrl"], $text, $fileConfig);
			$imgData["codeUrl"] = $codeData["codeUrl"];
			if ($imgData["code"] == 20) {
				$cache->set($posterPath, $imgData, 2592000);
				return $imgData;
			} else {
				return $imgData;
			}
		}
	}
	public function wxPoster()
	{
		$iMark = $this->iMark;
		$path = $this->path;
		$text = $this->text;
		$app = $this->app;
		$posterPath = $iMark . "poster" . md5($app . $iMark . $path);
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get($posterPath);
		if (!empty($data) && file_exists($_SERVER["DOCUMENT_ROOT"] . "/appImg/poster/" . $posterPath . ".jpg")) {
			$data["MSG"] = "缓存";
			return $data;
		} else {
			$text = json_decode($text, true);
			if (empty($text["vod_pic"])) {
				return array("imgUrl" => false, "MSG" => "海报图片为空");
			}
			$fileConfig = \PhalApi\DI()->uniapp[$iMark]["site"]["file"];
			$codeData = $this->wxCode($iMark, $path);
			if ($codeData["code"] != 200) {
				$codeData["imgUrl"] = false;
				return $codeData;
			}
			$imgData = $this->posterImg($posterPath, $codeData["codeUrl"], $text, $fileConfig);
			$imgData["codeUrl"] = $codeData["codeUrl"];
			if ($imgData["code"] == 20) {
				$cache->set($posterPath, $imgData, 2592000);
				return $imgData;
			} else {
				return $imgData;
			}
		}
	}
	private function wxCode($iMark, $path)
	{
		$codePath = $iMark . md5($iMark . $path);
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get($codePath);
		if (!empty($data) && file_exists($_SERVER["DOCUMENT_ROOT"] . "/appImg/code/" . $codePath . ".png")) {
			return $data;
		} else {
			$access_token = $cache->get($iMark);
			if (!empty($access_token)) {
				$codeData = $this->getWxCodeImg($codePath, $access_token, $path);
			} else {
				$tokenData = $this->getWxAccess_token($iMark);
				if ($tokenData["code"] == 2000) {
					$codeData = $this->getWxCodeImg($codePath, $access_token, $path);
				} elseif ($tokenData["code"] == 5000) {
					return $tokenData;
				} else {
					return $tokenData;
				}
			}
			if ($codeData["code"] == 200) {
				return $codeData;
			} else {
				if ($tokenData["code"] == 500) {
					return $codeData;
				} else {
					return $codeData;
				}
			}
		}
	}
	private function getWxAccess_token($iMark)
	{
		$uniapp = \PhalApi\DI()->uniapp[$iMark]["site"];
		if (empty($uniapp["appid"]) && empty($uniapp["AppSecret"])) {
			return array("access_token" => false, "MSG" => "appid或AppSecret为空" . $iMark, "code" => 5000);
		}
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $uniapp["appid"] . "&secret=" . $uniapp["AppSecret"];
		$curl = new \PhalApi\CUrl();
		$html = $curl->get($url, 3000);
		$html = json_decode($html, true);
		if (!empty($html["access_token"])) {
			$cache = \PhalApi\DI()->cache;
			$cache->set($iMark, $html["access_token"], 6200);
			return array("access_token" => $html["access_token"], "MSG" => "access_token获取成功", "code" => 2000);
		} else {
			return array("access_token" => false, "MSG" => "access_token获取失败", "code" => 4000);
		}
	}
	private function getWxCodeImg($codePath, $access_token, $path)
	{
		$curl = new \PhalApi\CUrl();
		$param = json_encode(array("path" => $path, "width" => 200, "auto_color" => false, "is_hyaline" => true));
		$postUrl = "https://api.weixin.qq.com/wxa/getwxacode?access_token=" . $access_token;
		$rs = $curl->post($postUrl, $param, 3000);
		if (!is_array($rs)) {
			$pathUrl = $_SERVER["DOCUMENT_ROOT"] . "/appImg/code/" . $codePath . ".png";
			$size = file_put_contents($pathUrl, $rs);
			if ($size < 10000) {
				unlink($pathUrl);
				return array("codeUrl" => false, "MSG" => "二维码已生成,但失败", "code" => 400);
			}
			if (file_exists($pathUrl)) {
				$cache = \PhalApi\DI()->cache;
				$cacheData = array("codeUrl" => "/appImg/code/" . $codePath . ".png", "MSG" => "二维码缓存", "code" => 200);
				$cache->set($codePath, $cacheData, 2592000);
				return array("codeUrl" => "/appImg/code/" . $codePath . ".png", "MSG" => "二维码获取成功,大小为:" . $size, "code" => 200);
			} else {
				return array("codeUrl" => false, "MSG" => "二维码不存在", "code" => 400);
			}
		} else {
			return array("codeUrl" => false, "MSG" => $rs, "code" => 500);
		}
	}
	private function posterImg($posterPath, $codeUrl, $text, $fileConfig)
	{
		$file = $_SERVER["DOCUMENT_ROOT"];
		$ttf = $file . "/appImg/img/xinwei.ttf";
		$ttf1 = $file . "/appImg/img/xinwei.ttf";
		$play = $file . "/appImg/img/tm.png";
		$codeUrl = $file . $codeUrl;
		$vod_name = $text["vod_name"];
		$vod_actor = $text["vod_actor"];
		$vod_pic = $text["vod_pic"];
		$qqText = $text["qqText"];
		$wxText = $text["wxText"];
		$codeText = $text["codeText"];
		$width = 280;
		$height = 500;
		$phoneB = $height / $width;
		$vod_pic_info = getimagesize($vod_pic);
		$vod_pic_url = imagecreatefromjpeg($vod_pic);
		$codeImg = imagecreatefrompng($codeUrl);
		$playImg = imagecreatefrompng($play);
		$image = imagecreatetruecolor($width, $height);
		$picB = $vod_pic_info[1] / $vod_pic_info[0];
		$picW = $width * 0.9;
		$pich = $picW * $picB;
		$pic_width = ($width - $picW) / 2;
		$color = imagecolorallocate($image, 245, 245, 245);
		imagefill($image, 0, 0, $color);
		imagecopyresampled($image, $vod_pic_url, $pic_width, 15, 0, 0, $picW, $pich, $vod_pic_info[0], $vod_pic_info[1]);
		imagecopyresampled($image, $playImg, $pic_width, 15, 0, 0, $picW, $pich, 448, 672);
		if (!empty($text["vod_name"])) {
			$vod_name = $text["vod_name"];
			$fontColor = imagecolorallocate($image, 255, 255, 255);
			imagettftext($image, 14, 0, $pic_width + 20, $pich - 30, $fontColor, $ttf1, $vod_name);
		}
		if (!empty($text["vod_actor"])) {
			$vod_actor = $text["vod_actor"];
			imagettftext($image, 10, 0, $pic_width + 20, $pich, $fontColor, $ttf, $vod_actor);
		}
		$codeW = $width * 0.3;
		$codeH = ($height - ($pich + 20)) / 2 + $pich + 20 - $codeW / 2;
		imagecopyresampled($image, $codeImg, 10, $codeH, 0, 0, $codeW, $codeW, 280, 280);
		$fontColor = imagecolorallocate($image, 255, 170, 0);
		imagettftext($image, 14, 0, $codeW + 25, $codeH + 30, $fontColor, $ttf1, $codeText);
		$fontColor = imagecolorallocate($image, 30, 30, 30);
		imagettftext($image, 9, 0, $codeW + 25, $codeH + 50, $fontColor, $ttf, $wxText);
		imagettftext($image, 9, 0, $codeW + 25, $codeH + 70, $fontColor, $ttf, $qqText);
		imagepng($image, $file . "/appImg/poster/" . $posterPath . ".jpg");
		imagedestroy($vod_pic_url);
		imagedestroy($codeImg);
		imagedestroy($playImg);
		imagedestroy($image);
		$filePath = "/appImg/poster/" . $posterPath . ".jpg";
		if (file_exists($_SERVER["DOCUMENT_ROOT"] . $filePath)) {
			if ($fileConfig["type"] == "1") {
				$qiniu = $this->qiniu($filePath, $fileConfig["qiniu"]);
				return $qiniu;
			} elseif ($fileConfig["type"] == "2") {
				$posterImg = $this->upload($filePath);
				return $posterImg;
			} else {
				return array("imgUrl" => "http://ys.vodzz.com/" . $filePath, "MSG" => "保存本地成功", "code" => 20);
			}
		} else {
			return array("imgUrl" => false, "MSG" => "绘制失败", "code" => 40);
		}
	}
	private function upload($filePath)
	{
		if (!$filePath) {
			return false;
		}
		$curl = new \PhalApi\CUrl();
		$postUrl = "http://img.bin00.com/update.php";
		$data = array("file" => new \CURLFile(realpath($_SERVER["DOCUMENT_ROOT"] . $filePath)));
		$rs = $curl->post($postUrl, $data, 3000);
		$rs = json_decode($rs, true);
		if ($rs["code"] == 0) {
			$posterImg = $rs["msg"];
			if (substr($posterImg, 0, 4) == "http") {
				return array("imgUrl" => $posterImg, "MSG" => "图床成功", "code" => 20);
			} else {
				return array("imgUrl" => false, "MSG" => "图床失败", "code" => 40);
			}
		} else {
			return array("imgUrl" => false, "MSG" => "图床失败", "code" => 40);
		}
	}
	public function juhe($filePath)
	{
		$filePath = "/appImg/img/xiaoxiaoyingxunposter.jpg";
		$config = array("access_key" => "XSNzLsyKL2oMWDqewUCBTIc5nRZMLKqu3lhKx5RD", "secret_key" => "-ILdzNOSsN_x0AyQQgO_ASix5-FcvnuC-uctwtWH", "space_bucket" => "yingshimao", "space_host" => "http://qiniu.vodzz.com", "preffix" => "", "upload_url" => "up-z1.qiniup.com");
		$qiniu = new \PhalApi\Qiniu\Lite($config);
		$posterImg = $qiniu->uploadFile($_SERVER["DOCUMENT_ROOT"] . $filePath);
		if (substr($posterImg, 0, 4) == "http") {
			return array("imgUrl" => $posterImg, "MSG" => "七牛成功", "code" => 20);
		} else {
			return array("imgUrl" => false, "MSG" => "七牛失败", "code" => 40);
		}
	}
	private function qiniu($filePath, $fileConfig)
	{
		if (!empty($fileConfig["ak"]) && !empty($fileConfig["sk"])) {
			$config = array("access_key" => $fileConfig["ak"], "secret_key" => $fileConfig["sk"], "space_bucket" => $fileConfig["name"], "space_host" => $fileConfig["host"], "preffix" => "", "upload_url" => "up-z1.qiniup.com");
			$qiniu = new \PhalApi\Qiniu\Lite($config);
			$posterImg = $qiniu->uploadFile($_SERVER["DOCUMENT_ROOT"] . $filePath);
		} else {
			$posterImg = \PhalApi\DI()->qiniu->uploadFile($_SERVER["DOCUMENT_ROOT"] . $filePath);
		}
		if (substr($posterImg, 0, 4) == "http") {
			return array("imgUrl" => $posterImg, "MSG" => "七牛成功", "code" => 20);
		} else {
			return array("imgUrl" => false, "MSG" => "七牛失败", "code" => 40);
		}
	}
	private function qqCode($iMark, $path)
	{
		$uniapp = \PhalApi\DI()->uniapp[$iMark]["site"];
		if (empty($uniapp["QQappid"]) && empty($uniapp["QQAppSecret"])) {
			return array("access_token" => false, "MSG" => "QQappid或QQAppSecret为空" . $iMark, "code" => 5000);
		}
		$codePath = $iMark . md5($iMark . $path);
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get($codePath);
		if (!empty($data) && file_exists($_SERVER["DOCUMENT_ROOT"] . "/appImg/code/" . $codePath . ".png")) {
			return $data;
		} else {
			$access_token = $cache->get($iMark);
			if (!empty($access_token)) {
				$codeData = $this->getqqCodeImg($codePath, $access_token, $path, $uniapp["QQappid"]);
			} else {
				$url = "https://api.q.qq.com/api/getToken?grant_type=client_credential&appid=" . $uniapp["QQappid"] . "&secret=" . $uniapp["QQAppSecret"];
				$tokenData = $this->getqqAccess_token($url, $iMark);
				if ($tokenData["code"] == 2000) {
					$codeData = $this->getqqCodeImg($codePath, $access_token, $path, $uniapp["QQappid"]);
				} elseif ($tokenData["code"] == 5000) {
					return $tokenData;
				} else {
					return $tokenData;
				}
			}
			if ($codeData["code"] == 200) {
				return $codeData;
			} else {
				if ($tokenData["code"] == 500) {
					return $codeData;
				} else {
					return $codeData;
				}
			}
		}
	}
	private function getqqAccess_token($url, $iMark)
	{
		$curl = new \PhalApi\CUrl();
		$html = $curl->get($url, 3000);
		$html = json_decode($html, true);
		if (!empty($html["access_token"])) {
			$cache = \PhalApi\DI()->cache;
			$cache->set($iMark, $html["access_token"], 6200);
			return array("access_token" => $html["access_token"], "MSG" => "access_token获取成功", "code" => 2000);
		} else {
			return array("access_token" => false, "MSG" => "access_token获取失败", "code" => 4000);
		}
	}
	private function getqqCodeImg($codePath, $access_token, $path, $appid)
	{
		$curl = new \PhalApi\CUrl();
		$param = json_encode(array("appid" => $appid, "path" => $path));
		$postUrl = "https://api.q.qq.com/api/json/qqa/CreateMiniCode?access_token=" . $access_token;
		$rs = $curl->post($postUrl, $param, 3000);
		if (!is_array($rs)) {
			$pathUrl = $_SERVER["DOCUMENT_ROOT"] . "/appImg/code/" . $codePath . ".png";
			$size = file_put_contents($pathUrl, $rs);
			if ($size < 10000) {
				unlink($pathUrl);
				return array("codeUrl" => false, "MSG" => "二维码已生成,但失败,文件大小为:" . $size, "code" => 400);
			}
			if (file_exists($pathUrl)) {
				$cache = \PhalApi\DI()->cache;
				$cacheData = array("codeUrl" => "/appImg/code/" . $codePath . ".png", "MSG" => "二维码缓存", "code" => 200);
				$cache->set($codePath, $cacheData, 2592000);
				return array("codeUrl" => "/appImg/code/" . $codePath . ".png", "MSG" => "二维码获取成功", "code" => 200);
			} else {
				return array("codeUrl" => false, "MSG" => "二维码不存在", "code" => 400);
			}
		} else {
			return array("codeUrl" => false, "MSG" => $rs, "code" => 500);
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