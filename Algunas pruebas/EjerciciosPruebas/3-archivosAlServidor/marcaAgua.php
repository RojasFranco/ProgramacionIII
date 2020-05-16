<?php
class MarcaAgua{    
    public static function AddImageWatermark($SourceFile, $WaterMark, $DestinationFile=NULL, $opacity) {
        $main_img = $SourceFile; 
        $watermark_img = $WaterMark; 
        $padding = 5; 
        $opacity = $opacity;
        // crear marca de agua
        $watermark = imagecreatefrompng($watermark_img); 
        $image = imagecreatefromjpeg($main_img); 
        if(!$image || !$watermark) die("Error: La imagen principal o la imagen de marca de agua no se pudo cargar!");
        $watermark_size = getimagesize($watermark_img);
        $watermark_width = $watermark_size[0]; 
        $watermark_height = $watermark_size[1]; 
        $image_size = getimagesize($main_img); 
        $dest_x = $image_size[0] - $watermark_width - $padding; 
        $dest_y = $image_size[1] - $watermark_height - $padding;
        imagecopymerge($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, $opacity);
        if ($DestinationFile<>'') {
           imagejpeg($image, $DestinationFile, 100); 
        } else {
            header('Content-Type: image/jpeg');
            imagejpeg($image);
        }
        imagedestroy($image); 
        imagedestroy($watermark); 
    }
}