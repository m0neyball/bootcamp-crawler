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
    const URI = 'http://amis.afa.gov.tw';

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
            $request = $client->request('POST', '/m_fruit/FruitProdDayTransInfo.aspx', [
                'form_params' => [
                    'ctl00$ScriptManager_Master' => 'ctl00$ScriptManager_Master|ctl00$contentPlaceHolder$btnQuery',
                    'ctl00_contentPlaceHolder_ucFruitMarket' => 'ALL',
                    'ctl00_contentPlaceHolder_ucFruitProduct' => 'ALL',
                    'ctl00$contentPlaceHolder$ucDateScope$rblDateScope' => 'D',
                    'ctl00$contentPlaceHolder$ucSolarLunar$radlSolarLunar' => 'S',
                    'ctl00$contentPlaceHolder$txtSTransDate' => '105/07/26',
                    'ctl00$contentPlaceHolder$txtETransDate' => '105/07/26',
                    'ctl00$contentPlaceHolder$ucFruitMarket$radlMarketRange' => 'A',
                    'ctl00$contentPlaceHolder$ucFruitProduct$radlProductRange' => 'A',
                    '__VIEWSTATE' => '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',
                    '__VIEWSTATEGENERATOR' => '405CE910',
                    '__EVENTVALIDATION' => '/wEdAIMCrAP7BAE6AhuovDPO4Ch3xyJs6JqR+BQKw4t9GPzryMq8BxsWLAP22+gk0AG8u9ZtR3kW2hapG4YRpMZu8SVu10fEsvvF4+3wT0S4cIz4vpAbRBeuELzZwdUO9afuosLOG7nTiBB02oBy6qRVjleDmaT2TjFllFEeYYrCnTuQNeFusTlsU+eZddTJtZfzKGjySAoK4xcJraWVoYpH00vPONys9ytwpOOf8jJPghkJ5rTPyHo4W9d+zEeB/VNhCittKhg+EnQtZ5WQ0AMy6in/b+qIzyx3/RAwQKkL8Uxoxe/HxTG7rnXMQx9k9eENJ7272qLxh1QdPP09xd7Mo5MKDEDEe+3pNpX4Z1tF1zI5G5C9IqSwCf1IQ8cSAlM+nsfZ3Q+Akv/qf6pY/PHo7Ay+Kjb0IX5Ki4UzVlC5mF73A2Nc5Fvd3k2GJ7RQKy459PJXSx9kaKLPsq4xVr/eBP94MTSv+F8uWSwSQ6UDN6DC3bKIcp1das/w7tJBqjZC8/MdxAU9e8uNELmKN4g59Q+TbGZZdRWFCeTYO01t6p05/k7wfMUKsEMHOqhK159T7pvyF/V6odQaDF8DUjVQqgA/XtLDcAQJvOo10jtxEJtQa80qqZndxLpyV7qCgIhHy6nzVLreekESF/itIVL+ZNqpbCxXpd7fW2HnAbt18z4wz/H/pMZr64hM923UfORecqy39NXZYlobno0LEIqaAvDAbDkC44ZCp1lw2a77S/wye0Jl/PDQ/g9Sb1r4q0XdPcGf/r4VF7eksQ7wjdfUbuSEafVUgYZKr8TO1cZdY5LbaBZj/qiZu6wHKV87J53MY3MeH0DSdyezgRzXrLiUuq8P2s3zhwXh9h7sQZjASau2pt1FbyfBrIRrXYM/P0R2zcr28CwqForknNrwH+/6aCm3CDw6V4HTXUz6+Qwr7OCGg/ZFLXQdq1e1EtL6ifQtTS3y2xGInEeJXZSeANsP9AQKnUXmILspJtNBfEMVAjQvScJeKhK11YZ1ogVVe/o9qqOS05jJIaWybColiophEi7gkGbYwpHwjnaH/yqBYcC/d0W9I2waOTpj74a72OpImijeWXnvUlT0Tf4QLcZ/SqdPRAROGLVJw2vsDQS4hpHG/lpCfyxf/UgY6mwtuXnCpA2mvPky/HpyrDj7H2kGuXfR62WAbugXO9zH1d+leY8249WT172wi9n9cDBfNt2yiECcpRfo9mOtARSiEjm5HXzCl5mhtoKoDL6PSAZST0l38JXVZyiU1DeMMCrrB8aTMphCY5Vd375qGcyJvhDVueg/HwNfeHmSlF9D8EvZMxQDc6VeFLIUhPx2LsLcLSijSiq9j1IZibAHI2BCT6K0H2hO5KSdmOLO6ZWPqH2WVQEO+qv4ANDn0iZU66YoIDt+w2yedta6HzvK6vJ6Ol99VMV2SF1W04C6QQ+g69uzD7tH6Ey6z4taPDI1R1wzMKhzYSy6+RIHRymoLWzD9I/0VWTt7D1xoGzloi996CsfV55Vc6QfntQdrsNdZo1XcHjkKbyF8dp4QCZv1/DIC0sPbcyUpFNlooF2EWZe0mpP84ivOn0dgM/5bxlYDFB80psapyWg2vzRJzQJF6nhs62cQ7bfsSn6XBib26ezmiGNphrB3URDzcG4LCbdj9xwb01iK4WAe3ctiFrocRq20Fjcfq/YqP7NhVdx1lzpw89rySt+ub5G7pBBHbz8eoJa6+JxscOfkRn3eBAJfD4PqnCHxqG13b6OlDLkXXj+S4BzRVt2dbOyqeCsXFEqP3bXoFBi9kXNvuOepwREphEDIv2YXxzl8etqDqnUtBCJNNfiKQ8kkeWJqfn1RIHY7cNFp62AXjB3RzcSF4yYtCRkfZupUdUUDezewtr9FK57/eTVbWVqWu/GIWVgRQFVCE2Nj3lLIL/SxA/F/y89MgubyuH7Vlbou713fQxSIYDYJsWhMKxar5Ls35tKEHNSVBDFhPWmhwWfe5Rwl8H7Lvatr5cyJqPmQlF0/5GErDSbt5obMGXEM5PM9lz1VTpjiz3mSj2OZeJZwEl1NKLg982RfZDbYlHPnggD4dGbnscZW8MZy+P3GjTEZRrcyQQrPrFAO2BqW4xnGGTsR2wdUsIoR5paX/3yznxcfOOT6LqP1UYD9oZK1GaXpKOUyFj3+pWm9g+pQX1xAQ6YtoEegXV2XCDDHf/VQQjHSOwmo/HawbdmihFu+xDYgGrBhFcycjgAo2mpPMVlZrRVzHFmUMYZtrSBxLAwtfeB8WKoAVH3d5cKKsYkETOAjtWeMyu/pw2u3uXrRDjhCgnJmoKmJO7kc2c2z0FIpbmSzaZR6mV53zg8576QWDMDMEsJAb4LHRV1Tvmi4aw5rdyqDh6NJJf6EIG9kQAJM9lpnph/zMyCyMOXO5kMx/uNnccReikBjYA8NkQ+nbUfZEoIr7eI8dIl5BI18bh86YXBxqHRn16LlCEhlM3WhQnxr0Qz4znTrh/sMExLLEZEMdY7/+jWXrFo4ubxjzkzI+Rb+9Y10UZ2dxYu/v1nTKFqjepVyTGJFr7/4a/QB89cyC88G2zwHDlZA4PUSGLoBpGCVvabpLCKxtrBszI5YCrQLe8pmUE3cbZsMOj0HBLJ1N6vki8DPYQf/2uj57IyvtfnaoIHABn4aJMNtTH97MiRcpEdlDHOdW41BGnkW8K26MogdYssoI4/TL4psz/f20+FjNZaewk8rYAZfqNZAeaFSm7+sc37nVETKOPK2YotXyBv6AYlgiPiRgBLQQrOcutQQTRiuG1oCsTgyxRNDKm4uH05nF+YTlCU40uih8oF388SJnI7ki+Q4zYB1KNtvnIVep5n5m9nBnCD9hteEpGBmSOeII2i5iTG1y9h9a4HxjJLwn0tUGI/exBdmOmRqJATNdWNjJMTMmsvHvuZLVI3EmEOZ+y0+pF2QAzT63gXL+k6TgWi/PDNEX/bIJvuGGntGaYuFQujnCO0yQxXDgONAMR0HeHA8xJNMSFwUeOhgfqnaH8TYluV+LmyVD9iY9mB2TE45dpx3uMgxDly2HG7+VFz7GzABwokXBnmKWZASOxCbxs1WCi4IukHUeXC4SZ01zVD0Lazr3d9r0e4COED/dGvPdkm4VUMOKCSqK1JtTF2968i4Y9YcORzd+Fdmgfpry723arzCn6uCryNZOsKpzsFwulaIVpygTHWadV1clMxOPRlfqjO7Hw3cVBKLmK8dJKlw97hZuOMURqfTHgUrrOsY+Y4NQT6ZA4LXeSU1KQnjzqJTBrcwp59RkufpjbNseNZjafd17hJhVEt+5RQuVa6wnsB3UH30t1Ocge0iD8rHrwh1kpkORAPXeIjzdneSmljGOIMNfiDGRAeDsGsbax7ht5nyvwoCivMNjH4fq+nGrW9G7WJfdup9r1JM2vZCIjhak9+gJhmBZllfYXczwoBfQn1Lj6tjId1FklM+EqVPNupJeuepbAoAYoCDEIStSdt23mquMgEBiXKPFCF7carGY087Kos7vS7UaKwpsM0qZjVndkmGOfTw3IK1tz9HpFiOKrUzUQb3Ie2OC6Jo159/XKGBMF9yfiX2YWPFmbFotdLQZ2UrJ/ivaq4SY1xCUK7VQplvHMs3ADVGrrp5QlNTJCcuvhshCbabUhpZ6do2CB4ciwnODPdj+LIwND1Zu42oXC+FZK7mx0YtNdiBOSCBrvR2+Hmj2LBVTRo8G8GtBEVzLbWpJMlvZjhyA5E4qLsBeH/8eqYeBcR8CeO0FTDY16C4+SNT9SJKmwLjhDelO50rdwoB5h8fi+t5+YsXXKyope0/Z4W1+Nb0ScybRehSp5ZK8kwSRLymVOHqKPFPssOqTWiJHmyrpbYA5ixQn3V2rnA85yo3JN6sXxmYjRGdiNSuM+SrfBqbkcM8WFVgqOFDsFXywvIYQEf2qKt/3nUqS9g88BhHvpyKX5J1JayNCUOCWuX32xk2bGw4pQO0tt+k4TBDbpack3NiQlR55msDBZ/QH7F7NJxlJHgLuRZUBrH7UbND6nP1luFGqO/v2Gh8u6l18qKMFbD0xzXACb7hwmF4O80wCTlRQnnBl/BOfLzlQFTDOK5Nc3o3ScOk96O3ZMYY8X5bgJszGLri3A2/mSSwUEFRu4bWNHMFu9G5Wox3BKfR3hbvSuwOFW5+Jg5MYWTdLM7bDVVB9BnBv6gyQZmBV9U1KicSoo2OgcWfmYMNDAoEhUdpdNMWjT31hGWBKv4Gjj3qQ3B/PIAxz7pdi6tjNpJsfAnZSNTBlzJlt8CiDSveGeqHAmoscLy8rPeSozdXQ5+sjubvjjYYpQsqY38P42aRtlxMoWXxzIYHVGO4lo3Qk/O5BsuSY7gYnfG75X2i6u0N4JsyHF7UQP4INQ7o2g1yPrAYm6flQYVaKuyJy/MKfY0fhYtKJZxxIxACnS1R9eYFyasApUnedza23fOxvN05yVCd33cT64MPFYBHgHEeC3hE2n2eCvd9viEI4H4hqjbiMDZIzHtTTkictd9vpG08VR98MDqBDCjuceuT3joPmIL+93Lw9b95B+F97sI53KEP1TIrzfhq8ZQQXw5kTi/rJ9xHY/4hfR6H7dQvCOnOOOlcQEDJub2eOkoPUB1Jw0QuFxk0MxixDHB13AS4/zCkAkyTDGR+D3pXOEnIBNg1yhzUHpxQUl5tMk+5VU2bbZyMNqCRpt/7lBSi234Pifto3aG7ylBmWt5vVgSY480gMbK2lLu6DPVN36koWA3DWRpiId4zMiQMQBhdykegwoudWf3Q/rdtkLBgK2i3TFgbQv6zDImGlLMXHgtdump23hgA1d5e8zVEoUfyHUREj05MYY544dTQpW51UgQdOrMD4juN0POapmI6jBcrw5NbklXIe0lkl9T+c5A1mHzLHBZqTrXI3ElNXKKdIztD7ANTKjXm6veRjQT4yErCaAnrh6duWLRcvlh7ZjZ+PexfZppwfxdLGJCWdEFKOrZHQsUGGq8hpg51Zswzb8NYmtnN3usBsIMIxsdiwcNsPXCa0ZeKG+aycnwGVlNg6m1y288Cc+v3LksmjJQJsHk5MNznJlFFDY8SaS98nRitKIdT7+hfIG/ZWGuQmMuK1CMXp4+2YZteIusjMcuqY/e+Z+EGjiKIFhs4CF1dujrLLiwHMn0SW6FIULnah/ibqirTGzDFW3q7ROWFBxkeU0gRhmIPctp260lGJuJYSQntn3EIVaUrIhjbVf9obnjWRO5ADe0wKNJite2G3cYZgT899gmcMYEWrkUdYOYIfB3qtctIvEIcIBDzP848x3ZVJcVHo20urh9TZ31wS3Lv/oHzTUyBVDi+/yMKrPzXSc20NR6XJQXN2D40n7a3lhjfyqcxuxTGN4txP7KL3ym77GGCN48QzGkuajSEVPvgdkwZxn0pBo1j5QjJyE1VOif4YDy5nhtHBmFS/PAhf/zCRUsqTaxUVcNiI+DLhxw4ip2sP6/+/Coc/phFL40ZramY6+3B3bLbmUTczYqLB6jOMCDSMYK36VECc7/YLFwTo/wUN9Ax2vhmObAhVBlIJ25BqFU2LO1XWIPiM/H',
                    '__ASYNCPOST' => true,
                    'ctl00$contentPlaceHolder$btnQuery' => '查詢'
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

            $infos = $crawler->filter('tr')->each(function (Crawler $node) {
                if ($node->filter('td')->count() === 9) {
                    yield [
                        'market' => $node->filter('td')->eq(0)->text(),
                        'product' => $node->filter('td')->eq(1)->text(),
                        'better_price' => $node->filter('td')->eq(2)->text(),
                        'middle_price' =>$node->filter('td')->eq(3)->text(),
                        'secondary_price' =>$node->filter('td')->eq(4)->text(),
                        'avg' => $node->filter('td')->eq(5)->text(),
                        'diff_yesterday＿avg_price' => $node->filter('td')->eq(6)->text(),
                        'trading_volume' => $node->filter('td')->eq(7)->text(),
                        'day_or' => $node->filter('td')->eq(8)->text()
                    ];
                }
            });

            // show info
            foreach ($infos as $info) {
                var_dump(iterator_to_array($info));
            }

        } catch (RequestException $e) {
            $this->error($e->getMessage());
        }


    }
}
