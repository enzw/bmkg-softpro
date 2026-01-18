<?php

namespace App\Http\Controllers;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class BeritaController extends Controller
{
    public function scrapper()
    {
        $browser = new HttpBrowser(HttpClient::create([
            'timeout' => 15,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Laravel Scraper)'
            ]
        ]));

        $crawler = $browser->request(
            'GET',
            'https://yogyakarta.bmkg.go.id/?post_type=berita'
        );

        $data = [];

        // Ambil semua CSS Elementor
        $styles = $crawler->filter('style')->each(function ($s) {
            return $s->text();
        });
        $allCss = implode("\n", $styles);

        $crawler->filter('.e-loop-item')->each(function ($node) use (&$data, $allCss) {

            if (!$node->filter('h2 a')->count()) return;

            $titleNode = $node->filter('h2 a')->first();
            $dateNode  = $node->filter('.elementor-post-info__item--type-date');

            preg_match('/e-loop-item-\d+/', $node->attr('class'), $m);
            $loopClass = $m[0] ?? null;

            $image = null;

            if ($loopClass) {
                preg_match('/' . $loopClass . '.*?url\("([^"]+)"\)/s', $allCss, $imgMatch);
                $image = $imgMatch[1] ?? null;
            }

            $data[] = [
                'title' => trim($titleNode->text()),
                'url'   => $titleNode->attr('href'),
                'date'  => $dateNode->count() ? trim($dateNode->text()) : null,
                'image' => $image
            ];
        });

        return response()->json([
            'source' => 'BMKG Yogyakarta',
            'count'  => count($data),
            'data'   => $data
        ]);
    }
}
