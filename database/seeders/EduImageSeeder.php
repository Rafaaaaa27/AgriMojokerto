<?php

namespace Database\Seeders;

use App\Models\EducationalInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class EduImageSeeder extends Seeder
{
    private array $categoryColors = [
        'panduan'    => ['from' => [22, 163, 74],  'to' => [5, 122, 85]],
        'hama'       => ['from' => [220, 38, 38],  'to' => [185, 28, 28]],
        'teknologi'  => ['from' => [37, 99, 235],  'to' => [29, 78, 216]],
        'budidaya'   => ['from' => [13, 148, 136], 'to' => [15, 118, 110]],
        'pengumuman' => ['from' => [217, 119, 6],  'to' => [180, 83, 9]],
    ];

    public function run(): void
    {
        $disk = Storage::disk('public');
        $disk->makeDirectory('educational');

        $infos = EducationalInfo::all();

        foreach ($infos as $info) {
            $filename = 'educational/edu_' . $info->id . '_' . time() . '.png';
            $path = $disk->path($filename);

            $colors = $this->categoryColors[$info->category] ?? $this->categoryColors['panduan'];
            $this->generateImage($path, $line = $info->title, $label = $info->category, $colors);

            $info->update(['image_path' => $filename]);
            $this->command->info("Image created for [{$info->id}] {$info->title}");
        }
    }

    private function generateImage(string $path, string $title, string $category, array $colors): void
    {
        $w = 800;
        $h = 500;
        $img = imagecreatetruecolor($w, $h);

        [$r1, $g1, $b1] = $colors['from'];
        [$r2, $g2, $b2] = $colors['to'];

        // Vertical gradient
        for ($y = 0; $y < $h; $y++) {
            $t = $y / $h;
            $c = imagecolorallocate($img,
                (int)($r1 + ($r2 - $r1) * $t),
                (int)($g1 + ($g2 - $g1) * $t),
                (int)($b1 + ($b2 - $b1) * $t)
            );
            imageline($img, 0, $y, $w, $y, $c);
        }

        // Decorative circles
        $c1 = imagecolorallocatealpha($img, 255, 255, 255, 70);
        $c2 = imagecolorallocatealpha($img, 255, 255, 255, 40);
        imagefilledellipse($img, 680, 100, 320, 320, $c1);
        imagefilledellipse($img, 120, 460, 220, 220, $c2);
        imagefilledellipse($img, 400, -60, 260, 260, $c1);

        // Semi-transparent overlay at bottom for text
        $overlay = imagecolorallocatealpha($img, 0, 0, 0, 50);
        imagefilledrectangle($img, 0, $h - 140, $w, $h, $overlay);

        $font = $this->findFont();
        $white = imagecolorallocate($img, 255, 255, 255);

        if ($font) {
            // Category badge
            $catLabel = strtoupper($category);
            $catSize = 13;
            $bbox = imagettfbbox($catSize, 0, $font, $catLabel);
            $cw = $bbox[2] - $bbox[0];
            $cx = (int)(($w - $cw) / 2);
            $cy = $h - 110;
            $bg = imagecolorallocatealpha($img, 255, 255, 255, 90);
            imagefilledrectangle($img, $cx - 12, $cy - 16, $cx + $cw + 12, $cy + 4, $bg);
            imagettftext($img, $catSize, 0, $cx, $cy, $white, $font, $catLabel);

            // Title (max 2 lines)
            $titleSize = 26;
            $words = explode(' ', $title);
            $lines = [];
            $cur = '';
            foreach ($words as $word) {
                $test = $cur === '' ? $word : $cur . ' ' . $word;
                $bbox = imagettfbbox($titleSize, 0, $font, $test);
                if ($bbox[2] - $bbox[0] > $w - 80 && $cur !== '') {
                    $lines[] = $cur;
                    $cur = $word;
                } else {
                    $cur = $test;
                }
            }
            if ($cur !== '') $lines[] = $cur;

            $lh = 38;
            $sy = $h - 70 - (count($lines) - 1) * ($lh / 2);
            foreach ($lines as $i => $line) {
                $bbox = imagettfbbox($titleSize, 0, $font, $line);
                $tx = (int)(($w - ($bbox[2] - $bbox[0])) / 2);
                imagettftext($img, $titleSize, 0, $tx, (int)($sy + $i * $lh), $white, $font, $line);
            }
        } else {
            $text = substr($title, 0, 48);
            $x = (int)(($w - strlen($text) * 8) / 2);
            imagestring($img, 5, $x, $h / 2, $text, $white);
        }

        imagepng($img, $path);
        imagedestroy($img);
    }

    private function findFont(): ?string
    {
        $candidates = [
            '/System/Library/Fonts/Helvetica.ttc',
            '/System/Library/Fonts/Supplemental/Arial.ttf',
            '/System/Library/Fonts/Supplemental/Helvetica.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf',
        ];
        foreach ($candidates as $f) {
            if (file_exists($f)) return $f;
        }
        return null;
    }
}
