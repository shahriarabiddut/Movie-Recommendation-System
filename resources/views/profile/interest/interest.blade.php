@extends('layout')
@section('title', 'My Interests | Staff Dashboard')
@section('content')


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"> Add Interests </h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add Interests Data
            <a href="{{ route('staff.movie.index') }}" class="float-right btn btn-success btn-sm"> <i class="fa fa-arrow-left"></i> View All </a> </h6>
        </div>
        <div class="card-body">
            <div class="container">
                    <form method="POST" action="{{ route('user.interest.store') }}" enctype="multipart/form-data">
                        @csrf
                      {{-- Genre  --}}
                    <div class="row border p-2 border-light">
                        <div class="col-md-4">
                          <label for="selectedItems">Genre <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-8">
                          <select name="genre[]" class="form-select" id="genre-field" data-placeholder="Choose Genre" multiple>
                              @foreach ($genres as $gr)
                              <option value="{{$gr->id}}">{{$gr->title}}</option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                      {{-- Cast  --}}
                    <div class="row border p-2 border-light">
                        <div class="col-md-4">
                          <label for="selectedItems">Cast <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <select multiple required name="cast[]" class="form-select" id="cast-field" data-placeholder="Choose Cast">
                                @foreach ($casts as $cast)
                                <option value="{{$cast->id}}">{{$cast->name}}</option>
                                @endforeach
                            </select>
                        </div>
                      </div>
                      {{-- Director  --}}
                    <div class="row border p-2 border-light">
                        <div class="col-md-4">
                          <label for="selectedItems">Director <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <select multiple required name="director[]" class="form-select" id="director-field" data-placeholder="Choose Director">
                                @foreach ($directors as $gr)
                                <option value="{{$gr->id}}">{{$gr->name}}</option>
                                @endforeach
                            </select>
                        </div>
                      </div>
                      {{-- Language  --}}
                    <div class="row border p-2 border-light">
                        <div class="col-md-4">
                          <label for="selectedItems">Language <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <select multiple required name="language[]" class="form-select" id="language-field" data-placeholder="Choose Language">
                                @foreach ($languages as $gr)
                                <option value="{{$gr->id}}">{{$gr->title}}</option>
                                @endforeach
                            </select>
                        </div>
                      </div>
                      {{-- Production Company  --}}
                    <div class="row border p-2 border-light">
                        <div class="col-md-4">
                          <label for="selectedItems">Production Company <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <select multiple required name="pcompany[]" class="form-select" id="pcompany-field" data-placeholder="Choose Production Company">
                                @foreach ($pcompanys as $gr)
                                <option value="{{$gr->id}}">{{$gr->title}}</option>
                                @endforeach
                            </select>
                        </div>
                      </div>
                      {{-- Ratings  --}}
                    <div class="row border p-2 border-light">
                      <div class="col-md-4">
                        <label for="selectedItems">Ratings <span class="text-danger">*</span></label> <br>
                        <button id="add-select" type="button" class="btn btn-block btn-info"> Add Ratings</button>
                      </div>
                      <div class="col-md-8" style="height: 55vh;">
                          <div id="input-container" class="m-1">
                          <!-- Select input will be added here -->
                        </div> 
                      
                      </div>
                    </div>
                    {{-- Submit Button --}}
                    <div class="row">
                        <div class="col-md-12 mt-2">
                        <input name="user_id" type="hidden" value="{{ $user->id }}">
                    <button type="submit" class="btn btn-block btn-primary">Submit</button>
                    </div>
                </div>
                </form>
                
                {{--  --}}
            </div>
        </div>
    </div>
    {{-- Multi Data --}}
    <script>
        $( '#genre-field' ).select2( {
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '50%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: false,
        } );
        $( '#cast-field' ).select2( {
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '50%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: false,
        } );
        $( '#director-field' ).select2( {
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '50%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: false,
        } );
        $( '#language-field' ).select2( {
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '50%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: false,
        } );
        $( '#pcompany-field' ).select2( {
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '50%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: false,
        } );
        
        // Initialize a counter to keep track of the button clicks
        let clickCount = 0;
        // Function to create and append a new select input
        function createSelect() {
            const inputContainer = document.getElementById("input-container");

            const select = document.createElement("select");
            select.name = "r"+clickCount; // Set the name attribute
            select.className = "form-control m-1"; // Set the class attribute
            // Create and append option elements
            const option1 = document.createElement("option");
            option1.text = "IMDB";
            const option2 = document.createElement("option");
            option2.text = "Rotten Tommetoes";
            const option3 = document.createElement("option");
            option3.text = "Extra";

            if (clickCount == 0) {
              select.appendChild(option1);
            }else if(clickCount == 1) {
              select.appendChild(option2);
            }else if(clickCount == 2) {
              select.appendChild(option3);
            }

            // Create and append text input with name attribute
            const textInput = document.createElement("input");
            textInput.type = "number";
            textInput.name = "rating"+clickCount; // Set the name attribute
            if (clickCount == 0) {
              textInput.max = 10; // Set the maximum value (e.g., 100)
            }else if(clickCount == 1) {
              textInput.max = 100; // Set the maximum value (e.g., 100)
            }else if(clickCount == 2) {
              textInput.max = 5; // Set the maximum value (e.g., 100)
            }
            textInput.className = "form-control m-1"; // Set the class attribute
            textInput.placeholder = "Enter rating";
            
            const lineBreak = document.createElement("br");

            inputContainer.appendChild(select);
            inputContainer.appendChild(textInput);
            inputContainer.appendChild(lineBreak);
            // Increase the click count
                clickCount++;

            // Check if the button should be hidden
            if (clickCount >= 3) {
                document.getElementById("add-select").style.display = "none";
            }
        }

        // Event listener for the "Add Select" button
        document.getElementById("add-select").addEventListener("click", createSelect);

    </script>
    @section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    @endsection
@endsection
