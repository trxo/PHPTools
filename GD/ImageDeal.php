<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 2017/10/27
 * Time: 15:45
 */

class ImageDeal
{
    private $src;
    private $imageinfo;
    private $image;
    public  $percent = 0.1;
    public function __construct($src){
        if(!file_exists($src)){
            exit('file not exist!\n');
        }
        $this->src = $src;
    }
    public function openImage()
    {
        //返回图片信息
        list($width, $height, $type, $attr) = getimagesize($this->src);

        $this->imageinfo = array(
            'width'=>$width,
            'height'=>$height,
            'type'=>image_type_to_extension($type,false),
            'attr'=>$attr
        );
        $fun = "imagecreatefrom".$this->imageinfo['type'];
        //获取图片操作资源
        $this->image = $fun($this->src);
    }
    /**
    操作图片
     */
    public function thumpImage(){

        $new_width = $this->imageinfo['width'] * $this->percent;
        $new_width = 1800;
        $new_height = $this->imageinfo['height'] * $this->percent;
        $new_height = 600;
        $image_thump = imagecreatetruecolor($new_width,$new_height);
        //将原图复制带图片载体上面，并且按照一定比例压缩,极大的保持了清晰度
        imagecopyresampled($image_thump,$this->image,0,0,0,0,$new_width,$new_height,$this->imageinfo['width'],$this->imageinfo['height']);
        imagedestroy($this->image);
        $this->image =   $image_thump;
    }

    /**
     *  输出图片
     */
    public function showImage(){

        header('Content-Type: image/'.$this->imageinfo['type']);
        $funcs = "image".$this->imageinfo['type'];
        $funcs($this->image);

    }

    /**
     * 保存图片到硬盘
     * @param $name 图片名称
     */
    public function saveImage($name){
        $funcs = "image".$this->imageinfo['type'];
        $funcs($this->image,$name.'.'.$this->imageinfo['type']);
    }

    /**
     *  销毁图片
     */
    public function __destruct(){

        imagedestroy($this->image);
    }
}
$obj = new ImageDeal("D:\\usr\\www\\file\\3.jpg");
$obj->percent = 0.2;
$obj->openImage();
$obj->thumpImage();
$obj->saveImage(md5("md5"));
