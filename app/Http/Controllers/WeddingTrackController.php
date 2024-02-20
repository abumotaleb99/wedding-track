<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuestInvitation;
use App\Models\CheckIn;
use Validator;
use Exception;

class WeddingTrackController extends Controller
{
    public function showCheckInList() {
        return view('pages.check-in');
    }
    public function showGuestInvitationsPage() {
        return view('pages.guest-invitations', ['guestInvitations' => GuestInvitation::get()]);
    }

    public function addGuestInvitation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'guest_id' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'gender' => 'required|string|in:Male,Female,Other',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation errors.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $unique_identifier = mt_rand(100000, 999999);            

            while ($this->checkUniqueIdentifierExists($unique_identifier)) {
                $unique_identifier = mt_rand(100000, 999999);
            }

            $guestInvitation = new GuestInvitation();
            $guestInvitation->unique_identifier = $unique_identifier;
            $guestInvitation->guest_id = $request->input('guest_id');
            $guestInvitation->name = $request->input('name');
            $guestInvitation->company_name = $request->input('company_name');
            $guestInvitation->gender = $request->input('gender');
            $guestInvitation->save();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Guest added successfully',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Guest add failed.',
            ], 422);
        }
    }

    public function checkUniqueIdentifierExists($unique_identifier) {
        return GuestInvitation::where('unique_identifier', $unique_identifier)->exists();
    }

    public function deleteGuest($id)
    {
        $guest = GuestInvitation::find($id);

        if (empty($guest)) {
            abort(404);  
        }

        CheckIn::where('guest_invitation_id', $guest->id)->delete();
        $guest->delete();
        
        return redirect('/guest-invitations')->with("success", "Guest successfully deleted.");
    }

    public function getCheckInList()
    {
        $checkIns = CheckIn::with('guestInvitation')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'CheckIn data retrieved successfully',
            'data' => $checkIns
        ], 200);
    }

    public function getInvitedGuestById($barcodeId) {
        try {
            $guest = GuestInvitation::where('unique_identifier', $barcodeId)->first();
            if ($guest) {
                return response()->json([
                    'success' => true,
                    'message' => 'Guest information retrieved successfully.',
                    'data' => $guest

                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Guest not found.',
                    'message' => 'Invalid Barcode. Please try again!'
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Internal server error.',
            ], 500);
        }
    }

    public function checkIn(Request $request)
    {
        try {
            $guestInvitationId = $request->input('guest_invitation_id');

            $existingCheckIn = CheckIn::where('guest_invitation_id', $guestInvitationId)->first();
            if ($existingCheckIn) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Already checked In.',
                ], 400);
            }

            $checkIn = new CheckIn();
            $checkIn->guest_invitation_id = $guestInvitationId;
            $checkIn->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Check In successful.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error.',
            ], 500);
        }
    }

    public function invitations() {
        $data = GuestInvitation::get();
        return response()->json([
            'data' => $data,
        ]);
    }

    
    
}
