<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Device;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ScrapeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:devices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This function scrapes mobile phone manufacturers and their devices from gsmarena.com';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $lastIndex = (int)file_get_contents('last_index.txt');
        $lastHash = (string)file_get_contents('last_hash.txt');

        $sitemapSource = Http::get('https://www.gsmarena.com/sitemap-phones.xml');

        if ($lastHash !== md5($sitemapSource->body())) {
            $lastIndex = 0;
            $lastHash = md5($sitemapSource->body());
            file_put_contents('last_hash.txt', $lastHash);
        }

        $xml = simplexml_load_string($sitemapSource->body());

        $progressbar = $this->output->createProgressBar(count($xml->url));
        $progressbar->start();

        $index = 0;

        foreach ($xml->url as $url) {

            if ($index < $lastIndex) {
                $index++;
                $progressbar->advance();
                continue;
            }

            if (!strpos($url->loc, 'related.php') && !strpos($url->loc, '-pictures-')) {

                $device = Device::where('url_hash', md5($url->loc))
                    ->get();

                if (!$device->count()) {

                    $httpSource = Http::get($url->loc);

                    $parser = str_get_html($httpSource->body());

                    $name = $parser->find('[data-spec="modelname"]')[0]->plaintext ?? null;

                    if ($name) {

                        $brandName = (explode(' ', $name))[0] ?? null;

                        if ($brandName) {

                            $brand = Brand::firstOrCreate([
                                'name' => $brandName
                            ]);

                            $specificationsDom = $parser->find('#specs-list table tr');

                            $specifications = [];
                            $lastGroup = null;

                            foreach ($specificationsDom as $row) {
                                $rowGroup = $row->find('th')[0]->plaintext ?? null;
                                if (!empty($rowGroup)){
                                    $lastGroup = $rowGroup;
                                }
                                $ttl = $row->find('.ttl')[0]->plaintext ?? null;
                                $info = $row->find('.nfo')[0]->plaintext ?? null;
                                if ($ttl) {
                                    $specifications[$lastGroup][$ttl] = $info;
                                }
                            }

                            $device = Device::firstOrCreate([
                                'url_hash' => md5($url->loc)
                            ], [
                                'url_hash' => md5($url->loc),
                                'brand_id' => $brand->id,
                                'name' => $name,
                                'picture' => $parser->find('.specs-photo-main img')[0]->src ?? null,

                                'released_at' => $parser->find('[data-spec="released-hl"]')[0]->plaintext ?? null,
                                'body' => $parser->find('[data-spec="body-hl"]')[0]->plaintext ?? null,
                                'os' => $parser->find('[data-spec="os-hl"]')[0]->plaintext ?? null,
                                'storage' => $parser->find('[data-spec="storage-hl"]')[0]->plaintext ?? null,

                                'display_size' => $parser->find('[data-spec="displaysize-hl"]')[0]->plaintext ?? null,
                                'display_resolution' => $parser->find('[data-spec="displayres-hl"]')[0]->plaintext ?? null,

                                'camera_pixels' => $parser->find('.accent.accent-camera')[0]->plaintext ?? null,
                                'video_pixels' => $parser->find('[data-spec="videopixels-hl"]')[0]->plaintext ?? null,

                                'ram' => $parser->find('.accent.accent-expansion')[0]->plaintext ?? null,
                                'chipset' => $parser->find('[data-spec="chipset-hl"]')[0]->plaintext ?? null,

                                'battery_size' => $parser->find('.accent.accent-battery')[0]->plaintext ?? null,
                                'battery_type' => $parser->find('[data-spec="battype-hl"]')[0]->plaintext ?? null,

                                'specifications' => $specifications,
                            ]);

                            $device->save();

                        }

                    }

                    sleep(2);

                }

            }

            file_put_contents('last_index.txt', $index);

            $progressbar->advance();

            $index++;

        }

        $progressbar->finish();

        return 0;

    }
}
