<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Image_lib extends CI_Image_lib {
  private $pdf_icon = 'resources/images/pdf.png';

  public function thumbnail_url($image, $width, $type='image/jpeg') {
    if ($type == 'application/pdf') {
      return base_url($this->pdf_icon);
    }

    $CI =& get_instance();
    $CI->load->helper('url');

    $photo_folder = $CI->config->item('photo_folder');
    $thumb_folder = $CI->config->item('thumbnail_folder');
    $pinfo = pathinfo($image);
    $thumb_name = $pinfo['filename'].'-'.$width.'px.'.$pinfo['extension'];
    $thumb_path = $thumb_folder.'/'.$thumb_name;

    if (! file_exists($thumb_path)) {
      $conf = [
        'source_image' => $photo_folder . '/' . $image,
        'new_image' => $thumb_path,
        'width' => $width
      ];
      $this->initialize($conf);
      $this->resize();
    }
    return base_url($thumb_path);
  }
}
