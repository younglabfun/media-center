# Media-center

Media center是Dcat Admin的扩展，支持媒体批量上传、分组管理，提供表单媒体上传和选择，提供全局上传服务接口。

####	预览

![媒体中心](https://github.com/jyounglabs/media-center/blob/main/screenshot/main.png)

媒体中心-媒体列表

#### 新功能 2022-04-29
- 修改图片预览功能
- 媒体中心页面支持两种视图，列表视图和网格视图
- 图片使用缩略图显示，降低带宽消耗
- 表单媒体组件支持调整调整顺序

#### 功能

- 全局文件上传服务接口
- 自定义文件上传规则
- 媒体文件批量上传、批量分组，图片预览、编辑、删除、代码复制
- 媒体文件回收站管理
- 媒体分组管理
- 表单媒体组件扩展，支持上传、预览、删除、选择

#### 环境

- php >= 7.1.0
- dcat/laravel-admin ~2.0

#### 安装

1. Composer

2. 扩展

   在Dcat Admin中 ```开发工具->扩展``` 中安装并启用

   在右侧菜单中查看Media Center菜单

#### 使用帮助

1. 扩展设置

   - 自带上传服务接口 ```/admin/uploadSerives```
   - 媒体文件夹命名规则 *日期命名* 或 *不分文件夹*存储
   - 文件名命名规则 使用 *原文件名* 或 *随机命名*

2. 媒体中心

   - 基于Dcat Admin数据表格构建工具，与系统风格功能统一
   - 批量上传文件，默认不限制文件类型，一次上传**10**个文件
   - 文件上传位置使用系统配置 ```admin.upload.disk```
   - 自定义媒体列表显示字段
   - 上传图片文件支持预览
   - 可编辑文件名称和分组
   - 文件使用软删除，回收站可恢复或彻底删除操作
   - 批量删除、批量分组或取消分组
   - 文件名称快捷搜索
   - 快捷复制链接代码、html代码、Markdown代码
   - 媒体分组支持一级子分组

3. 媒体表单

   数据库媒体文件字段使用text 或 varchar，以实际应用要求设置，表单字段数据为json字符串，内容包括媒体文件id、名称、路径、类型。

   示例：

   ```json
   "[{"id":7,"name":"mediacenter-1","path":"2022-03-25/mediacenter-1.jpg","fileType":"image"},{"id":8,"name":"mediacenter-2","path":"2022-03-25/mediacenter-2.jpg","fileType":"image"}]"
   ```



   在表单中使用

   **单文件（默认模式）**

   ```php
   $form->mediaSelector('file', '文件')
     	->help('上传或选择一个媒体文件，不限类型');
   ```



   **单图片**

   ```php
   $form->mediaSelector('image', '图片')
     	->options(['type' => 'image'])
     	->help('上传或选择一个图片');
   ```



   **多文件**

   ```php
   $form->mediaSelector('files', '系列文件')
     	->options(['length' => 10])
     	->help('上传或选择10个文件，不限类型');
   ```



   **多图**

   ```php
   $form->mediaSelector('images', '系列图片')
     	->options(['length' => 10,'type' => 'image'])
     	->help('上传或选择10个图片');
   ```



   **指定文件类型**

   ```php
   $form->mediaSelector('file', '文件压缩包')
     	->options(['type' => 'zip'])
     	->help('上传或选择一个文件压缩包');
   ```

   支持文件类型见type参数

#### 参数说明

**length**

`Int` 上传数量，默认1，当上传数量限制大于1时，自动开启上传多选方式。

**type**

`String` 上传类型，默认不限制。不在以下列表中的都视为不限制类型。

指定文件类型参数包括：

- image 图片
- audio 音频
- video 视频
- code 代码
- zip 压缩包
- text 文本文件
- word word文件
- xsl excel文件
- ppt ppt文件
- pdf pdf文件

#### 使用组件

- [Dcat Admin](https://github.com/jqhph/dcat-admin)
- [JQuery](https://jquery.com/)
- [font-awesome](http://fontawesome.io/)
- [webuploader](http://fex.baidu.com/webuploader/)

感谢他们使开发变得更便捷美好！
