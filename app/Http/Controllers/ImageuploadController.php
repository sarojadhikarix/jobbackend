<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Carbon\Carbon;
class ImageuploadController extends Controller
{
    
    public function store(Request $request)
    {
        $this->validate(request(), [
            'user_id' => 'required',
            'propic' => 'required |image|max:2048',
        ]); 


        $extension = $request->propic->getClientOriginalExtension();
        $filename = $request->user_id . '_propic';
        
    // start image converting
        $srcFile = $request->propic;
        $maxSize = 200;

        list($width_orig, $height_orig, $type) = getimagesize($srcFile);        

	    // Get the aspect ratio
	    $ratio_orig = $width_orig / $height_orig;

	    $width  = $maxSize; 
	    $height = $maxSize;

	    // resize to height (orig is portrait) 
	    if ($ratio_orig < 1) {
	        $width = $height * $ratio_orig;
	    } 
	    // resize to width (orig is landscape)
	    else {
	        $height = $width / $ratio_orig;
	    }

	    // Temporarily increase the memory limit to allow for larger images
	    ini_set('memory_limit', '32M'); 

	    switch ($type) 
	    {
	        case IMAGETYPE_GIF: 
	            $image = imagecreatefromgif($srcFile); 
	            break;   
	        case IMAGETYPE_JPEG:  
	            $image = imagecreatefromjpeg($srcFile); 
	            break;   
	        case IMAGETYPE_PNG:  
	            $image = imagecreatefrompng($srcFile);
	            break; 
	        //default:
	         //   throw new Exception('Unrecognized image type ' . $type);
	    }

	    // create a new blank image
	    $newImage = imagecreatetruecolor($width, $height);

	    // Copy the old image to the new image
	    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);


	    // Free memory                           
	    //imagedestroy($newImage);



	   	try{
	      	//$path = $newImage->storeAs(
	      	//'public/propic', $filename
	       //);;
	   		//Storage::move(imagepng($newImage), 'public/propic/'.$filename);
	   		$save = public_path('storage/propic/').$filename.'.png';
	   		imagepng($newImage, $save );
	        } catch (\PDOException $e){
	            $returnData = array(
	                'error' => 'Something worng! Problem on moving files.'
	            );

	            return response()->json($returnData, 200);
	        }

	        	try{
	   				User::where('id', $request->user_id)->update(array('propic_status' => '1'));
	        	} catch(\PDOException $e){
	        		$returnData = array(
	                'error' => 'Something worng! Problem while adding database.'
	            );

	            return response()->json($returnData, 200);
	        	}

	            $returnData = array(
	                'success' => 'Image successfully uploaded.',
	                'filename' => $filename
	            );
   
	        
	        return response()->json($returnData, 200);

    }


    public function delete($id){
        $filename = 'public/storage/propic/' . $id . '_propic.pdf';
        
        try{
            Storage::delete($filename);
        } catch (\PDOException $e){
            return 'data:' . json_encode(array(array('message'=>'Something worng! Error while deleting image.')));
        }
        return 'data:' . json_encode(array(array('message'=>'image successfully deleted.')));
    }

}
