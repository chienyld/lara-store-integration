<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Carousel;
use DB;

class CarouselController extends Controller
{
    public function index(){
        $carousel = Carousel::orderBy('created_at','desc')->get();
        //$carousel = Carousel::find(1)->get();
        return view('Carousel')->with('carousel',$carousel);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'card_image' => 'image|nullable|max:1999'
        ]);
        
        // Handle File Upload
        if($request->hasFile('card_image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('card_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('card_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('card_image')->storeAs('public/card_image', $fileNameToStore);
		
	    // make thumbnails
	    $thumbStore = 'thumb.'.$filename.'_'.time().'.'.$extension;
            $thumb = Image::make($request->file('card_image')->getRealPath());
            //$thumb->resize(80, 80);
            $thumb->save('storage/card_image/'.$thumbStore);
		
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        // Create 
        $carousel = new Carousel;
        $carousel->card_image = $fileNameToStore;
        $carousel->save();

        return redirect('/carousel')->with('success', 'carousel Created');
    }

    public function edit(Request $request)
    {
        $carousel = Carousel::find($request->input('id'));
        
        //Check if post exists before deleting
        if (!isset($carousel)){
            return redirect('/carousel')->with('error', 'No carousel Found');
        }

        // Check for correct user
        if(auth()->user()->privilege !=='sa_admin'){
            return redirect('/carousel')->with('error', 'Unauthorized Page');
        }

        return view('carousel')->with('carousel', $carousel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
		
         // Handle File Upload
        if($request->hasFile('card_image')){
            //$carousel = DB::table('carousel')->where('id', $id)->first();
            $carousel = Carousel::find($id);
            // Get filename with the extension
            $filenameWithExt = $request->file('card_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('card_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('card_image')->storeAs('public/card_image', $fileNameToStore);
            // Delete file if exists
            Storage::delete('public/card_image/'.$carousel->card_image);//123
		
	   //Make thumbnails
	    /*$thumbStore = 'thumb.'.$filename.'_'.time().'.'.$extension;
            $thumb = Image::make($request->file('card_image')->getRealPath());
            $thumb->resize(80, 80);
            $thumb->save('storage/card_images/'.$thumbStore);
		*/
        }

        if($request->hasFile('card_image')){
            $carousel->card_image = $fileNameToStore;
        }
        $carousel->save();

        return redirect('/carousel')->with('success', 'Carousel Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carousel = Carousel::find($id);
        
        //Check if post exists before deleting
        if (!isset($carousel)){
            return redirect('/carousel')->with('error', 'No carousel Found');
        }

        // Check for correct user
        if(auth()->user()->privilege !=='sa_admin'){
            return redirect('/carousel')->with('error', 'Unauthorized Page');
        }

        if($carousel->card_image != 'noimage.jpg'){
            // Delete Image
            Storage::delete('public/card_image/'.$carousel->card_image); //123
        }
        
        $carousel->delete();
        return redirect('/carousel')->with('success', 'Carousel Removed');
    }

}
