<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Validator;

use App\Models\Staff;
use App\Models\Student;
use App\Models\AcademicSession;

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


    public function unseen_announcements(Request $request)
    {
        if ($request->user()->is_admin()) {
            $students = Student::count();
            $staffs = Staff::count();
            $advisors = Staff::where('is_class_advisor', true)->count();
            $hod = Staff::where('is_hod', true)->count();
            $academicSession = AcademicSession::count();

            $notifications = [];

            if (!$academicSession) {
                $notifications[] = ['message' => 'No academic session has been created yet'];
            }
            if (!$hod) {
                $notifications[] = ['message' => 'You have not create an account of HOD yet'];
            }
             if (!$staffs) {
                $notifications[] = ['message' => 'No staff account has been created yet'];
            } else if (!$advisors) {
                $notifications[] = ['message' => 'No staff has been made class advisor of any class'];
            }
            if (!$students) {
                $notifications[] = ['message' => 'No Student account has been created yet'];
            }

            return $notifications;
        }
        $announcements = Announcement::where('id', '>', $request->user()->last_seen_announcement)
            ->where(function ($query) use ($request) {
                $user = $request->user();
                $role = $user->role;
                $query->where(function($query) use ($user) {
                        $query->where('target', '=', $user->role)
                            ->where('user_id', '=', $user->id);
                    })
                    ->orWhere('target', '=', "{$role}s");
            })
            ->with('announcer')
            ->paginate(10);

        return $announcements->map(function ($ann) {
            $ann->posted_at = timeago($ann->created_at);
            return $ann;
        });
    }
    /**
     * Mark announcement as seen
     */

     public function mark_as_seen(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ], [
            'id.required' => 'Announcement ID not specified',
            'id.numeric' => 'Invalid announcement ID',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
        
        $user = $request->user();

        $user->last_seen_announcement = $request->id;
        $user->save();

     }

    /**
     * Display form to insert announcement
     */

    public function insert()
    {
        return view('pages.announcement');
    }
}
