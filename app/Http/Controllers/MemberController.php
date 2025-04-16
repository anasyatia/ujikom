<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
    
        if ($search) {
            $search = strtolower($search);
            $members = Member::whereRaw('LOWER(nama) LIKE ?', ['%' . $search . '%'])
                ->orderByRaw('LOWER(nama) ASC')
                ->paginate(10)
                ->appends($request->only('search'));
        } else {
            $members = Member::latest()->paginate(10);
        }
    
        return view('members.index', compact('members'));
    }


    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'telp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'poin' => 'nullable|integer|min:0',
        ]);

        do {
            $memberCode = 'CDMBR-' . strtoupper(Str::random(6));
        } while (Member::where('member_code', '=', $memberCode)->exists());

        Member::create([
            'member_code' => $memberCode,
            'nama' => $request->nama,
            'telp' => $request->telp,
            'email' => $request->email,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'poin' => $request->poin ?? 0,
        ]);

        return redirect()->route('members.index')->with('success', 'Member created successfully!');
    }

    public function show(Member $member)
    {
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'member_code' => 'required|unique:members,member_code,' . $member->id,
            'nama' => 'required|string|max:255',
            'telp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'poin' => 'nullable|integer|min:0',
        ]);

        $member->update([
            'member_code' => $request->member_code,
            'nama' => $request->nama,
            'telp' => $request->telp,
            'email' => $request->email,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'poin' => $request->poin ?? 0,
        ]);

        return redirect()->route('members.index')->with('message', 'Member updated successfully!');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')->with('message', 'Member deleted.');
    }
    
}
