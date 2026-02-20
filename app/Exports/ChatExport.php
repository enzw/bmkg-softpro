<?php

namespace App\Exports;

// Chatbot/Dialogflow disabled
// use App\Models\Chatlog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ChatExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Chatbot/Dialogflow disabled
        // return Chatlog::select('id', 'question', 'session_id', 'intent', 'created_at')->get();
        return collect([]);
    }

    public function headings(): array
    {
        return ['ID', 'MESSAGE RECEIVED', 'SESSION ID', 'INTENT RESPONSE', 'CREATED DATE'];
    }
}
