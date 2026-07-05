<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{
    /**
     * একটি ফাইল নির্দিষ্ট ডিস্ক এবং ডিরেক্টরিতে আপলোড করার মেথড।
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string $disk
     * @return string|null
     */
    public function uploadFile(UploadedFile $file, string $folder = 'uploads', string $disk = 'public'): ?string
    {
        // ক্লিন এবং ইউনিক ফাইল নেম জেনারেশন (e.g. 1717530000_64b2f3a5.jpg)
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // ফাইলটি নির্দিষ্ট ডিস্কে স্টোর করা এবং তার রিলেটিভ পাথ রিটার্ন করা
        return $file->storeAs($folder, $filename, $disk);
    }

    /**
     * এক্সিস্টিং ফাইল সার্ভার থেকে ডিলেট করার মেথড।
     *
     * @param string|null $path
     * @param string $disk
     * @return bool
     */
    public function deleteFile(?string $path, string $disk = 'public'): bool
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }
}