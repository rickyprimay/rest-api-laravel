<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::where('id_user', Auth::id())->get();
        return response()->json($subscriptions, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'category' => 'required|in:Basic,Standard,Premium',
        ]);

        $user = User::findOrFail($request->id_user);
        if ($user->status == 1) {
            return response()->json(['error' => 'User is already active'], 422);
        }

        $subscription = Subscription::create([
            'id_user' => $request->id_user,
            'category' => $request->category,
            'price' => $this->getSubscriptionPrice($request->category),
            'transaction_date' => now(),
        ]);

        $user->update(['status' => 1]);

        return response()->json($subscription, 201);
    }

    private function getSubscriptionPrice($category)
    {
        $priceMap = [
            'Basic' => 50000,
            'Standard' => 100000,
            'Premium' => 150000,
        ];

        return $priceMap[$category];
    }

    public function show($id)
    {
        $subscription = Subscription::findOrFail($id);
        return response()->json($subscription, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'category' => 'required|in:Basic,Standard,Premium',
        ]);

        $user = User::findOrFail($request->id_user);
        if ($user->status == 1) {
            return response()->json(['error' => 'User is already active'], 422);
        }

        $subscription = Subscription::findOrFail($id);
        $subscription->update([
            'id_user' => $request->id_user,
            'category' => $request->category,
            'price' => $this->getSubscriptionPrice($request->category),
            'transaction_date' => now(),
        ]);

        $user->update(['status' => 1]);

        return response()->json($subscription, 200);
    }

    public function destroy($id)
    {
        Subscription::findOrFail($id)->delete();
        return response()->json(['message' => 'Subscription deleted successfully'], 200);
    }
}
