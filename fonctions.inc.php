<?php
  define("API_KEY","31223536-929a18de613be135e34f06473");
  define("RESULTS_MAX",20);

  /**
   * Décoder JSON vidéos pour API pixabay
  */
  function decode_json_videos(string $d):object{
    $url='https://pixabay.com/api/videos/?key='.API_KEY.'&q='.$d;
    $json=file_get_contents($url);
    $data = json_decode($json);
    return $data;
  }
  /**
   * Décoder JSON photos pour API pixabay
   */
  function decode_json_photos(string $d):object{
    $url='https://pixabay.com/api/?key='.API_KEY.'&q='.$d.'&image_type=photo';
    $json=file_get_contents($url);
    $data = json_decode($json);
    return $data;
  }
  /**
   * Préparer le string pour l'url
   */
  function explorer(string $requete):string{
    $exp = explode(" ", $requete);
    $def_req="";
    for ($n=0;$n<sizeof($exp);$n++){
      if ($n == count($exp)-1){
        $def_req .=$exp[$n];
      } else {
        $def_req .=$exp[$n]."+"; 
      }
    }
    return $def_req;
  }

  /**
   * Avoir les vidéos demandées par l'user
   */
  function get_videos(string $q):string{
    $str="";
    $def_q = explorer($q);
    $datas = decode_json_videos($def_q);
    $nbr = $datas->totalHits;
    if ($nbr == 0){
      $str="AUCUNE VIDÉOS NE CORRESPOND À VOTRE RECHERCHE";
      return $str;
    } else {
      $videos = $datas->hits;
      for ($i=0;$i<RESULTS_MAX;$i++){
        $video_page = $videos[$i]->pageURL;
        $video_small = $videos[$i]->videos->small->url;
        $str .='<li>
                  <figure>
                    <video width="220" height="140" controls>
                      <source src='.$video_small.' type=video/mp4>
                    </video>
                    <figcaption><a href='.$video_page.' target="_blank">Vidéo '.$i.'</a></figcaption>
                  </figure>
                </li>';
      }
    }
    return str;
  }

  /**
   * Avoir les photos demandées par l'user
   */
  function get_images(string $q):string{
    $str="";
    $def_q = explorer($q);
    $datas = decode_json_photos($def_q);
    $nbr = $datas->totalHits;
    if ($nbr == 0){
      $str="AUCUNE PHOTOS NE CORRESPOND À VOTRE RECHERCHE";
      return $str;
    } else {
      $photos = $datas->hits;
      for ($i=0;$i<RESULTS_MAX;$i++){
        $photo_page = $photos[$i]->pageURL;
        $photo_small = $photos[$i]->webformatURL;
        $str .='<li>
                  <figure>
                    <img width="220" height="140" src="'.$photo_small.'" alt="Résultat '.$i.'">
                    <figcaption><a href='.$photo_page.' target="_blank">Photos '.$i.'</a></figcaption>
                  </figure>
                </li>';
      }
    }
    return $str;
  }

  /**
   * Test avec yellow flower vidéos -> OK
  */
  function test():String{
    $url = 'https://pixabay.com/api/videos/?key=31223536-929a18de613be135e34f06473&q=yellow+flowers';
    $json=file_get_contents($url);
    $datas = json_decode($json);
    $str="";
    $nbr = $datas->totalHits;
    if ($nbr == 0){
      $str="AUCUNE VIDÉOS NE CORRESPOND À VOTRE RECHERCHE";
      return $str;
    } else {
      $videos = $datas->hits;
      for ($i=0;$i<RESULTS_MAX;$i++){
        $video_page = $videos[$i]->pageURL;
        $video_small = $videos[$i]->videos->small->url;
        $str .='<li>
                  <figure>
                    <video width="220" height="140" controls>
                      <source src='.$video_small.' type=video/mp4>
                    </video>
                    <figcaption><a href='.$video_page.' target="_blank">Vidéo '.$i.'</a></figcaption>
                  </figure>
                </li>';
      }
    }
    return $str;
  }

  /**
   * Test pour yellow flowers photos -> OK
   */
  function test_photos():string{
    $url="https://pixabay.com/api/?key=31223536-929a18de613be135e34f06473&q=yellow+flowers&image_type=photo";
    $json=file_get_contents($url);
    $datas = json_decode($json);
    $str="";
    $nbr = $datas->totalHits;
    if ($nbr == 0){
      $str="AUCUNE PHOTOS NE CORRESPOND À VOTRE RECHERCHE";
      return $str;
    } else {
      $photos = $datas->hits;
        for ($i=0;$i<RESULTS_MAX;$i++){
          $photo_page = $photos[$i]->pageURL;
          $photo_small = $photos[$i]->webformatURL;
          $str .='<li>
                    <figure>
                      <img width="220" height="140" src="'.$photo_small.'" alt="Résultat '.$i.'">
                      <figcaption><a href='.$photo_page.' target="_blank">Photos '.$i.'</a></figcaption>
                    </figure>
                  </li>';
        }
    }
    return $str;
  }
?>