<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Http\Requests\MessageRequest;
use App\Models\Message;
use Illuminate\Http\Request;

class ApiMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new MessageResource(Message::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MessageRequest $request)
    {
        $data = $request->validated();
        $message = Message::create($data);
        $paginate = $message->paginate(15);
        $amountPages = $paginate->lastPage();
        return new MessageResource($message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        return new MessageResource($message);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MessageRequest $request, Message $message)
    {
        $message->update($request->validated());
        return new MessageResource($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $message->delete();
        return response()->json(['status' => 'ok']);
    }
}
