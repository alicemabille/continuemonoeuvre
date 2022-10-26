<?php 

/**
 * Echos "active" if the page passed as a parameter is the current page. Useful with bootstrap for a cool nav menu.
 * @param page : the page you want to know is active or not.
 */
function active_page(string $page) : void{
    $uri = $_SERVER['REQUEST_URI'];
    $uri_arr = explode("?",$uri);
    $current_page = $uri_arr[0];
    if($current_page=="/".$page){
        echo "active";
    }
}

const MAX_TXT_PREVIEW_LENGTH = 1000;
const MAX_POEM_LENGTH = 20;

/**
 * Echos php code for the preview of the given file on the homepage when no user is connected.
 * @param filename : name of the file containing the text to display, without ".html".
 * @param category : novel or poem. Will change the way paragraphs are defined.
 */
function txt_preview(string $filename, ?string $category="novel") : string {
    $txt = file_get_contents("text-examples/".$filename.".txt");
    if(strlen($txt) >  MAX_TXT_PREVIEW_LENGTH) {
        $txt = substr($txt, 0, MAX_TXT_PREVIEW_LENGTH);
    }
    $txt = "<p>".$txt;
    if($category=="novel"){
        $txt = str_replace("\n\n","</p><p>",$txt);
    }
    else if($category=="poem"){
        $nbbr = substr_count($txt,"\n");
        if($nbbr >  MAX_POEM_LENGTH) {
            $txt = substr($txt, 0, MAX_POEM_LENGTH*11);
        }
        $txt = str_replace("\n\n","</p><p>",$txt);
        $txt = str_replace("\n","</br>",$txt);
    }
    else{
        return "Unknown text category.";
    }
    $txt = "<article class=\"text-preview col-12 col-md-5 bg-secondary text-white p-4 m-4 rounded\"> \n\t\t\t\t<h3>".
        ucfirst($filename)."</h3>\n\t\t\t\t"
        .$txt."...</p><a href=\"text-view.php\" class=\"btn btn-info\" role=\"button\">Lire la suite</a> \n\t\t\t </article> \n";
    return $txt;
}

?>