<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnnouncementController extends Controller
{

    public function announce(Request $request)
    {

        if (!$request->message) {
            return response()->json([
                'error' => 'Announcement Message is required',
            ], 400);
        }

        $target = $request->target;
        if (!$target) {
            $target = 'everyone';
        }

        $announcement = Announcement::create([
            'message' => $request->message,
            'target' => $target,
            'user_id' => $request->user()->id
        ]);

        return response()->json([
            'success' => 'Announcement has been made successfully.',
            'data' => $this->announcer_index($request)
        ]);
    }


    public function announcemnt_stream()
    {

        return new StreamedResponse(function () {
            while (true) {
                echo json_encode(['message' => 'working']) . PHP_EOL;
                ob_flush();
                flush();
                sleep(2);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' =>  'no-cache',
            'Connection' => 'keep-alive'
        ]);



        // return response()->stream(function ($sse) {

        // //     while(true) {
        // //         $sse->write('Hello'.PHP_EOL);
        // //     }

        // //     // while (true) {
        // //     //     $announcemnts = Announcement::where('user_id', '=', $request->user()->id)
        // //     //     ->orWhere('target', '=', $request->user()->role)
        // //     //     ->get();

        // //     //     foreach ($announcemnts as $notification) {
        // //     //         $sse->write(json_encode($notification->toArray()) . PHP_EOL);
        // //     //     }




        // //     //     // foreach ($announcemnts as $announcement) {
        // //     //     //     $notifications = $announcement->notifications()->latest()->limit(10)->get();
        // //     //     //     foreach ($notifications as $notification) {
        // //     //     //         $sse->write(json_encode($notification->toArray()) . PHP_EOL);
        // //     //     //     }
        // //     //     // }

        // //     //     sleep(2); // Adjust sleep time based on desired notification frequency
        // //     // }
        // },200, [
        //     'Content-Type' => 'text/event-stream',
        //     'Cache-Control' =>  'no-cache',
        //     'Connection' => 'keep-alive'
        // ]);
    }

    public function announcer_index(Request $request)
    {

        $announcements = \App\Models\Announcement::where('user_id', '=', $request->user()->id)
            ->orWhere('target', '=', $request->user()->role)
            ->with('announcer')
            ->paginate(10);

        $announcements = $announcements->map(function ($ann) {
            $ann->posted_at = timeago($ann->created_at);
            return $ann;
        });

        return $announcements;
    }

    /**
     * Display form to insert announcement
     */

    public function insert()
    {
        return view('pages.announcement');
    }
}
