@foreach($images as $img)
	<li>
	    <div class="scenes-photo" data-src="{{url('uploads/'.$img->location_image_url)}}">
	        <img src="{{url('uploads/'.$img->location_image_url)}}">
	    </div>
	</li>
@endforeach