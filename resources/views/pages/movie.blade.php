<!-- Header -->
@section('title', 'Movie')
@include('../layouts/homeHeader')
<!-- End of Header -->


  <main id="main ">

    <!-- ======= Movie Section ======= -->
    <section id="events" class="events mt-3">
      <div class="container-fluid" data-aos="fade-up">

        <div class="section-header ">
          <h2>Latest Movies</h2>
          <p>Share <span>Your Moments</span> In Our Website</p>
        </div>

        <div class="slides-3 swiper" data-aos="fade-up" data-aos-delay="100">
          <div class="swiper-wrapper">
            @foreach ($data as $movie)
            <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url({{$movie->photo ? asset('storage/'.$movie->photo) : asset('images/productioncompany.jpg')}})">
              <h3 class="text-white"><a href="{{ route('movie.show',$movie->id) }}">{{ $movie->title }}</a></h3>
              
              <p class="description mb-1">
                @excerpt($movie->info)
              </p>
              @foreach ($movie->MovieRating as $rating)
                @switch($rating->rating_id)
                @case(1)
                    <p class=" price align-self-start p-1 bg-info" style="font-size: 16px;border-bottom: none;"> IMDB : {{ $rating->ratings }} / 10 </p>
                    @break
                @case(2)
                    <p class=" price align-self-start p-1 bg-danger" style="font-size: 16px;border-bottom: none;"> Rotten Tommetoes : {{ $rating->ratings }} / 100 </p>  
                    @break
                @case(3)
                    <p class="price align-self-start p-1 bg-warning" style="font-size: 16px;border-bottom: none;"> Extra : {{ $rating->ratings }} / 5 </p>  
                    @break
                @default
                  <div class="price align-self-start" style="font-size: 16px;border-bottom: none;">{{ $rating->ratings }}</div>
                    
                @endswitch 
              @endforeach
            </div><!-- End Event item -->
            @endforeach
          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section><!-- End Events Section -->
  </main><!-- End #main -->


  <!-- ======= Footer ======= -->
  @include('../layouts/homeFooter')
  <!-- ======= End Footer ======= -->