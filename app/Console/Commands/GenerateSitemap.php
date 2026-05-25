<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PastQuestionPdf;
use App\Models\CourseMaterial;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml file';

    public function handle()
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $urls    = [];

        // ── Static pages ──────────────────────────────
        $urls[] = ['loc' => $baseUrl . '/',                    'priority' => '1.0'];
        $urls[] = ['loc' => $baseUrl . '/noun-past-questions', 'priority' => '0.9'];
        $urls[] = ['loc' => $baseUrl . '/noun-material',       'priority' => '0.9'];

        // ── Past questions — one URL per course code ──
        PastQuestionPdf::whereNotNull('file_path')
            ->select('course_code')
            ->distinct()
            ->orderBy('course_code')
            ->pluck('course_code')
            ->each(function ($code) use (&$urls, $baseUrl) {
                $urls[] = [
                    'loc'        => $baseUrl . '/noun-past-questions/' . strtolower($code),
                    'priority'   => '0.8',
                    'changefreq' => 'weekly',
                ];
            });

        // ── Course materials — one URL per course code ──
        CourseMaterial::whereNotNull('file_path')
            ->select('course_code')
            ->distinct()
            ->orderBy('course_code')
            ->pluck('course_code')
            ->each(function ($code) use (&$urls, $baseUrl) {
                $urls[] = [
                    'loc'        => $baseUrl . '/noun-material/' . strtolower($code),
                    'priority'   => '0.8',
                    'changefreq' => 'weekly',
                ];
            });

        // ── Build XML ─────────────────────────────────
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>'      . $url['loc']      . '</loc>'      . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            if (isset($url['changefreq'])) {
                $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            }
            $xml .= '    <lastmod>' . now()->toAtomString() . '</lastmod>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        file_put_contents(public_path('sitemap.xml'), $xml);

        $this->info('✅ Sitemap generated with ' . count($urls) . ' URLs');
        $this->info('📍 Saved to: ' . public_path('sitemap.xml'));
    }
}