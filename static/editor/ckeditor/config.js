/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	config.language = 'zh-cn';
	config.width = "100%"; 
	config.font_names="Arial;宋体;微软雅黑;楷体;黑体;Helvetica;sans-serif;Comic Sans MS;cursive;Courier New;Lucida Grande;sans-serif;Tahoma;Trebuchet MS;Verdana";
	config.resize_enabled = false; // 取消 “拖拽以改变尺寸”功能 plugins/resize/plugin.js
	config.dialog_backgroundCoverColor = '#333' //遮罩背景色
	config.dialog_backgroundCoverOpacity = 0.5 //背景的不透明度 数值应该在：0.0～1.0 之间 plugins/dialog/plugin.js
	config.uiColor = '#ddd'; // 按钮背景颜色
    /*
    config.skin = 'kama';	// 编辑器样式，有三种：'kama'（默认）、'office2003'、'v2' 	
		config.toolbar = 'Basic';工具栏（基础'Basic'、全能'Full'、自定义）plugins/toolbar/plugin.js
		这将配合：
		config.toolbar_Full = [
		   ['Source','-','Save','NewPage','Preview','-','Templates'],
		   ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
		   ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		   ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
		   '/',
		   ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
			['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
			['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
			['Link','Unlink','Anchor'],
		   ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		   '/',
			['Styles','Format','Font','FontSize'],
			['TextColor','BGColor']
		]; 
	
	config.toolbarCanCollapse = true;//工具栏是否可以被收缩    
    config.toolbarLocation = "top";//工具栏的位置 可选：bottom     
    config.toolbarStartupExpanded = true; //工具栏默认是否展开    
    config.resize_maxHeight = 3000; //改变大小的最大高度 
    config.resize_maxWidth = 3000; //改变大小的最大宽度
    config.resize_minHeight = 250; //改变大小的最小高度    
    config.resize_minWidth = 750;//改变大小的最小宽度
    config.autoUpdateElement = true; // 当提交包含有此编辑器的表单时，是否自动更新元素内的数据
    config.baseHref = "" // 设置是使用绝对目录还是相对目录，为空为相对目录
    config.baseFloatZIndex = 10000; // 编辑器的z-index值
    config.colorButton_enableMore = false; //是否在选择颜色时显示“其它颜色”选项 plugins/colorbutton/plugin.js	
    //config.contentsCss = './contents.css'; 所需要添加的CSS文件 在此添加 可使用相对路径和网站的绝对路径
    config.contentsLangDirection = 'ltr'; //文字方向:从左到右 
*/	
    //界面编辑框的背景色 plugins/dialog/plugin.js
    

    
    
	//是否开启 图片和表格 的改变大小的功能 config.disableObjectResizing = true;
    config.disableObjectResizing = false //默认为开启
	
    config.filebrowserBrowseUrl = '../ckfinder/ckfinder.html';  
    config.filebrowserImageBrowseUrl = '../ckfinder/ckfinder.html?Type=Images';  
    config.filebrowserFlashBrowseUrl = '../ckfinder/ckfinder.html?Type=Flash';  
    config.filebrowserUploadUrl = '../ckfinder/core/connector/asp/connector.asp?command=QuickUpload&type=Files';  
    config.filebrowserImageUploadUrl = '../ckfinder/core/connector/asp/connector.asp?command=QuickUpload&type=Images';  
    config.filebrowserFlashUploadUrl = '../ckfinder/core/connector/asp/connector.asp?command=QuickUpload&type=Flash';  

};
