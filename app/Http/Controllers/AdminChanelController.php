<?php

namespace App\Http\Controllers;

use App\Models\Chanel;
use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Http\Request;
use Alaouy\Youtube\Facades\Youtube;
use DateTime;
use App\Console\Commands\YouTubeVideo;
use App\Console\Commands\YouTubeVideoForShow;
use App\Console\Commands\JSONdbOperations;

class AdminChanelController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            $chanels = Chanel::all();
            //$chanels = Chanel::with('playlists.videos')->get();
        } catch (\Exception $e) {
            $chanels = JSONdbOperations::allChanels();
        }
        return view('admin_chanel.index', [
            'chanels' => $chanels,
        ]);
    }

    public function transaction(Request $request) {
        $answer = $request->input();
        try {
            YouTubeVideo::initialChanels($answer, false);
        } catch (\Exception $e) {
            YouTubeVideo::initialChanels($answer, true);
        }
        return redirect('/');
    }

    public function search(Request $request) {
        $playlists = null;
        $videos = null;
        $chanel_query = $request->input();
        $channelResult = Youtube::getChannelById($chanel_query);
        try {
            $chanels = Chanel::all();
        } catch (\Exception $e) {
            $chanels = JSONdbOperations::allChanels();
        }
        if ($channelResult) {
            $channelResult = $channelResult[0];
            YouTubeVideoForShow::initialChanels($chanel_query);
            $videos = YouTubeVideoForShow::$videosG;
            $playlists = YouTubeVideoForShow::$playlistsG;
        }
        return view('admin_chanel.search', [
            'chanels' => $chanels,
            'channelResult' => $channelResult,
            'playlists' => $playlists,
            'videos' => $videos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chanel  $chanel
     * @return \Illuminate\Http\Response
     */
    public function show($chanelId) {
        try {
            $chanels = Chanel::all();
            $chanel = Chanel::where('id', $chanelId)->first();
        } catch (\Exception $e) {
            $chanels = JSONdbOperations::allChanels();
            $chanel = JSONdbOperations::convertToChanel(JSONdbOperations::readFromJson('Chanel'))
                            ->where('id', $chanelId)->first();
        }
        return view('admin_chanel.show', [
            'chanels' => $chanels,
            'chanel' => $chanel,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chanel  $chanel
     * @return \Illuminate\Http\Response
     */
    public function edit(Chanel $chanel) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chanel  $chanel
     * @return \Illuminate\Http\Response
     */
    public function updatechanel(Request $request) {
        $chanel = Chanel::where('id', $request->id)->first();
        $chanel->name = $request->name;
        $chanel->description = $request->description;
        $chanel->save();
        return response()->json($chanel);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chanel  $chanel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $chanel = Chanel::where('id', $request->id)->first();
        $chanel->delete();
        return response()->json();
    }

}
