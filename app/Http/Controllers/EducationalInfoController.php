<?php

namespace App\Http\Controllers;

use App\Models\EducationalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EducationalInfoController extends Controller
{
    public function index(Request $request)
    {
        $query = EducationalInfo::with('user')->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $infos = $query->paginate(9);
        return view('educational.index', compact('infos'));
    }

    public function show($id)
    {
        $info = EducationalInfo::with('user')->findOrFail($id);
        $info->increment('views');

        $readingTime = ceil(str_word_count(strip_tags($info->content)) / 200);

        return view('educational.show', compact('info', 'readingTime'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt|max:10240',
        ]);

        $data = $request->only('title', 'category', 'content');
        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('educational', 'public');
        }

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('educational/files', 'public');
            $data['file_name'] = $request->file('file')->getClientOriginalName();
        }

        EducationalInfo::create($data);

        return redirect()->back()->with('success', 'Informasi penyuluhan berhasil dipublikasikan.');
    }

    public function update(Request $request, EducationalInfo $educationalInfo)
    {
        if ($educationalInfo->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt|max:10240',
        ]);

        $data = $request->only('title', 'category', 'content');

        if ($request->hasFile('image')) {
            if ($educationalInfo->image_path) {
                Storage::disk('public')->delete($educationalInfo->image_path);
            }
            $data['image_path'] = $request->file('image')->store('educational', 'public');
        }

        if ($request->hasFile('file')) {
            if ($educationalInfo->file_path) {
                Storage::disk('public')->delete($educationalInfo->file_path);
            }
            $data['file_path'] = $request->file('file')->store('educational/files', 'public');
            $data['file_name'] = $request->file('file')->getClientOriginalName();
        }

        $educationalInfo->update($data);

        return redirect()->back()->with('success', 'Informasi berhasil diperbarui.');
    }

    public function destroy(EducationalInfo $educationalInfo)
    {
        if ($educationalInfo->user_id !== auth()->id()) {
            abort(403);
        }

        if ($educationalInfo->image_path) {
            Storage::disk('public')->delete($educationalInfo->image_path);
        }
        if ($educationalInfo->file_path) {
            Storage::disk('public')->delete($educationalInfo->file_path);
        }

        $educationalInfo->delete();
        return redirect()->back()->with('success', 'Informasi berhasil dihapus.');
    }

    public function download($id)
    {
        $info = EducationalInfo::findOrFail($id);

        if (!$info->file_path) {
            return redirect()->back()->with('error', 'File tidak tersedia.');
        }

        return Storage::disk('public')->download($info->file_path, $info->file_name);
    }
}
