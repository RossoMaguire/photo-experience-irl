<?
function deleteImages() {

$days = 7;  
$path = "/ross-test-wp/wp-content/plugins/photo_experience_irl/img/";  
  
// Open the directory  
if ($handle = opendir($path))  
{  
    // Loop through the directory  
    while (false !== ($file = readdir($handle)))  
    {  
        // Check the file we're doing is actually a file  
        if (is_file($path.$file))  
        {  
            // Check if the file is older than X days old  
            //if (filemtime($path.$file) < ( time() - ( $days * 24 * 60 * 60 ) ) )  
                // Do the deletion  
                unlink($path.$file);  
        }  
    }  
}  
}
deleteImages();
?>