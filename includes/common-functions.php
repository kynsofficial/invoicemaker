<?PHP

/*  ---------------------------------------------------------------------------
 * 	@package	: Pcustom Functions
 *	@author 	: Akinola Abdulakeem
 *	@version	: 1.0
 *	@link		: https://akinolaakeem.com
 *	--------------------------------------------------------------------------- */



    function NormalDateFormat($dateValue)
    {

        $formatedDate = date("d.m.Y", strtotime($dateValue));
        return $formatedDate;

    }

    function dbDateFormat($dateValue)
    {

        $formatedDate = date("Y.m.d", strtotime($dateValue));
        return $formatedDate;

    }

    function moveUplaod($dir){

        $flag = false;

        if(isset($_FILES["image"]["name"]) && $_FILES["image"]["name"] != ''){
            $allowedExts = array("gif", "jpg", "png", "jpeg");
            $temp = explode(".", $_FILES["image"]["name"]);
            $extension = end($temp);
            $flag = true;
        }

        if($flag == true){
            $imagePath = $dir."/".$_FILES["image"]["name"];
            move_uploaded_file($_FILES["image"]["tmp_name"],$imagePath);
        }

        if($flag == true){
            $imagePath2 = $dir."/".$_FILES["image"]["name"];


            if($extension == 'jpg'){
                $source_image = imagecreatefromjpeg($imagePath);
                $width = imagesx($source_image);
                if($width > 180){
                    make_thumb_jpg($imagePath,$imagePath2,180); // for detail,
                    unset($imagePath);
                }

            }else if ($extension == 'png'){
                $source_image = imagecreatefrompng($imagePath);
                $width = imagesx($source_image);
                if($width > 180){
                    make_thumb_png($imagePath,$imagePath2,180);
                    unset($imagePath);
                }
            }else{
                $source_image = imagecreatefromjpeg($imagePath);
                $width = imagesx($source_image);
                if($width > 180){
                    make_thumb_jpg($imagePath,$imagePath2,180); // for detail,
                    unset($imagePath);
                }
            }
        }

    }
    
    function UserCookieData($cookie = ''){
        
        if (isset( $_COOKIE[$cookie] ) && $_COOKIE[$cookie] != ''){
            
            $data_id = $_COOKIE[ $cookie ];
            
        } else {
            
            $data_id = uniqid();
            
            setcookie($cookie, $data_id, time() + (12 * 30 * 24 * 60 * 60), "/");
        }
        
        return $data_id;
        
    }
    
    function clearCookie($cookie = ''){
        
        setcookie($cookie, "", time() - 3600);
        return true;
    }