<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all countries from REST Countries API and store in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching countries from API...');

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'CuroHelp Application'
            ])->timeout(30)->get('https://restcountries.com/v3.1/all', [
                'fields' => 'name,cca2,cca3,flag'
            ]);

            if ($response->failed()) {
                $this->error('Failed to fetch countries from API. Status: ' . $response->status());
                $this->error('Response: ' . $response->body());
                return 1;
            }

            $countries = $response->json();
            $this->info('Found ' . count($countries) . ' countries. Storing...');

            $bar = $this->output->createProgressBar(count($countries));
            $bar->start();

            foreach ($countries as $countryData) {
                Country::updateOrCreate(
                    ['code' => $countryData['cca2'] ?? null],
                    [
                        'name' => $countryData['name']['common'] ?? 'Unknown',
                        'code3' => $countryData['cca3'] ?? null,
                        'native_name' => $countryData['name']['official'] ?? null,
                        'flag' => $countryData['flag'] ?? null,
                    ]
                );
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info('Successfully stored all countries!');

            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
