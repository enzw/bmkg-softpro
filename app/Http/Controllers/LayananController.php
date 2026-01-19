<?php

namespace App\Http\Controllers;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class LayananController extends Controller
{
    public function index()
    {
        $browser = new HttpBrowser(HttpClient::create([
            'timeout' => 15,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Laravel Scraper)'
            ]
        ]));

        try {
            $crawler = $browser->request(
                'GET',
                'https://yogyakarta.bmkg.go.id/?post_type=berita'
            );

            $allCss = '';
            $crawler->filter('style')->each(function ($style) use (&$allCss) {
                $allCss .= $style->text();
            });

            $berita = [];

            $crawler->filter('.e-loop-item')->each(function ($node) use (&$berita, $allCss) {

                if (count($berita) >= 4) return;
                if (!$node->filter('h2 a')->count()) return;

                $titleNode = $node->filter('h2 a')->first();
                $dateNode  = $node->filter('.elementor-post-info__item--type-date');

                $classAttr = $node->attr('class');
                preg_match('/e-loop-item-\d+/', $classAttr, $loopMatch);
                $loopClass = $loopMatch[0] ?? null;

                $image = null;
                if ($loopClass) {
                    preg_match('/' . $loopClass . '.*?url\("([^"]+)"\)/s', $allCss, $imgMatch);
                    $image = $imgMatch[1] ?? null;
                }

                $berita[] = [
                    'title' => trim($titleNode->text()),
                    'url'   => $titleNode->attr('href'),
                    'date'  => $dateNode->count() ? trim($dateNode->text()) : null,
                    'image' => $image
                ];
            });
        } catch (\Exception $e) {
            $berita = [];
        }

        return view('pages.landing', [
            'berita' => $berita
        ]);
    }
}
