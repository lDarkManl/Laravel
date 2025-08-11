<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Requests\DeleteMessageRequest;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        return Message::getPaginatedViewData(
            request()->input('sort', 'id'),
            request()->input('order', 'asc'),
            request()->input('page', 1),
            'messages'
        );
    }

    public function store(StoreMessageRequest $request): RedirectResponse
    {
        Message::createMessage($request->validated());
        return redirect()->route('web.messages.list');
    }

    public function update(UpdateMessageRequest $request): RedirectResponse
    {
        Message::updateMessage($request->validated());
        return redirect()->route('web.messages.list');
    }

    public function destroy(DeleteMessageRequest $request): RedirectResponse
    {
        Message::deleteMessage($request->validated());
        return redirect()->route('web.messages.list');
    }
}
