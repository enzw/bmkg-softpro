<?php

// CHATBOT/DIALOGFLOW DISABLED
// This file has been disabled as per requirements

/*
namespace App\Http\Controllers;

use App\Models\Chatlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DialogflowWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Get the question from the user
        $userMessage = $request->input('queryResult.queryText');

        // Generate a response (or fetch one from Dialogflow)
        $botMessage = $request->input('queryResult.fulfillmentText');
        $intent = $request->input('queryResult.intent.displayName');
        // $session = json_encode($request->input('queryResult.outputContexts'));
        $getData = $request->input('queryResult.outputContexts');
        $data = $getData[0]['name'];
        $collection = collect(explode('/', $data));
        $session = $collection[4];


        // Save to database
        Chatlog::create([
            'question' => $userMessage,
            'answer' => $botMessage,
            'intent' => $intent,
            'session_id' => $session,
        ]);

        // Respond to Dialogflow
        return response()->json([
            'fulfillmentText' => $botMessage,
        ]);
    }

    public function index()
    {
        $results = DB::select('select question, count(question) as count 
        from chatlogs 
        where question != ?
        group by question 
        order by count DESC
        limit 10', array('WELCOME'));

        $layanan = DB::select('select  
        case when intent  = ?  then  ?
 	    when  intent  = ? then ?
 	    when intent = ? then ? end  as layanan,
 					count(intent) as jumlah
        from chatlogs c 
        where intent in(?, ?,?)
        group by intent order by jumlah DESC', array(
            '02 Alat',
            'Sewa Alat',
            'Layanan jasa',
            'Pelayanan Jasa',
            '05 Kunjungan',
            'Permohonan Kunjungan',
            '02 Alat',
            'Layanan jasa',
            '05 Kunjungan'
        ));

        $data = [
            'title' => 'Sewa Alat',
            'permohonan' => $results,
            'layanan' => $layanan,
        ];

        return view('pages.admin.history-megabot.index', $data);
    }
}
*/
