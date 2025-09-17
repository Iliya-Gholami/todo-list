<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UploadProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Upload and update the user's profile image.
     *
     * @param  UploadProfileRequest  $request
     * @return JsonResponse
     */
    public function uploadProfile(UploadProfileRequest $request): JsonResponse
    {
        $request->validated();
        $file = $request->file('image')->store('profiles', 'public');

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'Upload process failed.'
            ], 500);
        }

        $user = $request->user();
        $oldProfile = $user->profile;

        if (!$user->update(['profile' => $file])) {
            Storage::disk('public')->delete($file);

            return response()->json([
                'success' => false,
                'message' => 'Unable to set user profile.'
            ], 500);
        }

        if ($oldProfile && Storage::disk('public')->exists($oldProfile)) {
            Storage::disk('public')->delete($oldProfile);
        }

        return response()->json([
            'success' => true,
            'message' => 'User profile updated successfully.'
        ]);
    }

    /**
     * Update the authenticated user's data.
     *
     * @param  UpdateUserRequest  $request
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $affected = $request->user()->update($request->validated());

        return response()->json([
            'success' => $affected > 0,
            'message' => $affected > 0
                ? 'User updated successfully.'
                : 'No changes were made.'
        ]);
    }

    /**
     * Display the specified user.
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user'    => $request->user()
        ]);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
    }
}
