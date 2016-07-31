<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Console\Command;
use tidy;

class OpenGov extends Command
{
    /**
     * The basic url string
     *
     * @var string
     */
    const URI = 'http://tslc.stats.com';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'open:gov';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Open government';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client([
            'base_uri' => self::URI,
            'timeout' => 300.0
        ]);

        try {
            $request = $client->request('GET', '/mlb/scoreboard.asp', [
                'query' => [
                    'day' => date('Ymd'),
                    'ref' => 'off'
                ]
            ]);

            $config = [
                'uppercase-attributes' => true,
                'wrap' => 800
            ];
            $tidy = new tidy();
            $tidy->parseString($request->getBody()->getContents(), $config, 'utf8');
            $tidy->cleanRepair();
            $cleanedHtml  = tidy_get_output($tidy);

            $crawler = new Crawler($cleanedHtml);

            // mckey website
            $schdules = $crawler->filter('div.shsScoreboardCol table.shsTable.shsLinescore')->each(function (Crawler $node) {
                yield [
                    'time' => $node->filter('tr.shsTableTtlRow td')->eq(0)->text(),
                    'awayTeam' => $node->filter('tr.shsRow0Row td')->eq(0)->text(),
                    'homeTeam' => $node->filter('tr.shsRow0Row td')->eq(1)->text(),
                    'shsAwayStarter' => $node->filter('tr td.shsAwayStarter')->eq(0)->text(),
                    'shsHomeStarter' => $node->filter('tr td.shsHomeStarter')->eq(0)->text(),
                ];
            });

            // show info
            foreach ($schdules as $info) {
                var_dump(iterator_to_array($info));
            }

        } catch (RequestException $e) {
            $this->error($e->getMessage());
        }


    }
}
