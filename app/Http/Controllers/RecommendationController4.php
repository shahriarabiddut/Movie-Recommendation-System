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
use Phpml\Clustering\KMeans;

class RecommendationController3 extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function data2DArrayAll()
    {
        $mData = Movie::all();
        $uData = MovieCountry::all();
        $vData = MoviePcompany::all();
        $wData = MovieDirector::all();
        $xData = MovieLanguage::all();
        $yData = MovieGenre::all();
        $zData = MovieCast::all();
        $resultArray = [];
        foreach ($mData as $mItem) {
            foreach ($uData as $uItem) {
                if ($uItem->movie_id == $mItem->id) {
                    foreach ($vData as $vItem) {
                        if ($vItem->movie_id == $mItem->id) {
                            foreach ($wData as $wItem) {
                                if ($wItem->movie_id == $mItem->id) {
                                    foreach ($xData as $xItem) {
                                        if ($xItem->movie_id == $mItem->id) {
                                            foreach ($yData as $yItem) {
                                                if ($yItem->movie_id == $mItem->id) {
                                                    foreach ($zData as $zItem) {
                                                        if ($zItem->movie_id == $mItem->id) {
                                                            $resultArray[] = [
                                                                0 => $mItem->id,
                                                                1 => $uItem->country_id,
                                                                2 => $vItem->pcompany_id,
                                                                3 => $wItem->director_id,
                                                                4 => $xItem->language_id,
                                                                5 => $yItem->genre_id,
                                                                6 => $zItem->cast_id,
                                                            ];
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        //User Array
        $user = Auth::user();
        $data = Interest::all()->where('user_id', '=', $user->id)->first();
        $id = $data->id;
        //IF any interest Added
        // $resultArray2 = [];
        //IF any interest Added
        $data = Interest::find($id);
        $InterestGenredata = InterestGenre::all()->where('interest_id', '=', $id);
        $InterestCastdata = InterestCast::all()->where('interest_id', '=', $id);
        $InterestDirectordata = InterestDirector::all()->where('interest_id', '=', $id);
        $InterestLanguagedata = InterestLanguage::all()->where('interest_id', '=', $id);
        $InterestPcompanydata = InterestPcompany::all()->where('interest_id', '=', $id);
        $InterestCountrydata = InterestCountry::all()->where('interest_id', '=', $id);
        foreach ($InterestCountrydata as $uItem) {
            foreach ($InterestPcompanydata as $vItem) {
                foreach ($InterestDirectordata as $wItem) {
                    foreach ($InterestLanguagedata as $xItem) {
                        foreach ($InterestGenredata as $yItem) {
                            foreach ($InterestCastdata as $zItem) {
                                $resultArray[] = [
                                    0 => '-1',
                                    1 => $uItem->country_id,
                                    2 => $vItem->pcompany_id,
                                    3 => $wItem->director_id,
                                    4 => $xItem->language_id,
                                    5 => $yItem->genre_id,
                                    6 => $zItem->cast_id,
                                ];
                            }
                        }
                    }
                }
            }
        }
        // dd($resultArray2);
        return $resultArray;
    }
    public function data2DArrayold()
    {
        $user = Auth::user();
        $data = Interest::all()->where('user_id', '=', $user->id)->first();
        $id = $data->id;
        //IF any interest Added
        $data = Interest::find($id);
        $InterestGenredata = InterestGenre::all()->where('interest_id', '=', $id);
        $InterestLanguagedata = InterestLanguage::all()->where('interest_id', '=', $id);
        //
        $mData = Movie::all();
        $xData = MovieLanguage::all();
        $yData = MovieGenre::all();
        $resultArray = [];
        $i = 0;
        foreach ($mData as $mItem) {
            foreach ($xData as $xItem) {
                foreach ($InterestLanguagedata as $InterestLanguage) {
                    if ($InterestLanguage->language_id == $xItem->language_id) {
                        if ($mItem->id == $xItem->movie_id) {
                            foreach ($yData as $yItem) {
                                foreach ($InterestGenredata as $InterestGenre) {
                                    if ($InterestGenre->genre_id == $yItem->genre_id) {
                                        if ($mItem->id == $yItem->movie_id) {
                                            $resultArray[] = [
                                                0 => $mItem->id,
                                                1 => $xItem->language_id,
                                                2 => $mItem->id,
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        dd($resultArray);
        return $resultArray;
    }
    public function data2DArray()
    {
        $user = Auth::user();
        $data = Interest::all()->where('user_id', '=', $user->id)->first();
        $id = $data->id;
        //IF any interest Added
        $data = Interest::find($id);
        $InterestGenredata = InterestGenre::all()->where('interest_id', '=', $id);
        $InterestLanguagedata = InterestLanguage::all()->where('interest_id', '=', $id);
        //
        $mData = Movie::all();
        $xData = MovieLanguage::all();
        $yData = MovieGenre::all();
        $resultArray = [];
        $i = 0;
        foreach ($mData as $mItem) {
            foreach ($xData as $xItem) {
                foreach ($InterestLanguagedata as $InterestLanguage) {
                    if ($InterestLanguage->language_id == $xItem->language_id) {
                        if ($mItem->id == $xItem->movie_id) {
                            $resultArray[] = [
                                0 => $mItem->id,
                                1 => $xItem->language_id,
                                2 => $mItem->id,
                            ];
                        }
                    }
                }
            }
        }
        $resultArray1 = [];
        foreach ($mData as $mItem) {
            foreach ($yData as $yItem) {
                foreach ($InterestGenredata as $InterestGenre) {
                    if ($InterestGenre->genre_id == $yItem->genre_id) {
                        if ($mItem->id == $yItem->movie_id) {
                            foreach ($xData as $xItem) {
                                foreach ($InterestLanguagedata as $InterestLanguage) {
                                    if ($InterestLanguage->language_id == $xItem->language_id) {
                                        if ($mItem->id == $xItem->movie_id) {
                                            $resultArray[] = [
                                                0 => $mItem->id,
                                                1 => $xItem->language_id,
                                                2 => $yItem->genre_id,
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $resultArray;
    }
    public function index()
    {
        //
        // cluster
        $data = $this->data2DArrayAll();
        // Number of clusters
        $numberOfClusters = 10;

        // Kmean for Language
        $result = $this->KmeansControl($numberOfClusters, $data);
        //
        $data = [];
        foreach ($result[0] as $r) {
            $data[] = Movie::find($r);
        }
        shuffle($data);
        return view('pages.recom3', ['data' => $data, 'time' => $result[1]]);
    }
    public function KmeansControl($numberOfClusters, $data)
    {
        // Function to check if data exists in the array
        function isDataExist($dataArray, $newData)
        {
            return in_array($newData, $dataArray);
        }
        //$flag
        $flag = 0;
        //
        $numbers = 0;
        $numbers2 = 0;
        // Starting clock time in seconds 
        $start_time = microtime(true);
        // Create a KMeans instance
        $kmeans = new KMeans($numberOfClusters);

        // Perform clustering
        $clusters = $kmeans->cluster($data);
        // End clock time in seconds 
        $end_time = microtime(true);
        // Display the clusters of Language
        foreach ($clusters as $index => $cluster) {
            $flag = 0;
            // Example array to store previous data
            $previousDataArray = array();
            // Number of points in the cluster
            $numberOfPoints = count($cluster);
            $numbers = $numbers + $numberOfPoints;
            // echo "Cluster " . ($index + 1) . ": ($numberOfPoints) <br>";

            foreach ($cluster as $point) {
                // echo "[" . implode(", ", $point) . "]\n";
                foreach ($point as $key => $pointData) {
                    if ($key == 0) {
                        $Movie = Movie::find($pointData);
                        if ($pointData == '-1') {
                            $mDAta = 'User and Combination Number ' . $numbers2;
                            $flag = 1;
                        } else {
                            $mDAta = $Movie->title;
                        }
                    } elseif ($key == 1) {
                        $Country = Country::find($pointData);
                        if ($Country == null) {
                            $uDAta = 'Missing';
                        } else {
                            $uDAta = $Country->title;
                        }
                    } elseif ($key == 2) {
                        $ProductionCompany = ProductionCompany::find($pointData);
                        if ($ProductionCompany == null) {
                            $vDAta = 'Missing';
                        } else {
                            $vDAta = $ProductionCompany->title;
                        }
                    } elseif ($key == 3) {
                        $Director = Director::find($pointData);
                        if ($Director == null) {
                            $wDAta = 'Missing';
                        } else {
                            $wDAta = $Director->name;
                        }
                    } elseif ($key == 4) {
                        $Language = Language::find($pointData);
                        if ($Language == null) {
                            $xDAta = 'Missing';
                        } else {
                            $xDAta = $Language->title;
                        }
                    } elseif ($key == 5) {
                        $Genre = Genre::find($pointData);
                        if ($Genre == null) {
                            $yDAta = 'Missing';
                        } else {
                            $yDAta = $Genre->title;
                        }
                    } elseif ($key == 6) {
                        $Cast = Cast::find($pointData);
                        if ($Cast == null) {
                            $zDAta = 'Missing';
                        } else {
                            $zDAta = $Cast->name;
                        }
                    }
                } // Check if data exists in the array
                if (!isDataExist($previousDataArray, $mDAta)) {
                    // // Data doesn't exist, so process and store in the array
                    // echo "[" . $mDAta . " ," . $uDAta . " ," . $vDAta . " ," . $wDAta . " ," . $xDAta . " ," . $yDAta . " ," . $zDAta . "] <br>";
                    // Store data in the array
                    $previousDataArray[] = $mDAta;
                    $numbers2 = $numbers2 + 1;
                }
            }
            if ($flag == 1) {
                $movie = $index;
            }
            // echo " <br>";
        }
        // echo " <br> Total Data Combination - " . $numbers . ' and Total Movies ' . $numbers2 . ' <br>   ';
        // Calculate script execution time 
        $execution_time = ($end_time - $start_time);
        $Movie = $clusters[$movie];
        //
        $previousDataArray2 = array();
        foreach ($Movie as $key => $data) {
            // Check if data exists in the array
            if (!isDataExist($previousDataArray2, $data)) {
                $previousDataArray2[] = $data;
            }
        }
        $previousDataArray3 = array();
        foreach ($previousDataArray2 as $data) {
            if (!isDataExist($previousDataArray3, $data[0]) && $data[0] != -1) {
                $previousDataArray3[] = $data[0];
            }
        }
        //
        $dataReply = [];
        $dataReply[] = $previousDataArray3;
        $dataReply[] = $execution_time;

        return $dataReply;
    }
    public function KmeansControl2($numberOfClusters, $data)
    {
        $numbers = 0;
        // Create a KMeans instance
        $kmeans = new KMeans($numberOfClusters);

        // Perform clustering
        $clusters = $kmeans->cluster($data);

        // Display the clusters of Language
        foreach ($clusters as $index => $cluster) {
            // Number of points in the cluster
            $numberOfPoints = count($cluster);
            $numbers = $numbers + $numberOfPoints;
            echo "Cluster " . ($index + 1) . ": ($numberOfPoints) <br>";

            foreach ($cluster as $point) {
                // echo "[" . implode(", ", $point) . "]\n";
                foreach ($point as $key => $pointData) {
                    if ($key == 0) {
                        $Movie = Movie::find($pointData);
                        $mDAta = $Movie->title;
                    } elseif ($key == 1) {
                        $Language = Language::find($pointData);
                        if ($Language == null) {
                            $xDAta = 'Missing';
                        } else {
                            $xDAta = $Language->title;
                        }
                    } elseif ($key == 2) {
                        $Genre = Genre::find($pointData);
                        if ($Genre == null) {
                            $yDAta = 'Missing';
                        } else {
                            $yDAta = $Genre->title;
                        }
                    }
                }
                echo "[" . $mDAta . " ," . $xDAta . " ," . $yDAta . "] <br>";
            }

            echo " <br>";
        }
        echo " <br> Total Data Combination - " . $numbers . ' <br>   ';
    }
    public function indexMain()
    {
        //

        // Starting clock time in seconds 
        $start_time = microtime(true);
        //
        // cluster
        $data = $this->data2DArray();

        // Number of clusters
        $numberOfClusters = 5;

        // Kmean for Language
        $result = $this->KmeansControlMain($numberOfClusters, $data);
        // End clock time in seconds 
        $end_time = microtime(true);
        // Calculate script execution time 
        $execution_time = ($end_time - $start_time);
        $data = [];
        foreach ($result as $r) {
            $data[] = Movie::find($r[0]);
        }
        shuffle($data);
        return view('pages.recom3', ['data' => $data, 'time' => $execution_time]);
    }
    public function KmeansControlMain($numberOfClusters, $data)
    {
        $numbers = 0;
        // Create a KMeans instance
        $kmeans = new KMeans($numberOfClusters);

        // Perform clustering
        $clusters = $kmeans->cluster($data);

        // Display the clusters of Language
        foreach ($clusters as $index => $cluster) {
            // Number of points in the cluster
            $numberOfPoints = count($cluster);
            $numbers = $numbers + $numberOfPoints;
            // echo "Cluster " . ($index + 1) . ": ($numberOfPoints) <br>";

            if ($index == 0) {
                return $cluster;
            }
        }
    }
}
