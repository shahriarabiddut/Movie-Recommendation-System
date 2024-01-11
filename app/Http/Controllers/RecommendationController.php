<?php

namespace App\Http\Controllers;

use App\Models\Cast;
use App\Models\User;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Country;
use App\Models\Director;
use App\Models\Interest;
use App\Models\Language;
use App\Models\MovieCast;
use Illuminate\View\View;
use App\Models\MovieGenre;
use App\Models\InterestCast;
use Illuminate\Http\Request;
use App\Models\InterestGenre;
use App\Models\MovieDirector;
use App\Models\MovieLanguage;
use App\Models\InterestRating;
use App\Models\InterestCountry;
use App\Models\InterestDirector;
use App\Models\InterestLanguage;
use App\Models\InterestPcompany;
use App\Models\MovieCountry;
use App\Models\MoviePcompany;
use App\Models\ProductionCompany;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $user = Auth::user();
        $data = Interest::all()->where('user_id', '=', $user->id)->first();
        if ($data == null) {
            //IF no interest Added
            $genres = Genre::all();
            $casts = Cast::all();
            $languages = Language::all();
            $pcompanys = ProductionCompany::all();
            $directors = Director::all();
            $countries = Country::all();
            return view('profile.interest.interest', ['genres' => $genres, 'casts' => $casts, 'languages' => $languages, 'pcompanys' => $pcompanys, 'directors' => $directors, 'countries' => $countries, 'user' => $user]);
        } else {
            $id = $data->id;
            //IF any interest Added
            $data = Interest::find($id);
            $InterestGenredata = InterestGenre::all()->where('interest_id', '=', $id);
            $InterestCastdata = InterestCast::all()->where('interest_id', '=', $id);
            $InterestDirectordata = InterestDirector::all()->where('interest_id', '=', $id);
            $InterestLanguagedata = InterestLanguage::all()->where('interest_id', '=', $id);
            $InterestPcompanydata = InterestPcompany::all()->where('interest_id', '=', $id);
            $InterestRatingData = InterestRating::all()->where('interest_id', '=', $id);
            $InterestCountrydata = InterestCountry::all()->where('interest_id', '=', $id);
        }
        // Genre Weight & Prediction
        $genreId = [];
        foreach ($InterestGenredata as $genreData) { //change here
            $genreId[] = $genreData->genre_id; //change here
        }
        $genrePredict = $this->genrePredict($genreId);
        //
        // Language Weight & Prediction
        $languageId = [];
        foreach ($InterestLanguagedata as $genreData) { //change here
            $languageId[] = $genreData->language_id; //change here
        }
        $languagePredict = $this->languagePredict($languageId);
        // Cast Weight & Prediction
        $castId = [];
        foreach ($InterestCastdata as $genreData) { //change here
            $castId[] = $genreData->cast_id; //change here
        }
        $castPredict = $this->castPredict($castId);
        // Director Weight & Prediction
        $directorId = [];
        foreach ($InterestDirectordata as $genreData) { //change here
            $directorId[] = $genreData->director_id; //change here
        }
        $directorPredict = $this->directorPredict($directorId);
        // Country Weight & Prediction
        $countryId = [];
        foreach ($InterestCountrydata as $genreData) { //change here
            $countryId[] = $genreData->country_id; //change here
        }
        $countryPredict = $this->countryPredict($countryId);
        //
        // Prduction Company Weight & Prediction
        $pcompanyId = [];
        foreach ($InterestPcompanydata as $genreData) { //change here
            $pcompanyId[] = $genreData->pcompany_id; //change here
        }
        $pcompanyPredict = $this->pcompanyPredict($pcompanyId);
        //
        // Weight Merge 
        $calculation = $this->calculation($languagePredict, $genrePredict, $castPredict, $directorPredict, $countryPredict, $pcompanyPredict);
        //Sorting Weight
        asort($calculation);
        // dd($calculation, $languagePredict, $genrePredict, $castPredict, $directorPredict, $countryPredict, $pcompanyPredict);
        //Sending Data To Front
        $RecomendedMovies = [];
        $i = 1;
        $totalMovies = 2; // Set Value of movies to show here
        foreach ($calculation as $key => $MoviE) {
            $movieData = Movie::all()->where('id', $key)->first();
            $RecomendedMovies[] = $movieData;
            if ($i >= $totalMovies) {
                break;
            }
            $i += 1;
        }
        shuffle($RecomendedMovies);
        return view('pages.recom', ['calculation' => $calculation, 'data' => $RecomendedMovies]);
    }
    //Genre
    public function genrePredict(array $genreId)
    {
        //User Genres Sorted
        $genreUserMD = array_values($genreId);
        //All Movies
        $data = Movie::all();
        //KNN Selection Array
        $selectedMovie = [];
        // Check Match
        foreach ($data as $key => $movie) {
            $weight = 0;
            //reseting the value
            $genreM = MovieGenre::all()->where('movie_id', $movie->id);
            $genreUser = $genreUserMD;
            //Normalizing key
            $genreMovie = [];
            foreach ($genreM as $movieGenre) {
                $genreMovie[] = $movieGenre->genre_id;
            }

            array_values($genreMovie);
            $matchDiff = array_diff($genreUser, $genreMovie);
            $matchDiff2 = array_diff($genreMovie, $genreUser);
            if (empty($matchDiff) && empty($matchDiff2)) {
                $selectedMovie['movie' . $key] = $movie->id . '@' . '0';
            } else {
                // dd($genreMovie, $genreUser, $movie->id);
                //Remove matched values
                foreach ($genreUser as $genreData) {
                    $valueToRemove = $genreData;
                    while (($keyOfUser = array_search($valueToRemove, $genreMovie)) !== false) {
                        unset($genreMovie[$keyOfUser]);
                        $keyOfUserRemove = array_search($valueToRemove, $genreUser);
                        unset($genreUser[$keyOfUserRemove]);
                    }
                }
                //

                if (count($genreUser) == 0 && count($genreMovie) != 0) {
                    // // Movie to User Distance
                    // //Euclidean distance of values
                    // $nWeight = 0;
                    // foreach ($genreMovie as $genreData) {
                    //     $nWeight = $nWeight + ($genreData * $genreData);
                    // }
                    // $weight = sqrt($nWeight);
                    // $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    $selectedMovie['movie' . $key] = $movie->id . '@0';
                } elseif (count($genreMovie) == 0 && count($genreUser) != 0) {

                    //Euclidean distance of values
                    $nWeight = 0;
                    foreach ($genreUser as $genreData) {
                        $nWeight = $nWeight + ($genreData * $genreData);
                    }
                    $weight = sqrt($nWeight);
                    $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                } elseif (count($genreMovie) != 0 && count($genreUser) != 0) {
                    //Normalizing Movie Genre Values
                    $genreMovieD = [];
                    foreach ($genreMovie as $movieGenre) {
                        $genreMovieD[] = $movieGenre;
                    }
                    array_values($genreMovieD);
                    //Normalizing User Genre Values
                    $genreUserD = [];
                    foreach ($genreUser as $movieGenre) {
                        $genreUserD[] = $movieGenre;
                    }
                    array_values($genreUserD);
                    //
                    //counter 
                    $counterUser = count($genreUser);
                    $counterMovie = count($genreMovie);

                    if ($counterUser == $counterMovie) {
                        $nWeight = 0;
                        for ($i = 0; $i < $counterUser; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                        }
                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    } elseif ($counterMovie > $counterUser) {

                        $nWeight = 0;
                        for ($i = 0; $i < $counterUser; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                            unset($genreUserD[$i]);
                            unset($genreMovieD[$i]);
                        }
                        foreach ($genreUserD as $genreUserData) {
                            $nWeight = $nWeight + $genreUserData * $genreUserData;
                        }
                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    } elseif ($counterUser > $counterMovie) {

                        $nWeight = 0;
                        for ($i = 0; $i < $counterMovie; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                            unset($genreUserD[$i]);
                            unset($genreMovieD[$i]);
                        }
                        foreach ($genreUserD as $genreUserData) {
                            $nWeight = $nWeight + $genreUserData * $genreUserData;
                        }

                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    }
                }
            }
        }
        return $selectedMovie;
    }
    //Language
    public function languagePredict(array $languageId)
    {
        //User Genres Sorted
        $genreUserMD = array_values($languageId);
        //All Movies
        $data = Movie::all();
        //KNN Selection Array
        $selectedMovie = [];
        // Check Match
        foreach ($data as $key => $movie) {
            $weight = 0;
            //reseting the value
            $genreM = MovieLanguage::all()->where('movie_id', $movie->id); // change here
            $genreUser = $genreUserMD;
            //Normalizing key
            $genreMovie = [];
            foreach ($genreM as $movieGenre) {
                $genreMovie[] = $movieGenre->language_id; // change here
            }
            array_values($genreMovie);
            //
            $matchDiff = array_diff($genreUser, $genreMovie);
            $matchDiff2 = array_diff($genreMovie, $genreUser);
            if (empty($matchDiff) && empty($matchDiff2)) {
                $selectedMovie['movie' . $key] = $movie->id . '@' . '0';
            } else {
                // dd($genreMovie, $genreUser, $movie->id);
                //Remove matched values
                foreach ($genreUser as $genreData) {
                    $valueToRemove = $genreData;
                    while (($keyOfUser = array_search($valueToRemove, $genreMovie)) !== false) {
                        unset($genreMovie[$keyOfUser]);
                        $keyOfUserRemove = array_search($valueToRemove, $genreUser);
                        unset($genreUser[$keyOfUserRemove]);
                    }
                }
                //


                if (count($genreUser) == 0 && count($genreMovie) != 0) {
                    $selectedMovie['movie' . $key] = $movie->id . '@0';
                } elseif (count($genreMovie) == 0 && count($genreUser) != 0) {

                    //Euclidean distance of values
                    $nWeight = 0;
                    foreach ($genreUser as $genreData) {
                        $nWeight = $nWeight + ($genreData * $genreData);
                    }
                    $weight = sqrt($nWeight);
                    $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                } elseif (count($genreMovie) != 0 && count($genreUser) != 0) {
                    //Normalizing Movie Genre Values
                    $genreMovieD = [];
                    foreach ($genreMovie as $movieGenre) {
                        $genreMovieD[] = $movieGenre;
                    }
                    array_values($genreMovieD);
                    //Normalizing User Genre Values
                    $genreUserD = [];
                    foreach ($genreUser as $movieGenre) {
                        $genreUserD[] = $movieGenre;
                    }
                    array_values($genreUserD);
                    //
                    //counter 
                    $counterUser = count($genreUser);
                    $counterMovie = count($genreMovie);

                    if ($counterUser == $counterMovie) {
                        $nWeight = 0;
                        for ($i = 0; $i < $counterUser; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                        }
                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    } elseif ($counterMovie > $counterUser) {

                        $nWeight = 0;
                        for ($i = 0; $i < $counterUser; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                            unset($genreUserD[$i]);
                            unset($genreMovieD[$i]);
                        }
                        foreach ($genreUserD as $genreUserData) {
                            $nWeight = $nWeight + $genreUserData * $genreUserData;
                        }
                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    } elseif ($counterUser > $counterMovie) {

                        $nWeight = 0;
                        for ($i = 0; $i < $counterMovie; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                            unset($genreUserD[$i]);
                            unset($genreMovieD[$i]);
                        }
                        foreach ($genreUserD as $genreUserData) {
                            $nWeight = $nWeight + $genreUserData * $genreUserData;
                        }

                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    }
                }
            }
        }
        return $selectedMovie;
    }
    //Cast
    public function castPredict(array $castId)
    {
        //User Genres Sorted
        $genreUserMD = array_values($castId);
        //All Movies
        $data = Movie::all();
        //KNN Selection Array
        $selectedMovie = [];
        // Check Match
        foreach ($data as $key => $movie) {
            $weight = 0;
            //reseting the value
            $genreM = MovieCast::all()->where('movie_id', $movie->id); // change here
            $genreUser = $genreUserMD;
            //Normalizing key
            $genreMovie = [];
            foreach ($genreM as $movieGenre) {
                $genreMovie[] = $movieGenre->cast_id; // change here
            }
            array_values($genreMovie);
            //
            $matchDiff = array_diff($genreUser, $genreMovie);
            $matchDiff2 = array_diff($genreMovie, $genreUser);
            if (empty($matchDiff) && empty($matchDiff2)) {
                $selectedMovie['movie' . $key] = $movie->id . '@' . '0';
            } else {
                // dd($genreMovie, $genreUser, $movie->id);
                //Remove matched values
                foreach ($genreUser as $genreData) {
                    $valueToRemove = $genreData;
                    while (($keyOfUser = array_search($valueToRemove, $genreMovie)) !== false) {
                        unset($genreMovie[$keyOfUser]);
                        $keyOfUserRemove = array_search($valueToRemove, $genreUser);
                        unset($genreUser[$keyOfUserRemove]);
                    }
                }
                //


                if (count($genreUser) == 0 && count($genreMovie) != 0) {
                    $selectedMovie['movie' . $key] = $movie->id . '@0';
                } elseif (count($genreMovie) == 0 && count($genreUser) != 0) {

                    //Euclidean distance of values
                    $nWeight = 0;
                    foreach ($genreUser as $genreData) {
                        $nWeight = $nWeight + ($genreData * $genreData);
                    }
                    $weight = sqrt($nWeight);
                    $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                } elseif (count($genreMovie) != 0 && count($genreUser) != 0) {
                    //Normalizing Movie Genre Values
                    $genreMovieD = [];
                    foreach ($genreMovie as $movieGenre) {
                        $genreMovieD[] = $movieGenre;
                    }
                    array_values($genreMovieD);
                    //Normalizing User Genre Values
                    $genreUserD = [];
                    foreach ($genreUser as $movieGenre) {
                        $genreUserD[] = $movieGenre;
                    }
                    array_values($genreUserD);
                    //
                    //counter 
                    $counterUser = count($genreUser);
                    $counterMovie = count($genreMovie);

                    if ($counterUser == $counterMovie) {
                        $nWeight = 0;
                        for ($i = 0; $i < $counterUser; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                        }
                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    } elseif ($counterMovie > $counterUser) {

                        $nWeight = 0;
                        for ($i = 0; $i < $counterUser; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                            unset($genreUserD[$i]);
                            unset($genreMovieD[$i]);
                        }
                        foreach ($genreUserD as $genreUserData) {
                            $nWeight = $nWeight + $genreUserData * $genreUserData;
                        }
                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    } elseif ($counterUser > $counterMovie) {

                        $nWeight = 0;
                        for ($i = 0; $i < $counterMovie; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                            unset($genreUserD[$i]);
                            unset($genreMovieD[$i]);
                        }
                        foreach ($genreUserD as $genreUserData) {
                            $nWeight = $nWeight + $genreUserData * $genreUserData;
                        }

                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    }
                }
            }
        }
        return $selectedMovie;
    }
    //Director
    public function directorPredict(array $directorId)
    {
        //User Genres Sorted
        $genreUserMD = array_values($directorId);
        //All Movies
        $data = Movie::all();
        //KNN Selection Array
        $selectedMovie = [];
        // Check Match
        foreach ($data as $key => $movie) {
            $weight = 0;
            //reseting the value
            $genreM = MovieDirector::all()->where('movie_id', $movie->id); // change here
            $genreUser = $genreUserMD;
            //Normalizing key
            $genreMovie = [];
            foreach ($genreM as $movieGenre) {
                $genreMovie[] = $movieGenre->director_id; // change here
            }
            array_values($genreMovie);
            //
            $matchDiff = array_diff($genreUser, $genreMovie);
            $matchDiff2 = array_diff($genreMovie, $genreUser);
            if (empty($matchDiff) && empty($matchDiff2)) {
                $selectedMovie['movie' . $key] = $movie->id . '@' . '0';
            } else {
                // dd($genreMovie, $genreUser, $movie->id);
                //Remove matched values
                foreach ($genreUser as $genreData) {
                    $valueToRemove = $genreData;
                    while (($keyOfUser = array_search($valueToRemove, $genreMovie)) !== false) {
                        unset($genreMovie[$keyOfUser]);
                        $keyOfUserRemove = array_search($valueToRemove, $genreUser);
                        unset($genreUser[$keyOfUserRemove]);
                    }
                }
                //


                if (count($genreUser) == 0 && count($genreMovie) != 0) {
                    $selectedMovie['movie' . $key] = $movie->id . '@0';
                } elseif (count($genreMovie) == 0 && count($genreUser) != 0) {

                    //Euclidean distance of values
                    $nWeight = 0;
                    foreach ($genreUser as $genreData) {
                        $nWeight = $nWeight + ($genreData * $genreData);
                    }
                    $weight = sqrt($nWeight);
                    $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                } elseif (count($genreMovie) != 0 && count($genreUser) != 0) {
                    //Normalizing Movie Genre Values
                    $genreMovieD = [];
                    foreach ($genreMovie as $movieGenre) {
                        $genreMovieD[] = $movieGenre;
                    }
                    array_values($genreMovieD);
                    //Normalizing User Genre Values
                    $genreUserD = [];
                    foreach ($genreUser as $movieGenre) {
                        $genreUserD[] = $movieGenre;
                    }
                    array_values($genreUserD);
                    //
                    //counter 
                    $counterUser = count($genreUser);
                    $counterMovie = count($genreMovie);

                    if ($counterUser == $counterMovie) {
                        $nWeight = 0;
                        for ($i = 0; $i < $counterUser; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                        }
                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    } elseif ($counterMovie > $counterUser) {

                        $nWeight = 0;
                        for ($i = 0; $i < $counterUser; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                            unset($genreUserD[$i]);
                            unset($genreMovieD[$i]);
                        }
                        foreach ($genreUserD as $genreUserData) {
                            $nWeight = $nWeight + $genreUserData * $genreUserData;
                        }
                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    } elseif ($counterUser > $counterMovie) {

                        $nWeight = 0;
                        for ($i = 0; $i < $counterMovie; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                            unset($genreUserD[$i]);
                            unset($genreMovieD[$i]);
                        }
                        foreach ($genreUserD as $genreUserData) {
                            $nWeight = $nWeight + $genreUserData * $genreUserData;
                        }

                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    }
                }
            }
        }
        return $selectedMovie;
    }
    //Country
    public function countryPredict(array $countryId)
    {
        //User Genres Sorted
        $genreUserMD = array_values($countryId);
        //All Movies
        $data = Movie::all();
        //KNN Selection Array
        $selectedMovie = [];
        // Check Match
        foreach ($data as $key => $movie) {
            $weight = 0;
            //reseting the value
            $genreM = MovieCountry::all()->where('movie_id', $movie->id); // change here
            $genreUser = $genreUserMD;
            //Normalizing key
            $genreMovie = [];
            foreach ($genreM as $movieGenre) {
                $genreMovie[] = $movieGenre->country_id; // change here
            }
            array_values($genreMovie);
            //
            $matchDiff = array_diff($genreUser, $genreMovie);
            $matchDiff2 = array_diff($genreMovie, $genreUser);
            if (empty($matchDiff) && empty($matchDiff2)) {
                $selectedMovie['movie' . $key] = $movie->id . '@' . '0';
            } else {
                // dd($genreMovie, $genreUser, $movie->id);
                //Remove matched values
                foreach ($genreUser as $genreData) {
                    $valueToRemove = $genreData;
                    while (($keyOfUser = array_search($valueToRemove, $genreMovie)) !== false) {
                        unset($genreMovie[$keyOfUser]);
                        $keyOfUserRemove = array_search($valueToRemove, $genreUser);
                        unset($genreUser[$keyOfUserRemove]);
                    }
                }
                //


                if (count($genreUser) == 0 && count($genreMovie) != 0) {
                    $selectedMovie['movie' . $key] = $movie->id . '@0';
                } elseif (count($genreMovie) == 0 && count($genreUser) != 0) {

                    //Euclidean distance of values
                    $nWeight = 0;
                    foreach ($genreUser as $genreData) {
                        $nWeight = $nWeight + ($genreData * $genreData);
                    }
                    $weight = sqrt($nWeight);
                    $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                } elseif (count($genreMovie) != 0 && count($genreUser) != 0) {
                    //Normalizing Movie Genre Values
                    $genreMovieD = [];
                    foreach ($genreMovie as $movieGenre) {
                        $genreMovieD[] = $movieGenre;
                    }
                    array_values($genreMovieD);
                    //Normalizing User Genre Values
                    $genreUserD = [];
                    foreach ($genreUser as $movieGenre) {
                        $genreUserD[] = $movieGenre;
                    }
                    array_values($genreUserD);
                    //
                    //counter 
                    $counterUser = count($genreUser);
                    $counterMovie = count($genreMovie);

                    if ($counterUser == $counterMovie) {
                        $nWeight = 0;
                        for ($i = 0; $i < $counterUser; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                        }
                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    } elseif ($counterMovie > $counterUser) {

                        $nWeight = 0;
                        for ($i = 0; $i < $counterUser; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                            unset($genreUserD[$i]);
                            unset($genreMovieD[$i]);
                        }
                        foreach ($genreUserD as $genreUserData) {
                            $nWeight = $nWeight + $genreUserData * $genreUserData;
                        }
                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    } elseif ($counterUser > $counterMovie) {

                        $nWeight = 0;
                        for ($i = 0; $i < $counterMovie; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                            unset($genreUserD[$i]);
                            unset($genreMovieD[$i]);
                        }
                        foreach ($genreUserD as $genreUserData) {
                            $nWeight = $nWeight + $genreUserData * $genreUserData;
                        }

                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    }
                }
            }
        }
        return $selectedMovie;
    }
    //Production Company
    public function pcompanyPredict(array $pcompanyId)
    {
        //User Genres Sorted
        $genreUserMD = array_values($pcompanyId);
        //All Movies
        $data = Movie::all();
        //KNN Selection Array
        $selectedMovie = [];
        // Check Match
        foreach ($data as $key => $movie) {
            $weight = 0;
            //reseting the value
            $genreM = MoviePcompany::all()->where('movie_id', $movie->id); // change here
            $genreUser = $genreUserMD;
            //Normalizing key
            $genreMovie = [];
            foreach ($genreM as $movieGenre) {
                $genreMovie[] = $movieGenre->pcompany_id; // change here
            }
            array_values($genreMovie);
            //
            $matchDiff = array_diff($genreUser, $genreMovie);
            $matchDiff2 = array_diff($genreMovie, $genreUser);
            if (empty($matchDiff) && empty($matchDiff2)) {
                $selectedMovie['movie' . $key] = $movie->id . '@' . '0';
            } else {
                // dd($genreMovie, $genreUser, $movie->id);
                //Remove matched values
                foreach ($genreUser as $genreData) {
                    $valueToRemove = $genreData;
                    while (($keyOfUser = array_search($valueToRemove, $genreMovie)) !== false) {
                        unset($genreMovie[$keyOfUser]);
                        $keyOfUserRemove = array_search($valueToRemove, $genreUser);
                        unset($genreUser[$keyOfUserRemove]);
                    }
                }
                //


                if (count($genreUser) == 0 && count($genreMovie) != 0) {
                    // // Movie to User Distance
                    // //Euclidean distance of values
                    // $nWeight = 0;
                    // foreach ($genreMovie as $genreData) {
                    //     $nWeight = $nWeight + ($genreData * $genreData);
                    // }
                    // $weight = sqrt($nWeight);
                    // $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    $selectedMovie['movie' . $key] = $movie->id . '@0';
                } elseif (count($genreMovie) == 0 && count($genreUser) != 0) {

                    //Euclidean distance of values
                    $nWeight = 0;
                    foreach ($genreUser as $genreData) {
                        $nWeight = $nWeight + ($genreData * $genreData);
                    }
                    $weight = sqrt($nWeight);
                    $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                } elseif (count($genreMovie) != 0 && count($genreUser) != 0) {
                    //Normalizing Movie Genre Values
                    $genreMovieD = [];
                    foreach ($genreMovie as $movieGenre) {
                        $genreMovieD[] = $movieGenre;
                    }
                    array_values($genreMovieD);
                    //Normalizing User Genre Values
                    $genreUserD = [];
                    foreach ($genreUser as $movieGenre) {
                        $genreUserD[] = $movieGenre;
                    }
                    array_values($genreUserD);
                    //
                    //counter 
                    $counterUser = count($genreUser);
                    $counterMovie = count($genreMovie);

                    if ($counterUser == $counterMovie) {
                        $nWeight = 0;
                        for ($i = 0; $i < $counterUser; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                        }
                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    } elseif ($counterMovie > $counterUser) {

                        $nWeight = 0;
                        for ($i = 0; $i < $counterUser; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                            unset($genreUserD[$i]);
                            unset($genreMovieD[$i]);
                        }
                        foreach ($genreUserD as $genreUserData) {
                            $nWeight = $nWeight + $genreUserData * $genreUserData;
                        }
                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    } elseif ($counterUser > $counterMovie) {

                        $nWeight = 0;
                        for ($i = 0; $i < $counterMovie; $i++) {
                            //Euclidean distance of values
                            $nWeight = $nWeight + ($genreUserD[$i] - $genreMovieD[$i]) * ($genreUserD[$i] - $genreMovieD[$i]);
                            unset($genreUserD[$i]);
                            unset($genreMovieD[$i]);
                        }
                        foreach ($genreUserD as $genreUserData) {
                            $nWeight = $nWeight + $genreUserData * $genreUserData;
                        }

                        $weight = sqrt($nWeight);
                        $selectedMovie['movie' . $key] = $movie->id . '@' . $weight;
                    }
                }
            }
        }
        return $selectedMovie;
    }
    //Calculation
    public function calculation(array $languagePredict, array $genrePredict, array $castPredict, array $directorPredict, array $countryPredict, array $pcompanyPredict)
    {
        $mergeWeight = [];
        //genre
        foreach ($languagePredict as $key => $weight) {
            // Explode Movie and its weight
            $parts = explode("@", $weight);
            foreach ($parts as $keyPart => $part) {
                if ($keyPart == 0) {
                    $movie_id = $part;
                } else {
                    $movie_weight = $part;
                }
            }
            $mergeWeight[$movie_id] = $movie_weight;
        }
        //language
        foreach ($genrePredict as $key => $weight) {
            // Explode Movie and its weight
            $parts = explode("@", $weight);
            foreach ($parts as $keyPart => $part) {
                if ($keyPart == 0) {
                    $movie_id = $part;
                } else {
                    $movie_weight = $part;
                }
            }
            $mergeWeight[$movie_id] = $mergeWeight[$movie_id] + $movie_weight;
        }
        //cast
        foreach ($castPredict as $key => $weight) {
            // Explode Movie and its weight
            $parts = explode("@", $weight);
            foreach ($parts as $keyPart => $part) {
                if ($keyPart == 0) {
                    $movie_id = $part;
                } else {
                    $movie_weight = $part;
                }
            }
            $mergeWeight[$movie_id] = $mergeWeight[$movie_id] + $movie_weight;
        }
        //Director
        foreach ($directorPredict as $key => $weight) {
            // Explode Movie and its weight
            $parts = explode("@", $weight);
            foreach ($parts as $keyPart => $part) {
                if ($keyPart == 0) {
                    $movie_id = $part;
                } else {
                    $movie_weight = $part;
                }
            }
            $mergeWeight[$movie_id] = $mergeWeight[$movie_id] + $movie_weight;
        }
        //Country
        foreach ($countryPredict as $key => $weight) {
            // Explode Movie and its weight
            $parts = explode("@", $weight);
            foreach ($parts as $keyPart => $part) {
                if ($keyPart == 0) {
                    $movie_id = $part;
                } else {
                    $movie_weight = $part;
                }
            }
            $mergeWeight[$movie_id] = $mergeWeight[$movie_id] + $movie_weight;
        }
        //Production company 
        foreach ($pcompanyPredict as $key => $weight) {
            // Explode Movie and its weight
            $parts = explode("@", $weight);
            foreach ($parts as $keyPart => $part) {
                if ($keyPart == 0) {
                    $movie_id = $part;
                } else {
                    $movie_weight = $part;
                }
            }
            $mergeWeight[$movie_id] = $mergeWeight[$movie_id] + $movie_weight;
        }
        // dd($movie_id, $movie_weight);
        return $mergeWeight;
    }
}
